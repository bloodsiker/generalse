<?php
namespace Umbrella\controllers\umbrella;

use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\Services\UserService;
use Umbrella\app\User;
use Umbrella\components\Functions;
use Umbrella\components\ImportExcel;
use Umbrella\models\Admin;
use Umbrella\models\Branch;
use Umbrella\models\Country;
use Umbrella\models\DeliveryAddress;
use Umbrella\models\Denied;
use Umbrella\models\GroupModel;
use Umbrella\models\Log;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

        if($user->getRole() == 'partner' || $user->getRole() == 'manager'){

            Url::redirect('/adm/access_denied');

        } else if($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin' ){

            $this->render('admin/users/index', compact('user', 'listUsers', 'groupList',
                'countryList', 'branchList', 'message'));
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

        if(isset($_REQUEST['add_multi-user_control']) && $_REQUEST['add_multi-user_control'] == 'true'){
            $users_id = $_POST['id_user'];
            foreach ($users_id as $user_control_id){
                Admin::addUserControl($id_user, $user_control_id);
            }
            Url::previous();
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

        if($user->getRole() == 'administrator'){
            Admin::deleteUserControl($id_user, $control_user_id);
        } else {
            echo "<script>alert('У вас нету прав на удаление')</script>";
        }

        Url::previous();
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
        $currencyList = Admin::getCurrencyList();
        $ADBCPriceList = Admin::getABSDPriceList();
        $staffList = Admin::getStaffList();
        $stockPlaceList = Admin::getStockPlaceList();
        $regionList = Admin::getRegionsList();

        $countryList = Country::getAllCountry();
        $groupList = GroupModel::getGroupList();

        if($user->getRole() == 'partner' || $user->getRole() == 'manager'){

            Url::redirect('/adm/access_denied');

        } else if($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin'){

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

                    // Сохраняем изменения
                    $id_user = Admin::addUser($options);
                    if($options['id_role'] == 2){
                        $denied_lithograph = new UserService($id_user);
                        $denied_lithograph->addDeniedLithograph();

                        $options['site_client_name'] = iconv('UTF-8', 'WINDOWS-1251', $_POST['name_partner']);
                        $options['name_en'] = !empty($_POST['name_en']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['name_en']) : null;
                        $options['address'] = !empty($_POST['address']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['address']) : null;
                        $options['address_en'] = !empty($_POST['address_en']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['address_en']) : null;
                        $options['for_ttn'] = !empty($_POST['for_ttn']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['for_ttn']) : null;
                        $options['curency_id'] = $_POST['curency_id'];
                        $options['abcd_id'] = !empty($_POST['abcd_id']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['abcd_id']) : null;
                        $options['to_electrolux'] = $_POST['to_electrolux'];
                        $options['to_mail_send'] = $_POST['to_mail_send'];
                        $options['contract_number'] = !empty($_POST['contract_number']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['contract_number']) : null;
                        $options['staff_id'] = !empty($_POST['staff_id']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['staff_id']) : null;
                        $options['stock_place_id'] = $_POST['stock_place_id'];
                        $options['phone'] = !empty($_POST['phone']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['phone']) : null;
                        $options['gm_email'] = !empty($_POST['gm_email']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['gm_email']) : null;
                        $options['region_id'] = $_POST['region_id'];
                        Admin::addUserMsSql($id_user, $options);
                    }
                    if($id_user){
                        if(!empty($_REQUEST['id_group'])){
                            //Добавляем пользователя в выбранную группу
                            $ok_group = GroupModel::addUserGroup($_REQUEST['id_group'], $id_user);
                            if($ok_group){
                                // Добавляем юзеру все запрещенные страницы группы
                                $user->addDeniedForGroupUser($_REQUEST['id_group'], $id_user);
                            }
                        }
                        $log = "добавил нового пользователя " . $options['name_partner'];
                        Log::addLog($user->id_user, $log);
                        Session::set('user_success', "User {$options['name_partner']} successfully added");
                        Url::redirect('/adm/users');
                    }
                }
            }
            $this->render('admin/users/create', compact('user', 'roleList', 'countryList', 'groupList',
                'currencyList', 'ADBCPriceList', 'staffList', 'stockPlaceList', 'regionList'));
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

        if($user->getRole() == 'partner' || $user->getRole() == 'manager'){

            Url::redirect('/adm/access_denied');

        } else if($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin'){

            // Обработка формы
            if (isset($_POST['update'])) {

                if($_POST['_token'] == Session::get('_token')){
                    $options['role'] = $_POST['role'];
                    if($options['role'] == 2){
                        $options['name_partner'] = $userInfo['name_partner'];
                    } else {
                        //$options['name_partner'] = $_POST['name_partner'];
                        $options['name_partner'] = $userInfo['name_partner'];
                    }
                    $options['id_country'] = $_POST['id_country'];
                    $options['login'] = $_POST['login'];
                    $options['email'] = $_POST['email'];
                    $options['login_url'] = $_POST['login_url'];
                    $options['kpi_view'] = $_POST['kpi_view'];

                    // Сохраняем изменения
                    $ok = Admin::updateUserById($id, $options);

                    if($ok){
                        Session::destroy('info_user');
                        $log = "редактировал учетку пользователя " . $userInfo['name_partner'];
                        Log::addLog($user->id_user, $log);
                        Session::set('user_success', "user information edited");
                        Url::redirect('/adm/users');
                    }
                }
            }


            if (isset($_POST['update_password'])) {
                if($_POST['_token'] == Session::get('_token')){
                    //$options['password'] = Functions::hashPass($_POST['password']);
                    $options['password'] = Functions::hashPass($_POST['password']);

                    // Сохраняем изменения
                    $ok = Admin::updateUserPassword($id, $options);

                    if($ok){
                        $log = "изменил пароль от учетки пользователя " . $userInfo['name_partner'];
                        Log::addLog($user->id_user, $log);
                        Session::set('user_success', "user password edited");
                        Url::redirect('/adm/users');
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
     * Подгружаем информацию о пользователе из GM
     * @return bool
     */
    public function actionInfoGmUser()
    {
        $user_id = $_REQUEST['user_id'];

        $userInfo = Admin::getInfoGmUser($user_id);

        $this->render('admin/users/info_gm_user', compact('userInfo'));
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

        $userInfo = Admin::getAdminById($id);

        if($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin'){
            Admin::deleteUserById($id);
            $log = "удалил учетку пользователя " . $userInfo['name_partner'];
            Session::set('user_success', "User {$userInfo['name_partner']} deleted!");
            Log::addLog($user->id_user, $log);
        } else {
            echo "<script>alert('У вас нету прав на удаление пользователя')</script>";
        }

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

        $this->render('admin/users/denied/index', compact('user','user_check', 'list_page',
            'sub_menu', 'sub_menu_button', 'new_array', 'p_id', 'sub_id', 'id_user'));
        return true;
    }


    public function actionUserTest()
    {
        //require ROOT . '/vendor/autoload.php';

        $excel_file = '/upload/users-Umbrella.xlsx';
        //$usersArray = ImportExcel::importUsers($excel_file);
//        var_dump($usersArray);
//        die();

        $mail = new PHPMailer(true);
        $mail->Host = 'smtp.generalse.com';  // Specify main and backup SMTP servers
        //$mail->Port = 993;
        $mail->setFrom('from@example.com', 'First Last');
        $mail->addAddress('maldini2@ukr.net', 'John Doe');
        $mail->Subject = 'PHPMailer file sender';
        $mail->msgHTML("My message body");
        // Attach uploaded files
        //$mail->addAttachment($excel_file);
        $mail->send();

//        try {
//            $mail->isMail();                                      // Set mailer to use SMTP
//            $mail->Host = 'mail.generalse.com';  // Specify main and backup SMTP servers
//            $mail->Port = 993;                                    // TCP port to connect to
//            //Recipients
//            $mail->setFrom('maldini2@ukr.net', 'Mailer');
//            $mail->addAddress('maldini2@ukr.net', 'Joe User');     // Add a recipient
//
//            //Content
//            $mail->isHTML(true);                                  // Set email format to HTML
//            $mail->Subject = 'Here is the subject';
//            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
//            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
//
//            $mail->send();
//            echo 'Message has been sent';
//        } catch (Exception $e) {
//            echo 'Message could not be sent.';
//            echo 'Mailer Error: ' . $mail->ErrorInfo;
//        }

//        $user = new User(1);
//
//        $stocks = [2871, 3139, 3140, 3143, 3959, 3960];
//
//        foreach ($usersArray as $addUser){
//            //var_dump($addUser);
//            $id_user = Admin::addUser($addUser);
//
//            if($id_user){
//                $denied_lithograph = new UserService($id_user);
//                $denied_lithograph->addDeniedLithograph();
//
//                Admin::addUserMsSql($id_user, $addUser);
//
//                foreach ($stocks as $stock_id){
//                    Admin::addUserStocksMsSql($id_user, $stock_id);
//                }
//
//                //Добавляем пользователя в выбранную группу
//                $ok_group = GroupModel::addUserGroup(7, $id_user);
//                if($ok_group){
//                    // Добавляем юзеру все запрещенные страницы группы
//                    $user->addDeniedForGroupUser(7, $id_user);
//                }
//            }
//        }
//
//        $table = "<table border='1' cellspacing='0'>";
//        foreach ($usersArray as $userI){
//            $table .= "<tr>";
//            $table .= "<td>{$userI['login']}</td>";
//            $table .= "<td>{$userI['v_password']}</td>";
//            $table .= "</tr>";
//        }
//        $table .= "</table>";
//
//        echo $table;
    }

}