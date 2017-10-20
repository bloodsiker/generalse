<?php
namespace Umbrella\controllers\umbrella\crm;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\ImportExcel;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\GroupModel;
use Umbrella\models\Products;
use Umbrella\models\Purchases;

/**
 * Class PurchaseController
 */
class PurchaseController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * PurchaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.purchase', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * Покупки
     * @return bool
     */
    public function actionPurchase()
    {
        $user = $this->user;

        $partnerList = Admin::getAllPartner();

        if(isset($_SESSION['error_purchase'])){
            unset($_SESSION['error_purchase']);
        }
        if(isset($_SESSION['error_check_stock'])){
            unset($_SESSION['error_check_stock']);
        }

        if(isset($_POST['send_excel_file']) && $_REQUEST['send_excel_file'] == 'true'){
            // Полачем последний номер покупки
            $lastId = Purchases::getLastPurchasesId();
            if($lastId == false){
                $lastId = 100;
            }
            $lastId++;

            if(!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_purchase/";
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
                        $excelArray = ImportExcel::importPurchase($excel_file);

                        $insertArray = [];
                        $arr_error_pn = [];
                        $arr_check_stock = [];
                        $i = 0;
                        $n = 0;
                        $m = 0;
                        $stock = iconv('UTF-8', 'WINDOWS-1251', $_POST['stock']);
                        // собираем массив где парт номера найдены в базе
                        foreach($excelArray as $excel){
                            // если выбран сток Local Source, ищем на других складах, естли такие продукты
                            if($stock == 'Local Source'){
                                $result = 0;
                                $id_partner = $_POST['id_partner'];
                                $result = Products::checkPurchasesPartNumberInStocks($id_partner ,$excel['part_number']);

                                // если нету на складах Restored, Dismantling, Not Used, то совершаем покупку
                                if($result == 0){
                                    $result = Products::checkPurchasesPartNumber($excel['part_number']);
                                    if($result == 0){
                                        array_push($arr_error_pn, $excel['part_number']);
                                    } else {
                                        $insertArray[$m]['site_id'] = $lastId;
                                        $insertArray[$m]['part_number'] = $excel['part_number'];
                                        $insertArray[$m]['goods_name'] = iconv('WINDOWS-1251', 'UTF-8', $result['mName']);
                                        $insertArray[$m]['quantity'] = $excel['quantity'];
                                        $insertArray[$m]['price'] = str_replace(',','.', $excel['price']);
                                        $insertArray[$m]['so_number'] = $excel['so_number'];
                                        $m++;
                                    }
                                    // Иначе уведомляем пользовател о наличии товаров на складах
                                } else {
                                    $arr_check_stock[$n]['part_number'] = $excel['part_number'];
                                    $arr_check_stock[$n]['stock_name'] = $result['stock_name'];
                                    $arr_check_stock[$n]['mName'] = iconv('WINDOWS-1251', 'UTF-8', $result['mName']);
                                    $n++;
                                }

                            } else {
                                $success = Products::checkPurchasesPartNumber($excel['part_number']);
                                if($success != 0){
                                    $insertArray[$i]['site_id'] = $lastId;
                                    $insertArray[$i]['part_number'] = $excel['part_number'];
                                    $insertArray[$i]['goods_name'] = iconv('WINDOWS-1251', 'UTF-8', $success['mName']);
                                    $insertArray[$i]['quantity'] = $excel['quantity'];
                                    $insertArray[$i]['price'] = str_replace(',','.', $excel['price']);
                                    $insertArray[$i]['so_number'] = $excel['so_number'];
                                    $i++;
                                } else {
                                    array_push($arr_error_pn, $excel['part_number']);
                                }
                            }
                        }

                        // Пишем в сессию массив с ненайденными партномерами
                        $_SESSION['error_purchase'] = $arr_error_pn;
                        // Пишем в сессию массив с ненайденными партномерами на складах
                        $_SESSION['error_check_stock'] = $arr_check_stock;

//                        echo "<pre>";
//                        print_r($insertArray);
                        // Если массив не пустой, то пишем в базу
                        if(count($insertArray) > 0){
                            $options['site_id'] = $lastId;
                            $options['stock_name'] = $stock;
                            $options['so_number'] = '';
                            $options['id_user'] = $_POST['id_partner'];
                            $options['ready'] = 1;
                            $options['file_attache'] = 'excel';
                            $ok = Purchases::addPurchasesMsSQL($options);
                            //$ok = Purchases::addPurchases($options);

                            if($ok){
                                $ok = Purchases::addPurchases($options);
                                // Пишем в массив с покупками в базу
                                foreach($insertArray as $insert){
                                    $okk = Purchases::addPurchasesElementsMsSQL($insert);
                                    //$okk = Purchases::addPurchasesElements($insert);
                                    if($okk){
                                        Purchases::addPurchasesElements($insert);
                                    }
                                }
                                Logger::getInstance()->log($user->id_user, 'загрузил массив с excel в Purchase');
                            }
                        }
                    }
                }

                header("Location: /adm/crm/purchase_success");
            }
        }


        if($user->role == 'partner' || $user->role == 'manager') {

            $filter = "";
            $interval = " AND sgp.created_on >= DATEADD(day, -7, GETDATE())";

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgp.created_on BETWEEN '$start' AND '$end'";
                $interval = '';
            }
            $filter .= $interval;
            $listPurchases = Purchases::getPurchasesByPartnerMsSql($user->controlUsers($user->id_user), $filter);

            // Параметры для формирование фильтров
            $user_ids = $user->controlUsers($user->id_user);
            $partnerList = Admin::getPartnerControlUsers($user_ids);

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin'){

            $filter = "";
            $interval = " AND sgp.created_on >= DATEADD(day, -7, GETDATE())";

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgp.created_on BETWEEN '$start' AND '$end'";
                $interval = '';
            }
            $filter .= $interval;

            $listPurchases = Purchases::getAllPurchasesMsSql($filter);

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

        $this->render('admin/crm/purchase/purchase', compact('user', 'userInGroup', 'partnerList', 'listPurchases'));
        return true;

    }

    /**
     * Страница благодарности за покупку после импорта
     * @return bool
     */
    public function actionPurchaseSuccess()
    {
        $user = $this->user;

        $partnerList = Admin::getAllPartner();

        $arr_error_pn = $_SESSION['error_purchase'];
        $arr_check_stock = $_SESSION['error_check_stock'];

        if($user->role == 'partner' || $user->role == 'manager') {

            $filter = "";
            $interval = " AND sgp.created_on >= DATEADD(day, -7, GETDATE())";

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgp.created_on BETWEEN '$start' AND '$end'";
                $interval = '';
            }
            $filter .= $interval;
            $listPurchases = Purchases::getPurchasesByPartnerMsSql($user->controlUsers($user->id_user), $filter);

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
            $interval = " AND sgp.created_on >= DATEADD(day, -7, GETDATE())";

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgp.created_on BETWEEN '$start' AND '$end'";
                $interval = '';
            }
            $filter .= $interval;
            //$listPurchases = Purchases::getAllPurchases($filter);
            $listPurchases = Purchases::getAllPurchasesMsSql($filter);

            // Параметры для формирование фильтров
            $partnerList = Admin::getAllPartner();
            $new_partner = array_chunk($partnerList, (int)count($partnerList) / 3);
        }

        $this->render('admin/crm/purchase/purchase', compact('user', 'new_partner', 'partnerList', 'listPurchases', 'arr_error_pn', 'arr_check_stock'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionPurchasePartNumAjax()
    {
        $part_number = $_REQUEST['part_number'];
        $stock = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['stock']);
        // если выбран сток Local Source, ищем на других складах, естли такие продукты
        if($stock == 'Local Source'){

            $result = 0;

            $id_partner = $_REQUEST['id_partner'];
            $result = Products::checkPurchasesPartNumberInStocks($id_partner ,$part_number);

            // если нету на складах Restored, Dismantling, Not Used, то совершаем покупку
            if($result == 0){

                $result = Products::checkPurchasesPartNumber($part_number);
                if($result == 0){
                    $data['result'] = 0;
                    $data['action'] = 'not_found';
                    print_r(json_encode($data));
                } else {
                    $data['result'] = 1;
                    $data['action'] = 'purchase';
                    //$data['mName'] = $result['mName'];
                    $data['mName'] = iconv('WINDOWS-1251', 'UTF-8', $result['mName']);
                    print_r(json_encode($data));
                }
                // Иначе уведомляем пользовател о наличии товаров на складах
            } else {
                $data['result'] = 1;
                $data['action'] = 'stock';
                $data['stock_name'] = $result['stock_name'];
                //$data['mName'] = $result['mName'];
                $data['mName'] = iconv('WINDOWS-1251', 'UTF-8', $result['mName']);
                print_r(json_encode($data));
            }

            // Совершаем покупку
        } else {
            $result = Products::checkPurchasesPartNumber($part_number);
            if($result == 0){
                $data['result'] = 0;
                $data['action'] = 'not_found';
                print_r(json_encode($data));
            } else {
                $data['result'] = 1;
                $data['action'] = 'purchase';
                //$data['mName'] = $result['mName'];
                $data['mName'] = iconv('WINDOWS-1251', 'UTF-8', $result['mName']);
                print_r(json_encode($data));
            }
        }

        return true;
    }


    /**
     * @return bool
     */
    public function actionPurchaseAjax()
    {
        $user = $this->user;

        $data = $_REQUEST['json'];
        $data_json = json_decode($data, true);

        // Полачем последний номер разборки
        $lastId = Purchases::getLastPurchasesId();
        if($lastId == false){
            $lastId = 100;
        }
        $lastId++;

        $options['site_id'] = $lastId;
        $options['stock_name'] = iconv('UTF-8', 'WINDOWS-1251', $data_json['stock']);
        $options['so_number'] = '';
        $options['id_user'] = $data_json['id_partner'];
        $options['ready'] = 1;
        $options['file_attache'] = '';

        $ok = Purchases::addPurchasesMsSQL($options);
        //$ok = Purchases::addPurchases($options);
        if($ok){
            //Если добавили в mssql, добавляем себе в mysql
            Purchases::addPurchases($options);

            $options['part_number'] = $data_json['part_number'];
            $options['goods_name'] = $data_json['goods_name'];
            $options['quantity'] = $data_json['quantity'];
            if(empty($data_json['price'])){
                $options['price'] = 0;
            } else {
                $options['price'] = str_replace(',','.', $data_json['price']);
            }
            $options['so_number'] = $data_json['service_order'];
            $okk = Purchases::addPurchasesElementsMsSQL($options);
            //$okk = Purchases::addPurchasesElementsMsSQL($options);
            if($okk){
                Purchases::addPurchasesElements($options);
                Logger::getInstance()->log($user->id_user, 'совершил покупку в Purchase ' . $options['part_number']);
            }
            //Успех
            echo 1;

        } else {
            //Неудача
            echo 0;
        }

        return true;

    }

    /**
     * Показать продукты с разборки
     * @return bool
     */
    public function actionShowDetailPurchases()
    {
        $site_id = $_REQUEST['site_id'];
        $data = Purchases::getShowDetailsPurchases($site_id);
        //print_r($data);
        $html = "";
        foreach($data as $item){
            $html .= "<tr>";
            $html .= "<td>" . $item['part_number'] . "</td>";
            $html .= "<td>" . $item['so_number'] . "</td>";
            $html .= "<td>" . iconv('WINDOWS-1251', 'UTF-8', $item['goods_name']) . "</td>";
            $html .= "<td>" . $item['quantity'] . "</td>";
            $html .= "<td>" . round($item['price'], 2) . "</td>";
            $html .= "</tr>";
        }

        print_r($html);

        return true;
    }

    /**
     * генерация таблицы покупок для экспорта
     * @return bool
     */
    public function actionExportPurchase()
    {
        $user = $this->user;

        $filter = '';
        $start =  isset($_POST['start']) ? $_POST['start'] .' 00:00' : '';
        $end =  isset($_POST['end']) ? $_POST['end'] .' 23:59' : '';
        if(!empty($_POST['status_name'])){
            $status = iconv('UTF-8', 'WINDOWS-1251', trim($_POST['status_name']));
            $filter .= " AND sgp.status_name = '$status'";
        }
        $id_partners = isset($_POST['id_partner']) ? $_POST['id_partner'] : [];
        $listExport = Purchases::getExportPurchaseByPartner($id_partners, $start, $end, $filter);

        $this->render('admin/crm/export/purchase', compact('user', 'listExport'));
        return true;
    }

}