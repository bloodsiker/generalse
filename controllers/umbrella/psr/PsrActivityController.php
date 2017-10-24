<?php

namespace Umbrella\controllers\umbrella\psr;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\Products;
use Umbrella\models\psr\Psr;

class PsrActivityController extends AdminBase
{
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

        if($user->role == 'partner'){
            $listPsr = Psr::getPsrByPartner($user->controlUsers($user->id_user));
        } elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){
            $listPsr = Psr::getAllPsr();
        }
        $listPsr = [];

        $this->render('admin/psr/activity/index', compact('user', 'listPsr'));
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

}