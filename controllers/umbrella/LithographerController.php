<?php

namespace Umbrella\controllers\umbrella;

use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\Group;
use Umbrella\app\User;
use Umbrella\components\Functions;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\lithographer\File;
use Umbrella\models\lithographer\Lithographer;
use upload as FileUpload;

/**
 * Class LithographerController
 */
class LithographerController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * LithographerController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('adm.lithographer', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }


    /**
     * List articles in category
     * @param $category
     *
     * @return bool
     */
    public function actionCategories($category)
    {
        $user = $this->user;

        $group = new Group();
        $userInGroup = $group->groupFormationForFilter();

        if($user->isPartner()){

            $listArticle = Lithographer::getAllContent($category, $user->getId());

        } else if($user->isAdmin() || $user->isManager()){

            $listArticle = Lithographer::getAllContent($category, $user->getId());
        }
        if($category == 'video'){
            $this->render('admin/lithographer/video', compact('user', 'userInGroup', 'listArticle'));
        } else {
            $this->render('admin/lithographer/article', compact('user', 'userInGroup', 'listArticle'));
        }
        return true;
    }


    /**
     * @param $category
     * @param $id
     *
     * @return bool
     */
    public  function actionView($category, $id)
    {
        $user = $this->user;

        $group = new Group();
        $userInGroup = $group->groupFormationForFilter();

        // список закрытых статей для пользователя
        $listArticlesCloseViewUser = array_column(Lithographer::getArticleCloseViewByIdUser($user->id_user),'id_lithographer');
        if(in_array($id, $listArticlesCloseViewUser)){
            Url::redirect('/adm/access_denied');
        }

        $view = Lithographer::getContentById($id);
        $files = File::getAllFilesById($id);
        // Увеличиваем кол-во просмотров
        Lithographer::updateViewArticleById($id);

        $this->render('admin/lithographer/view', compact('user', 'userInGroup', 'listArticlesCloseViewUser', 'view', 'files'));
        return true;
    }


    /**
     * Добавление материалов
     * @return bool
     */
    public  function actionForms()
    {
        $user = $this->user;

        if(isset($_POST['add_video'])){
            $options['id_author'] = $user->id_user;
            $options['published'] = $_POST['published'];
            $options['title'] = $_POST['title'];
            $options['text'] = '';
            $options['type_row'] = 'video';

            $usersCloseView = $_POST['privilege'] ?? [];

            if(!empty($_FILES['upload_video']['name'])) {

                $options['name_real'] = $_FILES['upload_video']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/upload_video/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                // Получаем расширение файла
                $getMime = explode('.', $options['name_real']);
                $mime = end($getMime);

                $randomName = $getMime['0'] . "-" . $randomName . "." . $mime;
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["upload_video"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['upload_video']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $id = Lithographer::addVideo($options);

                        if(is_array($usersCloseView)){
                            foreach ($usersCloseView as $key => $user_id){
                                Lithographer::addUserViewClose($user_id, $id);
                            }
                        }
                    }
                }
            }
            Url::redirect('/adm/lithographer/list');
        }

        if(isset($_REQUEST['add_new']) && $_REQUEST['add_new'] == 'true'){
            $usersCloseView = $_REQUEST['privilege'] ?? [];

            $options['id_author'] = $user->getId();

            if($user->isPartner()){
                $options['published'] = 0;
            } else if($user->isAdmin() || $user->isManager()) {

                $options['published'] = $_REQUEST['published'];
            }
            $options['description'] = $_REQUEST['description'];
            $options['title'] = $_REQUEST['title'];
            $options['text'] = $_REQUEST['content'];
            $options['type_row'] = $_REQUEST['type_row'];
            $options['file_name'] = '';
            $options['file_path'] = '';

            $id = Lithographer::addVideo($options);
            if($id){
                if(is_array($usersCloseView)){
                    foreach ($usersCloseView as $key => $user_id){
                        Lithographer::addUserViewClose($user_id, $id);
                    }
                }
                Url::redirect('/adm/lithographer/list');
            }
        }
        return true;
    }


    /**
     * Список публикаций
     * @return bool
     */
    public  function actionList()
    {
        $user = $this->user;

        $group = new Group();
        $userInGroup = $group->groupFormationForFilter();

        if($user->isPartner()){

            $listLithographer = Lithographer::getAllContentByPartner($user->getId());

        } elseif($user->isAdmin() || $user->isManager()){

            $listLithographer = Lithographer::getAllContentByAdmin();

        }
        $this->render('admin/lithographer/list_article', compact('user', 'userInGroup', 'listLithographer'));
        return true;
    }


    /**
     * @param $id
     * @return bool
     */
    public function actionEdit($id)
    {
        $user = $this->user;

        $group = new Group();
        $userInGroup = $group->groupFormationForFilter();

        $files = File::getAllFilesById($id);

        // Получаем данные о конкретной статье
        $article = Lithographer::getContentById($id);
        // массив пользователей, для которых запрещен просмотр
        $listUserCloseView = array_column(Lithographer::getUsersCloseViewById($id),'id_user');

        //Если партнер откроет для редактирование не свою статью, закрываем доступ
        if($user->isPartner()) {
            if($article['id_author'] == $user->getId() && $article['published'] == 0) {

            } else {
               Url::redirect('/adm/access_denied');
            }
        }

        if(isset($_POST['edit_article'])){
            if($user->isPartner()){

                $options['published'] = $article['published'];

            } elseif ($user->isAdmin() || $user->isManager()) {

                $options['published'] = $_POST['published'];
                $usersCloseView = $_POST['privilege'];

            }
            $options['description'] = $_POST['description'];
            $options['title'] = $_POST['title'];
            $options['text'] = $_POST['content'];
            Lithographer::deleteUserInCloseViewArticleById($id);
            $ok = Lithographer::updateArticleById($id, $options);
            if($ok){
                if(is_array($usersCloseView)){
                    foreach ($usersCloseView as $key => $user_id){
                        Lithographer::addUserViewClose($user_id, $id);
                    }
                }
                Url::redirect('/adm/lithographer/list');
            }
        }

        if(isset($_REQUEST['upload_document']) && $_REQUEST['upload_document'] == 'true'){
            if (!empty($_FILES['file'])) {
                $id = $_REQUEST['id'];
                $handle = new FileUpload($_FILES['file']);
                if ($handle->uploaded) {
                    $file_name_real = $handle->file_src_name;
                    $handle->file_new_name_body = Functions::strUrl($file_name_real);
                    $file_name = $handle->file_new_name_body . '.' . $handle->file_src_name_ext;
                    $path = '/upload/upload_img/file_lithographer/';
                    $handle->process(ROOT . $path);
                    if ($handle->processed) {
                        File::addFile($id, $path, $file_name, $file_name_real);
                        $handle->clean();
                        Url::previous();
                    }
                }
            }
        }

        $this->render('admin/lithographer/edit', compact('user', 'userInGroup', 'article', 'listUserCloseView', 'files'));
        return true;
    }

    /**
     * @return bool
     */
    public  function actionFileDelete()
    {
        $user = $this->user;

        if(isset($_REQUEST['id'])){
            $id = (int)$_REQUEST['id'];
            $fileInfo = File::getInfoFilesById($id);
            if(!$fileInfo){
                return false;
            }

            $file = ROOT . $fileInfo['file_path'] . $fileInfo['file_name'];
            if (file_exists($file)) {
                unlink($file);
                File::deleteFileById($id);
                Logger::getInstance()->log($user->getId(), "Lithograph. Удалил(а) файл - " . $fileInfo['file_name_real']);
                echo 200;
            }
        }
        return true;
    }


    /**
     * @return bool
     */
    public  function actionFileDownload()
    {
        $user = $this->user;
        $id = (int)$_REQUEST['id'];
        $fileInfo = File::getInfoFilesById($id);
        File::addCountDownloadFile($id);
        Logger::getInstance()->log($user->getId(), "Lithograph. Скачал(а) файл - " . $fileInfo['file_name_real']);
        echo 200;
        return true;
    }


    /**
     * @return bool
     */
    public  function actionSearch()
    {
        $user = $this->user;

        $group = new Group();
        $userInGroup = $group->groupFormationForFilter();
        // список закрытых статей для пользователя
        $listArticlesCloseViewUser = array_column(Lithographer::getArticleCloseViewByIdUser($user->getId()),'id_lithographer');

        if(isset($_GET['search']) && !empty($_GET['search'])){
            $search_item = $_GET['search'];

            $listSearch = Lithographer::getSearchContent($search_item);
        }

        $this->render('admin/lithographer/search', compact('user', 'userInGroup', 'listArticlesCloseViewUser', 'listSearch'));
        return true;
    }


    /**
     * Удаляем публикацию
     * @param $id
     * @return bool
     */
    public function actionDelete($id)
    {
        $user = $this->user;

        // Получаем данные о конкретной статье
        $article = Lithographer::getContentById($id);

        if ($user->isAdmin() || $user->isManager()) {
            Lithographer::deleteArticleById($id);
            Url::redirect('/adm/lithographer/list');

        } elseif ($user->isPartner()) {

            if ($article['id_author'] == $user->getId() && $article['published'] == 0) {
                Lithographer::deleteArticleById($id);
                Url::redirect('/adm/lithographer/list');
            } else {
                Url::redirect('/adm/access_denied');
            }
        }
        return true;
    }

}