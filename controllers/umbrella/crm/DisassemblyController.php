<?php

namespace Umbrella\controllers\umbrella\crm;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\Disassembly;

/**
 * Class DisassemblyController
 */
class DisassemblyController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * DisassemblyController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.disassembly', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * Разборка устройств
     * @return bool
     */
    public function actionDisassembly()
    {
        $user = $this->user;

        $partnerList = Admin::getAllPartner();

        if (isset($_POST['search_serial'])) {
            $serial_number = $_POST['serial_number'];
            $id_partner = $_POST['id_partner'];

            //QB08242887
            $bomList = Disassembly::getRequestByPartner($id_partner, $serial_number);
        }

        $this->render('admin/crm/disassemble/disassemble', compact('user', 'partnerList', 'bomList'));
        return true;
    }

    /**
     * Постмотреть список заявок на разбор
     * @param string $filter
     * @return bool
     */
    public function actionDisassemblyResult($filter = '')
    {
        $user = $this->user;

        if($user->role == 'partner'){
            $filter = "";
            $interval = " AND gd.date_create >= DATE(NOW()) - INTERVAL 30 DAY";
            if(!empty($_GET['start'])){
                if(empty($_GET['end'])){
                    $end = date('Y-m-d') . " 23:59";
                } else {
                    $end = $_GET['end']. " 23:59";
                }
                $start = $_GET['start']. " 00:00";
                $filter .= " AND gd.date_create BETWEEN '$start' AND '$end'";
                $interval = "";
            }
            $filter .= $interval;
            $listDisassembly = Disassembly::getDisassemblyByPartner($user->controlUsers($user->getId()), $filter);

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $filter = "";
            $interval = " AND gd.date_create >= DATE(NOW()) - INTERVAL 30 DAY";
            $partnerList = Admin::getAllPartner();

            if(!empty($_GET['start'])){
                if(empty($_GET['end'])){
                    $end = date('Y-m-d') . " 23:59";
                } else {
                    $end = $_GET['end']. " 23:59";
                }
                $start = $_GET['start']. " 00:00";
                $filter .= " AND gd.date_create BETWEEN '$start' AND '$end'";
                $interval = "";
            }

//            if(!empty($_GET['id_partner'])){
//                $id_partner = $_GET['id_partner'];
//                $filter .= " AND gd.id_user = " .(int)$id_partner;
//                $interval = "";
//            }
            $filter .= $interval;
            $listDisassembly = Disassembly::getAllDisassembly($filter);
        }

        $this->render('admin/crm/disassemble/disassemble_result',
            compact('user', 'partnerList', 'listDisassembly'));
        return true;
    }


    /**
     * search disassemble
     * @return bool
     */
    public function actionSearch()
    {
        $user = $this->user;

        if($user->role == 'partner'){
            $search = iconv('UTF-8', 'WINDOWS-1251', trim($_REQUEST['search']));

            $user_ids = $user->controlUsers($user->getId());
            $partnerList = Admin::getPartnerControlUsers($user_ids);

            $idS = implode(',', $user_ids);
            $filter = " AND gd.id_user IN({$idS})";
            $listDisassembly = Disassembly::getSearchInDisassemble($search, $filter);

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $search = iconv('UTF-8', 'WINDOWS-1251', trim($_REQUEST['search']));

            $listDisassembly = Disassembly::getSearchInDisassemble($search);
        }

        $this->render('admin/crm/disassemble/disassemble_search',
            compact('user', 'partnerList', 'listDisassembly', 'search'));
        return true;
    }


    /**
     * Делаем разборку
     * @return bool
     */
    public function actionDisassemblyAjax()
    {
        $user = $this->user;

        $data = $_REQUEST['json'];
        $data_json = json_decode($data, true);

        // Полачем последний номер разборки
        $lastId = Disassembly::getLastDecompileId();
        if($lastId == false){
            $lastId = 166;
        }
        $lastId++;

        $count = count($data_json);
        $i = 0;

        $options['site_id'] = $lastId;
        $options['part_number'] = $data_json[0]['dev_pn'];
        $options['serial_number'] = $data_json[0]['sn'];
        $options['note'] = $data_json[0]['note'];
        $options['note_ms'] = Decoder::strToWindows($data_json[0]['note']);
        $options['dev_name'] = $data_json[0]['dev_name'];
        $options['stockName'] = $data_json[0]['stock_name'];
        $options['id_user'] = $data_json[0]['id_partner'];
        $options['ready'] = 0;
        // Разборка детали - шапка
        $idDecompile = Disassembly::addDecompilesMsSql($options);
        if($idDecompile){
            Disassembly::addDecompiles($options);

            // Перебор массива разборки и запись в бд
            foreach($data_json as $data){
                $options['site_id'] = $lastId;
                $options['mName'] = $data['desc'];
                $options['part_number'] = $data['pn'];
                //$options['serial_number'] = $data['sn'];
                $options['stock_name'] = $data['stock'];
                $options['quantity'] = $data['qua'];
                // Разборка детали
                $ok = Disassembly::addDecompilesPartsMsSql($options);
                if($ok){
                    Disassembly::addDecompilesParts($options);
                    $i++;
                }
            }
            // Кол-во обьектов в массиве должно быть равным кол-ву успешных записей в бд
            if($count == $i){
                Disassembly::decompileIsReady($idDecompile, 1);
                Logger::getInstance()->log($user->getId(), 'произвел разборку устройства, SN ' . $options['serial_number']);
                echo 1;
            } else {
                echo 0;
            }
        }
        //print_r($options);
        return true;
    }

    /**
     * @return bool
     */
    public function actionDisassemblyActionAjax()
    {
        if($_REQUEST['action'] == 'accept'){
            $decompile_id = $_REQUEST['decompile_id'];
            $ok = Disassembly::updateStatusDisassemblyGM($decompile_id, 1, NULL);
            $status = [];
            if($ok){
                $status['ok'] = 1;
                $status['class'] = 'green';
                $status['text'] = 'Подтверждена';
            } else {
                $status['ok'] = 0;
                $status['error'] = 'Ошибка подтверждения!';
            }
            echo json_encode($status);
        }

        if($_REQUEST['action'] == 'dismiss'){
            $decompile_id = $_REQUEST['decompile_id'];
            $comment = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['comment']);
            $ok = Disassembly::updateStatusDisassemblyGM($decompile_id, 2, $comment);
            $status = [];
            if($ok){
                $status['ok'] = 1;
                $status['class'] = 'red';
                $status['text'] = 'Отклонена';
            } else {
                $status['ok'] = 0;
                $status['error'] = 'Ошибка подтверждения!';
            }
            echo json_encode($status);
        }

        if($_REQUEST['action'] == 'delete'){
            $site_id = $_REQUEST['site_id'];
            $ok = Disassembly::deleteDecompileById($site_id);
            $status = [];
            if($ok){
                $status['ok'] = 1;
            } else {
                $status['ok'] = 0;
                $status['error'] = 'Ошибка удаления!';
            }
            echo json_encode($status);
        }
        return true;
    }


    /**
     * Показать продукты с разборки
     * @return bool
     */
    public function actionShowDetailDisassembl()
    {
        $site_id = $_REQUEST['site_id'];
        $data = Disassembly::getShowDetailsDisassembly($site_id);
        $comment = Disassembly::getShowCommentDisassembly($site_id);
        $this->render('admin/crm/disassemble/disassemble_show_detailes', compact('user', 'data', 'comment'));
        return true;
    }

    /**
     * Страница експорта
     * @param $data
     * @return bool
     */
    public function actionExportDisassembly($data)
    {
        $user = $this->user;

        if($user->role == 'partner'){

            $listExport = [];
            $start = '';
            $end = '';

            if(isset($_GET['start']) && !empty($_GET['start'])){
                $start = $_GET['start'] .' 00:00';
            }

            if(isset($_GET['end']) && !empty($_GET['end'])){
                $end = $_GET['end'] . ' 23:59';
            }

            $listExport = Disassembly::getExportDisassemblyByPartner($user->controlUsers($user->getId()), $start, $end);

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager' ){

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
                    $listExport = Disassembly::getExportDisassemblyAllPartner($start, $end);
                } else {
                    $user_id = $_GET['id_partner'];
                    $listExport = Disassembly::getExportDisassemblyByPartner($user->controlUsers($user_id), $start, $end);
                }
            }
        }

        $this->render('admin/crm/export/disassemble', compact('user', 'listExport', 'comment'));
        return true;
    }
}