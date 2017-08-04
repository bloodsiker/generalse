<?php
namespace Umbrella\controllers\umbrella\crm;

use Umbrella\app\AdminBase;
use Umbrella\app\Group;
use Umbrella\app\User;
use Umbrella\components\Functions;
use Umbrella\components\ImportExcel;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\Orders;
use Umbrella\models\Products;
use Umbrella\models\Stocks;

/**
 * Class RequestController
 */
class RequestController extends AdminBase
{

    ##############################################################################
    ##############################      Request         #############################
    ##############################################################################

    /**
     * RequestController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.request', 'controller');
    }

    /**
     * @param string $filter
     * @return bool
     */
    public function actionIndex($filter = '')
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);
        $group = new Group();

        if(isset($_SESSION['add_request'])){
            $request_message = $_SESSION['add_request'];
            unset($_SESSION['add_request']);
        } else {
            $request_message = '';
        }

        $partnerList = Admin::getAllPartner();
        $order_type = Orders::getAllOrderTypes();
        $delivery_address = $user->getDeliveryAddress($user->id_user);

        if(isset($_POST['add_request']) && $_POST['add_request'] == 'true'){

            $note = null;
            $note_mysql = null;
            if(isset($_POST['note'])){
                $note = iconv('UTF-8', 'WINDOWS-1251', $_POST['note']);
                $note_mysql = $_POST['note'];
            }
            $options['id_user'] = $user->id_user;
            $options['part_number'] = iconv('UTF-8', 'WINDOWS-1251', trim($_POST['part_number']));
            $options['so_number'] = iconv('UTF-8', 'WINDOWS-1251', trim($_POST['so_number']));
            $options['note'] = $note;
            $options['note_mysql'] = $note_mysql;
            $mName = Products::checkPurchasesPartNumber($options['part_number']);
            $price = Products::getPricePartNumber($options['part_number'], $user->id_user);
            $options['goods_name'] = $mName['mName'];
            $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;
            $options['status_name'] = iconv('UTF-8', 'WINDOWS-1251', 'Нет в наличии, формируется поставка, ориентировочная дата поставки на наш склад ' . Functions::whatDayOfTheWeekAndAdd(date('Y-m-d')));
            $options['status_name_mysql'] = 'Нет в наличии, формируется поставка, ориентировочная дата поставки на наш склад ' . Functions::whatDayOfTheWeekAndAdd(date('Y-m-d'));
            $options['created_on'] = date('Y-m-d H:i:s');
            $options['order_type_id'] = $_POST['order_type_id'];
            $options['note1'] = isset($_POST['note1']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['note1']): null;
            $options['note1_mysql'] = isset($_POST['note1']) ? $_POST['note1']: null;

            $ok = Orders::addReserveOrdersMsSQL($options);
            if($ok){
                $options['request_id'] = $ok;
                //Пишем в mysql
                Orders::addReserveOrders($options);
                $_SESSION['add_request'] = 'Out of stock, delivery is forming';
                Logger::getInstance()->log($user->id_user, ' создал новый запрос в Request');
            }
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }

        if($user->role == 'partner'){
            //$listCheckOrders = Orders::getReserveOrdersByPartner($user->id_user);
            $listCheckOrders = Orders::getReserveOrdersByPartnerMsSQL($user->controlUsers($user->id_user), 0);
        } elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){
            //$listCheckOrders = Orders::getAllReserveOrders();
            $listCheckOrders = Orders::getAllReserveOrdersMsSQL(0);
        }

        $this->render('admin/crm/request', compact('user','group', 'partnerList', 'order_type', 'delivery_address', 'listCheckOrders', 'request_message'));
        return true;
    }



    /**
     * Completed request
     * @return bool
     */
    public function actionCompletedRequest()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        if($user->role == 'partner'){

            $filter = "";
            $interval = " AND sgog.created_on >= DATEADD(day, -30, GETDATE())";

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgog.created_on BETWEEN '$start' AND '$end'";
                $interval = '';
            }
            $filter .= $interval;

            $listCheckOrders = Orders::getCompletedRequestInOrdersByPartnerMsSQL($user->controlUsers($user->id_user), $filter);
        } elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $filter = "";
            $interval = " AND sgog.created_on >= DATEADD(day, -30, GETDATE())";

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgog.created_on BETWEEN '$start' AND '$end'";
                $interval = '';
            }
            $filter .= $interval;

            $listCheckOrders = Orders::getAllCompletedRequestInOrdersMsSQL($filter);
        }

        $this->render('admin/crm/request_completed', compact('user','listCheckOrders'));
        return true;
    }

    /**
     * @return bool
     */
    public function actionRequestImport()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);
        $group = new Group();

        if(isset($_POST['import_request']) && $_POST['import_request'] == 'true'){
            if(!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_request/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                $randomName = $user->name_partner . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importRequest($excel_file);

                        foreach ($excelArray as $import){
                            $note = null;
                            if(isset($_REQUEST['note'])){
                                $note = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['note']);
                            }
                            $options['id_user'] = $user->id_user;
                            $options['part_number'] = iconv('UTF-8', 'WINDOWS-1251', trim($import['part_number']));
                            $options['so_number'] = iconv('UTF-8', 'WINDOWS-1251', trim($import['so_number']));
                            $options['note'] = $note;
                            $mName = Products::checkPurchasesPartNumber($options['part_number']);
                            $price = Products::getPricePartNumber($options['part_number'], $user->id_user);
                            $options['goods_name'] = $mName['mName'];
                            $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;
                            $options['status_name'] = iconv('UTF-8', 'WINDOWS-1251', 'Нет в наличии, формируется поставка, ориентировочная дата поставки на наш склад ' . Functions::whatDayOfTheWeekAndAdd(date('Y-m-d')));
                            $options['created_on'] = date('Y-m-d H:i:s');
                            $options['order_type_id'] = $_REQUEST['order_type_id'];
                            $options['note1'] = isset($_POST['note1']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['note1']): null;

                            if(!empty($options['part_number'])){
                                Orders::addReserveOrdersMsSQL($options);
                            }
                        }
                        $_SESSION['add_request'] = 'Out of stock, delivery is forming';

                        Logger::getInstance()->log($user->id_user, ' загрузил массив с excel в Request');
                        header("Location: /adm/crm/request");
                    }
                }
            }
        }

        return true;
    }


    /**
     * Получаем цену продукта по парт номеру
     * @return bool
     */
    public function actionPricePartNumAjax()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);
        $group = new Group();
        $part_number = $_REQUEST['part_number'];

        $stocks_group = $group->stocksFromGroup($user->idGroupUser($user->id_user), 'name', 'request');

        $result = Products::getPricePartNumber($part_number, $user->id_user);
        $partInStock = Stocks::checkGoodsInStocksPartners($user->id_user, $stocks_group, $part_number);
        if($result == 0){
            $data['result'] = 0;
            $data['action'] = 'not_found';
            print_r(json_encode($data));
        } else {
            $data['result'] = 1;
            $data['action'] = 'purchase';
            $data['price'] = round($result['price'], 2);
            $data['mName'] = iconv('WINDOWS-1251', 'UTF-8', $result['mName']);
            $data['stock'] = iconv('WINDOWS-1251', 'UTF-8', $partInStock['stock_name']);
            $data['quantity'] = $partInStock['quantity'] . ' Units';
            print_r(json_encode($data));
        }

        return true;
    }


    public function actionRequestAjax()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();

        $user = new User($userId);

        // Редактируем парт номер
        if($_REQUEST['action'] == 'edit_pn'){
            $id_order = $_REQUEST['id_order'];
            $order_pn = trim($_REQUEST['order_pn']);

            $ok = Orders::editPartNumberFromCheckOrdersById($id_order, $order_pn);
            if($ok){
                Logger::getInstance()->log($user->id_user, ' изменил part number в request #' . $id_order . ' на ' . $order_pn);
                print_r(200);
            }
        }

        // Редактируем СО_номер номер
        if($_REQUEST['action'] == 'edit_so'){
            $id_order = $_REQUEST['id_order'];
            $order_so = trim(iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['order_so']));

            $ok = Orders::editSoNumberFromCheckOrdersById($id_order, $order_so);
            if($ok){
                Logger::getInstance()->log($user->id_user, ' изменил so number в request #' . $id_order . ' на ' . $order_so);
                print_r(200);
            }
        }

        // Очищаем название парт номера
        if($_REQUEST['action'] == 'clear_goods_name'){
            $id_order = $_REQUEST['id_order'];
            $goods_name = null;

            $ok = Orders::clearGoodsNameFromCheckOrdersById($id_order, $goods_name);
            if($ok){
                Logger::getInstance()->log($user->id_user, ' очистил(а) название part_number в request #' . $id_order);
                print_r(200);
            }
        }

        if($_REQUEST['action'] == 'edit_status'){
            $id_order = $_REQUEST['id_order'];
            $order_status = trim(iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['order_status']));

            $ok = Orders::editStatusFromCheckOrdersById($id_order, $order_status);
            if($ok){
                Logger::getInstance()->log($user->id_user, ' изменил Status в request #' . $id_order);
                print_r(200);
            }
        }

        return true;
    }


    /**
     * Delete request
     * @param $id
     * @return bool
     */
    public function actionRequestDelete($id)
    {
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('crm.request.delete', 'controller');

        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $ok = Orders::deleteRequestMsSQLById($id);

        if($ok){
            Logger::getInstance()->log($user->id_user, 'удалил request #' . $id);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
        return true;
    }
}