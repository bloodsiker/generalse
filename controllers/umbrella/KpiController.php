<?php

/**
 * Class KpiController
 */
class KpiController extends AdminBase
{

    /**
     * KpiController constructor.
     */
    public function __construct()
    {
        self::checkDenied('adm.kpi', 'controller');
    }

    /**
     * @return bool
     */
    public function actionIndex()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        //$all = Data::FTF_30_Days('GS Fine Service', '2016-10-00', '2016-11-02');

        if($user->role == 'partner'){

            $lastData = Data::getLastData($user->name_partner, 'DESC');
            $firstData = Data::getLastData($user->name_partner, 'ASC');

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $listPartner = Admin::getPartnerViewKpi(1);
            $lastData = Data::getLastDataAdmin('DESC');
            $firstData = Data::getLastDataAdmin('ASC');
        }

        require_once(ROOT . '/views/admin/kpi/index.php');
        return true;
    }


    /**
     * @return bool
     */
    public function actionUsage()
    {
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('kpi.usage', 'controller');

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if($user->role == 'partner'){

            $start = $_GET['start'];
            $end = $_GET['end'];
            $id_partner = $_GET['id_partner'];

            $listUsage = Data::getUsageByAdmin($id_partner, $start, $end);
            Logger::getInstance()->log($user->id_user, "Посмотрел Usage c {$start} - {$end}");

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $listPartner = Admin::getPartnerViewKpi(1);

            if(isset($_GET['id_partner']) && $_GET['id_partner'] == 'all'){
                $start = $_GET['start'];
                $end = $_GET['end'];

                $listUsage = Data::getAllUsageByAdmin($start, $end);
                Logger::getInstance()->log($user->id_user, "Посмотрел Usage c {$start} - {$end} для {$name}");

            } else {

                $start = $_GET['start'];
                $end = $_GET['end'];
                $id_partner = $_GET['id_partner'];

                $name = Admin::getNameById($id_partner);

                $listUsage = Data::getUsageByAdmin($id_partner, $start, $end);
                Logger::getInstance()->log($user->id_user, "Посмотрел Usage c {$start} - {$end} для {$name}");
            }
        }

        require_once(ROOT . '/views/admin/kpi/usage.php');
        return true;
    }


    /**
     * @return bool
     */
    public function actionImport()
    {
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('kpi.import', 'controller');

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if($user->role == 'partner'){

            header("Location: /adm/access_denied");

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $cout_kpi_success = isset($_SESSION['kpi_success']) ? $_SESSION['kpi_success'] : '';
            $cout_call_success = isset($_SESSION['call_success']) ? $_SESSION['call_success'] : '';
            $cout_email_success = isset($_SESSION['email_success']) ? $_SESSION['email_success'] : '';

            if(isset($_SESSION['kpi_success'])){
                unset($_SESSION['kpi_success']);
            }
            if(isset($_SESSION['call_success'])){
                unset($_SESSION['call_success']);
            }
            if(isset($_SESSION['email_success'])){
                unset($_SESSION['email_success']);
            }

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
                            $_SESSION['kpi_success'] = $i;
                        }
                    }
                    header("Location: /adm/kpi/import");
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
                            $_SESSION['call_success'] = $i;
                        }
                    }
                    header("Location: /adm/kpi/import");
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
                            $_SESSION['email_success'] = $i;
                        }
                    }
                    header("Location: /adm/kpi/import");
                }
            }
        }

        require_once(ROOT . '/views/admin/kpi/import.php');
        return true;
    }


    /**
     * Страниа отображения релузьтатов выборки
     * @return bool
     */
    public function actionResult()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);
        $start = $_GET['start'];
        $end = $_GET['end'];
        if($end == ''){
            $end = date('Y-m-d');
        }

        if($user->role == 'partner'){

            $KPI = new KPI($user->name_partner, $start, $end);
            $lastData = Data::getLastData($user->name_partner, 'DESC');
            $firstData = Data::getLastData($user->name_partner, 'ASC');
            $name_partner = $user->name_partner;

            Logger::getInstance()->log($user->id_user, "посмотрел отчет KPI с " . $start . " по " . $end);

            require_once(ROOT . '/views/admin/kpi/result_one_partner.php');


        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $listPartner = Admin::getPartnerViewKpi(1);

            if(isset($_GET['name_partner'])){
                $name_partner = $_GET['name_partner'];

                if($name_partner == 'all'){

                    $KPI = new KPI($user->name_partner, $start, $end);
                    $lastData = Data::getLastDataAdmin('DESC');
                    $firstData = Data::getLastDataAdmin('ASC');

                    Logger::getInstance()->log($user->id_user, "посмотрел отчет KPI с " . $start . " по " . $end . " для всех партнеров");

                    require_once(ROOT . '/views/admin/kpi/result_all_partner.php');

                } else {

                    $KPI = new KPI($name_partner, $start, $end);
                    $lastData = Data::getLastData($name_partner, 'DESC');
                    $firstData = Data::getLastData($name_partner, 'ASC');

                    Logger::getInstance()->log($user->id_user, "посмотрел отчет KPI с " . $start . " по " . $end . " для " . $name_partner);

                    require_once(ROOT . '/views/admin/kpi/result_one_partner.php');
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
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

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

        require_once(ROOT . '/views/admin/kpi/show_probem_kpi.php');
        return true;
    }

}