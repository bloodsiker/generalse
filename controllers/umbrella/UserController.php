<?php
namespace Umbrella\controllers\umbrella;

use Josantonius\Request\Request;
use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\Group;
use Umbrella\app\Services\UserService;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\components\Functions;
use Umbrella\models\Admin;
use Umbrella\models\Branch;
use Umbrella\models\Country;
use Umbrella\models\crm\Orders;
use Umbrella\models\crm\Price;
use Umbrella\models\DeliveryAddress;
use Umbrella\models\Denied;
use Umbrella\models\GroupModel;
use Umbrella\models\Log;
use Umbrella\models\crm\Stocks;

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
     * @throws \Exception
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

        $message = Session::pull('user_success');

        $_REQUEST['group'] = isset($_REQUEST['group']) ? $_REQUEST['group'] : 1;
        $filter = ' true';

        if(isset($_REQUEST['group'])){
            if($_REQUEST['group'] == 'all'){
                $filter = "true";
            } else {
                $filter = " ggu.id_group = {$_REQUEST['group']}";
            }
        }
        $listUsers = Admin::getAllUsers($filter);

        $groupList = GroupModel::getGroupList();
        $countryList = Country::getAllCountry();
        $branchList = Branch::getBranchList();

        if($user->isPartner()){

            Url::redirect('/adm/access_denied');

        } else if($user->isAdmin() || $user->isManager()){

            $this->render('admin/users/index', compact('user', 'listUsers', 'groupList',
                'countryList', 'branchList', 'message'));
        }
        return true;
    }


    /**
     * User control
     *
     * @param $id_user
     *
     * @return bool
     * @throws \Exception
     */
    public function actionUserControl($id_user)
    {
        self::checkDenied('user.control', 'controller');

        $user = $this->user;
        $group = new Group();

        $listControlUsers = Admin::getControlUsersId($id_user);

        // Параметры для формирование фильтров
        $userInGroup = $group->groupFormationForFilter();

        if(isset($_REQUEST['add_multi-user_control']) && $_REQUEST['add_multi-user_control'] == 'true'){
            $users_id = $_POST['id_user'];
            if(is_array($users_id)){
                foreach ($users_id as $user_control_id){
                    Admin::addUserControl($id_user, $user_control_id);
                }
            }
            Url::previous();
        }
        $this->render('admin/users/user_control', compact('user', 'id_user', 'listControlUsers', 'userInGroup'));
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

        if($user->isAdmin() || $user->isManager()){
            Admin::deleteUserControl($id_user, $control_user_id);
        } else {
            echo "<script>alert('У вас нету прав на удаление')</script>";
        }

        Url::previous();
        return true;
    }


    /**
     * Multi-delete users
     * @return bool
     */
    public function actionUserControlMultiDelete()
    {
        $user = $this->user;

        if(isset($_REQUEST['multi_delete_user']) && $_REQUEST['multi_delete_user'] == 'true'){
            if($user->isAdmin() || $user->isManager()){
                $id_user = $_REQUEST['id_user'];
                $usersIds = $_REQUEST['delete_users'];
                $implodeIds = implode(',', $usersIds);
                Admin::deleteUserControl($id_user, $implodeIds);
            } else {
                echo "<script>alert('У вас нету прав на удаление')</script>";
            }
        }
        Url::previous();
        return true;
    }

    /**
     * Добавление нового пользователя
     * @return bool
     * @throws \Exception
     */
    public function actionAddUser()
    {
        self::checkDenied('user.add', 'controller');

        $user = $this->user;

        if($user->isPartner()){

            Url::redirect('/adm/access_denied');

        } else if($user->isAdmin() || $user->isManager()){

            $roleList = Admin::getRoleList();
            $managerList = Admin::getAllManager();
            $currencyList = Price::getCurrencyList();
            $ADBCPriceList = Price::getABSDPriceList();
            $staffList = Admin::getStaffList();
            $stockPlaceList = Stocks::getStockPlaceList();
            $regionList = Stocks::getRegionsList();
            $stocksToPartners = Decoder::arrayToUtf(Stocks::getAllStocksToPartner());

            $countryList = Country::getAllCountry();
            $groupList = GroupModel::getGroupList();
            $orderType = Decoder::arrayToUtf(Orders::getAllOrderTypes());

            // Обработка формы
            if (isset($_POST['add_user']) && $_POST['add_user'] == 'true') {
                if($_POST['_token'] == Session::get('_token')){
                    $options['id_role'] = $_POST['role'];
                    $options['name_partner'] = $_POST['name_partner'];
                    $options['id_country'] = $_POST['id_country'];
                    $options['login'] = $_POST['login'];
                    $options['email'] = $_POST['email'];
                    $options['password'] = Functions::hashPass($_POST['password']);
                    $options['login_url'] = $_POST['login_url'];
                    $options['kpi_view'] = $_POST['kpi_view'];
                    $options['date_create'] = date("Y-m-d H:i");
                    $options['project'] = (isset($_POST['project']) && is_array($_POST['project'])) ? json_encode($_POST['project']) : json_encode(['umbrella']);
                    $options['type_repair'] = !empty($_POST['type_repair']) ? $_POST['type_repair'] : null;

                    // Сохраняем изменения
                    $id_user = Admin::addUser($options);
                    if($options['id_role'] == 2){
                        $denied_lithograph = new UserService($id_user);
                        $denied_lithograph->addDeniedLithograph();

                        $options['site_client_name'] = iconv('UTF-8', 'WINDOWS-1251', $_POST['name_partner']);
                        $options['name_en'] = !empty($_POST['name_en']) ? Decoder::strToWindows($_POST['name_en']) : null;
                        $options['address'] = !empty($_POST['address']) ?  Decoder::strToWindows($_POST['address']) : null;
                        $options['address_en'] = !empty($_POST['address_en']) ?  Decoder::strToWindows($_POST['address_en']) : null;
                        $options['for_ttn'] = !empty($_POST['for_ttn']) ?  Decoder::strToWindows($_POST['for_ttn']) : null;
                        $options['curency_id'] = $_POST['curency_id'];
                        $options['abcd_id'] = !empty($_POST['abcd_id']) ?  Decoder::strToWindows($_POST['abcd_id']) : null;
                        $options['to_electrolux'] = $_POST['to_electrolux'];
                        $options['to_mail_send'] = $_POST['to_mail_send'];
                        $options['contract_number'] = !empty($_POST['contract_number']) ?  Decoder::strToWindows($_POST['contract_number']) : null;
                        $options['staff_id'] = !empty($_POST['staff_id']) ?  Decoder::strToWindows($_POST['staff_id']) : null;
                        $options['stock_place_id'] = $_POST['stock_place_id'];
                        $options['phone'] = !empty($_POST['phone']) ?  Decoder::strToWindows($_POST['phone']) : null;
                        $options['gm_email'] = !empty($_POST['gm_email']) ?  Decoder::strToWindows($_POST['gm_email']) : null;
                        $options['region_id'] = $_POST['region_id'];
                        $okMsSQL = Admin::addUserMsSql($id_user, $options);
                        if($okMsSQL){
                            if(isset($_POST['stocks_partner']) && !empty($_POST['stocks_partner'])){
                                $arrayStocks = $_POST['stocks_partner'];
                                if(is_array($arrayStocks)){
                                    foreach ($arrayStocks as $stock){
                                        Stocks::addStockToPartner($id_user, $stock);
                                    }
                                }
                            }
                        }
                    }
                    if($id_user){

                        //Добавляем пользователя под управление менеджеров
                        if(isset($_POST['managers']) && !empty($_POST['managers'])){
                            $arrayManagers = $_POST['managers'];
                            if(is_array($arrayManagers)){
                                foreach ($arrayManagers as $manager_id){
                                    Admin::addUserControl($manager_id, $id_user);
                                }
                            }
                        }

                        if(!empty($_REQUEST['id_group'])){
                            //Добавляем пользователя в выбранную группу
                            $ok_group = GroupModel::addUserGroup($_REQUEST['id_group'], $id_user);
                            if($ok_group){
                                // Добавляем юзеру все запрещенные страницы группы
                                $user->addDeniedForGroupUser($_REQUEST['id_group'], $id_user);
                            }
                        }
                        $log = "добавил нового пользователя " . $options['name_partner'];
                        Log::addLog($user->getId(), $log);
                        Session::set('user_success', "User {$options['name_partner']} successfully added");
                        Url::redirect('/adm/users');
                    }
                }
            }
            $this->render('admin/users/create', compact('user', 'roleList', 'countryList', 'groupList',
                'currencyList', 'ADBCPriceList', 'staffList', 'stockPlaceList', 'regionList', 'stocksToPartners', 'orderType',
                'managerList'));
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
     *
     * @param $id
     *
     * @return bool
     * @throws \Exception
     */
    public function actionUpdate($id)
    {
        self::checkDenied('user.edit', 'controller');

        $user = $this->user;

        if($user->isPartner()){

            Url::redirect('/adm/access_denied');

        } else if($user->isAdmin() || $user->isManager()){

            $roleList = Admin::getRoleList();
            $countryList = Country::getAllCountry();

            $userInfo = Admin::getAdminById($id);
            $userProjects = !empty($userInfo['project']) ? json_decode($userInfo['project']) : [];

            $orderType = Decoder::arrayToUtf(Orders::getAllOrderTypes());

            if (isset($_POST['update'])) {

                if($_POST['_token'] == Session::get('_token')){
                    $options['role'] = $_POST['role'];
                    if($options['role'] == 2){
                        $options['name_partner'] = $userInfo['name_partner'];
                    } else {
                        $options['name_partner'] = $userInfo['name_partner'];
                    }
                    $options['id_country'] = $_POST['id_country'];
                    $options['login'] = $_POST['login'];
                    $options['email'] = $_POST['email'];
                    $options['login_url'] = $_POST['login_url'];
                    $options['kpi_view'] = $_POST['kpi_view'];
                    $options['project'] = (isset($_POST['project']) && is_array($_POST['project'])) ? json_encode($_POST['project']) : null;
                    $options['type_repair'] = !empty($_POST['type_repair']) ? $_POST['type_repair'] : null;

                    $ok = Admin::updateUserById($id, $options);

                    if($ok){
                        Session::destroy('info_user');
                        $log = "редактировал учетку пользователя " . $userInfo['name_partner'];
                        Log::addLog($user->getId(), $log);
                        Session::set('user_success', "user information edited");
                        Url::redirect('/adm/users');
                    }
                }
            }


            if (isset($_POST['update_password'])) {
                if($_POST['_token'] == Session::get('_token')){
                    $options['password'] = Functions::hashPass($_POST['password']);

                    $ok = Admin::updateUserPassword($id, $options);

                    if($ok){
                        $log = "изменил пароль от учетки пользователя " . $userInfo['name_partner'];
                        Log::addLog($user->getId(), $log);
                        Session::set('user_success', "user password edited");
                        Url::redirect('/adm/users');
                    }
                }
            }
            // Подключаем вид
            $this->render('admin/users/update', compact('user','userInfo', 'roleList',
                'branchList', 'countryList', 'userProjects', 'orderType'));
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
     * Подгружаем информацию о пользователе из GM
     * @return bool
     * @throws \Exception
     */
    public function actionInfoGmUser()
    {
        $user_id = $_REQUEST['user_id'];

        $userInfo = Decoder::arrayToUtf(Admin::getInfoGmUser($user_id), ['blocked']);

        $this->render('admin/users/info_gm_user', compact('userInfo'));
        return true;
    }


    /**
     * Удаление пользователя
     *
     * @param $id
     *
     * @return bool
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        self::checkDenied('user.delete', 'controller');

        $user = $this->user;

        $userInfo = Admin::getAdminById($id);

        if($user->isAdmin()){
            Admin::deleteUserById($id);
            $log = "удалил учетку пользователя " . $userInfo['name_partner'];
            Session::set('user_success', "User {$userInfo['name_partner']} deleted!");
            Log::addLog($user->getId(), $log);
        } else {
            echo "<script>alert('У вас нету прав на удаление пользователя')</script>";
        }

        header("Location: /adm/users");
        return true;
    }


    /**
     * Список адресов для доставки клиентам
     *
     * @param $id_user
     *
     * @return bool
     * @throws \Exception
     */
    public function actionUserAddress($id_user)
    {
        $user = $this->user;

        $listAddress = Decoder::arrayToUtf(DeliveryAddress::getAddressByPartnerMsSQL($id_user));

        $selectUser = Admin::getAdminById($id_user);

        if(isset($_REQUEST['add_user_address']) && $_REQUEST['add_user_address'] == 'true'){
            $address = $_REQUEST['address'];
            $phone = $_REQUEST['phone'];
            $options['id_user'] = $id_user;
            $options['address'] = $_REQUEST['address'];
            $options['phone'] = $_REQUEST['phone'];
            $ok = DeliveryAddress::addAddress($id_user, $address, $phone);
            DeliveryAddress::addAddressMsSQL(Decoder::arrayToWindows($options));
            if($ok) {
                Url::previous();
            }
        }

        if(Request::post('change') && Request::post('change') == 'true'){
            $idAddress = Request::post('id');
            $isDefault = Request::post('is_default');
            DeliveryAddress::clearDefaultAddressMsSQL($id_user, 0);
            DeliveryAddress::updateDefaultAddressMsSQL($idAddress, $isDefault);
            Url::previous();
        }

        $this->render('admin/users/address/index', compact('user', 'listAddress', 'selectUser'));
        return true;
    }


    /**
     * Редактируем список адресов партнера
     * @return bool
     * @throws \Exception
     */
    public function actionUserAddressUpdate()
    {
        if($_REQUEST['action'] == 'edit_address'){
            $options['id'] = $_REQUEST['id_address'];
            $options['address'] = $_REQUEST['address'];
            $options['phone'] = $_REQUEST['phone'];
            $ok = DeliveryAddress::updateAddressMsSQL(Decoder::arrayToWindows($options));
            if($ok) {
                echo 200;
            }
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
        $address = DeliveryAddress::getAddressByIdMsSQL($id);

        DeliveryAddress::deleteUserAddressMsSQL($id);

        $log = "удалил(а) адрес пользователя - " . $address['address'];
        Log::addLog($this->user->getId(), $log);
        Url::previous();
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


    ##############################################################################
    ##############################      Denied         ###########################
    ##############################################################################

    /**
     * @param $id_user
     * @param null $p_id
     * @param null $sub_id
     *
     * @return bool
     * @throws \Exception
     */
    public function actionUserDenied($id_user, $p_id = null, $sub_id = null)
    {
        // Проверка доступа
        self::checkDenied('user.denied', 'controller');
        // Получаем идентификатор пользователя из сессии
        $user = $this->user;

        $user_check = Admin::getNameById($id_user);
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
                Url::previous();
            }
        } elseif(isset($_POST['action']) && $_POST['action'] == 'success'){
            $name = $_POST['name'];
            $slug = $_POST['slug'];
            $ok = Denied::deleteSlugInUser($id_user, $name, $slug);
            if($ok){
                Url::previous();
            }
        }

        $this->render('admin/users/denied/index', compact('user','user_check', 'list_page',
            'sub_menu', 'sub_menu_button', 'new_array', 'p_id', 'sub_id', 'id_user'));
        return true;
    }
}