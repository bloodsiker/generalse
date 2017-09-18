<?php
namespace Umbrella\controllers\umbrella;

use Umbrella\app\AdminBase;
use Umbrella\app\Services\UserService;
use Umbrella\app\User;
use Umbrella\components\Functions;
use Umbrella\models\Admin;
use Umbrella\models\Branch;
use Umbrella\models\Country;
use Umbrella\models\DeliveryAddress;
use Umbrella\models\Denied;
use Umbrella\models\GroupModel;
use Umbrella\models\Log;
use Umbrella\models\Weekend;

/**
 * Class UserController
 */
class UserController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('adm.users', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }


    /**
     * @return bool
     */
    public function actionIndex()
    {
        $user = $this->user;

        $listUsers = Admin::getAllUsers();

        $groupList = GroupModel::getGroupList();
        $countryList = Country::getAllCountry();
        $branchList = Branch::getBranchList();

        if($user->role == 'partner' || $user->role == 'manager'){

            header("Location: /adm/access_denied");

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' ){

            $this->render('admin/users/index', compact('user', 'listUsers', 'groupList', 'countryList', 'branchList'));
        }
        return true;
    }


    /**
     * @param $id_user
     * @return bool
     */
    public function actionUserControl($id_user)
    {
        self::checkDenied('user.control', 'controller');

        $user = $this->user;

        $listUsers = Admin::getAllUsers();
        $listControlUsers = Admin::getControlUsersId($id_user);

        if(isset($_POST['add_user_control']) && $_POST['add_user_control'] == 'true'){
            $control_user_id = $_POST['id_user'];
            $ok = Admin::addUserControl($id_user, $control_user_id);
            if($ok){
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }

        $this->render('admin/users/user_control', compact('user', 'id_user', 'listUsers', 'listControlUsers'));
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
        $user = $this->user;

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
        self::checkDenied('user.add', 'controller');

        $user = $this->user;

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
                    $options['email'] = $_POST['email'];
                    $options['password'] = Functions::hashPass($_POST['password']);
                    $options['kpi_view'] = $_POST['kpi_view'];
                    $options['date_create'] = date("Y-m-d H:i");

                    // Сохраняем изменения
                    $id_user = Admin::addUser($options);
                    if($options['id_role'] == 2){
                        $denied_lithograph = new UserService($id_user);
                        $denied_lithograph->addDeniedLithograph();
                        Admin::addUserMsSql($id_user, $options['name_partner']);
                    }
                    if($id_user){
                        $log = "добавил нового пользователя " . $options['name_partner'];
                        Log::addLog($user->id_user, $log);
                        // Перенаправляем пользователя на страницу юзеров
                        header("Location: /adm/users");
                    }
                }
            }
            $this->render('admin/users/create', compact('user', 'roleList', 'branchList', 'countryList'));
        }
        return true;
    }


    /**
     * @return bool
     */
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
        self::checkDenied('user.edit', 'controller');

        $user = $this->user;

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
                    $options['email'] = $_POST['email'];
                    $options['kpi_view'] = $_POST['kpi_view'];

                    // Сохраняем изменения
                    $ok = Admin::updateUserById($id, $options);

                    if($ok){
                        $log = "редактировал учетку пользователя " . $userInfo['name_partner'];
                        Log::addLog($user->id_user, $log);
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
                        Log::addLog($user->id_user, $log);

                        header("Location: /adm/users");
                    }
                }
            }
            // Подключаем вид
            $this->render('admin/users/update', compact('user','userInfo', 'roleList', 'branchList', 'countryList'));
        }
        return true;
    }



    /**
     * Отображаем список функций доступных действйи по пользователю
     * @return bool
     */
    public function actionShowListFunc()
    {
        $user = $this->user;

        $user_id = $_REQUEST['user_id'];

        $this->render('admin/users/show_user_list_func', compact('user', 'user_id'));
        return true;
    }


    /**
     * Удаление пользователя
     * @param $id
     * @return bool
     */
    public function actionDelete($id)
    {
        self::checkDenied('user.delete', 'controller');

        $user = $this->user;

        // Получаем данные о конкретной пользователе
        $userInfo = Admin::getAdminById($id);

        if($user->role == 'administrator' || $user->role == 'administrator-fin'){
            Admin::deleteUserById($id);
            $log = "удалил учетку пользователя " . $userInfo['name_partner'];
            Log::addLog($user->id_user, $log);
        } else {
            echo "<script>alert('У вас нету прав на удаление пользователя')</script>";
        }

        // Перенаправляем пользователя на страницу управлениями товарами
        header("Location: /adm/users");
        return true;
    }



    /**
     * Список адресов для доставки клиентам
     * @param $id_user
     * @return bool
     */
    public function actionUserAddress($id_user)
    {
        $user = $this->user;

        $listAddress = DeliveryAddress::getAddressByPartner($id_user);

        $selectUser = new User($id_user);

        if(isset($_REQUEST['add_user_address']) && $_REQUEST['add_user_address'] == 'true'){
            $address = $_REQUEST['address'];
            $ok = DeliveryAddress::addAddress($id_user, $address);
            if($ok) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }

        $this->render('admin/users/address/index', compact('user', 'listAddress', 'selectUser'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionUserAddressUpdate()
    {
        //$user = $this->user;

        if($_REQUEST['action'] == 'edit_address'){
            $id = $_REQUEST['id_address'];
            $address = $_REQUEST['address'];
            $ok = DeliveryAddress::updateAddress($id, $address);
            if($ok) {
                echo 200;
            }
        }

        return true;
    }


    /**
     * Ajax action in the Users
     * @return bool
     */
    public function actionAjaxAction()
    {
        if($_REQUEST['action'] == 'user_lock'){
            $user_id = $_REQUEST['user_id'];
            $lock = $_REQUEST['lock'] == 1 ? 0 : 1;
            $ok = Admin::updateLockUser($user_id, $lock);
            $result = [];
            if ($ok && $lock == 1) {
                $result['lock'] = 1;
                $result['class'] = 'green';
                $result['icon'] = 'fi-unlock';
            } else if ($ok && $lock == 0) {
                $result['lock'] = 0;
                $result['class'] = 'red';
                $result['icon'] = 'fi-lock';
            }
            print_r(json_encode($result));
        }

        return true;
    }


    /**
     * Delete user address
     * @param $id
     * @return bool
     */
    public function actionUserAddressDelete($id)
    {
        $user = $this->user;

        $address = DeliveryAddress::getAddressById($id);

        DeliveryAddress::deleteUserAddress($id);

        $log = "удалил(а) адрес пользователя - " . $address['address'];
        Log::addLog($user->id_user, $log);

        header("Location: " . $_SERVER['HTTP_REFERER']);
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
        self::checkDenied('user.denied', 'controller');
        // Получаем идентификатор пользователя из сессии
        $user = $this->user;

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

        $this->render('admin/users/denied/index', compact('user','user_check', 'list_page', 'sub_menu', 'sub_menu_button', 'new_array', 'p_id', 'sub_id', 'id_user'));
        return true;
    }

}