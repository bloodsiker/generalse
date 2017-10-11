<?php

namespace Umbrella\controllers\umbrella\psr;

use Umbrella\app\AdminBase;
use Umbrella\app\Mail\PsrMail;
use Umbrella\app\User;
use Umbrella\components\Functions;
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
        if(isset($_SESSION['psr_success'])){
            $message_success = $_SESSION['psr_success'];
            $class = $_SESSION['class'];
            unset($_SESSION['psr_success']);
            unset($_SESSION['class']);
        }

        if(isset($_REQUEST['add_psr']) && $_REQUEST['add_psr'] == 'true') {
            $options['id_user'] = $user->id_user;
            $options['serial_number'] = $_REQUEST['serial_number'];
            $options['part_number'] = $_REQUEST['mtm'];
            $device = Products::checkPartNumberInGM($_REQUEST['mtm']);
            $options['device_name'] = iconv('WINDOWS-1251', 'UTF-8', $device['mName']);
            $options['manufacture_date'] = $_REQUEST['manufacture_date'];
            $options['purchase_date'] = $_REQUEST['purchase_date'];
            $options['defect_description'] = $_REQUEST['defect_description'];
            $options['device_condition'] = $_REQUEST['device_condition'];
            $options['complectation'] = $_REQUEST['complectation'];
            $options['note'] = $_REQUEST['note'];
            $options['declaration_number'] = $_REQUEST['declaration_number'];
            $options['status_name'] = 'Зарегистрирован';

            $id = Psr::addPsr($options);
            if($id){
                PsrMail::getInstance()->sendEmailWithNewPsr($id, $user->name_partner, $options);
                $_SESSION['psr_success'] = 'Successful PSR device registration';
                $_SESSION['class'] = 'alert-success';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
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
                    $handle->clean();
                    $_SESSION['psr_success'] = 'The warranty card is attached';
                    $_SESSION['class'] = 'alert-success';
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                } else {
                    $_SESSION['psr_success'] = 'Error : ' . $handle->error;
                    $_SESSION['class'] = 'alert-danger';
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }
            }
        }

        //Добавляем нномер декларации к ПСР
        if(isset($_REQUEST['add_psr_dec']) && $_REQUEST['add_psr_dec'] == 'true'){
            $psr_id = $_REQUEST['psr_id'];
            $declaration_number = $_REQUEST['declaration_number'];
            $ok = Psr::addNumberDeclarationByPsr($psr_id, $declaration_number, 'declaration_number');
            if($ok){
                $_SESSION['psr_success'] = 'Declaration number added';
                $_SESSION['class'] = 'alert-success';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }else {
                $_SESSION['psr_success'] = 'Error : Could not add declaration number';
                $_SESSION['class'] = 'alert-danger';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }

        if($user->role == 'partner'){
            $listPsr = Psr::getPsrByPartner($user->controlUsers($user->id_user));
        } elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){
            $listPsr = Psr::getAllPsr();
        }

        $this->render('admin/psr/index', compact('user', 'listPsr', 'message_success', 'class'));
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

        $this->render('admin/psr/show_upload_file', compact('listDocuments'));
        return true;
    }
}