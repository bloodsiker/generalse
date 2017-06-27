<?php

/**
 * Class UserController
 */
class UserController extends AdminBase
{
    //private $user;

    public function __construct()
    {
        //self::checkAdmin();
        self::checkDenied('adm.users', 'controller');
//        $userId = Admin::CheckLogged();
//        $this->user = new User($userId);
    }


    /**
     * @return bool
     */
    public function actionIndex()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

        $listUsers = Admin::getAllUsers();

        $groupList = GroupModel::getGroupList();
        $countryList = Country::getAllCountry();
        $branchList = Branch::getBranchList();

        if($user->role == 'partner' || $user->role == 'manager'){

            header("Location: /adm/access_denied");

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' ){

            require_once(ROOT . '/views/admin/users/index.php');
        }
        return true;
    }


    /**
     * @param $id_user
     * @return bool
     */
    public function actionUserControl($id_user)
    {
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('user.control', 'controller');

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);
        $listUsers = Admin::getAllUsers();
        $listControlUsers = Admin::getControlUsersId($id_user);

        if(isset($_POST['add_user_control']) && $_POST['add_user_control'] == 'true'){
            $control_user_id = $_POST['id_user'];
            $ok = Admin::addUserControl($id_user, $control_user_id);
            if($ok){
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }

        require_once(ROOT . '/views/admin/users/user_control.php');
        return true;
    }

    /**
     * Удаляем пользователя из списка управляемых пользователем
     * @param $id_user
     * @param $control_user_id
     * @return bool
     */
    public function actionUserControlDelete($id_user, $control_user_id)
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        //Получаем информацию о пользователе из БД
        $user = new User($userId);

        if($user->role == 'administrator'){
            Admin::deleteUserControl($id_user, $control_user_id);
        } else {
            echo "<script>alert('У вас нету прав на удаление')</script>";
        }

        // Перенаправляем пользователя на страницу управлениями товарами
        header("Location: " . $_SERVER['HTTP_REFERER']);

        return true;
    }

    /**
     * Добавление нового пользователя
     * @return bool
     */
    public function actionAddUser()
    {
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('user.add', 'controller');

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

        $roleList = Admin::getRoleList();
        $branchList = Branch::getBranchList();

        $countryList = Country::getAllCountry();

        if($user->role == 'partner' || $user->role == 'manager'){

            header("Location: /adm/access_denied");

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin'){

            // Обработка формы
            if (isset($_POST['add_user'])) {
                if($_POST['_token'] == $_SESSION['_token']){
                    $options['id_role'] = $_POST['role'];
                    $options['name_partner'] = $_POST['name_partner'];
                    $options['id_country'] = $_POST['id_country'];
                    $options['login'] = $_POST['login'];
                    $options['password'] = Functions::hashPass($_POST['password']);
                    $options['kpi_view'] = $_POST['kpi_view'];
                    $options['date_create'] = date("Y-m-d H:i");

                    // Сохраняем изменения
                    $id_user = Admin::addUser($options);
                    if($options['id_role'] == 2){
                        $denied_lithograph = new UserService($id_user);
                        $denied_lithograph->addDeniedLithograph();
                        //Admin::addUserMsSql($id_user, $options['name_partner']);
                    }
                    if($id_user){
                        $log = "добавил нового пользователя " . $options['name_partner'];
                        Log::addLog($userId, $log);
                        // Перенаправляем пользователя на страницу юзеров
                        header("Location: /adm/users");
                    }
                }
            }
            require_once(ROOT . '/views/admin/users/create.php');
        }
        return true;
    }


    public function actionCheckUserLogin()
    {
        $login = $_REQUEST['login'];

        $count = Admin::checkUserLogin($login);
        if($count > 0){
            echo 2;
        } else {
            echo 1;
        }
        return true;
    }


    /**
     * Обновление аккаунта пользователя
     * @param $id
     * @return bool
     */
    public function actionUpdate($id)
    {
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('user.edit', 'controller');

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        //Получаем информацию о пользователе из БД
        $user = new User($userId);

        $roleList = Admin::getRoleList();
        $countryList = Country::getAllCountry();

        // Получаем данные о конкретной пользователе
        $userInfo = Admin::getAdminById($id);

        if($user->role == 'partner' || $user->role == 'manager'){

            header("Location: /adm/access_denied");

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin'){

            // Обработка формы
            if (isset($_POST['update'])) {

                if($_POST['_token'] == $_SESSION['_token']){
                    $options['role'] = $_POST['role'];
                    if($options['role'] == 2){
                        $options['name_partner'] = $userInfo['name_partner'];
                    } else {
                        $options['name_partner'] = $_POST['name_partner'];
                    }
                    $options['id_country'] = $_POST['id_country'];
                    $options['login'] = $_POST['login'];
                    $options['kpi_view'] = $_POST['kpi_view'];

                    // Сохраняем изменения
                    $ok = Admin::updateUserById($id, $options);

                    if($ok){
                        $log = "редактировал учетку пользователя " . $userInfo['name_partner'];
                        Log::addLog($userId, $log);
                        // Перенаправляем пользователя на страницу юзеров
                        header("Location: /adm/users");
                    }
                }
            }


            if (isset($_POST['update_password'])) {
                if($_POST['_token'] == $_SESSION['_token']){
                    //$options['password'] = Functions::hashPass($_POST['password']);
                    $options['password'] = Functions::hashPass($_POST['password']);

                    // Сохраняем изменения
                    $ok = Admin::updateUserPassword($id, $options);

                    if($ok){
                        $log = "изменил пароль от учетки пользователя " . $userInfo['name_partner'];
                        Log::addLog($userId, $log);

                        header("Location: /adm/users");
                    }
                }
            }
            // Подключаем вид
            require_once(ROOT . '/views/admin/users/update.php');
        }
        return true;
    }


    /**
     * Просмотр праздничных дней и добавление
     * @param $id_user
     * @return bool
     */
    public function actionUserWeekend($id_user)
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

        if($user->role == 'partner' || $user->role == 'manager'){

            header("Location: /adm/access_denied");

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin'){

            $userInfo = Admin::getAdminById($id_user);

            $listWeekend = Weekend::getWeekendByUser($id_user);

            if(isset($_POST['add_weekend'])){
                $date_weekend = $_POST['date_weekend'];
                $description = $_POST['description'];

                $ok = Weekend::addWeekend($id_user, $date_weekend, $description);

                if($ok){
                    if (!empty($_SERVER['HTTP_REFERER'])){
                        header("Location: " . $_SERVER['HTTP_REFERER']);
                    }
                }
            }
            require_once(ROOT . '/views/admin/users/weekend.php');
        }
        return true;
    }


    /**
     * Обновление праздничных дней
     * @param $id
     * @return bool
     */
    public function actionUserWeekendUpdate($id)
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

        if($user->role == 'partner' || $user->role == 'manager'){

            header("Location: /adm/access_denied");

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin'){

            $weekendInfo = Weekend::getWeekendById($id);

            if(isset($_POST['update_weekend'])){
                $id_user = $_POST['id_user'];
                $options['date_weekend'] = $_POST['date_weekend'];
                $options['description'] = $_POST['description'];

                $ok = Weekend::updateWeekend($id, $options);

                if($ok){
                    header("Location: /adm/user/weekend/" . $id_user);
                }
            }
            require_once(ROOT . '/views/admin/users/update_weekend.php');

        }

        return true;
    }


    /**
     * удаление праздничного дня для юзера
     * @param $id
     * @return bool
     */
    public function actionUserWeekendDelete($id)
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        //Получаем информацию о пользователе из БД
        $user = new User($userId);

        // Получаем данные о конкретной пользователе
        $userInfo = Admin::getAdminById($id);

        if($user->role == 'administrator' || $user->role == 'administrator-fin'){
            Weekend::deleteWeekendById($id);
        } else {
            echo "<script>alert('У вас нету прав на удаление')</script>";
        }

        // Перенаправляем пользователя на страницу управлениями товарами
        if (!empty($_SERVER['HTTP_REFERER'])){
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
        return true;
    }

    /**
     * Удаление пользователя
     * @param $id
     * @return bool
     */
    public function actionDelete($id)
    {
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('user.delete', 'controller');

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        //Получаем информацию о пользователе из БД
        $user = new User($userId);

        // Получаем данные о конкретной пользователе
        $userInfo = Admin::getAdminById($id);

        if($user->role == 'administrator' || $user->role == 'administrator-fin'){
            Admin::deleteUserById($id);
            $log = "удалил учетку пользователя " . $userInfo['name_partner'];
            Log::addLog($userId, $log);
        } else {
            echo "<script>alert('У вас нету прав на удаление пользователя')</script>";
        }

        // Перенаправляем пользователя на страницу управлениями товарами
        header("Location: /adm/users");
        return true;
    }


    ##############################################################################
    ##############################      Denied         ###########################
    ##############################################################################

    /**
     * @param $id_user
     * @param null $p_id
     * @param null $sub_id
     * @return bool
     */
    public function actionUserDenied($id_user, $p_id = null, $sub_id = null)
    {
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('user.denied', 'controller');
        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

        $user_check = new User($id_user);
        // Список страниц
        $list_page = Denied::getListPageInSystem();

        if($p_id !== null){
            $sub_menu = Denied::getListPageInSystem(intval($p_id), 0);
        }

        if($sub_id === null){
            //Кнопки которые присутсвуют в категорях главного меню
            $sub_menu_button = Denied::getListPageInSystem(intval($p_id), 0, 1);
        } else {
            //Кнопки которые присутсвуют в подкатегориях
            $sub_menu_button = Denied::getListPageInSystem(intval($sub_id), 0, 1);
        }

        $list_denied = Denied::getDeniedByUser($id_user);
        $new_array = array_column($list_denied, 'name');

        if(isset($_POST['action']) && $_POST['action'] == 'denied'){
            $name = $_POST['name'];
            $slug = $_POST['slug'];
            $ok = Denied::addDeniedSlugInUser($id_user, $name, $slug);
            if($ok){
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        } elseif(isset($_POST['action']) && $_POST['action'] == 'success'){
            $name = $_POST['name'];
            $slug = $_POST['slug'];
            $ok = Denied::deleteSlugInUser($id_user, $name, $slug);
            if($ok){
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }

        require_once(ROOT . '/views/admin/users/denied/index.php');
        return true;
    }

}