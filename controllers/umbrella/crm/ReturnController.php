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
use Umbrella\models\Returns;

/**
 * Class ReturnController
 */
class ReturnController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * ReturnController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.returns', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function actionReturns()
    {
        $user = $this->user;

        $partnerList = Admin::getAllPartner();

        $arr_error_return = Session::pull('error_return');

        $allReturnsByPartner = [];
        if($user->isPartner() || $user->isManager()) {
            $interval = "";
            if($user->role == 'manager'){
                $status_1 = Decoder::strToWindows('Предварительный');
                $status_2 = Decoder::strToWindows('В обработке');
                $interval = " AND (sgs.status_name = '$status_1' OR sgs.status_name = '$status_2')";
            }

            $interval .= " AND sgs.created_on >= DATEADD(day, -7, GETDATE())";
            $allReturnsByPartner = Returns::getReturnsByPartner($user->controlUsers($user->id_user), $interval);

        } else if($user->isAdmin()){

            $status_1 = Decoder::strToWindows('Предварительный');
            $status_2 = Decoder::strToWindows('В обработке');
            $interval = " AND (sgs.status_name = '$status_1' OR sgs.status_name = '$status_2')";
            $interval .= " AND sgs.created_on >= DATEADD(day, -7, GETDATE())";
            $allReturnsByPartner = Returns::getAllReturns($interval);
        }

        $this->render('admin/crm/returns/returns', compact('user','partnerList', 'arr_error_return', 'allReturnsByPartner'));
        return true;
    }


    /**
     * @return bool
     * @throws \Exception
     */
    public function actionReturnsAjax()
    {
        $user = $this->user;

        //
        if($_REQUEST['action'] == 'update'){
            $response = [];
            $stock = $_REQUEST['stock'];
            $id_return = $_REQUEST['id_return'];

            $response['stock'] = $stock;
            $stock_name = Decoder::strToWindows($stock);
            //$ok = Returns::updateStatusReturns($id_return, $stock);
            $ok = Returns::updateStatusAndStockReturns($id_return, $stock_name);
            if($ok){
                $response['status'] = 'ok';
                Logger::getInstance()->log($user->id_user, 'сделал возврат в Returns ' . $id_return);
            } else {
                $response['status'] = 'bad';
            }
            print_r(json_encode($response));
        }

        if($_REQUEST['action'] == 'accept'){
            $return_id = $_REQUEST['return_id'];
            $ok = Returns::updateStatusReturnsGM($return_id, 1, NULL);
            $status = [];
            if($ok){
                $status['ok'] = 1;
                $status['class'] = 'green';
                $status['text'] = 'Принят';
            } else {
                $status['ok'] = 0;
                $status['error'] = 'Ошибка подтверждения!';
            }
            echo json_encode($status);
        }

        if($_REQUEST['action'] == 'dismiss'){
            $return_id = $_REQUEST['return_id'];
            $comment = Decoder::strToWindows($_REQUEST['comment']);
            $ok = Returns::updateStatusReturnsGM($return_id, 2, $comment);
            $status = [];
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
        return true;
    }


    /**
     * Import returns
     * @return bool
     * @throws \Exception
     */
    public function actionImportReturns()
    {
        $user = $this->user;

        if(isset($_POST['send_excel_file']) && $_REQUEST['send_excel_file'] == 'true'){
            if(!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_return/";
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
                        $excelArray = ImportExcel::importReturn($excel_file);

                        $insertArray = [];
                        $errorReturn = [];
                        $i = 0;
                        foreach($excelArray as $excel){
                            $insertArray[$i]['so_number'] = Decoder::strToWindows($excel['so_number']);
                            $insertArray[$i]['stock_name'] = Decoder::strToWindows($excel['stock_name']);
                            $insertArray[$i]['order_number'] = $excel['order_number'];
                            $insertArray[$i]['id_user'] = $user->id_user;
                            $ok = Returns::getSoNumberByPartnerInReturn($insertArray[$i]);
                            if($ok){
                                Returns::updateStatusImportReturns($insertArray[$i]);
                            } else {
                                array_push($errorReturn, $insertArray[$i]['so_number']);
                            }
                            $i++;
                        }
                        Logger::getInstance()->log($user->id_user, 'загрузил массив с excel в Returns');
                        // Пишем в сессию массив с ненайденными so number
                        Session::set('error_return',  $errorReturn);
                    }
                }
                Url::previous();
            }
        }
        return true;
    }



    /**
     * Фильтр возвратов
     * @param string $filter
     * @return bool
     */
    public function actionFilterReturns($filter = "")
    {
        $user = $this->user;

        $partnerList = Admin::getAllPartner();

        $arr_error_return = Session::pull('error_return');

        if($user->isPartner() || $user->isManager()) {

            $filter = "";

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgs.created_on BETWEEN '$start' AND '$end'";
            }
            $allReturnsByPartner = Returns::getReturnsByPartner($user->controlUsers($user->id_user), $filter);

        } else if($user->isAdmin()){

            $filter = "";

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgs.created_on BETWEEN '$start' AND '$end'";
            }
            $allReturnsByPartner = Returns::getAllReturns($filter);
        }

        $this->render('admin/crm/returns', compact('user','partnerList', 'arr_error_return', 'allReturnsByPartner'));
        return true;
    }

    /**
     * генерация таблицы возвратов для экспорта
     * @param $data
     * @return bool
     */
    public function actionExportReturns($data)
    {
        $user = $this->user;

        if($user->isPartner()){

            $listExport = [];
            $start = '';
            $end = '';

            if(isset($_GET['start']) && !empty($_GET['start'])){
                $start = $_GET['start'] .' 00:00';
            }

            if(isset($_GET['end']) && !empty($_GET['end'])){
                $end = $_GET['end'] . ' 23:59';
            }

            $listExport = Returns::getExportReturnsByPartner($user->controlUsers($user->id_user), $start, $end);

        } elseif($user->isAdmin() || $user->isManager() ){

            $listExport = [];
            $start = '';
            $end = '';

            if(isset($_GET['start']) && !empty($_GET['start'])){
                $start = $_GET['start'] .' 00:00';
            }

            if(isset($_GET['end']) && !empty($_GET['end'])){
                $end = $_GET['end'] . ' 23:59';
            }

            if(isset($_GET['id_partner']) && !empty($_GET['id_partner'])){
                if($_GET['id_partner'] == 'all'){
                    $listExport = Returns::getExportReturnsAllPartner($start, $end);
                } else {
                    $user_id = $_GET['id_partner'];
                    $listExport = Returns::getExportReturnsByPartner($user->controlUsers($user_id), $start, $end);
                }
            }
        }

        $this->render('admin/crm/export/returns', compact('user','listExport'));
        return true;
    }


    /**
     * Поиск по Возвратам
     * @return bool
     * @throws \Exception
     */
    public function actionSearch()
    {
        $user = $this->user;

        if($user->isPartner() || $user->isManager()) {

            $search = Decoder::strToWindows(trim($_REQUEST['search']));

            $user_ids = $user->controlUsers($user->id_user);
            $partnerList = Admin::getPartnerControlUsers($user_ids);

            $idS = implode(',', $user_ids);
            $filter = " AND sgs.site_account_id IN ($idS)";
            $allReturnsByPartner = Returns::getSearchInReturns($search, $filter);

        } else if($user->isAdmin()){

            $search = Decoder::strToWindows(trim($_REQUEST['search']));

            $partnerList = Admin::getAllPartner();

            $allReturnsByPartner = Returns::getSearchInReturns($search);
        }

        $this->render('admin/crm/returns/returns_search', compact('user','partnerList',
            'allReturnsByPartner', 'search'));
        return true;
    }

}