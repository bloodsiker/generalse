<?php

namespace Umbrella\controllers\umbrella;

use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\Lithographer;

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
     * @return bool
     */
    public  function actionVideo()
    {
        $user = $this->user;

        $listUsers = Admin::getAllUsers();

        // список закрытых статей для пользователя
        $listArticlesCloseViewUser = array_column(Lithographer::getArticleCloseViewByIdUser($user->id_user),'id_lithographer');

        if($user->getRole() == 'partner'){

            $listVideo = Lithographer::getAllContent('video');

        } else if($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin' || $user->getRole() == 'manager'){

            $listVideo = Lithographer::getAllContent('video');
        }

        $this->render('admin/lithographer/video', compact('user', 'listUsers', 'listArticlesCloseViewUser', 'listVideo'));
        return true;
    }


    /**
     * @return bool
     */
    public  function actionTips()
    {
        $user = $this->user;

        $listUsers = Admin::getAllUsers();

        // список закрытых статей для пользователя
        $listArticlesCloseViewUser = array_column(Lithographer::getArticleCloseViewByIdUser($user->id_user),'id_lithographer');

        if($user->getRole() == 'partner'){

            $listTips = Lithographer::getAllContent('tips');

        } else if($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin' || $user->getRole() == 'manager'){

            $listTips = Lithographer::getAllContent('tips');

        }

        $this->render('admin/lithographer/tips', compact('user', 'listUsers', 'listArticlesCloseViewUser', 'listTips'));
        return true;
    }


    /**
     * @return bool
     */
    public  function actionRules()
    {
        $user = $this->user;

        $listUsers = Admin::getAllUsers();

        // список закрытых статей для пользователя
        $listArticlesCloseViewUser = array_column(Lithographer::getArticleCloseViewByIdUser($user->id_user),'id_lithographer');

        if($user->getRole() == 'partner'){

            $listRules = Lithographer::getAllContent('rules');

        } else if($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin' || $user->getRole() == 'manager'){

            $listRules = Lithographer::getAllContent('rules');
        }

        $this->render('admin/lithographer/rules', compact('user', 'listUsers', 'listArticlesCloseViewUser', 'listRules'));
        return true;
    }


    /**
     * @param $id
     * @return bool
     */
    public  function actionView($id)
    {
        $user = $this->user;

        // список закрытых статей для пользователя
        $listArticlesCloseViewUser = array_column(Lithographer::getArticleCloseViewByIdUser($user->id_user),'id_lithographer');
        if(in_array($id, $listArticlesCloseViewUser)){
            Url::redirect('/adm/access_denied');
        }

        $listUsers = Admin::getAllUsers();

        $view = Lithographer::getContentById($id);
        // Увеличиваем кол-во просмотров
        Lithographer::updateViewArticleById($id);

        $this->render('admin/lithographer/view', compact('user', 'listUsers', 'listArticlesCloseViewUser', 'view'));
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
            $options['privilege'] = 'partner';
            $options['id_author'] = $user->id_user;
            $options['published'] = $_POST['published'];
            $options['title'] = $_POST['title'];
            $options['text'] = '';
            $options['type_row'] = 'video';

            $usersCloseView = $_POST['privilege'];

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
            Url::previous();
        }


        if(isset($_POST['add_tips']) && $_POST['add_tips'] == 'true'){

            $usersCloseView = $_POST['privilege'];

            $options['id_author'] = $user->id_user;

            if($user->getRole() == 'partner'){
                $options['published'] = 0;
            } else if($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin' || $user->getRole() == 'manager') {

                $options['published'] = $_POST['published'];
            }
            $options['description'] = $_POST['description'];
            $options['title'] = $_POST['title'];
            $options['text'] = $_POST['content'];
            $options['type_row'] = 'tips';
            $options['file_name'] = '';
            $options['file_path'] = '';

            $id = Lithographer::addVideo($options);
            if($id){
                if(is_array($usersCloseView)){
                    foreach ($usersCloseView as $key => $user_id){
                        Lithographer::addUserViewClose($user_id, $id);
                    }
                }
                Url::previous();
            }
        }


        if(isset($_POST['add_rules'])){

            $usersCloseView = $_POST['privilege'];

            $options['id_author'] = $user->id_user;

            if($user->getRole() == 'partner'){
                $options['published'] = 0;
            } else if($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin' || $user->getRole() == 'manager') {

                $options['published'] = $_POST['published'];
            }
            $options['description'] = $_POST['description'];
            $options['title'] = $_POST['title'];
            $options['text'] = $_POST['content'];
            $options['type_row'] = 'rules';
            $options['file_name'] = '';
            $options['file_path'] = '';

            $id = Lithographer::addVideo($options);
            if($id){
                if(is_array($usersCloseView)){
                    foreach ($usersCloseView as $key => $user_id){
                        Lithographer::addUserViewClose($user_id, $id);
                    }
                }
                Url::previous();
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

        $listUsers = Admin::getAllUsers();

        if($user->getRole() == 'partner'){

            $listLithographer = Lithographer::getAllContentByPartner($user->id_user);

        } elseif($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin' || $user->getRole() == 'manager'){

            $listLithographer = Lithographer::getAllContentByAdmin();

        }
        $this->render('admin/lithographer/list_article', compact('user', 'listUsers', 'listLithographer'));
        return true;
    }


    /**
     * @param $id
     * @return bool
     */
    public function actionEdit($id)
    {
        $user = $this->user;

        $listUsers = Admin::getAllUsers();

        // Получаем данные о конкретной статье
        $article = Lithographer::getContentById($id);
        // массив пользователей, для которых запрещен просмотр
        $listUserCloseView = array_column(Lithographer::getUsersCloseViewById($id),'id_user');

        //Если партнер откроет для редактирование не свою статью, закрываем доступ
        if($user->getRole() == 'partner') {
            if($article['id_author'] == $user->id_user && $article['published'] == 0) {

            } else {
               Url::redirect('/adm/access_denied');
            }
        }

        if(isset($_POST['edit_article'])){
            if($user->getRole() == 'partner'){

                $options['published'] = $article['published'];

            } elseif ($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin' || $user->getRole() == 'manager') {

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

        $this->render('admin/lithographer/edit', compact('user', 'listUsers', 'article', 'listUserCloseView'));
        return true;
    }


    /**
     * @return bool
     */
    public  function actionSearch()
    {
        $user = $this->user;

        $listUsers = Admin::getAllUsers();
        // список закрытых статей для пользователя
        $listArticlesCloseViewUser = array_column(Lithographer::getArticleCloseViewByIdUser($user->id_user),'id_lithographer');

        if(isset($_GET['search']) && !empty($_GET['search'])){
            $search_item = $_GET['search'];

            $listSearch = Lithographer::getSearchContent($search_item);
        }

        $this->render('admin/lithographer/search', compact('user', 'listUsers', 'listArticlesCloseViewUser', 'listSearch'));
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

        if ($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin') {
            Lithographer::deleteArticleById($id);
            Url::redirect('/adm/lithographer/list');

        } elseif ($user->getRole() == 'partner') {

            if ($article['id_author'] == $user->id_user && $article['published'] == 0) {
                Lithographer::deleteArticleById($id);
                Url::redirect('/adm/lithographer/list');
            } else {
                Url::redirect('/adm/access_denied');
            }
        }
        return true;
    }

}