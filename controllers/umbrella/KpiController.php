<?php

namespace Umbrella\controllers\umbrella;

use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\ImportExcel;
use Umbrella\components\KPI;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\Data;
use Umbrella\models\ProblemData;

/**
 * Class KpiController
 */
class KpiController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * KpiController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('adm.kpi', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * @return bool
     */
    public function actionIndex()
    {
        $user = $this->user;

        if($user->getRole() == 'partner'){

            $lastData = Data::getLastData($user->getName(), 'DESC');
            $firstData = Data::getLastData($user->getName(), 'ASC');

        } else if($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin' || $user->getRole() == 'manager'){

            $listPartner = Admin::getPartnerViewKpi(1);
            $lastData = Data::getLastDataAdmin('DESC');
            $firstData = Data::getLastDataAdmin('ASC');
        }

        $this->render('admin/kpi/index', compact('user', 'lastData', 'firstData', 'listPartner'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionUsage()
    {
        self::checkDenied('kpi.usage', 'controller');
        $user = $this->user;

        if($user->getRole() == 'partner'){

            $start = $_GET['start'];
            $end = $_GET['end'];
            $id_partner = $_GET['id_partner'];

            $listUsage = Data::getUsageByAdmin($id_partner, $start, $end);
            Logger::getInstance()->log($user->id_user, "Посмотрел Usage c {$start} - {$end}");

        } else if($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin' || $user->getRole() == 'manager'){

            $listPartner = Admin::getPartnerViewKpi(1);

            if(isset($_GET['id_partner']) && $_GET['id_partner'] == 'all'){
                $start = $_GET['start'];
                $end = $_GET['end'];


                $listUsage = Data::getAllUsageByAdmin($start, $end);
                $listUsage = [];
                Logger::getInstance()->log($user->id_user, "Посмотрел Usage c {$start} - {$end} для {$_GET['id_partner']}");

            } else {

                $start = $_GET['start'];
                $end = $_GET['end'];
                $id_partner = $_GET['id_partner'];

                $name = Admin::getNameById($id_partner);

                $listUsage = Data::getUsageByAdmin($id_partner, $start, $end);
                Logger::getInstance()->log($user->id_user, "Посмотрел Usage c {$start} - {$end} для {$name}");
            }
        }

        $this->render('admin/kpi/usage', compact('user', 'listUsage', 'listPartner'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionImport()
    {
        self::checkDenied('kpi.import', 'controller');

        $user = $this->user;

        if($user->getRole() == 'partner'){

            header("Location: /adm/access_denied");

        } else if($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin' || $user->getRole() == 'manager'){

            $count_kpi_success = Session::pull('kpi_success');
            $count_call_success = Session::pull('call_success');
            $count_email_success = Session::pull('email_success');

            if(isset($_REQUEST['kpi_excel_file']) && $_REQUEST['kpi_excel_file'] == 'true'){

                // Полачем последний номер покупки
                if(!empty($_FILES['excel_file']['name'])) {

                    $options['name_real'] = $_FILES['excel_file']['name'];
                    // Все загруженные файлы помещаются в эту папку
                    $options['file_path'] = "/upload/import_kpi/";
                    $randomName = substr_replace(sha1(microtime(true)), '', 5);

                    // Получаем расширение файла
                    $getMime = explode('.', $options['name_real']);
                    $mime = end($getMime);

                    $randomName = 'KPI-'. date('Y-m-d') . "-" . $randomName . "." . $mime;
                    $options['file_name'] = $randomName;

                    if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                        if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                            $excel_file = $options['file_path'] . $options['file_name'];
                            // Получаем массив данных из файла

                            $excelArray = ImportExcel::importKpi($excel_file);

//                            echo "<pre>";
//                            print_r($excelArray);

                            $i = 0;
                            if(isset($excelArray) && count($excelArray) > 0){
                                foreach ($excelArray as $insert){
                                    $ok = Data::importKPI($insert);
                                    if($ok){
                                        $i++;
                                    }
                                }
                            }
                            Logger::getInstance()->log($user->id_user, 'Импортировал массив KPI');
                            Session::set('kpi_success', $i);
                        }
                    }
                    Url::redirect('/adm/kpi/import');
                }
            }

            if(isset($_REQUEST['call_excel_file']) && $_REQUEST['call_excel_file'] == 'true'){

                // Полачем последний номер покупки
                if(!empty($_FILES['excel_file']['name'])) {

                    $options['name_real'] = $_FILES['excel_file']['name'];
                    // Все загруженные файлы помещаются в эту папку
                    $options['file_path'] = "/upload/import_kpi/";
                    $randomName = substr_replace(sha1(microtime(true)), '', 5);

                    // Получаем расширение файла
                    $getMime = explode('.', $options['name_real']);
                    $mime = end($getMime);

                    $randomName = 'Call_CSAT-'. date('Y-m-d') . "-" . $randomName . "." . $mime;
                    $options['file_name'] = $randomName;

                    if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                        if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                            $excel_file = $options['file_path'] . $options['file_name'];
                            // Получаем массив данных из файла

                            $excelArray = ImportExcel::importCallCSAT($excel_file);

//                            echo "<pre>";
//                            print_r($excelArray);

                            $i = 0;
                            if(isset($excelArray) && count($excelArray) > 0){
                                foreach ($excelArray as $insert){
                                    $ok = Data::importCallCSAT($insert);
                                    if($ok){
                                        $i++;
                                    }
                                }
                            }
                            Logger::getInstance()->log($user->id_user, 'Импортировал массив Call CSAT');
                            Session::set('call_success', $i);
                        }
                    }
                    Url::redirect('/adm/kpi/import');
                }
            }


            if(isset($_REQUEST['email_excel_file']) && $_REQUEST['email_excel_file'] == 'true'){

                // Полачем последний номер покупки
                if(!empty($_FILES['excel_file']['name'])) {

                    $options['name_real'] = $_FILES['excel_file']['name'];
                    // Все загруженные файлы помещаются в эту папку
                    $options['file_path'] = "/upload/import_kpi/";
                    $randomName = substr_replace(sha1(microtime(true)), '', 5);

                    // Получаем расширение файла
                    $getMime = explode('.', $options['name_real']);
                    $mime = end($getMime);

                    $randomName = 'Email_CSAT-'. date('Y-m-d') . "-" . $randomName . "." . $mime;
                    $options['file_name'] = $randomName;

                    if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                        if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                            $excel_file = $options['file_path'] . $options['file_name'];
                            // Получаем массив данных из файла

                            $excelArray = ImportExcel::importEmailCallCSAT($excel_file);

                            $i = 0;
                            if(isset($excelArray) && count($excelArray) > 0){
                                foreach ($excelArray as $insert){
                                    $ok = Data::importEmailCSAT($insert);
                                    if($ok){
                                        $i++;
                                    }
                                }
                            }
                            Logger::getInstance()->log($user->id_user, 'Импортировал массив Email CSAT');
                            Session::set('email_success', $i);
                        }
                    }
                    Url::redirect('/adm/kpi/import');
                }
            }
        }

        $this->render('admin/kpi/import', compact('user', 'count_kpi_success', 'count_call_success', 'count_email_success'));
        return true;
    }


    /**
     * Страниа отображения релузьтатов выборки
     * @return bool
     */
    public function actionResult()
    {
        $user = $this->user;

        $start = $_GET['start'];
        $end = $_GET['end'];
        if($end == ''){
            $end = date('Y-m-d');
        }

        if($user->getRole() == 'partner'){

            $KPI = new KPI($user->getName(), $start, $end);
            $lastData = Data::getLastData($user->getName(), 'DESC');
            $firstData = Data::getLastData($user->getName(), 'ASC');
            $name_partner = $user->getName();

            Logger::getInstance()->log($user->id_user, "посмотрел отчет KPI с " . $start . " по " . $end);

            $this->render('admin/kpi/result_one_partner', compact('user', 'KPI', 'lastData', 'firstData', 'name_partner', 'start', 'end'));


        } else if($user->getRole() == 'administrator' || $user->getRole() == 'administrator-fin' || $user->getRole() == 'manager'){

            $listPartner = Admin::getPartnerViewKpi(1);

            if(isset($_GET['name_partner'])){
                $name_partner = $_GET['name_partner'];

                if($name_partner == 'all'){

                    $KPI = new KPI($user->getName(), $start, $end);
                    $lastData = Data::getLastDataAdmin('DESC');
                    $firstData = Data::getLastDataAdmin('ASC');

                    Logger::getInstance()->log($user->id_user, "посмотрел отчет KPI с " . $start . " по " . $end . " для всех партнеров");

                    $this->render('admin/kpi/result_all_partner', compact('user', 'KPI', 'lastData', 'firstData', 'listPartner', 'start', 'end'));

                } else {

                    $KPI = new KPI($name_partner, $start, $end);
                    $lastData = Data::getLastData($name_partner, 'DESC');
                    $firstData = Data::getLastData($name_partner, 'ASC');

                    Logger::getInstance()->log($user->id_user, "посмотрел отчет KPI с " . $start . " по " . $end . " для " . $name_partner);

                    $this->render('admin/kpi/result_one_partner', compact('user','KPI', 'lastData', 'firstData', 'name_partner', 'listPartner', 'start', 'end'));
                }
            }
        }

        return true;
    }


    /**
     * @return bool
     */
    public function actionShowProblem()
    {
        $user = $this->user;

        $partner = $_REQUEST['partner'];
        $kpi = $_REQUEST['kpi'];
        $start = $_REQUEST['start'];
        $end = $_REQUEST['end'];

        if($partner == 'GS Servisa'){
            $count_day_14 = 31;
            $count_day_21 = 35;
        } else {
            $count_day_14 = 16;
            $count_day_21 = 21;
        }

        // В зависимости от выбраного показателя KPI делаем запрос в бд
        switch ($kpi){
            case 'email CSAT':
                $data = ProblemData::PPl($partner, $start, $end);
                break;
            case 'call CSAT':
                $data = ProblemData::PPl($partner, $start, $end);
                break;
            case 'ECR':
                $data = ProblemData::ECR($partner, $start, $end);
                break;
            case 'Order TAT':
                $data = ProblemData::Order_TAT($partner, $start, $end);
                break;
            case 'Repair TAT':
                $data = ProblemData::Repair_TAT($partner, $start, $end);
                break;
            case 'SW repair TAT':
                $data = ProblemData::SW_Repair_TAT($partner, $start, $end);
                break;
            case 'SO creation TAT':
                $data = ProblemData::SO_Creation_TAT($partner, $start, $end);
                break;
            case 'L0 Rate':
                $data = ProblemData::PPl($partner, $start, $end);
                break;
            case 'PPl':
                $data = ProblemData::PPl($partner, $start, $end);
                break;
            case 'LongTail 14 days':
                $data = ProblemData::LongTail_14_Days($partner, $start, $end, $count_day_14);
                break;
            case 'LongTail 21 days':
                $data = ProblemData::LongTail_21_Days($partner, $start, $end, $count_day_21);
                break;
            case 'FTF 30 days':
                $data = ProblemData::PPl($partner, $start, $end);
                break;
            case 'FTF 90 days':
                $data = ProblemData::PPl($partner, $start, $end);
                break;
//            case 'L2 Rate':
//                $data = ProblemData::PPl($partner, $start, $end);
//                break;
            case 'Refund Rate':
                $data = ProblemData::Refund_Rate($partner, $start, $end);
                break;
//            case 'LS rate':
//                $data = ProblemData::PPl($partner, $start, $end);
//                break;
            default:
                $data = [];
        }

        //print_r($data);

        $this->render('admin/kpi/show_probem_kpi', compact('user','data', 'start', 'end', 'partner'));
        return true;
    }

}