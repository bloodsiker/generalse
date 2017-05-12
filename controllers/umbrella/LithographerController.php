<?php

/**
 * Class LithographerController
 */
class LithographerController extends AdminBase
{

    /**
     * LithographerController constructor.
     */
    public function __construct()
    {
        self::checkDenied('adm.lithographer', 'controller');
    }

    /**
     * @return bool
     */
    public  function actionVideo()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);
        $listUsers = Admin::getAllUsers();

        // список закрытых статей для пользователя
        $listArticlesCloseViewUser = array_column(Lithographer::getArticleCloseViewByIdUser($user->id_user),'id_lithographer');

        if($user->role == 'partner'){

            $listVideo = Lithographer::getAllContent('video');

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $listVideo = Lithographer::getAllContent('video');
        }

        require_once(ROOT . '/views/admin/lithographer/video.php');
        return true;
    }


    /**
     * @return bool
     */
    public  function actionTips()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);
        $listUsers = Admin::getAllUsers();

        // список закрытых статей для пользователя
        $listArticlesCloseViewUser = array_column(Lithographer::getArticleCloseViewByIdUser($user->id_user),'id_lithographer');

        if($user->role == 'partner'){

            $listTips = Lithographer::getAllContent('tips');

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $listTips = Lithographer::getAllContent('tips');

        }

        require_once(ROOT . '/views/admin/lithographer/tips.php');
        return true;
    }


    /**
     * @return bool
     */
    public  function actionRules()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);
        $listUsers = Admin::getAllUsers();

        // список закрытых статей для пользователя
        $listArticlesCloseViewUser = array_column(Lithographer::getArticleCloseViewByIdUser($user->id_user),'id_lithographer');

        if($user->role == 'partner'){

            $listRules = Lithographer::getAllContent('rules');

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $listRules = Lithographer::getAllContent('rules');
        }

        require_once(ROOT . '/views/admin/lithographer/rules.php');
        return true;
    }


    /**
     * @param $id
     * @return bool
     */
    public  function actionView($id)
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);
        // список закрытых статей для пользователя
        $listArticlesCloseViewUser = array_column(Lithographer::getArticleCloseViewByIdUser($user->id_user),'id_lithographer');
        if(in_array($id, $listArticlesCloseViewUser)){
            header("Location: /adm/access_denied");
        }

        $listUsers = Admin::getAllUsers();

        $view = Lithographer::getContentById($id);
        // Увеличиваем кол-во просмотров
        Lithographer::updateViewArticleById($id);

        require_once(ROOT . '/views/admin/lithographer/view.php');
        return true;
    }


    /**
     * Добавление материалов
     * @return bool
     */
    public  function actionForms()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);


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
            if (!empty($_SERVER['HTTP_REFERER'])){
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }


        if(isset($_POST['add_tips']) && $_POST['add_tips'] == 'true'){

            $usersCloseView = $_POST['privilege'];

            $options['id_author'] = $user->id_user;

            if($user->role == 'partner'){
                $options['published'] = 0;
            } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager') {

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

                if (!empty($_SERVER['HTTP_REFERER'])){
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                }
            }
            //print_r($options);
        }


        if(isset($_POST['add_rules'])){

            $usersCloseView = $_POST['privilege'];

            $options['id_author'] = $user->id_user;

            if($user->role == 'partner'){
                $options['published'] = 0;
            } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager') {

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

                if (!empty($_SERVER['HTTP_REFERER'])){
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                }
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
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);
        $listUsers = Admin::getAllUsers();

        if($user->role == 'partner'){

            $listLithographer = Lithographer::getAllContentByPartner($userId);

        } elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $listLithographer = Lithographer::getAllContentByAdmin();

        }
        require_once(ROOT . '/views/admin/lithographer/list_article.php');
        return true;
    }


    /**
     * @param $id
     * @return bool
     */
    public function actionEdit($id)
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        //Получаем информацию о пользователе из БД
        $user = new User($userId);
        $listUsers = Admin::getAllUsers();

        // Получаем данные о конкретной статье
        $article = Lithographer::getContentById($id);
        // массив пользователей, для которых запрещен просмотр
        $listUserCloseView = array_column(Lithographer::getUsersCloseViewById($id),'id_user');

        //Если партнер откроет для редактирование не свою статью, закрываем доступ
        if($user->role == 'partner'){
            if($article['id_author'] == $userId && $article['published'] == 0){

            } else {
               header("Location: /adm/access_denied");
            }
        }

        if(isset($_POST['edit_article'])){
            if($user->role == 'partner'){

                $options['published'] = $article['published'];

            } elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

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
                header("Location: /adm/lithographer/list");
            }
        }

        require_once(ROOT . '/views/admin/lithographer/edit.php');
        return true;
    }


    /**
     * @return bool
     */
    public  function actionSearch()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);
        $listUsers = Admin::getAllUsers();
        // список закрытых статей для пользователя
        $listArticlesCloseViewUser = array_column(Lithographer::getArticleCloseViewByIdUser($user->id_user),'id_lithographer');

        if(isset($_GET['search']) && !empty($_GET['search'])){
            $search_item = $_GET['search'];

            $listSearch = Lithographer::getSearchContent($search_item);
        }

        require_once(ROOT . '/views/admin/lithographer/search.php');
        return true;
    }


    /**
     * Удаляем публикацию
     * @param $id
     * @return bool
     */
    public function actionDelete($id)
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        //Получаем информацию о пользователе из БД
        $user = new User($userId);

        // Получаем данные о конкретной статье
        $article = Lithographer::getContentById($id);

        if ($user->role == 'administrator' || $user->role == 'administrator-fin') {
            Lithographer::deleteArticleById($id);
            header("Location: /adm/lithographer/list");

        } elseif ($user->role == 'partner') {

            if ($article['id_author'] == $userId && $article['published'] == 0) {
                Lithographer::deleteArticleById($id);
                header("Location: /adm/lithographer/list");
            } else {
                header("Location: /adm/access_denied");
            }
        }

        return true;
    }

}