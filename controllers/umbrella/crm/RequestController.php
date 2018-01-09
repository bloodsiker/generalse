<?php
namespace Umbrella\controllers\umbrella\crm;

use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\Group;
use Umbrella\app\Mail\RequestMail;
use Umbrella\app\Services\crm\StockService;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\components\ExportExcel;
use Umbrella\components\Functions;
use Umbrella\components\ImportExcel;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\crm\Request;
use Umbrella\models\crm\Currency;
use Umbrella\models\File;
use Umbrella\models\Orders;
use Umbrella\models\PartAnalog;
use Umbrella\models\Price;
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
     *
     * @return bool
     * @throws \Exception
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

        $arrayPartNumber = array_column(PartAnalog::getListPartAnalog(" AND type_part = 'analog' "), 'part_number');

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
            $options['id_user'] = $user->getId();

            $partAnalog = PartAnalog::getAnalogByPartNumber($_POST['part_number']);
            //если имееться аналог парт номера, заменяем его
            if($partAnalog && $partAnalog['type_part']== 'analog'){
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
            $options['expected_date'] = null;
            if($user->getName() == 'Servisexpress'
                || $user->getName() == 'Technoservice'
                || $user->getName() == 'Techpoint'){
                $options['status_name'] = Decoder::strToWindows('Нет в наличии, формируется поставка.');
            } else {
                $options['status_name'] = Decoder::strToWindows('Нет в наличии, формируется поставка, ориентировочная дата поставки на наш склад ');
                $options['expected_date'] = Functions::whatDayOfTheWeekAndAdd(date('Y-m-d'));
            }

            $options['status_name_mysql'] = 'Нет в наличии, формируется поставка, ориентировочная дата поставки на наш склад ' . Functions::whatDayOfTheWeekAndAdd(date('Y-m-d'));
            $options['created_on'] = date('Y-m-d H:i:s');
            $options['order_type_id'] = $_POST['order_type_id'];
            $options['note1'] = isset($_POST['note1']) ? Decoder::strToWindows($_POST['note1']): null;
            $options['note1_mysql'] = $_POST['note1'] ?? null;
            $options['created_by'] = $user->getId();

            // Прошить на PNC 1-yes/0-no
            $options['is_npc'] = isset($_POST['is_npc']) ? $_POST['is_npc'] : 0;

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
                    Logger::getInstance()->log($user->getId(), ' создал новый запрос в Request '. $options['part_number']);
                }
            }
            Url::previous();
        }

        $rateCurrencyEuro = Currency::getRatesCurrency('euro')['OutputRate'];

        if($user->isPartner() || $user->isManager()){

            $listCheckOrders = Request::getReserveOrdersByPartnerMsSQL($user->controlUsers($user->getId()), 0, 1);
            $listCheckOrders = Functions::getUniqueArray('number', $listCheckOrders);
            $listCheckOrders = array_map(function ($value) use ($rateCurrencyEuro){
                $value['price_euro'] = round($value['price'] / $rateCurrencyEuro, 2);
                return $value;
            }, $listCheckOrders);

            $listRemovedRequest = Decoder::arrayToUtf(Request::getReserveOrdersByPartnerMsSQL($user->controlUsers($user->getId()), 0, 0));
            //$listRemovedRequest = [];

            $partnerList = Admin::getPartnerControlUsers($user->controlUsers($user->getId()));

        } elseif($user->isAdmin()){

            $listCheckOrders = Request::getAllReserveOrdersMsSQL(0, 1);
            $listCheckOrders = Functions::getUniqueArray('number', $listCheckOrders);
            $listCheckOrders = array_map(function ($value) use ($rateCurrencyEuro){
                $value['price_euro'] = round($value['price'] / $rateCurrencyEuro, 2);
                return $value;
            }, $listCheckOrders);
            //$listCheckOrders = [];
            $listRemovedRequest = Decoder::arrayToUtf(Request::getAllReserveOrdersMsSQL(0, 0));
            //$listRemovedRequest = [];

            // Параметры для формирование фильтров
            $userInGroup = $group->groupFormationForFilter();
        }

        $this->render('admin/crm/request/request', compact('user','group', 'partnerList', 'order_type',
            'delivery_address', 'listCheckOrders', 'request_message', 'arrayPartNumber', 'listRemovedRequest',
            'userInGroup'));
        return true;
    }


    /**
     * @return bool
     * @throws \Exception
     */
    public function actionRequestImport()
    {
        $user = $this->user;

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
                            if($_REQUEST['note'] == 'other_address'){
                                $address = $_REQUEST['your_address'];
                                $note = !empty($address) ? Decoder::strToWindows($address) : null;
                            } else {
                                $note = Decoder::strToWindows($_REQUEST['note']);
                            }
                            $options['id_user'] = $user->getId();

                            $partAnalog = PartAnalog::getAnalogByPartNumber($import['part_number']);
                            //если имееться аналог парт номера, заменяем его
                            if($partAnalog && $partAnalog['type_part']== 'analog'){
                                $options['part_number'] = Decoder::strToWindows($partAnalog['part_analog']);
                                array_push($arrayReplaceAnalog, "[{$import['part_number']}] => [{$partAnalog['part_analog']}]");
                            } else {
                                $options['part_number'] = Decoder::strToWindows(trim($import['part_number']));
                            }

                            $options['so_number'] = Decoder::strToWindows(trim($import['so_number']));
                            $options['note'] = $note;
                            $mName = Products::checkPurchasesPartNumber($options['part_number']);
                            $price = Products::getPricePartNumber($options['part_number'], $user->getId());
                            $options['goods_name'] = $mName['mName'];
                            $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;
                            $options['expected_date'] = null;

                            if($user->getName() == 'Servisexpress'
                                || $user->getName() == 'Technoservice'
                                || $user->getName() == 'Techpoint'){
                                $options['status_name'] = Decoder::strToWindows('Нет в наличии, формируется поставка.');
                            } else {
                                $options['status_name'] = Decoder::strToWindows('Нет в наличии, формируется поставка, ориентировочная дата поставки на наш склад ');
                                $options['expected_date'] = Functions::whatDayOfTheWeekAndAdd(date('Y-m-d'));
                            }

                            $options['created_on'] = date('Y-m-d H:i:s');
                            $options['order_type_id'] = $_REQUEST['order_type_id'];
                            $options['note1'] = !empty($import['note1']) ? Decoder::strToWindows($import['note1']): null;
                            $options['created_by'] = $user->getId();

                            // Прошить на PNC 1-yes/0-no
                            $options['is_npc'] = 0;

                            $quantity = !empty($import['quantity']) ? $import['quantity'] : 1;
                            $so_number = $options['so_number'];
                            if(!empty($options['part_number'])) {
                                for ($i = 1; $i <= $quantity; $i++) {
                                    // Если кол-во больше 1, номеруем каждую заявку
                                    if ($quantity > 1) {
                                        $options['so_number'] = $so_number . ' (' . $i . ')';
                                    }
                                    Request::addReserveOrdersMsSQL($options);
                                }
                            }
                        }
                        Session::set('add_request', 'Out of stock, delivery is forming');
                        if(count($arrayReplaceAnalog) > 0){
                            $partImplode = implode(', ', $arrayReplaceAnalog);
                            Session::set('replace_by_analog', "Part numbers is replaced by an analog {$partImplode}");
                        }

                        Logger::getInstance()->log($user->getId(), ' загрузил массив с excel в Request');
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
     * @throws \Exception
     */
    public function actionPricePartNumAjax()
    {
        $user = $this->user;
        $group = new Group();

        // Для electrolux
        if($_REQUEST['action'] == 'part-price') {
            $part_number = $_REQUEST['part_number'];

            $stocks_group = $group->stocksFromGroup($user->idGroupUser($user->getId()), 'name', 'request');

            $result = Products::getPricePartNumber($part_number, $user->getId());
            $partInStock = Stocks::checkGoodsInStocksPartners($user->getId(), $stocks_group, $part_number);
            $partNumberAnalog = PartAnalog::getAnalogByPartNumber($part_number);

            if($partNumberAnalog['type_part'] == 'available'){
                $data['is_available'] = 1;
                $data['comment'] = 'Парт номер не доступен к заказу: ' . $partNumberAnalog['comment'];
            } else {
                if($partNumberAnalog['type_part'] == 'analog'){
                    $price = Products::getPricePartNumber($partNumberAnalog['part_analog'], $user->id_user);
                    $data['is_analog'] = 1;
                    $data['message'] = 'Парт номер будет заменен на аналог ';
                    $data['analog'] = $partNumberAnalog['part_analog'];
                    $data['analog_price'] = round($price['price'], 2);
                }
            }

            if($result == 0){
                $data['result'] = 0;
                $data['action'] = 'not_found';
            } else {
                $data['result'] = 1;
                $data['action'] = 'purchase';
                $data['price'] = round($result['price'], 2);
                $data['mName'] = Decoder::strToUtf($result['mName']);
                if($partInStock){
                    $data['in_stock'] = 1;
                    $data['stock'] = Decoder::strToUtf($partInStock['stock_name']);
                    $data['quantity'] = $partInStock['quantity'] . ' Units';
                }
            }
            print_r(json_encode($data));
        }

        // Для партнеров Lenovo
        if($_REQUEST['action'] == 'part-stock') {
            $part_number = $_REQUEST['part_number']; //SB18C01336
            $quantity = $_REQUEST['quantity'];
            $user_id = $_REQUEST['user_id'];
            $result = [];

            $userID = $user_id != 'false' ? $user_id : $user->getId();

            $userClient = Admin::getInfoGmUser($userID);

            $infoPart = Products::checkPartNumberInGM($part_number);
            //$stocks_group = explode(',', 'BAD,Not Used,Restored,Dismantling,Local Source'); 12
            $stocks_group = $group->stocksFromGroup($user->idGroupUser($userID), 'name', 'request');
            $stockService = new StockService();
            $partInStock = $stockService->checkInStockAndReplaceName($userID, $stocks_group, $part_number);

            if(sizeof($partInStock) > 0){
                $result['status'] = 200;
                $result['user_role'] = $user->getRole();
                $result['request_user_id'] = $userID;
                $result['user_currency'] = $userClient['ShortName'];
                $result['rate_currency_usd'] = Currency::getRatesCurrency('usd')['OutputRate'];
                $result['rate_currency_euro'] = Currency::getRatesCurrency('euro')['OutputRate'];
                $result['stocks'] = Decoder::arrayToUtf($partInStock);
            } else {
                $result['status'] = 404;
                $result['user_role'] = $user->role;
                $result['user_request_id'] = $userID;
                $result['user_currency'] = $userClient['ShortName'];
                $result['rate_currency_usd'] = Currency::getRatesCurrency('usd')['OutputRate'];
                $result['rate_currency_euro'] = Currency::getRatesCurrency('euro')['OutputRate'];
            }
            $result['goods_name'] = Decoder::arrayToUtf([$infoPart['mName']]);

            print_r(json_encode($result));
        }
        return true;
    }


    /**
     * @return bool
     * @throws \Exception
     */
    public function actionRequestAjax()
    {
        $user = $this->user;
        $group = new Group();
        $stockService = new StockService();

        // Редактируем парт номер
        if($_REQUEST['action'] == 'edit_pn'){
            $id_order = $_REQUEST['id_order'];
            $order_pn = trim($_REQUEST['order_pn']);

            $requestInfo = Request::getOrderRequestInfo($id_order);

            $ok = Request::editPartNumberFromCheckOrdersById($id_order, $order_pn);
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
            $order_so = trim(Decoder::strToWindows($_REQUEST['order_so']));

            $ok = Request::editSoNumberFromCheckOrdersById($id_order, $order_so);
            if($ok){
                Logger::getInstance()->log($user->getId(), ' изменил so number в request #' . $id_order . ' на ' . $order_so);
                print_r(200);
            }
        }

        // Очищаем название парт номера
        if($_REQUEST['action'] == 'clear_goods_name'){
            $id_order = $_REQUEST['id_order'];
            $goods_name = null;

            $ok = Request::clearGoodsNameFromCheckOrdersById($id_order, $goods_name);
            if($ok){
                Logger::getInstance()->log($user->getId(), ' очистил(а) название part_number в request #' . $id_order);
                print_r(200);
            }
        }

        if($_REQUEST['action'] == 'edit_status'){
            $id_order = $_REQUEST['id_order'];
            $order_status = trim(Decoder::strToWindows($_REQUEST['order_status']));
            $expected_date = !empty($_REQUEST['expected_date']) ? $_REQUEST['expected_date'] : null;

            $requestInfo = Request::getOrderRequestInfo($id_order);

            $ok = Request::editStatusFromCheckOrdersById($id_order, $order_status, $expected_date);
            if($ok){
                $userRequest = Admin::getAdminById($requestInfo['site_account_id']);
                $oldStatus = $requestInfo['status_name'] . ' ' . $requestInfo['expected_date'];
                $newStatus = $order_status. ' ' . $expected_date;

                RequestMail::getInstance()->sendEmailEditStatus($id_order, $oldStatus, $newStatus, $userRequest['email']);

                Logger::getInstance()->log($user->id_user, ' изменил Status в request #' . $id_order);
                print_r(200);
            }
        }


        // Редактируем парт номер и аналог
        if($_REQUEST['action'] == 'edit_pn_analog'){
            $id_record = $_REQUEST['id_record'];
            $part_number = $_REQUEST['part_number'];
            $part_analog = $_REQUEST['part_analog'];
            $r_comment = $_REQUEST['r_comment'];

            $ok = PartAnalog::updatePartNumberAndAnalog($id_record, $part_number, $part_analog, $r_comment);
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

            $userRequestId = (int)$_REQUEST['request_user_id'];

            $options['site_account_id'] = $userRequestId;
            $options['part_number'] = trim($_REQUEST['multi_part_number']);
            if($_REQUEST['part_quantity'] > $_REQUEST['stock_count'] && $_REQUEST['stock_name'] != 'ЗАПРОС НА ПОСТАВКУ'){
                $options['part_quantity'] = $_REQUEST['stock_count'];
            } else {
                $options['part_quantity'] = $_REQUEST['part_quantity'];
            }
            $options['goods_name'] = Decoder::strToWindows($_REQUEST['goods_name']);
            $price = Products::getPricePartNumber($options['part_number'], $userRequestId);
            //$options['price'] = ($price['price'] != 0) ? round($price['price'], 2) : 0;
            $options['price'] = ($_REQUEST['pn_price'] != 0) ? $_REQUEST['pn_price'] : $price['price'];
            $options['number'] = $lastNumber;
            $options['period'] = $_REQUEST['period'];
            $options['user_currency'] = $_REQUEST['user_currency'];
            $options['note1'] = $_REQUEST['note1'];
            $options['stock_id'] = isset($_REQUEST['stock_id']) ? $_REQUEST['stock_id'] : null;
            $options['stock_name'] = $_REQUEST['stock_name'];
            if($options['stock_name'] == 'НОВЫЙ(UA)' || $options['stock_name'] == 'БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ'){
                $options['used'] = 0;
            } else if($options['stock_name'] == 'БУ(UA)' || $options['stock_name'] == 'БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ'){
                $options['used'] = 1;
            } else {
                $options['used'] = 0;
            }
            $options['stock_count'] = ($_REQUEST['stock_count'] != 0) ? $_REQUEST['stock_count'] : 0;

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
                $options['used'] = $product['used'];
                $options['created_by'] = $user->getId();
                $stock_count = $product['stock_count'];

                for ($i = 1; $i <= $options['part_quantity']; $i++) {

                    if ($stock_count >= $i) {
                        $options['stock_id'] = $product['stock_id'];
                        $stock_name = Stocks::replaceNameStockInResultTable($product['stock_name'], 'partner');
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
                $html .= "<td>" . round($item['price'], 2) . ' ' .  $user->getInfoUserGM()['ShortName'] . "</td>";
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

        if($_REQUEST['action'] == 'send_content_manager'){
            $part_number = $_REQUEST['part_number'];
            $part_desc= $_REQUEST['part_desc'];
            RequestMail::getInstance()->sendEmailContentManager($part_number, $part_desc);
            print_r(200);
        }

        // Поиск аналогов в GM
        if($_REQUEST['action'] == 'find_analog_gm'){
            $part_number = trim($_REQUEST['part_number']);
            $userID = (int)$_REQUEST['user_id'];
            $listAnalog = Decoder::arrayToUtf(PartAnalog::getAnalogByPartNumberMsSQL($part_number));

            $stocks_group = $group->stocksFromGroup($user->idGroupUser($userID), 'name', 'request');

            $analogPartInStocks = [];
            $i = 0;
            foreach ($listAnalog as $part){
                $analogPartInStocks[$i]['PartNumber'] = $part['PartNumber'];
                $analogPartInStocks[$i]['mName'] = $part['mName'];
                $analogPartInStocks[$i]['stocks'] = $stockService->checkInStockAndReplaceName($userID, $stocks_group, $part['PartNumber']);
                $i++;
            }
            $this->render('admin/crm/request/_part/request_part_analog_show', compact('analogPartInStocks', 'user'));
        }

        return true;
    }


    /**
     * Edit status from import excel
     * @return bool
     * @throws \Exception
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

                $randomName = $user->getName() . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importStatusInRequest($excel_file);

                        foreach ($excelArray as $import){

                            $id = $import['id'];
                            $status_name = Decoder::strToWindows($import['status_name']);
                            $expected_date = Decoder::strToWindows($import['expected_date']);
                            $requestInfo = Request::getOrderRequestInfo($id);
                            $ok = Request::editStatusFromCheckOrdersById($id, $status_name, $expected_date);
                            if($ok){
                                $oldStatus = $requestInfo['status_name'] . ' ' . $requestInfo['expected_date'];
                                $newStatus = $import['status_name'] . ' ' . $import['expected_date'];
                                $partnersEmails = Admin::getAdminById($requestInfo['site_account_id']);
                                RequestMail::getInstance()->sendEmailEditStatus($id, $oldStatus, Decoder::strToWindows($newStatus), $partnersEmails);
                            }
                        }
                        Logger::getInstance()->log($user->getId(), ' изменил(а) статусы в Request с excel');
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

        if($ok){
            Logger::getInstance()->log($user->getId(), 'переместил в корзину request #' . $id);
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

        $filter = '';
        if(isset($_REQUEST['type_filter'])){
            if($_REQUEST['type_filter'] != 'all'){
                $filter .= " AND type_part = '{$_REQUEST['type_filter']}'";
            }
        }

        $listPartAnalog = PartAnalog::getListPartAnalog($filter);

        if(isset($_POST['add-analog']) && $_POST['add-analog'] == 'true'){
            $options['part_number'] = $_POST['r_part_number'];
            $options['part_analog'] = $_POST['r_part_analog'];
            $options['type_part'] = 'analog';
            $options['comment'] = null;

            $ok = PartAnalog::addPartAnalog($options);
            if($ok){
                Url::previous();
            }
        }

        if(isset($_POST['add-not-available']) && $_POST['add-not-available'] == 'true'){
            $options['part_number'] = $_POST['r_part_number'];
            $options['part_analog'] = null;
            $options['type_part'] = 'available';
            $options['comment'] = $_POST['comment'];


            $ok = PartAnalog::addPartAnalog($options);
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

                $randomName = $user->getName() . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importPartNumberAnalog($excel_file);

                        foreach ($excelArray as $import){

                            $options['part_number'] = $import['part_number'];
                            $options['part_analog'] = $import['part_analog'];
                            $options['type_part'] = $_REQUEST['type_part'];
                            $options['comment'] = $import['comment'];
                            PartAnalog::addPartAnalog($options);

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
     * @throws \Exception
     */
    public function actionExportRequests()
    {
        $user = $this->user;
        $filter = '';

        $start =  isset($_REQUEST['start']) ? $_REQUEST['start'] .' 00:00' : '';
        $end =  isset($_REQUEST['end']) ? $_REQUEST['end'] .' 23:59' : '';

        $processed = (int)$_REQUEST['processed'];
        if(isset($processed)){
            $filter .= " AND sgog.processed = {$processed}";
        }

        $type = (int)$_REQUEST['order_type_id'];
        if($type != 'all'){
            $filter .= " AND sgog.order_type_id = '{$type}'";
        }

        $id_partners = isset($_REQUEST['id_partner']) ? $_REQUEST['id_partner'] : [];
        $listExport = Decoder::arrayToUtf(Request::getExportRequestsByPartners($id_partners, $start, $end, $filter));

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


    /**
     *
     * @return bool
     * @throws \Exception
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function actionAllUkrainePrice()
    {
        $listPrice = [];
        if($this->user->isAdmin() || $this->user->isManager()){
            $listPrice = Decoder::arrayToUtf(Price::getAllPriceMsSQL());
        } elseif ($this->user->isPartner()){
            $listPrice = Decoder::arrayToUtf(Price::getAllPriceMsSQL());
        }
        ExportExcel::exportRequestAllPrice($listPrice);
        return true;
    }

}