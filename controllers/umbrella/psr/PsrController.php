<?php

namespace Umbrella\controllers\umbrella\psr;

use Carbon\Carbon;
use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\Mail\PsrMail;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\components\Functions;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\Products;
use Umbrella\models\psr\Psr;
use upload as FileUpload;

class PsrController extends AdminBase
{
    /**
     *  Path to the upload file for the psr
     */
    const UPLOAD_PATH_PSR = '/upload/attach_psr/';

    /**
     * @var User
     */
    private $user;

    /**
     * PsrController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('adm.psr', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }


    /**
     * @return bool
     */
    public function actionIndex()
    {
        $user = $this->user;

        $message_success = Session::pull('psr_success');
        $class = Session::pull('class');


        if(isset($_REQUEST['add_psr']) && $_REQUEST['add_psr'] == 'true') {
            $lastPsrId = Psr::getLasId();
            $lastPsrId++;
            $options['id'] = $lastPsrId;

            $options['id_user'] = $user->getId();
            $options['serial_number'] = $_REQUEST['serial_number'];
            $options['part_number'] = $_REQUEST['mtm'];
            $device = Products::checkPartNumberInGM($_REQUEST['mtm']);
            $options['device_name'] = Decoder::strToUtf($device['mName']);
            $options['manufacture_date'] = $_REQUEST['manufacture_date'];
            $options['purchase_date'] = $_REQUEST['purchase_date'];
            $options['defect_description'] = $_REQUEST['defect_description'];
            $options['device_condition'] = $_REQUEST['device_condition'];
            $options['complectation'] = $_REQUEST['complectation'];
            $options['note'] = $_REQUEST['note'];
            $options['declaration_number'] = $_REQUEST['declaration_number'];
            $options['status_name'] = 'Зарегистрирован';
            $options['ready'] = 1;
            $options['created_at'] = date('Y-m-d');

            Psr::addPsr($options);

            $id = Psr::addPsrMsSQL(Decoder::arrayToWindows($options));
            if($id){
                PsrMail::getInstance()->sendEmailWithNewPsr($id, $user->name_partner, $options);
                Logger::getInstance()->log($user->getId(), "Зарегистрировал ПСР MTM {$options['part_number']}, SN {$options['serial_number']}");
                Session::set('psr_success', 'Successful PSR device registration');
                Session::set('class', 'alert-success');
                Url::previous();
            }
        }

        // Загружаем файл и привязываем его к ПСР
        if (!empty($_FILES['attach_psr'])) {
            $handle = new FileUpload($_FILES['attach_psr']);
            if ($handle->uploaded) {
                $handle->file_new_name_body = Functions::strUrl($user->name_partner);
                $handle->file_name_body_add = '-' . substr_replace(sha1(microtime(true)), '', 15);
                $file_name = $handle->file_new_name_body . $handle->file_name_body_add . '.' . $handle->file_src_name_ext;
                $handle->process(ROOT . self::UPLOAD_PATH_PSR);
                if ($handle->processed) {
                    Psr::addDocumentInPsrMsSQL($_REQUEST['psr_id'], 'http://generalse.com' . self::UPLOAD_PATH_PSR, $file_name, 1);
                    Logger::getInstance()->log($user->getId(),"Загрузил квитанцию к ПСР # {$_REQUEST['psr_id']}");
                    $handle->clean();
                    Session::set('psr_success', 'The warranty card is attached');
                    Session::set('class', 'alert-success');
                    Url::previous();
                } else {
                    Session::set('psr_success', 'Error : '. $handle->error);
                    Session::set('class', 'alert-danger');
                    Url::previous();
                }
            }
        }

        if($user->isPartner()){
            $listPsr = Decoder::arrayToUtf(Psr::getPsrByPartnerMsSQL($user->controlUsers($user->id_user)), ['manufacture_date', 'purchase_date']);
        } elseif($user->isAdmin() || $user->isManager()){
            $listPsr = Decoder::arrayToUtf(Psr::getAllPsrMsSQL(), ['manufacture_date', 'purchase_date']);
        }

        $this->render('admin/psr/psr_ua/index', compact('user', 'listPsr', 'message_success', 'class'));
        return true;
    }


