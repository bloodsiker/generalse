<?php
namespace Umbrella\controllers\umbrella\crm;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\Moto;
use Umbrella\models\Products;

/**
 * Class MotoController
 */
class MotoController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * MotoController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.moto', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * @param string $filter
     * @return bool
     */
    public function actionMoto($filter = '')
    {
        $user = $this->user;

        $partnerList = Admin::getAllPartner();

        if($user->role == 'partner') {

            $filter = "";

            if(isset($_GET['status'])){
                if($_GET['status'] == 'Все'){
                    $filter = '';
                } else {
                    $status = iconv('UTF-8', 'WINDOWS-1251', $_GET['status']);
                    $filter .= " AND sgso.status_name = '$status'";
                }
            }
            $listMoto = Moto::getAllMotoByPartner($user->id_user, $filter);

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $filter = "";

            if(isset($_GET['status'])){
                if($_GET['status'] == 'Все'){
                    $filter = '';
                } else {
                    $status = iconv('UTF-8', 'WINDOWS-1251', $_GET['status']);
                    $filter .= " AND sgso.status_name = '$status'";
                }
            }
            $listMoto = Moto::getAllMoto($filter);
        }

        $options = [];


        // 5B28C02926
        if(isset($_REQUEST['new-repair']) && $_REQUEST['new-repair'] == 'true'){
            $lastId = Moto::getLastMotoId();
            if($lastId == false){
                $lastId = 100;
            }
            $lastId++;

            $options['site_id'] = $lastId;
            $options['site_account_id'] = $user->id_user;
            $options['client_name'] = iconv('UTF-8', 'WINDOWS-1251',$_REQUEST['client_name']);
            $options['client_phone'] = $_REQUEST['client_phone'];
            $options['client_email'] = $_REQUEST['client_email'];
            $options['serial_number'] = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['serial_number']);
            $options['part_number'] = $_REQUEST['mtm'];
            $options['goods_name'] = $_REQUEST['goods_name'];
            $options['problem_description'] = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['problem_description']);
            $options['purchase_date'] = $_REQUEST['purchase_date'];
            $options['carry_in_date'] = $_REQUEST['carry_in_date'];
            $options['ready'] = 1;

            $ok = Moto::addServiceObjectsMsSQL($options);
            //$okk = Moto::addServiceObjects($options);
            foreach ($_FILES['attach_file']['name'] as $key => $val) {
                if(!empty($_FILES['attach_file']['name'][$key])){
                    $options['name'] = $_FILES['attach_file']['name'][$key];

                    // Получаем бинарник файла
//                    $data = file_get_contents($_FILES['attach_file']['tmp_name'][$key], true);
//                    $options['file_name'] = $options['name'];
//                    $options['file_data'] = base64_encode($data);
//                    Moto::addDocumentServiceObjectsMsSql($options);


                    $options['real_file_name'] = $options['name'];
                    // Все загруженные файлы помещаются в эту папку
                    $options['file_path'] = "/upload/attach_moto/";
                    $randomName = substr_replace(sha1(microtime(true)), '', 5);

                    // Получаем расширение файла
                    $getMime = explode('.', $options['name']);
                    $mime = end($getMime);

                    $randomName = date('Y-m-d') . "-" . $randomName . "." . $mime;
                    $options['file_name'] = $randomName;

                    if (is_uploaded_file($_FILES["attach_file"]["tmp_name"][$key])) {
                        if (move_uploaded_file($_FILES['attach_file']['tmp_name'][$key], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                            Moto::addDocumentServiceObjects($options);
                        }
                    }
                }
            }

            if($ok){
                Logger::getInstance()->log($user->id_user, 'создал новую заявку ремонта в Motorola ' . $_REQUEST['serial_number']);
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }


        // Add Parts
        if(isset($_REQUEST['add-parts']) && $_REQUEST['add-parts'] == 'true'){
            $options['site_id'] = $_REQUEST['site_id'];;
            $options['site_account_id'] = $user->id_user;
            $options['serial_number'] = $_REQUEST['serial_num_parts'];
            $options['part_number'] = $_REQUEST['mtm'];
            $options['operation_type'] = 1;
            $options['ready'] = 1;

            $ok = Moto::addPartsMsSQL($options);
            //$ok = Moto::addParts($options);
            if($ok){
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }

        // Add Local Source
        if(isset($_REQUEST['add-local-source']) && $_REQUEST['add-local-source'] == 'true'){
            $options['site_id'] = $_REQUEST['site_id'];;
            $options['site_account_id'] = $user->id_user;
            $options['serial_number'] = $_REQUEST['serial_num_local'];
            $options['part_number'] = $_REQUEST['mtm'];
            $options['price'] = str_replace(',','.', $_REQUEST['price']);
            $options['operation_type'] = 2;
            $options['ready'] = 1;

            $ok = Moto::addLocalSourceMsSQL($options);
            if($ok){
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }

        // Close Repair
        if(isset($_REQUEST['close-repair']) && $_REQUEST['close-repair'] == 'true'){
            $options['site_id'] = $_REQUEST['site_id'];;
            $options['site_account_id'] = $user->id_user;
            $options['serial_number'] = $_REQUEST['serial_num_close'];
            $options['complete_date'] = $_REQUEST['complete_date'];
            $options['repair_level'] = $_REQUEST['repair_level'];
            $options['operation_type'] = 3;
            $options['ready'] = 1;

            $ok = Moto::closeRepairMsSQL($options);
            if($ok){
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }

        }

        $this->render('admin/crm/moto/moto', compact('user', 'partnerList', 'listMoto'));
        return true;
    }


    /**
     * Проверка патр номера в базе
     * @return bool
     */
    public function actionMotoPartNumAjax()
    {
        $mtm = $_REQUEST['mtm'];

        $result = 0;
        // 5B28C02926
        $result = Products::checkPartNumberInGM($mtm);

        $mName = '';
        $mName = iconv('WINDOWS-1251', 'UTF-8', $result['mName']);
        print_r($mName);

        return true;
    }


    /**
     * Проверка на наличие записи по серийнику
     * @return bool
     */
    public function actionMotoSerialNumAjax()
    {
        $user = $this->user;

        $serial_num = $_REQUEST['serial_number'];
        $result = 0;
        // 5B28C02926
        $result = Products::checkMotoSerialNumber($user->id_user, $serial_num);
        print_r(json_encode($result));

        return true;
    }

    /**
     * Показываем подробности
     * @return bool
     */
    public function actionShowMoto()
    {
        $user = $this->user;

        $site_id = $_REQUEST['site_id'];
        $data = Moto::getShowMoto($site_id);
        $listDocument = Moto::getShowDocumentByMoto($site_id);

        $this->render('admin/crm/moto/moto_show_detailes', compact('user', 'data', 'listDocument'));
        return true;
    }
}