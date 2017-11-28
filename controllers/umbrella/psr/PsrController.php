<?php

namespace Umbrella\controllers\umbrella\psr;

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

            $id = Psr::addPsr($options);
            $options['id'] = $id;
            //Psr::addPsrMsSQL(Decoder::arrayToWindows($options));
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
                    Psr::addDocumentInPsr($_REQUEST['psr_id'], self::UPLOAD_PATH_PSR, $file_name);
                    //Psr::addDocumentInPsrMsSQL($_REQUEST['psr_id'], 'http://generalse.com' . self::UPLOAD_PATH_PSR, $file_name, 1);
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

        //Добавляем нномер декларации к ПСР
        if(isset($_REQUEST['add_psr_dec']) && $_REQUEST['add_psr_dec'] == 'true'){
            $psr_id = $_REQUEST['psr_id'];
            $declaration_number = $_REQUEST['declaration_number'];
            $ok = Psr::addNumberDeclarationByPsr($psr_id, $declaration_number, 'declaration_number');
            if($ok){
                Session::set('psr_success', 'Declaration number added');
                Session::set('class', 'alert-success');
            }else {
                Session::set('psr_success', 'Error : Could not add declaration number');
                Session::set('class', 'alert-danger');
            }
        }

        if($user->role == 'partner'){
            $listPsr = Psr::getPsrByPartner($user->controlUsers($user->id_user));
        } elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){
            $listPsr = Psr::getAllPsr();
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
            $search = $_REQUEST['search'];

            $user_ids = $user->controlUsers($user->id_user);
            $idS = implode(',', $user_ids);
            $filter = " AND gp.id_user ($idS)";

            $listPsr = Psr::getSearchInPsr($search, $filter);
        } elseif($user->isAdmin()){
            $search = trim($_REQUEST['search']);

            $listPsr = Psr::getSearchInPsr($search);
        }

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

        // Поиск ПСР по ID
        if($_REQUEST['action'] == 'add_psr_dec') {
            $id = $_REQUEST['id_psr'];
            $id_user = $this->user->id_user;
            $psr = Psr::getPsrById($id, $id_user);
            print_r(json_encode($psr));
        }

        // Редактируем SO
        if($_REQUEST['action'] == 'edit_so') {
            $id = $_REQUEST['id_psr'];
            $psr_so =  $_REQUEST['psr_so'];
            $ok = Psr::editSoNumberByPsr($id, $psr_so);
            if($ok){
                print_r(200);
            }
        }

        // Редактируем Declaration number
        if($_REQUEST['action'] == 'edit_dec') {
            $id = $_REQUEST['id_psr'];
            $psr_dec =  $_REQUEST['psr_dec'];
            $ok = Psr::addNumberDeclarationByPsr($id, $psr_dec, 'declaration_number');
            if($ok){
                print_r(200);
            }
        }

        // Редактируем Declaration number return
        if($_REQUEST['action'] == 'edit_dec_return') {
            $id = $_REQUEST['id_psr'];
            $psr_dec_return =  $_REQUEST['psr_dec'];
            $ok = Psr::addNumberDeclarationByPsr($id, $psr_dec_return, 'declaration_number_return');
            if($ok){
                print_r(200);
            }
        }

        // Редактируем статус
        if($_REQUEST['action'] == 'edit_status') {
            $id = $_REQUEST['id_psr'];
            $status =  $_REQUEST['psr_status'];
            $ok = Psr::editStatusByPsr($id, $status);
            if($ok){
                $response['status'] = 200;
                $response['class'] = Psr::getStatusRequest($status);
                print_r(json_encode($response));
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

        $list = $listPsr = Psr::getAllPsrMsSQL();

        $new_psr = [];
        $i = 0;
        foreach ($list as $iconv){
            $new_psr[$i] = Decoder::arrayToWindows($iconv);
            $new_psr[$i]['ready'] = 0;
            $i++;
        }
        //var_dump($new_psr);

//        foreach ($new_psr as $psr){
//            Psr::addPsrMsSQL($psr);
//        }

        $allDocument = Psr::getAllDocuments();
        $allDocument = array_map(function ($value) {
            $value['file_path'] = 'http://generalse.com' . $value['file_path'];
            $value['ready'] = 0;
            return $value;
        },$allDocument);
        //var_dump($allDocument);

//        foreach ($allDocument as $psr) {
//            Psr::addDocumentInPsrMsSQL($psr['id_psr'], $psr['file_path'], $psr['file_name'], $psr['ready']);
//        }

        return true;
    }
}