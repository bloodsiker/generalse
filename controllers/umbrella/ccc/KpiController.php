<?php

namespace Umbrella\controllers\umbrella\ccc;

use Josantonius\Session\Session;
use Umbrella\app\AdminBase;
use Umbrella\app\Services\CCCKpi;
use Umbrella\app\User;
use Umbrella\components\ImportExcel;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\ccc\KPI;

class KpiController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * CustomerCareCenterController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('adm.ccc.kpi', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }


    /**
     * KPI
     * @return bool
     */
    public function actionIndex()
    {
        $user = $this->user;

        $success_import = Session::pull('success_import');

        $listData = KPI::getListImportData();

        $KPI = new CCCKpi();
        $lastDate = $KPI->lastDate($listData[0]['created_at']);
        $listManager = array_column($lastDate, 'name_manager');

        if(isset($_GET['date'])){
            $lastDate = $KPI->lastDate($_GET['date']);
            $listManager = array_column($lastDate, 'name_manager');
        }

        if(isset($_GET['start']) && isset($_GET['end'])){
            $lastDate = $KPI->getDataInterval($_GET['start'], $_GET['end']);
            $listManager = array_column($lastDate, 'name_manager');
        }

        $this->render('admin/ccc/kpi/index', compact('user', 'listData', 'lastDate', 'listManager', 'KPI', 'success_import'));
        return true;
    }



    public function actionImportKpi(){
        $user = $this->user;

        if (isset($_POST['import-ccc-kpi']) && $_POST['import-ccc-kpi'] == 'true') {
            if (!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/import_kpi/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                $randomName = $user->name_partner . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importCCCKpi($excel_file);

                        echo "<pre>";
                        print_r($excelArray);

                            if(count($excelArray) > 0){
                                foreach($excelArray as $excel) {
                                    KPI::addKPI($excel);
                                }
                            }


                        Logger::getInstance()->log($user->id_user, 'Загрузил excel файл с массивом CCC KPI' );
                        //Пишем в сессию массив с ненайденными партномерами
                        Session::set('success_import', count($excelArray));
                    }
                }
                header("Location: /adm/ccc/kpi");
            }
        }
        return true;
    }
}