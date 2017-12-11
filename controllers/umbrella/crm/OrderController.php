<?php
namespace Umbrella\controllers\umbrella\crm;

use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\components\ImportExcel;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\Currency;
use Umbrella\models\GroupModel;
use Umbrella\models\Orders;
use Umbrella\models\Products;
use Umbrella\models\Stocks;

/**
 * Class OrderController
 */
class OrderController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * OrderController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.orders', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * @param string $filter
     *
     * @return bool
     * @throws \Exception
     */
    public function actionOrders($filter = "")
    {
        $user = $this->user;

        $delivery_address = $user->getDeliveryAddress();
        $order_type = Decoder::arrayToUtf(Orders::getAllOrderTypes());

        Session::destroy('error_orders');
        Session::destroy('error_orders_text');

        if(isset($_REQUEST['send_excel_file']) && $_REQUEST['send_excel_file'] == 'true'){
            // Полачем последний номер покупки
            if(!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_order/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                // Получаем расширение файла
                $getMime = explode('.', $options['name_real']);
                $mime = end($getMime);

                $randomName = $getMime['0'] . "-" . $randomName . "." . $mime;
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла

                        $excelArray = ImportExcel::importOrder($excel_file);

                        // От какого пользователя идет запрос
                        $options['id_user'] = $_REQUEST['id_partner'];

                        // Выбранный сток
                        $stock = Decoder::strToWindows($_REQUEST['stock']);
                        $note = '';
                        $note_mysql = '';
                        if(isset($_REQUEST['notes'])){
                            $note = Decoder::strToWindows($_REQUEST['notes']);
                            $note_mysql = $_REQUEST['notes'];
                        }

                        $insertArray = [];
                        $arr_error_pn = [];
                        $i = 0;
                        $lastId = Orders::getLastOrdersId();
                        // собираем массив где парт номера найдены в базе
                        foreach($excelArray as $excel){

                            if($lastId == false){
                                $lastId = 100;
                            }
                            $lastId++;
                            //Проверяем парт номер на наличие в базе
                            $success = Products::checkOrdersPartNumberMsSql($options['id_user'], $excel['part_number'], $stock);
                            if($success != 0){
                                $insertArray[$i]['site_id'] = $lastId;
                                $insertArray[$i]['id_user'] = $options['id_user'];
                                $insertArray[$i]['part_number'] = $excel['part_number'];
                                $insertArray[$i]['goods_name'] = $success['goods_name'];
                                if($stock == 'BAD'){
                                    // если кол-во из excel больше чем в базе, заносим макс значение которое есть в базе по этому парт номеру
                                    if ($excel['quantity'] > $success['quantity']){
                                        $insertArray[$i]['quantity'] = $success['quantity'];
                                    } else {
                                        $insertArray[$i]['quantity'] = $excel['quantity'];
                                    }
                                } else {
                                    $insertArray[$i]['quantity'] = 1;
                                }
                                $insertArray[$i]['so_number'] = $excel['so_number'];
                                $i++;
                            } else {
                                array_push($arr_error_pn, $excel['part_number']);
                            }
                        }

//                        echo "<pre>";
//                        print_r($insertArray);

                        // Пишем в сессию массив с ненайденными партномерами
                        Session::set('error_orders', $arr_error_pn);

                        if(count($insertArray) > 0){
                            Session::set('error_orders_text', 'Заказы созданы кроме парт номеров, которые не найдены на складе ' . $stock . ':');
                            foreach ($insertArray as $insert){
                                $options['site_id'] = $insert['site_id'];
                                $options['id_user'] = $insert['id_user'];
                                $options['so_number'] = Decoder::strToWindows($insert['so_number']);
                                $options['note'] = $note;
                                $options['order_type_id'] = $_REQUEST['order_type_id'];
                                $options['note_mysql'] = $note_mysql;
                                $options['ready'] = 1;
                                Orders::addOrdersMsSQL($options);
                                Orders::addOrders($options);

                                $options['part_number'] = $insert['part_number'];
                                $options['goods_mysql_name'] = $insert['goods_name'];
                                $options['goods_name'] = Decoder::strToWindows($insert['goods_name']);
                                $options['stock_name'] = $stock;
                                $options['quantity'] = $insert['quantity'];
                                Orders::addOrdersElementsMsSql($options);
                                Orders::addOrdersElements($options);
                            }
                            Logger::getInstance()->log($user->id_user, 'импортировал массив с excel в Orders');
                        } else {
                            Session::set('error_orders_text', 'Заказы не созданы, так как не один парт номер не найден на складе ' . $stock . ':');
                        }
                    }
                }

                Url::redirect('/adm/crm/orders_success');
            }
        }


        if($user->isPartner() || $user->isManager()) {

            $filter = "";
            $status = "";
            $interval = "";

            if($user->isPartner()){
                $interval = " AND sgo.created_on >= DATEADD(day, -14, GETDATE())";
            }

            if($user->isManager()){
                $status_1 = Decoder::strToWindows( 'Предварительный');
                $status_2 = Decoder::strToWindows( 'В обработке');
                $status_3 = Decoder::strToWindows('Резерв');
                $status = " AND (sgo.status_name = '$status_1' OR sgo.status_name = '$status_2' OR sgo.status_name = '$status_3')";
            }

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgo.created_on BETWEEN '$start' AND '$end'";
                $interval = "";
                $status = "";
            }
            $filter .= $interval;
            $filter .= $status;

            $allOrders = Orders::getOrdersByPartnerMsSql($user->controlUsers($user->id_user), $filter);

            // Параметры для формирование фильтров
            $user_ids = $user->controlUsers($user->id_user);
            $partnerList = Admin::getPartnerControlUsers($user_ids);

        } else if($user->isAdmin()){

            $filter = "";
            $status_1 = Decoder::strToWindows( 'Предварительный');
            $status_2 = Decoder::strToWindows('В обработке');
            $status_3 = Decoder::strToWindows('Резерв');
            $interval = " AND (sgo.status_name = '$status_1' OR sgo.status_name = '$status_2' OR sgo.status_name = '$status_3')";

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgo.created_on BETWEEN '$start' AND '$end'";
                $interval = "";
            }
            $filter .= $interval;
            $allOrders = Orders::getAllOrdersMsSql($filter);

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

            $partnerList = Admin::getAllPartner();
        }

        $this->render('admin/crm/orders/orders', compact('partnerList', 'allOrders',
            'delivery_address', 'user', 'userInGroup', 'order_type'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionOrdersSuccess()
    {
        $user = $this->user;

        $arr_error_pn = Session::pull('error_orders');
        $arr_error_text = Session::pull('error_orders_text');
        $order_type = Decoder::arrayToUtf(Orders::getAllOrderTypes());

        if($user->isPartner() || $user->isManager()) {

            $filter = "";
            $status = "";
            $interval = "";

            if($user->isPartner()){
                $interval = " AND sgo.created_on >= DATEADD(day, -14, GETDATE())";
            }

            if($user->isManager()){
                $status_1 = Decoder::strToWindows( 'Предварительный');
                $status_2 = Decoder::strToWindows('В обработке');
                $status_3 = Decoder::strToWindows('Резерв');
                $status = " AND (sgo.status_name = '$status_1' OR sgo.status_name = '$status_2' OR sgo.status_name = '$status_3')";
            }

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgo.created_on BETWEEN '$start' AND '$end'";
                $interval = "";
                $status = "";
            }
            $filter .= $interval;
            $filter .= $status;

            $allOrders = Orders::getOrdersByPartnerMsSql($user->controlUsers($user->id_user), $filter);

            // Параметры для формирование фильтров
            $user_ids = $user->controlUsers($user->id_user);
            $partnerList = Admin::getPartnerControlUsers($user_ids);

        } else if($user->isAdmin()){

            $filter = "";
            $status_1 = Decoder::strToWindows('Предварительный');
            $status_2 = Decoder::strToWindows('В обработке');
            $interval = " AND (sgo.status_name = '$status_1' OR sgo.status_name = '$status_2')";

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgo.created_on BETWEEN '$start' AND '$end'";
                $interval = "";
            }
            $filter .= $interval;
            $allOrders = Orders::getAllOrdersMsSql($filter);

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
            $partnerList = Admin::getAllPartner();
        }

        $this->render('admin/crm/orders/orders', compact('userInGroup', 'partnerList', 'allOrders',
            'delivery_address', 'user', 'arr_error_pn', 'arr_error_text', 'order_type'));
        return true;
    }


    /**
     * Проверка парт номера
     * @return bool
     */
    public function actionOrdersPartNumAjax()
    {
        $part_number = $_REQUEST['part_number'];
        $stock = Decoder::strToWindows($_REQUEST['stock']);
        $id_partner = $_REQUEST['id_partner'];
        $result = Products::checkOrdersPartNumberMsSql($id_partner, $part_number, $stock);
        if($result == 0){
            print_r($result);
        } else {
            print_r(json_encode($result));
        }
        return true;
    }


    /**
     * @return bool
     */
    public function actionOrdersAjax()
    {
        $user = $this->user;

        $data = $_REQUEST['json'];
        $data_json = json_decode($data, true);
        //print_r($data_json);

        // Полачем последний номер покупки
        $lastId = Orders::getLastOrdersId();
        if($lastId == false){
            $lastId = 100;
        }
        $lastId++;

        $note = null;
        $note_mysql = null;
        if(isset($data_json['note'])){
            $note = Decoder::strToWindows($data_json['note']);
            $note_mysql = $data_json['note'];
        }

        $options['site_id'] = $lastId;
        $options['id_user'] = $data_json['id_partner'];
        $options['so_number'] = Decoder::strToWindows($data_json['service_order']);
        $options['note'] = $note;
        $options['order_type_id'] = $data_json['order_type_id'];;
        $options['note_mysql'] = $note_mysql;
        $options['ready'] = 1;

        $ok = Orders::addOrdersMsSQL($options);
        Orders::addOrders($options);

        if($ok){
            $options['stock_name'] = Decoder::strToWindows($data_json['stock']);
            $options['goods_mysql_name'] = $data_json['goods_name'];
            $options['goods_name'] = Decoder::strToWindows($data_json['goods_name']);
            $options['part_number'] = $data_json['part_number'];
            $options['so_number'] = Decoder::strToWindows($data_json['service_order']);
            if($data_json['quantity'] == ''){
                $options['quantity'] = 1;
            } else {
                $options['quantity'] = $data_json['quantity'];
            }
            Orders::addOrdersElementsMsSql($options);
            Orders::addOrdersElements($options);
            Logger::getInstance()->log($user->id_user, 'совершил новый заказ в Orders ' . $options['part_number']);
            //Успех
            echo 1;
        } else {
            //Неудача
            echo 0;
        }
        return true;
    }

    /**
     * Принимаем\отклоняем заказы
     * @return bool
     */
    public function actionOrdersAction()
    {
        if($_REQUEST['action'] == 'accept'){
            $order_id = $_REQUEST['order_id'];
            $ok = Orders::updateStatusOrders($order_id, 1, NULL);
            if($ok){
                $status['ok'] = 1;
                $status['class'] = 'green';
                $status['text'] = 'Выдан';
            } else {
                $status['ok'] = 0;
                $status['error'] = 'Ошибка подтверждения!';
            }
            echo json_encode($status);
        }

        if($_REQUEST['action'] == 'dismiss'){
            $order_id = $_REQUEST['order_id'];
            $comment = Decoder::strToWindows($_REQUEST['comment']);
            $ok = Orders::updateStatusOrders($order_id, 2, $comment);
            if($ok){
                $status['ok'] = 1;
                $status['class'] = 'red';
                $status['text'] = 'Отказано';
            } else {
                $status['ok'] = 0;
                $status['error'] = 'Ошибка подтверждения!';
            }
            echo json_encode($status);
        }

        if($_REQUEST['action'] == 'return'){
            $request_id = $_REQUEST['request_id'];
            $ok = Orders::returnOrderToRequestById($request_id);
            if($ok){
                $status['ok'] = 1;
                $status['class'] = 'green';
                $status['text'] = 'Вернули в Request';
            } else {
                $status['ok'] = 0;
                $status['error'] = 'Ошибка подтверждения!';
            }
            echo json_encode($status);
        }

        return true;
    }


    /**
     * Показать продукты с заказа
     * @return bool
     * @throws \Exception
     */
    public function actionShowDetailOrders()
    {
        $user = $this->user;

        $order_id = $_REQUEST['order_id'];
        $user_id = $_REQUEST['user_id'];
        $ordersElements = Decoder::arrayToUtf(Orders::getShowDetailsOrdersMsSql($order_id));

        if($user->isPartner()){
            $ordersElements = array_map(function ($value) use ($user) {
                $value['stock_name'] = Stocks::replaceNameStockInResultTable($value['stock_name'], $user->getRole());
                $value['price'] = round($value['price'], 2);
                return $value;
            }, $ordersElements);

        }elseif ($user->isAdmin() || $user->isManager()){

            $userClient = Decoder::arrayToUtf(Admin::getInfoGmUser($user_id));
            $rateCurrencyUsd = Currency::getRatesCurrency('usd')['OutputRate'];
            $rateCurrencyEuro = Currency::getRatesCurrency('euro')['OutputRate'];

            $ordersElements = array_map(function ($value) use ($user, $userClient, $rateCurrencyUsd, $rateCurrencyEuro) {
                if($userClient['ShortName'] == 'usd') {
                    $value['price_usd'] = round($value['price'], 2);
                    $value['price_uah'] = round($value['price'] * $rateCurrencyUsd, 2);
                } elseif ($userClient['ShortName'] == 'uah') {
                    $value['price_usd'] = round($value['price'] / $rateCurrencyUsd, 2);
                    $value['price_uah'] = round($value['price'], 2);
                } elseif ($userClient['ShortName'] == 'euro') {
                    $value['price_euro'] = round($value['price'], 2);
                    $value['price_uah'] = round($value['price'] * $rateCurrencyEuro, 2);
                }
                $value['stock_name'] = Stocks::replaceNameStockInResultTable($value['stock_name'], $user->getRole());
                $value['price'] = round($value['price'], 2);
                return $value;
            }, $ordersElements);

            $sumPriceUsd = 0;
            $sumPriceEuro = 0;
            $sumPriceUah = 0;
            foreach ($ordersElements as $element){
                if($userClient['ShortName'] == 'usd' || $userClient['ShortName'] == 'uah') {
                    $sumPriceUsd += $element['quantity'] * $element['price_usd'];
                    $sumPriceUah += $element['quantity'] * $element['price_uah'];
                } elseif ($userClient['ShortName'] == 'euro') {
                    $sumPriceEuro += $element['quantity'] * $element['price_euro'];
                    $sumPriceUah += $element['quantity'] * $element['price_uah'];
                }
            }
        }

        $this->render('admin/crm/orders/_part/show_details', compact('user', 'ordersElements',
            'sumPriceUsd', 'sumPriceUah', 'sumPriceEuro', 'userClient'));
        return true;
    }


    /**
     * генерация таблицы заказов для экспорта
     * @return bool
     */
    public function actionExportOrders()
    {
        $user = $this->user;

        $filter = '';
        $start =  isset($_POST['start']) ? $_POST['start'] .' 00:00' : '';
        $end =  isset($_POST['end']) ? $_POST['end'] .' 23:59' : '';
        if(!empty($_POST['status_name'])){
            $status = Decoder::strToWindows(trim($_POST['status_name']));
            $filter .= " AND sgo.status_name = '$status'";
        }
        if(!empty($_POST['order_type_id'])){
            $type_repair = Decoder::strToWindows((int)$_POST['order_type_id']);
            $filter .= " AND sgo.order_type_id = '$type_repair'";
        }
        $id_partners = isset($_POST['id_partner']) ? $_POST['id_partner'] : [];
        $listExport = Decoder::arrayToUtf(Orders::getExportOrdersByPartner($id_partners, $start, $end, $filter));

        $this->render('admin/crm/export/orders', compact('user', 'listExport'));
        return true;
    }



    public function actionSearch()
    {
        $user = $this->user;

        if($user->isPartner() || $user->isManager()) {

            $search = iconv('UTF-8', 'WINDOWS-1251', trim($_REQUEST['search']));

            $idS = implode(',', $user->controlUsers($user->id_user));
            $filter = " AND sgo.site_account_id IN($idS)";
            $allOrders = Orders::getSearchInOrders($search, $filter);

            // Параметры для формирование фильтров
            $user_ids = $user->controlUsers($user->id_user);
            $partnerList = Admin::getPartnerControlUsers($user_ids);

        } else if($user->isAdmin()){

            $search = iconv('UTF-8', 'WINDOWS-1251', trim($_REQUEST['search']));

            $allOrders = Orders::getSearchInOrders($search);

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

        $this->render('admin/crm/orders/orders_search', compact('userInGroup', 'partnerList', 'allOrders',
            'delivery_address', 'user', 'arr_error_pn', 'arr_error_text', 'search'));
        return true;
    }

}