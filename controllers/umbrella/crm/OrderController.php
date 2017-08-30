<?php
namespace Umbrella\controllers\umbrella\crm;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\ImportExcel;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\Orders;
use Umbrella\models\Products;

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
     * @return bool
     */
    public function actionOrders($filter = "")
    {
        $user = $this->user;

        $delivery_address = $user->getDeliveryAddress();

        if(isset($_SESSION['error_orders'])){
            unset($_SESSION['error_orders']);
            unset($_SESSION['error_orders_text']);
        }

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
                        $stock = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['stock']);
                        $note = '';
                        $note_mysql = '';
                        if(isset($_REQUEST['notes'])){
                            $note = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['notes']);
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
                        $_SESSION['error_orders'] = $arr_error_pn;

                        if(count($insertArray) > 0){
                            $_SESSION['error_orders_text'] = 'Заказы созданы кроме парт номеров, которые не найдены на складе ' . $stock . ':';
                            foreach ($insertArray as $insert){
                                $options['site_id'] = $insert['site_id'];
                                $options['id_user'] = $insert['id_user'];
                                $options['so_number'] = iconv('UTF-8', 'WINDOWS-1251', $insert['so_number']);
                                $options['note'] = $note;
                                $options['note_mysql'] = $note_mysql;
                                $options['ready'] = 1;
                                Orders::addOrdersMsSQL($options);
                                Orders::addOrders($options);

                                $options['part_number'] = $insert['part_number'];
                                $options['goods_mysql_name'] = $insert['goods_name'];
                                $options['goods_name'] = iconv('UTF-8', 'WINDOWS-1251', $insert['goods_name']);
                                $options['stock_name'] = $stock;
                                $options['quantity'] = $insert['quantity'];
                                Orders::addOrdersElementsMsSql($options);
                                Orders::addOrdersElements($options);
                            }
                            Logger::getInstance()->log($user->id_user, 'импортировал массив с excel в Orders');
                        } else {
                            $_SESSION['error_orders_text'] = 'Заказы не созданы, так как не один парт номер не найден на складе ' . $stock . ':';
                        }
                    }
                }

                header("Location: /adm/crm/orders_success");
            }
        }


        if($user->role == 'partner' || $user->role == 'manager') {

            $filter = "";
            $status = "";
            $interval = "";

            if($user->role == 'partner'){
                $interval = " AND sgo.created_on >= DATEADD(day, -14, GETDATE())";
            }

            if($user->role == 'manager'){
                $status_1 = iconv('UTF-8', 'WINDOWS-1251', 'Предварительный');
                $status_2 = iconv('UTF-8', 'WINDOWS-1251', 'В обработке');
                $status_3 = iconv('UTF-8', 'WINDOWS-1251', 'Резерв');
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
            $new_partner = array();
            if(count($partnerList) > 3){
                $new_partner = array_chunk($partnerList, (int)count($partnerList) / 3);
            } else {
                $new_partner[] = $partnerList;
            }

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin'){

            $filter = "";
            $status_1 = iconv('UTF-8', 'WINDOWS-1251', 'Предварительный');
            $status_2 = iconv('UTF-8', 'WINDOWS-1251', 'В обработке');
            $status_3 = iconv('UTF-8', 'WINDOWS-1251', 'Резерв');
            $interval = " AND (sgo.status_name = '$status_1' OR sgo.status_name = '$status_2' OR sgo.status_name = '$status_3')";

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgo.created_on BETWEEN '$start' AND '$end'";
                $interval = "";
            }
            $filter .= $interval;
            //$allOrders = Orders::getAllOrders($filter);
            $allOrders = Orders::getAllOrdersMsSql($filter);

            // Параметры для формирование фильтров
            $partnerList = Admin::getAllPartner();
            $new_partner = array_chunk($partnerList, (int)count($partnerList) / 3);
        }

        //require_once(ROOT . '/views/admin/crm/orders.php');
        $this->render('admin/crm/orders', compact('new_partner', 'partnerList', 'allOrders', 'delivery_address', 'user'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionOrdersSuccess()
    {
        $user = $this->user;

        $arr_error_pn = (isset($_SESSION['error_orders'])) ? $_SESSION['error_orders'] : '';
        $arr_error_text = (isset($_SESSION['error_orders_text'])) ? $_SESSION['error_orders_text'] : '';

        if($user->role == 'partner' || $user->role == 'manager') {

            $filter = "";
            $status = "";
            $interval = "";

            if($user->role == 'partner'){
                $interval = " AND sgo.created_on >= DATEADD(day, -14, GETDATE())";
            }

            if($user->role == 'manager'){
                $status_1 = iconv('UTF-8', 'WINDOWS-1251', 'Предварительный');
                $status_2 = iconv('UTF-8', 'WINDOWS-1251', 'В обработке');
                $status_3 = iconv('UTF-8', 'WINDOWS-1251', 'Резерв');
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
            if(count($partnerList) > 3){
                $new_partner = array_chunk($partnerList, (int)count($partnerList) / 3);
            } else {
                $new_partner[] = $partnerList;
            }

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin'){

            $filter = "";
            $status_1 = iconv('UTF-8', 'WINDOWS-1251', 'Предварительный');
            $status_2 = iconv('UTF-8', 'WINDOWS-1251', 'В обработке');
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
            $partnerList = Admin::getAllPartner();
            $new_partner = array_chunk($partnerList, (int)count($partnerList) / 3);
        }

        //require_once(ROOT . '/views/admin/crm/orders.php');
        $this->render('admin/crm/orders', compact('new_partner', 'partnerList', 'allOrders', 'delivery_address', 'user', 'arr_error_pn', 'arr_error_text'));
        return true;
    }


    /**
     * Проверка парт номера
     * @return bool
     */
    public function actionOrdersPartNumAjax()
    {
        $part_number = $_REQUEST['part_number'];
        $stock = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['stock']);
        $id_partner = $_REQUEST['id_partner'];
        $result = Products::checkOrdersPartNumberMsSql($id_partner, $part_number, $stock);
        if($result == 0){
            print_r($result);
        } else {
            //print_r($result['quantity']);
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
            $note = iconv('UTF-8', 'WINDOWS-1251', $data_json['note']);
            $note_mysql = $data_json['note'];
        }

        $options['site_id'] = $lastId;
        $options['id_user'] = $data_json['id_partner'];
        $options['so_number'] = iconv('UTF-8', 'WINDOWS-1251', $data_json['service_order']);
        $options['note'] = $note;
        $options['note_mysql'] = $note_mysql;
        $options['ready'] = 1;

        $ok = Orders::addOrdersMsSQL($options);
        Orders::addOrders($options);

        if($ok){
            $options['stock_name'] = iconv('UTF-8', 'WINDOWS-1251', $data_json['stock']);
            $options['goods_mysql_name'] = $data_json['goods_name'];
            $options['goods_name'] = iconv('UTF-8', 'WINDOWS-1251', $data_json['goods_name']);
            $options['part_number'] = $data_json['part_number'];
            $options['so_number'] = iconv('UTF-8', 'WINDOWS-1251', $data_json['service_order']);
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
            $comment = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['comment']);
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
     */
    public function actionShowDetailOrders()
    {
        $order_id = $_REQUEST['order_id'];
        $data = Orders::getShowDetailsOrdersMsSql($order_id);
        $html = "";
        foreach($data as $item){
            $html .= "<tr>";
            $html .= "<td>" . $item['part_number'] . "</td>";
            $html .= "<td>" . iconv('WINDOWS-1251', 'UTF-8', $item['goods_name']) . "</td>";
            $html .= "<td>" . iconv('WINDOWS-1251', 'UTF-8', $item['so_number']) . "</td>";
            $html .= "<td>" . iconv('WINDOWS-1251', 'UTF-8', $item['stock_name']) . "</td>";
            $html .= "<td>" . $item['quantity'] . "</td>";
            $html .= "<td>" . round($item['price'], 2) . "</td>";
            $html .= "</tr>";
        }
        print_r($html);
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
            $status = iconv('UTF-8', 'WINDOWS-1251', trim($_POST['status_name']));
            $filter .= " AND sgo.status_name = '$status'";
        }
        $id_partners = isset($_POST['id_partner']) ? $_POST['id_partner'] : [];
        $listExport = Orders::getExportOrdersByPartner($id_partners, $start, $end, $filter);

        $this->render('admin/crm/export/orders', compact('user', 'listExport'));
        return true;
    }

}