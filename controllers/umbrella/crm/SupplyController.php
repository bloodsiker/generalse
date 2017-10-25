<?php
namespace Umbrella\controllers\umbrella\crm;

use Josantonius\Session\Session;
use Umbrella\app\AdminBase;
use Umbrella\app\Group;
use Umbrella\app\User;
use Umbrella\components\Functions;
use Umbrella\components\ImportExcel;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\Products;
use Umbrella\models\Supply;

/**
 * Class SupplyController
 */
class SupplyController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * SupplyController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.supply', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * Создание поставки с excel файла
     * @return bool
     */
    public function actionSupply()
    {
        $user = $this->user;

        $supply_error_part = Session::pull('error_supply');

        if ($user->role == 'partner') {
            $allSupply = Supply::getSupplyByPartner($user->idUsersInGroup($user->id_user));
        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){
            $allSupply = Supply::getAllSupply();
        }

        $listPayDesk = Supply::getListPaydesk();

        if (isset($_POST['add_supply']) && $_POST['add_supply'] == 'true') {
            if (!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_supply/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                $randomName = $user->name_partner . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importSupply($excel_file);
//                        echo "<pre>";
//                        print_r($excelArray);

                        $lastId = Supply::getLastSupplyId();
                        if($lastId == false){
                            $lastId = 100;
                        }
                        $lastId++;
                        $options['site_id'] = $lastId;
                        $options['site_account_id'] = $user->id_user;
                        $options['supply_name'] = iconv('UTF-8', 'WINDOWS-1251', $_POST['supply_name']);
                        $options['paydesk_id'] = $_POST['paydesk_id'];
                        $options['arriving_date'] = $_POST['arriving_date'];
                        $options['ready'] = 0;
                        Supply::addSupplyMSSQL($options);

                        $supply_error_part = [];
                        if(count($excelArray) > 0){
                            foreach($excelArray as $excel) {
                                $insert['site_id'] = $lastId;
                                $insert['part_number'] = $excel['part_number'];
                                $insert['so_number'] = $excel['so_number'];
                                $insert['price'] = $excel['price'];
                                $insert['quantity'] = $excel['quantity'];
                                $insert['tracking_number'] = $excel['tracking_number'];
                                $insert['manufacture_country'] = iconv('UTF-8', 'WINDOWS-1251', $excel['manufacture_country']);
                                $insert['partner'] = $excel['partner'];
                                $insert['manufacturer'] = iconv('UTF-8', 'WINDOWS-1251', $excel['manufacturer']);

                                if(!empty($insert['part_number'])){
                                    $part_num = Products::checkPurchasesPartNumber($insert['part_number']);
                                    if($part_num != 0){
                                        Supply::addSupplyPartsMSSQL($insert);
                                    } else {
                                        array_push($supply_error_part, $insert['part_number']);
                                    }
                                }
                            }
                        }
                        Logger::getInstance()->log($user->id_user, 'Загрузил excel файл с поставками');
                        // Пишем в сессию массив с ненайденными партномерами
                        Session::set('error_supply', $supply_error_part);
                    }
                }
                header("Location: /adm/crm/supply");
            }
        }

        $this->render('admin/crm/supply/supply', compact('user', 'partnerList',
            'supply_error_part', 'allSupply', 'listPayDesk'));
        return true;
    }


    /**
     * Added parts in supply
     * @return bool
     */
    public function actionImportAddParts()
    {
        $user = $this->user;

        if (isset($_POST['add_parts_supply']) && $_POST['add_parts_supply'] == 'true') {
            if (!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_supply/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                $randomName = $user->name_partner . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importSupply($excel_file);

                        $site_id = $_POST['site_id'];

                        $supply_error_part = [];
                        if(!empty($site_id)){
                            if(count($excelArray) > 0){
                                foreach($excelArray as $excel) {
                                    $insert['site_id'] = $site_id;
                                    $insert['part_number'] = $excel['part_number'];
                                    $insert['so_number'] = $excel['so_number'];
                                    $insert['price'] = $excel['price'];
                                    $insert['quantity'] = $excel['quantity'];
                                    $insert['tracking_number'] = $excel['tracking_number'];
                                    $insert['manufacture_country'] = iconv('UTF-8', 'WINDOWS-1251', $excel['manufacture_country']);
                                    $insert['partner'] = $excel['partner'];
                                    $insert['manufacturer'] = iconv('UTF-8', 'WINDOWS-1251', $excel['manufacturer']);

                                    if(!empty($insert['part_number'])){
                                        $part_num = Products::checkPurchasesPartNumber($insert['part_number']);
                                        if($part_num != 0){
                                            Supply::addSupplyPartsMSSQL($insert);
                                        } else {
                                            array_push($supply_error_part, $insert['part_number']);
                                        }
                                    }
                                }
                            }
                        }

                        Logger::getInstance()->log($user->id_user, 'Загрузил excel файл с дополнениями к поставке ID-' . $site_id);
                        // Пишем в сессию массив с ненайденными партномерами
                        Session::set('error_supply', $supply_error_part);
                    }
                }
                header("Location: /adm/crm/supply");
            }
        }
        return true;
    }


    /**
     * Распределяем детали по складам
     * @return bool
     */
    public function actionSupplyAjax()
    {
        $user = $this->user;

        $group = new Group();

        $site_id = $_REQUEST['site_id'];
        $array_supply = Supply::getSupplyPartsByIdS($site_id);

        foreach ($array_supply as $item){

            if($item['manufacturer'] == 'Lenovo' || $item['manufacturer'] == 'lenovo'){
                // Проверяем на наличие в таблице КПИ
                $count_kpi = Supply::getCountSoNumberOnKpi($item['so_number']);
                if($count_kpi > 0){
                    $stock = iconv('UTF-8', 'WINDOWS-1251', 'not used');
                } else {
                    // Если в таблице КПИ не найден, ищем в таблице Refund Request
                    $stock = 'transit';
                    $status = iconv('UTF-8', 'WINDOWS-1251', 'подтверждено');
                    $count_refund = Supply::getCountSoNumberOnRefund($item['so_number'], $status);
                    if($count_refund > 0){
                        $stock = iconv('UTF-8', 'WINDOWS-1251', 'not used');
                        //Supply::updateCommand($item['site_id'], 1);
                    } else {
                        $stock = iconv('UTF-8', 'WINDOWS-1251', 'transit');
                    }
                }
            } else {
                // Используем для поставки склад, который привязанный к группе в которой находиться пользователь создавший поствку
                $stocks_group = $group->stocksFromGroup($user->idGroupUser($item['site_account_id']), 'name', 'supply');
                $stock = iconv('UTF-8', 'WINDOWS-1251', $stocks_group[0]);
            }
            Supply::updateStock($item['id'], $stock);
        }
        $ok = Supply::updateCommand($site_id, 1);
        if($ok){
            echo 200;
        }
        Logger::getInstance()->log($user->id_user, 'в поставках нажал Accept');
        return true;
    }


    /**
     * Показываем детали поставки в модальном окне
     * @return bool
     */
    public function actionShowDetailSupply()
    {
        $site_id = $_REQUEST['site_id'];
        $data = Supply::getShowDetailsSupply($site_id);
        //print_r($data);
        $html = "";
        foreach($data as $item){
            $color = Functions::compareQuantity($item['quantity'], $item['quantity_reserv']);
            $quantity = "style=color:" . $color ;
            $html .= "<tr>";
            $html .= "<td>" . $item['part_number'] . "</td>";
            $html .= "<td>" . iconv('WINDOWS-1251', 'UTF-8', $item['goods_name']) . "</td>";
            $html .= "<td>" . iconv('WINDOWS-1251', 'UTF-8', $item['so_number']) . "</td>";
            $html .= "<td {$quantity}><b>" . $item['quantity'] . "</b></td>";
            $html .= "<td {$quantity}><b>" . $item['quantity_reserv'] . "</b></td>";
            $html .= "<td>" . round($item['price'], 2) . "</td>";
            $html .= "<td>" . $item['tracking_number'] . "</td>";
            $html .= "<td>" . iconv('WINDOWS-1251', 'UTF-8', $item['manufacture_country']) . "</td>";
            $html .= "<td>" . iconv('WINDOWS-1251', 'UTF-8', $item['partner']) . "</td>";
            $html .= "</tr>";
        }
        print_r($html);
        return true;
    }


    /**
     * @return bool
     */
    public function actionActionSupplyAjax()
    {
        // Поиск поставки по site_id
        if($_REQUEST['action'] == 'search_site_id'){
            $site_id = $_REQUEST['site_id'];
            $data = Supply::getInfoSupply($site_id);
            $status = iconv('WINDOWS-1251', 'UTF-8', $data['status_name']);
            if ($data == false) {
                $result['name'] = "Supply not found";
                $result['status'] = 404;
            } elseif($status == 'Подтверждена') {
                $result['name'] = iconv('WINDOWS-1251', 'UTF-8', $data['name']);
                $result['warning'] = '(Невозможно дополнить поставку!)';
                $result['status'] = 403;
            } else {
                $result['name'] = iconv('WINDOWS-1251', 'UTF-8', $data['name']);
                $result['status'] = 200;
            }
            echo json_encode($result);
        }

        // Привязываем поставку к GM для дальнейшей обработки
        if($_REQUEST['action'] == 'quantity'){
            $site_id = $_REQUEST['site_id'];
            $result = Supply::getCountDetailsInSupply($site_id);
            $count['supply'] = $result['count'];
            $count['reserve'] = $result['count_reserv'];
            echo json_encode($count);
        }

        // Привязываем поставку к GM для дальнейшей обработки
        if($_REQUEST['action'] == 'bind_gm'){
            $site_id = $_REQUEST['site_id'];
            $ok = Supply::updateReady($site_id, 1);
            if ($ok == false) {
                $status = 404;
            } else {
                $status = 200;
            }
            echo json_encode($status);
        }

        // Привязываем поставку к GM для дальнейшей обработки
        if($_REQUEST['action'] == 'delete_supply'){
            $site_id = $_REQUEST['site_id'];
            $data = Supply::getInfoSupply($site_id);
            $status = iconv('WINDOWS-1251', 'UTF-8', $data['status_name']);
            if($status == 'Подтверждена'){
                $status = 403;
            } else {
                $ok = Supply::deleteSupplyBySiteId($site_id);
                $okk = Supply::deleteSupplyPartsBySiteId($site_id);
                if ($ok == false) {
                    $status = 404;
                } else {
                    $status = 200;
                }
            }
            echo json_encode($status);
        }

        return true;
    }

}