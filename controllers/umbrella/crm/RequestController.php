<?php
namespace Umbrella\controllers\umbrella\crm;

use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\Group;
use Umbrella\app\Mail\RequestMail;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\components\Functions;
use Umbrella\components\ImportExcel;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\crm\Request;
use Umbrella\models\File;
use Umbrella\models\GroupModel;
use Umbrella\models\Orders;
use Umbrella\models\PartAnalog;
use Umbrella\models\Products;
use Umbrella\models\Stocks;
use upload as FileUpload;

/**
 * Class RequestController
 */
class RequestController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * RequestController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.request', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * @param string $filter
     * @return bool
     */
    public function actionIndex($filter = '')
    {
        $user = $this->user;
        $group = new Group();

        $request_message['add_request'] = Session::pull('add_request');
        $request_message['replace_by_analog'] = Session::pull('replace_by_analog');

        $partnerList = Admin::getAllPartner();
        $order_type = Orders::getAllOrderTypes();
        $delivery_address = $user->getDeliveryAddress();

        $arrayPartNumber = array_column(PartAnalog::getListPartAnalog(), 'part_number');

        if(isset($_POST['add_request']) && $_POST['add_request'] == 'true'){

            $note = null;
            $note_mysql = null;
            if(isset($_POST['note'])){
                if($_POST['note'] == 'other_address'){
                    $address = $_POST['your_address'];
                    $note = !empty($address) ? Decoder::strToWindows($address) : null;
                    $note_mysql = $_POST['your_address'];
                } else {
                    $note = Decoder::strToWindows($_POST['note']);
                    $note_mysql = $_POST['note'];
                }
            }
            $options['id_user'] = $user->id_user;

            $partAnalog = PartAnalog::getAnalogByPartNumber($_POST['part_number']);
            //если имееться аналог парт номера, заменяем его
            if($partAnalog){
                $options['part_number'] = Decoder::strToWindows($partAnalog['part_analog']);
                Session::set('replace_by_analog', "Part number {$_POST['part_number']} is replaced by an analog {$partAnalog['part_analog']}");
            } else {
                $options['part_number'] = Decoder::strToWindows(trim($_POST['part_number']));
            }

            $options['pn_name_rus'] = isset($_POST['pn_name_rus']) ? Decoder::strToWindows(trim($_POST['pn_name_rus'])) : null;
            $options['so_number'] = Decoder::strToWindows(trim($_POST['so_number']));
            $options['so_number'] = !empty($options['pn_name_rus']) ? '[' . $options['pn_name_rus'] . '] - ' . $options['so_number'] : $options['so_number'];
            $options['note'] = $note;
            $options['note_mysql'] = $note_mysql;
            $mName = Products::checkPurchasesPartNumber($options['part_number']);
            $price = Products::getPricePartNumber($options['part_number'], $user->id_user);
            $options['goods_name'] = $mName['mName'];
            $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;
            if($user->name_partner == 'Servisexpress'
                || $user->name_partner == 'Technoservice'
                || $user->name_partner == 'Techpoint'){
                $options['status_name'] = Decoder::strToWindows('Нет в наличии, формируется поставка.');
            } else {
                $options['status_name'] = Decoder::strToWindows('Нет в наличии, формируется поставка, ориентировочная дата поставки на наш склад ' . Functions::whatDayOfTheWeekAndAdd(date('Y-m-d')));
            }

            $options['status_name_mysql'] = 'Нет в наличии, формируется поставка, ориентировочная дата поставки на наш склад ' . Functions::whatDayOfTheWeekAndAdd(date('Y-m-d'));
            $options['created_on'] = date('Y-m-d H:i:s');
            $options['order_type_id'] = $_POST['order_type_id'];
            $options['note1'] = isset($_POST['note1']) ? Decoder::strToWindows($_POST['note1']): null;
            $options['note1_mysql'] = isset($_POST['note1']) ? $_POST['note1']: null;

            $so_number = $options['so_number'];
            $part_quantity = $_REQUEST['part_quantity'];

            for($i = 1; $i <= $part_quantity; $i++) {
                // Если кол-во больше 1, номеруем каждую заявку
                if($part_quantity > 1){
                    $options['so_number'] = $so_number . ' (' . $i . ')';
                }

                $ok = Request::addReserveOrdersMsSQL($options);

                if($ok){
                    $options['request_id'] = $ok;
                    $options['so_number'] = $_POST['so_number'];
                    //Пишем в mysql
                    Orders::addReserveOrders($options);
                    Session::set('add_request', 'Out of stock, delivery is forming');
                    Logger::getInstance()->log($user->id_user, ' создал новый запрос в Request '. $options['part_number']);
                }
            }
            Url::previous();
        }

        if($user->role == 'partner' || $user->role == 'manager'){

            $listCheckOrders = Request::getReserveOrdersByPartnerMsSQL($user->controlUsers($user->id_user), 0, 1);
            $listCheckOrders = Functions::getUniqueArray('number', $listCheckOrders);
            $listRemovedRequest = Decoder::arrayToUtf(Request::getReserveOrdersByPartnerMsSQL($user->controlUsers($user->id_user), 0, 0));
            //$listRemovedRequest = [];

            $partnerList = Admin::getPartnerControlUsers($user->controlUsers($user->id_user));

        } elseif($user->role == 'administrator' || $user->role == 'administrator-fin'){

            $listCheckOrders = Request::getAllReserveOrdersMsSQL(0, 1);
            $listCheckOrders = Functions::getUniqueArray('number', $listCheckOrders);
            //$listCheckOrders = [];
            $listRemovedRequest = Decoder::arrayToUtf(Request::getAllReserveOrdersMsSQL(0, 0));
            //$listRemovedRequest = [];

            // Параметры для формирование фильтров
            $groupList = GroupModel::getGroupList();
            $userInGroup = [];
            $i = 0;
            foreach ($groupList as $group) {
                $userInGroup[$i]['group_name'] = $group['group_name'];
                $userInGroup[$i]['group_id'] = $group['id'];
                $userInGroup[$i]['users'] = GroupModel::getUsersByGroup($group['id']);
                $i++;
            }
            // Добавляем в массив пользователей без групп
            $userNotGroup[0]['group_name'] = 'Without group';
            $userNotGroup[0]['group_id'] = 'without_group';
            $userNotGroup[0]['users'] = GroupModel::getUsersWithoutGroup();
            $userInGroup = array_merge($userInGroup, $userNotGroup);
        }

        $this->render('admin/crm/request/request', compact('user','group', 'partnerList', 'order_type',
            'delivery_address', 'listCheckOrders', 'request_message', 'arrayPartNumber', 'listRemovedRequest',
            'userInGroup'));
        return true;
    }



    /**
     * Completed request
     * @return bool
     */
    public function actionCompletedRequest()
    {
        $user = $this->user;

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

        $this->render('admin/crm/request/request_completed', compact('user','listCheckOrders'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionRequestImport()
    {
        $user = $this->user;
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

                        $arrayReplaceAnalog = [];
                        foreach ($excelArray as $import){
                            $note = null;
                            if($_POST['note'] == 'other_address'){
                                $address = $_POST['your_address'];
                                $note = !empty($address) ? Decoder::strToWindows($address) : null;
                            } else {
                                $note = Decoder::strToWindows($_POST['note']);
                            }
                            $options['id_user'] = $user->id_user;

                            $partAnalog = PartAnalog::getAnalogByPartNumber($import['part_number']);
                            //если имееться аналог парт номера, заменяем его
                            if($partAnalog){
                                $options['part_number'] = iconv('UTF-8', 'WINDOWS-1251', $partAnalog['part_analog']);
                                array_push($arrayReplaceAnalog, "[{$import['part_number']}] => [{$partAnalog['part_analog']}]");
                            } else {
                                $options['part_number'] = iconv('UTF-8', 'WINDOWS-1251', trim($import['part_number']));
                            }

                            $options['so_number'] = iconv('UTF-8', 'WINDOWS-1251', trim($import['so_number']));
                            $options['note'] = $note;
                            $mName = Products::checkPurchasesPartNumber($options['part_number']);
                            $price = Products::getPricePartNumber($options['part_number'], $user->id_user);
                            $options['goods_name'] = $mName['mName'];
                            $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;

                            if($user->name_partner == 'Servisexpress'
                                || $user->name_partner == 'Technoservice'
                                || $user->name_partner == 'Techpoint'){
                                $options['status_name'] = iconv('UTF-8', 'WINDOWS-1251', 'Нет в наличии, формируется поставка.');
                            } else {
                                $options['status_name'] = iconv('UTF-8', 'WINDOWS-1251', 'Нет в наличии, формируется поставка, ориентировочная дата поставки на наш склад ' . Functions::whatDayOfTheWeekAndAdd(date('Y-m-d')));
                            }

                            $options['created_on'] = date('Y-m-d H:i:s');
                            $options['order_type_id'] = $_REQUEST['order_type_id'];
                            $options['note1'] = isset($_POST['note1']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['note1']): null;

                            if(!empty($options['part_number'])){
                                Orders::addReserveOrdersMsSQL($options);
                            }
                        }
                        Session::set('add_request', 'Out of stock, delivery is forming');
                        if(count($arrayReplaceAnalog) > 0){
                            $partImplode = implode(', ', $arrayReplaceAnalog);
                            Session::set('replace_by_analog', "Part numbers is replaced by an analog {$partImplode}");
                        }

                        Logger::getInstance()->log($user->id_user, ' загрузил массив с excel в Request');
                        Url::redirect('/adm/crm/request');
                    }
                }
            }
        }

        if(isset($_POST['edit_status_from_excel']) && $_POST['edit_status_from_excel'] == 'true'){
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
                        $excelArray = ImportExcel::importStatusInRequest($excel_file);

                        foreach ($excelArray as $import){

                            $options['id'] = $import['id'];
                            $options['status_name'] = iconv('UTF-8', 'WINDOWS-1251', $import['status_name']);

                            Orders::addReserveOrdersMsSQL($options);
                        }
                        Logger::getInstance()->log($user->id_user, ' изменил(а) статусы в Request с excel');
                        Url::redirect('/adm/crm/request');
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
        $user = $this->user;
        $group = new Group();

        // Для electrolux
        if($_REQUEST['action'] == 'part-price') {
            $part_number = $_REQUEST['part_number'];

            $stocks_group = $group->stocksFromGroup($user->idGroupUser($user->id_user), 'name', 'request');

            $result = Products::getPricePartNumber($part_number, $user->id_user);
            $partInStock = Stocks::checkGoodsInStocksPartners($user->id_user, $stocks_group, $part_number);
            $partNumberAnalog = PartAnalog::getAnalogByPartNumber($part_number);

            if($result == 0){
                $data['result'] = 0;
                $data['action'] = 'not_found';
                print_r(json_encode($data));
            } else {
                $data['result'] = 1;
                $data['action'] = 'purchase';
                $data['price'] = round($result['price'], 2);
                $data['mName'] = iconv('WINDOWS-1251', 'UTF-8', $result['mName']);
                if($partInStock){
                    $data['in_stock'] = 1;
                    $data['stock'] = iconv('WINDOWS-1251', 'UTF-8', $partInStock['stock_name']);
                    $data['quantity'] = $partInStock['quantity'] . ' Units';
                }
                if($partNumberAnalog){
                    $price = Products::getPricePartNumber($partNumberAnalog['part_analog'], $user->id_user);
                    $data['is_analog'] = 1;
                    $data['message'] = 'Парт номер будет заменен на аналог ';
                    $data['analog'] = $partNumberAnalog['part_analog'];
                    $data['analog_price'] = round($price['price'], 2);
                }
                print_r(json_encode($data));
            }
        }

        // Для партнеров Lenovo
        if($_REQUEST['action'] == 'part-stock') {
            $part_number = $_REQUEST['part_number']; //SB18C01336
            $quantity = $_REQUEST['quantity'];
            $result = [];

            $infoPart = Products::checkPartNumberInGM($part_number);
            $stocks_group = explode(',', 'BAD,Not Used,Restored,Dismantling,Local Source');
            $partInStock = Stocks::checkInStockAndReplaceName(12, $stocks_group, $part_number);

            if(sizeof($partInStock) > 0){
                $result['status'] = 200;
                $result['stocks'] = Decoder::arrayToUtf($partInStock);
            } else {
                $result['status'] = 404;
            }
            $result['goods_name'] = Decoder::arrayToUtf([$infoPart['mName']]);


            print_r(json_encode($result));
        }
        return true;
    }


    /**
     * @return bool
     */
    public function actionRequestAjax()
    {
        $user = $this->user;

        // Редактируем парт номер
        if($_REQUEST['action'] == 'edit_pn'){
            $id_order = $_REQUEST['id_order'];
            $order_pn = trim($_REQUEST['order_pn']);

            $requestInfo = Orders::getOrderRequestInfo($id_order);

            $ok = Orders::editPartNumberFromCheckOrdersById($id_order, $order_pn);
            if($ok){
                $analogPrice = Products::getPricePartNumber($order_pn, $requestInfo['site_account_id']);
                $originPrice = Products::getPricePartNumber($requestInfo['part_number'], $requestInfo['site_account_id']);

                $userRequest = new User($requestInfo['site_account_id']);

                RequestMail::getInstance()->sendEmailAnalogPartNumber($id_order, $analogPrice, $originPrice, $userRequest->email);

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

            $requestInfo = Orders::getOrderRequestInfo($id_order);

            $ok = Orders::editStatusFromCheckOrdersById($id_order, $order_status);
            if($ok){
                $userRequest = new User($requestInfo['site_account_id']);

                RequestMail::getInstance()->sendEmailEditStatus($id_order, $requestInfo['status_name'], $order_status, $userRequest->email);

                Logger::getInstance()->log($user->id_user, ' изменил Status в request #' . $id_order);
                print_r(200);
            }
        }


        // Редактируем парт номер и аналог
        if($_REQUEST['action'] == 'edit_pn_analog'){
            $id_record = $_REQUEST['id_record'];
            $part_number = $_REQUEST['part_number'];
            $part_analog = $_REQUEST['part_analog'];

            $ok = PartAnalog::updatePartNumberAndAnalog($id_record, $part_number, $part_analog);
            if($ok){
                Logger::getInstance()->log($user->id_user, ' изменил(а) запись в аналогах с id #' . $id_record);
                print_r(200);
            }
        }


        // Возвращаем реквест из корзины
        if($_REQUEST['action'] == 'restore_request'){
            $id_request = $_REQUEST['id_request'];
            $options['period'] = !empty($_REQUEST['period']) ? (int)$_REQUEST['period'] : null;
            $options['created_on'] = date('Y-m-d H:i:s');
            $options['active'] = 1;

            $ok = Request::moveRequestInList($id_request, $options);
            if($ok){
                Logger::getInstance()->log($user->getId(), ' переместил request id #' . $id_request . ' с корзины');
                print_r(200);
            }
        }

        if($_REQUEST['action'] == 'save_to_cart'){

            if(Session::get('multi_request_cart')){
                $arrayInCart = Session::get('multi_request_cart');
                $lastNumber = array_shift($arrayInCart)['number'];
            } else {
                $lastNumber = Request::generateNumber();
            }

            $saveToCart = [];

            $options['site_account_id'] = $user->getID();
            $options['part_number'] = trim($_REQUEST['multi_part_number']);
            $options['part_quantity'] = $_REQUEST['part_quantity'];
            $options['goods_name'] = Decoder::strToWindows($_REQUEST['goods_name']);
            $price = Products::getPricePartNumber($options['part_number'], $user->getId());
            $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;
            $options['number'] = $lastNumber;
            $options['period'] = $_REQUEST['period'];
            $options['note1'] = $_REQUEST['note1'];
            $options['stock_id'] = isset($_REQUEST['stock_id']) ? $_REQUEST['stock_id'] : null;
            $options['stock_name'] = $_REQUEST['stock_name'];
            $options['stock_count'] = $_REQUEST['stock_count'];
            $options['part_quantity'] = $_REQUEST['part_quantity'];

            $saveToCart = Session::get('multi_request_cart');
            $saveToCart[] =  $options;
            Session::set('multi_request_cart', $saveToCart);
            require_once ROOT . '/views/admin/crm/request/multi-request-cart.php';
        }

        // Чистим корзину
        if($_REQUEST['action'] == 'clear_multi_cart'){
            if(Session::destroy('multi_request_cart')){
                require_once ROOT . '/views/admin/crm/request/multi-request-cart.php';
            }
        }

        //Удаляем элемент с корзины
        if($_REQUEST['action'] == 'delete_element_multi_cart'){
            $id_trash = $_REQUEST['id_trash'];
            $saveToCart = Session::get('multi_request_cart');
            if(array_key_exists($id_trash, $saveToCart)){
                unset($saveToCart[$id_trash]);
                Session::set('multi_request_cart', $saveToCart);
                require_once ROOT . '/views/admin/crm/request/multi-request-cart.php';
            }
        }

        //Send Multi request
        if($_REQUEST['action'] == 'send-multi-request'){

            $productInCart = Session::get('multi_request_cart');

            foreach ($productInCart as $product){
                $options['site_account_id'] = $product['site_account_id'];
                $options['part_number'] = $product['part_number'];
                $options['part_quantity'] = $product['part_quantity'];
                $options['goods_name'] = $product['goods_name'];
                $options['price'] = $product['price'];
                $options['status_name'] = '';
                $options['created_on'] = date('Y-m-d H:i:s');
                $options['number'] = $product['number'];
                $options['active'] = 1;
                $options['period'] = !empty($product['period']) ? $product['period'] : null;
                $options['note1'] = Decoder::strToWindows($product['note1']);
                $options['stock_id'] = null;
                $stock_count = $product['stock_count'];

                for ($i = 1; $i <= $options['part_quantity']; $i++) {

                    if ($stock_count >= $i) {
                        $options['stock_id'] = $product['stock_id'];
                        $stock_name = $product['stock_name'];
                        $options['status_name'] = Decoder::strToWindows('Создан запрос со склада ' . $stock_name);
                    } else {
                        $options['stock_id'] = null;
                        $options['status_name'] = Decoder::strToWindows('Ожидает появления позиции на складах или в разборках');
                    }
                    Request::addMultiRequestMsSQL($options);
                }
            }
            Session::set('add_request', 'Request is forming');
            Logger::getInstance()->log($user->id_user, ' создал новый multi-запрос в Request');
            Session::destroy('multi_request_cart');
            echo 200;
        }

        //Удаляем элемент с корзины
        if($_REQUEST['action'] == 'show-multi-request'){
            $number = $_REQUEST['number'];
            $listRequests = Request::getMultiRequestsByNumber($number);
            $listRequests = Decoder::arrayToUtf($listRequests);

            $html = "";
            foreach($listRequests as $item){
                $html .= "<tr>";
                $html .= "<td>" . $item['id'] . "</td>";
                $html .= "<td>" . $item['part_number'] . "</td>";
                $html .= "<td>" . $item['goods_name'] . "</td>";
                $html .= "<td>" . $item['so_number'] . "</td>";
                $html .= "<td>" . $item['status_name'] . "</td>";
                $html .= "<td>" . Functions::formatDate($item['created_on']) . "</td>";
                $html .= "<td>" . $item['period'] . "</td>";
                $html .= "<td><button data-reqid='" . $item['id'] . "' class='delete delete-request'>Delete</button></td>";
                $html .= "</tr>";
            }
            print_r($html);
            return true;
        }

        // Удаляем реквест в корзину
        if($_REQUEST['action'] == 'delete_request'){
            $id_request = $_REQUEST['id_request'];
            $ok = Request::moveRequest($id_request, 0);
            if($ok){
                Logger::getInstance()->log($user->getId(), ' удалил request id #' . $id_request . ' в корзину');
                print_r(200);
            }
        }
        return true;
    }


    /**
     * Edit status from import excel
     * @return bool
     */
    public function actionEditStatusFromExcel()
    {
        $user = $this->user;

        if(isset($_POST['edit_status_from_excel']) && $_POST['edit_status_from_excel'] == 'true'){
            if(!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_request/other/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                $randomName = $user->name_partner . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importStatusInRequest($excel_file);

                        foreach ($excelArray as $import){

                            $id = $import['id'];
                            $status_name = iconv('UTF-8', 'WINDOWS-1251', $import['status_name']);
                            $requestInfo = Orders::getOrderRequestInfo($id);
                            $ok = Orders::editStatusFromCheckOrdersById($id, $status_name);
                            if($ok){
                                $userRequest = new User($requestInfo['site_account_id']);
                                RequestMail::getInstance()->sendEmailEditStatus($id, $requestInfo['status_name'], $status_name, $userRequest->email);
                            }
                        }
                        Logger::getInstance()->log($user->id_user, ' изменил(а) статусы в Request с excel');
                        Url::redirect('/adm/crm/request');
                    }
                }
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
        $user = $this->user;
        self::checkDenied('crm.request.delete', 'controller');
        $ok =  Request::moveRequest($id, 0);

//        $requestInfo = Orders::getOrderRequestInfo($id);
//        $requestInfo['goods_name'] = iconv('WINDOWS-1251', 'UTF-8', $requestInfo['goods_name']);
//        $requestInfo['so_number'] = iconv('WINDOWS-1251', 'UTF-8', $requestInfo['so_number']);
//        $requestInfo['note'] = iconv('WINDOWS-1251', 'UTF-8', $requestInfo['note']);
//        $requestInfo['status_name'] = iconv('WINDOWS-1251', 'UTF-8', $requestInfo['status_name']);
//        $requestInfo['subtype_name'] = iconv('WINDOWS-1251', 'UTF-8', $requestInfo['subtype_name']);
//        $json = json_encode($requestInfo);
//
//        $ok = Orders::deleteRequestMsSQLById($id);

        if($ok){
            //Orders::addRemovedRequest($json);
//            $file = ROOT . '/storage/logs/removed_request.txt';
//            $person = (string)$json . "\r\n";
//            // используя флаг FILE_APPEND flag для дописывания содержимого в конец файла
//            // и флаг LOCK_EX для предотвращения записи данного файла кем-нибудь другим в данное время
//            file_put_contents($file, $person, FILE_APPEND | LOCK_EX);

            Logger::getInstance()->log($user->id_user, 'переместил в корзину request #' . $id);
            Url::previous();
        }
        return true;
    }



    /**
     * Part analog list
     * @return bool
     */
    public function actionListAnalog()
    {
        $user = $this->user;

        $listPartAnalog = PartAnalog::getListPartAnalog();

        if(isset($_POST['add-analog']) && $_POST['add-analog'] == 'true'){
            $part_number = $_POST['r_part_number'];
            $part_analog = $_POST['r_part_analog'];

            $ok = PartAnalog::addPartAnalog($part_number, $part_analog);
            if($ok){
                Url::previous();
            }
        }

        if(isset($_POST['import-excel-analog']) && $_POST['import-excel-analog'] == 'true'){
            if(!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_request/other/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                $randomName = $user->name_partner . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importPartNumberAnalog($excel_file);

                        foreach ($excelArray as $import){

                            $part_number = $import['part_number'];
                            $part_analog = $import['part_analog'];
                            PartAnalog::addPartAnalog($part_number, $part_analog);

                        }
                        Url::previous();
                    }
                }
            }
        }

        $this->render('admin/crm/request/request_list_analog', compact('user','listPartAnalog'));
        return true;
    }


    /**
     * Page export to excel
     * @return bool
     */
    public function actionExportRequests()
    {
        $user = $this->user;

        $start =  isset($_POST['start']) ? $_POST['start'] .' 00:00' : '';
        $end =  isset($_POST['end']) ? $_POST['end'] .' 23:59' : '';
        $processed = $_REQUEST['processed'];

        $id_partners = isset($_POST['id_partner']) ? $_POST['id_partner'] : [];
        $listExport = Request::getExportRequestsByPartners($id_partners, $start, $end, $processed);

        $this->render('admin/crm/export/requests', compact('user', 'listExport'));
        return true;
    }


    /**
     * Загружаем новый файл с ценами
     * @return bool
     */
    public function actionUploadPrice()
    {
        if (!empty($_FILES['excel_file'])) {
            $handle = new FileUpload($_FILES['excel_file']);
            if ($handle->uploaded) {
                //$handle->file_auto_rename = false;
                $handle->file_overwrite = true;
                $file_name = $handle->file_src_name;
                $path = '/upload/attach_request/';
                $handle->process(ROOT . $path);
                if ($handle->processed) {
                    File::addNewPriceFile($path, $file_name, $_REQUEST['id_group'], $_REQUEST['partner_status'], date('Y-m-d'));
                    $handle->clean();
                    echo 'Файл успешно загружен!';
                } else {
                    echo $handle->error;
                }
            }
        }
        return true;
    }

}