    /**
     * Search in Psr
     * @return bool
     */
    public function actionSearch()
    {
        $user = $this->user;

        if($user->isPartner() || $user->isManager()){
            $search = Decoder::strToWindows(trim($_REQUEST['search']));

            $user_ids = $user->controlUsers($user->id_user);
            $idS = implode(',', $user_ids);
            $filter = " AND gp.id_user ($idS)";

            $listPsr = Psr::getSearchInPsr($search, $filter);
            $listPsr = Decoder::arrayToUtf($listPsr);
        } elseif($user->isAdmin()){
            $search = Decoder::strToWindows(trim($_REQUEST['search']));

            $listPsr = Psr::getSearchInPsr($search);
            $listPsr = Decoder::arrayToUtf($listPsr);
        }

        $search = isset($search) ? Decoder::strToUtf($search) : null;

        $this->render('admin/psr/psr_ua/result_search', compact('user', 'listPsr', 'search'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionPsrAjax()
    {
        // Поиск устройства по парт номеру
        if($_REQUEST['action'] == 's_part_number') {
            $part_number = $_REQUEST['part_number'];

            $result = Products::checkPartNumberInGM($part_number);

            if($result == 0){
                $data['result'] = 0;
                $data['mName'] = 'not found';
                print_r(json_encode($data));
            } else {
                $data['result'] = 1;
                $data['mName'] = iconv('WINDOWS-1251', 'UTF-8', $result['mName']);
                print_r(json_encode($data));
            }
        }

        // Редактируем Declaration number
        if($_REQUEST['action'] == 'edit_dec') {
            $id = $_REQUEST['id_psr'];
            $psr_dec =  Decoder::strToWindows($_REQUEST['psr_dec']);
            $ok = Psr::addNumberDeclarationByPsr($id, $psr_dec, 'declaration_number', 1);
            if($ok){
                print_r(200);
            }
        }

        // Редактируем Declaration number return
        if($_REQUEST['action'] == 'edit_dec_return') {
            $id = $_REQUEST['id_psr'];
            $psr_dec_return =  Decoder::strToWindows($_REQUEST['psr_dec']);
            $ok = Psr::addNumberDeclarationByPsr($id, $psr_dec_return, 'declaration_number_return', 1);
            if($ok){
                print_r(200);
            }
        }
        return true;
    }


    /**
     * Подгружаем список прикрепленных файлов к ПСР
     * @return bool
     */
    public function actionShowUploadFile()
    {
        $psr_id = $_REQUEST['psr_id'];

        $listDocuments = Psr::getAllDocumentsInPsr($psr_id);

        $this->render('admin/psr/psr_ua/show_upload_file', compact('listDocuments'));
        return true;
    }


    public function actionTest()
    {

//        $list = $listPsr = Psr::getAllPsrMsSQL();
//
//        $new_psr = [];
//        $i = 0;
//        foreach ($list as $iconv){
//            $new_psr[$i] = Decoder::arrayToWindows($iconv);
//            $new_psr[$i]['ready'] = 0;
//            $i++;
//        }
        //var_dump($new_psr);

//        foreach ($new_psr as $psr){
//            Psr::addPsrMsSQL($psr);
//        }

//        $allDocument = Psr::getAllDocumentsInPsr();
//        $allDocument = array_map(function ($value) {
//            $value['file_path'] = 'http://generalse.com' . $value['file_path'];
//            $value['ready'] = 0;
//            return $value;
//        },$allDocument);
//        var_dump($allDocument);

//        foreach ($allDocument as $psr) {
//            Psr::addDocumentInPsrMsSQL($psr['id_psr'], $psr['file_path'], $psr['file_name'], $psr['ready']);
//        }

        return true;
    }
}