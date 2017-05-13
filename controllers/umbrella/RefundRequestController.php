<?php

/**
 * Class RefundRequestController
 */
class RefundRequestController extends AdminBase
{

    /**
     * RefundRequestController constructor.
     */
    public function __construct()
    {
        self::checkDenied('adm.refund_request', 'controller');
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

        $countryList = Country::getAllCountry();

        if(isset($_POST['send_request'])){
//            $options['id_user'] = $userId;
//            $options['Request_Country'] = $_POST['Request_Country'];
//            $options['Request_Type'] = $_POST['Request_Type'];
//            $options['Requestor_First_Name'] = $_POST['Requestor_First_Name'];
//            $options['Requestor_Last_Name'] = $_POST['Requestor_Last_Name'];
//            $options['Requestor_Email'] = $_POST['Requestor_Email'];

            // Если стоит чек-бокс
            if($_POST['Multiple_Request'] == 1){

                if(!empty($_FILES['csv_file']['name'])){

                    $csv_file =  $_FILES['csv_file']['tmp_name'];
                    $count_not_pn = 0;
                    $arr_error_pn = [];
                    if (is_file($csv_file)) {
                        $input = fopen($csv_file, 'a+');
                        $row = fgetcsv($input, 1024, ','); // here you got the header
                        $lastSiteId = Warranty::getLastRequest();
                        $lastSiteId++;
                        $i = 0;
                        while ($row = fgetcsv($input, 1024, ',')) {

                            $options[$i]['id_user'] = $userId;
                            $options[$i]['Request_Country'] = $_POST['Request_Country'];
                            $options[$i]['Request_Type'] = $_POST['Request_Type'];
                            $options[$i]['Requestor_First_Name'] = $_POST['Requestor_First_Name'];
                            $options[$i]['Requestor_Last_Name'] = $_POST['Requestor_Last_Name'];
                            $options[$i]['Requestor_Email'] = $_POST['Requestor_Email'];

                            $options[$i]['Refund_Reason'] = $_POST['Refund_Reason'];
                            $options[$i]['Additional_Comment'] = $_POST['Additional_Comment'];
                            $options[$i]['site_id'] = $lastSiteId;

                            $options[$i]['SN'] =  $row[0];       //SN

                            if(Products::checkPartNumber($row[1]) == 1){
                                $options[$i]['PN_MTM'] =  $row[1];          // PN_MTM
                            } else {
                                array_push($arr_error_pn, $row[1]);
                                $count_not_pn++;
                            }

                            $options[$i]['Lenovo_SO'] =  $row[2];      // Lenovo_SO
                            $options[$i]['SO_Create_Date'] = $row[3];   // SO_Create_Date
                            $options[$i]['Partner_SO_RMA'] = $row[4];      // Partner_SO_RMA
                            $options[$i]['Product_Group'] = $row[5];      // Product_Group
                            $options[$i]['Future_Unit_location'] = $row[6];     // Future_Unit_location
                            $options[$i]['Estimated_cost'] = $row[7];     // Estimated_cost
                            $options[$i]['Refund_Reason'] = $row[8];     // Refund_Reason Future_Unit_location
                            $i++;
                        }

                        //print_r($options);

                        if($count_not_pn == 0 ){
                            //echo "пишем в бд";
                            foreach($options as $item){
                                $options['id_warranty'] = Warranty::addWarrantyRegistration($item);
                                // Запись в gm_manager в mssql
                                $item['ready'] = 1;
                                Warranty::addWarrantyRegistrationMsSql($item);
                                //print_r($item);
                            }
                            $options['name_real'] = $_FILES['csv_file']['name'];

                            // Все загруженные файлы помещаются в эту папку
                            $options['file_path'] = "/upload/attach_file/";
                            $randomName = substr_replace(sha1(microtime(true)), '', 12);

                            // Получаем расширение файла
                            $getMime = explode('.', $options['name_real']);
                            //$mime = end($getMime);

                            $randomName = $getMime['0'] . "-" . $randomName . "." . $getMime['1'];
                            $options['file_name'] = $randomName;

                            if (is_uploaded_file($_FILES["csv_file"]["tmp_name"])) {
                                if (move_uploaded_file($_FILES['csv_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                                    File::addWarrantyFile($options);
                                }
                            }

                            header("Location: /adm/refund_request/thank_you_page");

                        } else {
                            //echo "ПН не совпадает " .$count_not_pn;
                            //print_r($arr_error_pn);
                        }
                    }

                } else {
                    $not_exit_file = "The file is not loaded!";
                }

            } else {
                // Если чекбокс не поставлен - пише данные с формы
				$arr_error_pn = [];
				
                $options['id_user'] = $userId;
                $options['Request_Country'] = $_POST['Request_Country'];
                $options['Request_Type'] = $_POST['Request_Type'];
                $options['Requestor_First_Name'] = $_POST['Requestor_First_Name'];
                $options['Requestor_Last_Name'] = $_POST['Requestor_Last_Name'];
                $options['Requestor_Email'] = $_POST['Requestor_Email'];

                $options['Refund_Reason'] = $_POST['Refund_Reason'];

                if($options['Refund_Reason'] == 'Part_shortage'){
                    $options['Refund_Reason'] = "Part shortage: " . $_POST['Missing_Part'];
                }
                if($options['Refund_Reason'] == 'DOA'){
                    $options['Refund_Reason'] = "DOA: " . $_POST['DOA_Validation_results'];
                }

                $options['Lenovo_SO'] = $_POST['Lenovo_SO'];
                $options['SO_Create_Date'] = $_POST['SO_Create_Date'];
                $options['SN'] = $_POST['SN'];
                $options['PN_MTM'] = $_POST['PN_MTM'];
                $options['Product_Group'] = $_POST['Product_Group'];

                $options['Partner_SO_RMA'] = $_POST['Partner_SO_RMA'];
                $options['Future_Unit_location'] = $_POST['Future_Unit_location'];
                $options['Additional_Comment'] = $_POST['Additional_Comment'];
                $options['Estimated_cost'] = $_POST['Estimated_cost'];

                // Получаем последнюю запись
                $lastSiteId = Warranty::getLastRequest();
                $lastSiteId++;
                $options['site_id'] = $lastSiteId;
                $options['ready'] = 1;
				
				if(Products::checkPartNumber($options['PN_MTM']) == 1){
                    $options['id_warranty'] = Warranty::addWarrantyRegistration($options);
                    // Запись в gm_manager в mssql
                    Warranty::addWarrantyRegistrationMsSql($options);
					
					if(!empty($_FILES['csv_file']['name'])){
                    $options['name_real'] = $_FILES['csv_file']['name'];

                    // Все загруженные файлы помещаются в эту папку
                    $options['file_path'] = "/upload/attach_file/";
                    $randomName = substr_replace(sha1(microtime(true)), '', 12);

                    // Получаем расширение файла
                    $getMime = explode('.', $options['name_real']);
                    //$mime = end($getMime);

                    $randomName =  $getMime['0'] . "-" . $randomName . "." . $getMime['1'];
                    $options['file_name'] = $randomName;

                    if (is_uploaded_file($_FILES["csv_file"]["tmp_name"])) {
                        if (move_uploaded_file($_FILES['csv_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                            File::addWarrantyFile($options);
                        }
                    }
                }
                header("Location: /adm/refund_request/thank_you_page");
                } else {
                    array_push($arr_error_pn, $options['PN_MTM']);
                }

                //$options['id_warranty'] = Warranty::addWarrantyRegistration($options);
                // Запись в gm_manager в mssql
                //Warranty::addWarrantyRegistrationMsSql($options);

            }
        }

        if($user->role == 'partner'){

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

        }
        require_once(ROOT . '/views/admin/refund_request/index.php');
        return true;
    }


    /**
     * Поиск через ajax по партийному номеру
     * @return bool
     */
    public function actionPartNumAjax()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

        $partNum = $_REQUEST['pn_number'];

        $PartNumber = Products::checkPartNumber($partNum);

        echo $PartNumber;

        return true;
    }


    /**
     * Подгружаем список прикрепенный файлов к заявке
     * @return bool
     */
    public function actionRequestAjax()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

       if($_REQUEST['action'] == 'file'){
           $id_request = $_REQUEST['id_request'];

           $listFile = File::fileByWarranty($id_request);
           $html = "";
           $html .= "<ul class='list-attachment-file'>";
           foreach($listFile as $file){
               $html .= "<li><a href='" . $file['file_path'] . $file['file_name'] . "' target='_blank' download> <i class='fi-page-doc'></i> " . $file['name_real'] . "</a></li>";
           }
           $html .= "</ul>";

           echo $html;
       }
	   
	   // Подгрузка заявок по клику
        if($_REQUEST['action'] == 'load_refund_request'){
            $num_req = $_REQUEST['num_req'];

            $allRequest = Warranty::getAllRequest($num_req);
            if(count($allRequest) > 0){
                $html = "";
                foreach($allRequest as $req){
                    ($req['lenovo_ok'] == 1) ? $check_lenovo_ok = 'check_lenovo_ok' : $check_lenovo_ok = '';
                    ($req['lenovo_ok'] == 0) ? $check_lenovo = 'check_lenovo' : $check_lenovo = 'uncheck_lenovo';
                    // Получаем статус и id из GS MANAGER
                    $status_all = Warranty::checkStatusRequest($req['SN'], $req['PN_MTM'], $req['site_id']);
                    $id_gs = iconv('WINDOWS-1251', 'UTF-8', $status_all['purchase_id']);

                    $html .= "<tr class='goods ". $check_lenovo_ok . "' data-id='" . $req['id_warrantry'] . "' data-gm-id='" . $status_all['id'] . "'>";
                    //$html .= "<td class='" . $check_lenovo . "'>" . $req['site_id'] . "</td>";
                    $html .= "<td class='" . $check_lenovo . "'>" . $id_gs . "</td>";
                    $html .= "<td>" . $req['name_partner'] . "</td>";
                    $html .= "<td>" . $req['Request_Country'] . "</td>";
                    $html .= "<td>" . $req['SN'] . "</td>";
                    $html .= "<td>" . $req['PN_MTM'] . "</td>";
                    $html .= "<td>" . $req['Lenovo_SO'] . "</td>";
                    $html .= "<td>" . $req['SO_Create_Date'] . "</td>";
                    $html .= "<td>" . $req['Partner_SO_RMA'] . "</td>";
                    $html .= "<td>" . $req['Product_Group'] . "</td>";
                    $html .= "<td>" . $req['Future_Unit_location'] . "</td>";
                    $html .= "<td>" . $req['Estimated_cost'] . "</td>";
                    $html .= "<td>" . $req['Refund_Reason'] . "</td>";

                    $count = count(File::fileByWarranty($req['id_warrantry']));
                    ($count > 0) ? $text = 'show-file' : $text = '';
                    $html .= "<td><a data-open='" . $text . "' class='file_request' data-file='" . $req['id_warrantry'] . "'> $count <i class='fi-download'></i></a></td>";
					
					(empty($req['Additional_Comment'])) ? $count_comment = '' : $count_comment = '+';
                    $html .= "<td data-comment='" . $req['Additional_Comment'] . "' class='comment'>
                    <a href='' class='add-lenovo-num'><i class='fi-comments'></i></a><br>
                    <span class='text-lenovo-num'>" .$req['lenovo_num'] . "</span><br>" . $count_comment . "</td>";

                    $status = iconv('WINDOWS-1251', 'UTF-8', $status_all['status_name']);
                    ($status == NULL) ? $status = 'Expect' : $status;
                    $date_write = iconv('WINDOWS-1251', 'UTF-8', $status_all['writeoff_status_on']);
                    $html .= "<td class='" . Warranty::getStatusRequest($status) . "'>" . $status . "<br>" . $date_write . "</td>";
                    $html .= "<td>" . $req['date_create_request'] . "</td>";
                    $html .= "<td class='action-control'>";
                    if($status == 'предварительное'){
                        $html .= "<a href='' class='accept refund-accept'><i class='fi-check'></i></a>";
                        $html .= "<a href='' class='dismiss refund-dismiss'><i class='fi-x'></i></a>";
                    }
                    $html .= "</td>";
                    $html .= "</tr>";
                }
                sleep(1); //Сделана задержка в 1 секунду чтобы можно проследить выполнение запроса
                echo $html;
            } else {
                echo 0; //Если записи закончились
            }
        }
	   
	   // ОТмечаем что отправленно на леново
        if($_REQUEST['action'] == 'check_lenovo'){
            $id_warranty = $_REQUEST['id_warranty'];
            $ok = Warranty::checkWarrantyById($id_warranty, 1);
            if($ok){
                echo 1;
            }
        }

        // убираем отметку что отправленно на леново
        if($_REQUEST['action'] == 'uncheck_lenovo'){
            $id_warranty = $_REQUEST['id_warranty'];
            $ok = Warranty::checkWarrantyById($id_warranty, 0);
            if($ok){
                echo 1;
            }
        }

        // Пишем номер с леново
        if($_REQUEST['action'] == 'add_lenovo_num'){
            $id_warranty = $_REQUEST['id_warranty'];
            $lenovo_num = $_REQUEST['lenovo_num'];

            Warranty::addLenovoNumWarrantyById($id_warranty, $lenovo_num);
        }

        // убираем отметку что отправленно на леново
        if($_REQUEST['action'] == 'accept'){
            $refund_id = $_REQUEST['refund_id'];
            $ok = Warranty::updateStatusRefundGM($refund_id, 1, NULL);
            if($ok){
                $status['ok'] = 1;
                $status['class'] = 'green';
                $status['text'] = 'подтверждено';
            } else {
                $status['ok'] = 0;
                $status['error'] = 'Ошибка подтверждения!';
            }
            echo json_encode($status);
        }

        if($_REQUEST['action'] == 'dismiss'){
            $refund_id = $_REQUEST['refund_id'];
            $comment = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['comment']);
            $ok = Warranty::updateStatusRefundGM($refund_id, 2, $comment);
            if($ok){
                $status['ok'] = 1;
                $status['class'] = 'red';
                $status['text'] = 'отклонено';
            } else {
                $status['ok'] = 0;
                $status['error'] = 'Ошибка подтверждения!';
            }
            echo json_encode($status);
        }

        return true;
    }


    /**
     *  Страница просмотра оставленных заявок
     * @return bool
     */
    public function actionViewRequest()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        $allPartner = Admin::getAllPartner();
        $countryList = Country::getAllCountry();

        if($user->role == 'partner'){

            $requestByPartner = Warranty::getRequestByPartner($userId);

            require_once(ROOT . '/views/admin/refund_request/view_request_partner.php');

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){
            $allRequest = Warranty::getAllRequest(0);

            require_once(ROOT . '/views/admin/refund_request/view_request_admin.php');
        }

        return true;
    }

    /**
     * Страница фильтрации данных
     * @return bool
     */
    public function actionFilterRequest()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        $allPartner = Admin::getAllPartner();
        $countryList = Country::getAllCountry();

        if($user->role == 'partner'){

            if(empty($_GET['start']) &&
                empty($_GET['end'])){

                //$allRequest = null;
                $filter = "";
                $requestByPartner = Warranty::getRequestByPartner($userId, $filter);
            } else {

                $filter = "";

                if(!empty($_GET['end']) && !empty($_GET['start'])){
                    $start = $_GET['start']. " 00:00";
                    $end = $_GET['end']. " 23:59";
                    $filter .= " AND gw.date_create_request BETWEEN '$start' AND '$end'";
                }
                $requestByPartner = Warranty::getRequestByPartner($userId, $filter);
            }

            require_once(ROOT . '/views/admin/refund_request/view_request_partner.php');

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){
            // Фильтрация

            if(empty($_GET['Request_Country']) &&
                empty($_GET['id_partner']) &&
                empty($_GET['start']) &&
                empty($_GET['end'])){

                //$allRequest = null;
                $filter = "";
                $allRequest = Warranty::getFilterRequest($filter);

            } else {

                $filter = "";

                if(!empty($_GET['Request_Country'])){
                    $country = $_GET['Request_Country'];
                    $filter .= " AND gw.Request_Country = '$country'";
                }

                if(!empty($_GET['id_partner'])){
                    $id_user = $_GET['id_partner'];
                    $filter .= " AND gu.id_user = '$id_user'";
                }

                if(!empty($_GET['end']) && !empty($_GET['start'])){
                    $start = $_GET['start']. " 00:00";
                    $end = $_GET['end']. " 23:59";
                    $filter .= " AND gw.date_create_request BETWEEN '$start' AND '$end'";

                }

                $allRequest = Warranty::getFilterRequest($filter);

            }

            require_once(ROOT . '/views/admin/refund_request/view_request_admin.php');
        }

        return true;
    }


    /**
     * Страница благодарности
     * @return bool
     */
    public function actionThankYouPage()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        $log = "отправил запрос на списание (Warranty Exception Registration)";
        Log::addLog($userId, $log);

        require_once(ROOT . '/views/admin/refund_request/thank_you_page.php');
        return true;
    }


    public function actionTest()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if(isset($_POST['send_request'])){
            //print_r($_FILES);
            $csv_file =  $_FILES['csv_file']['tmp_name'];
            //echo "<pre>";
            $count_not_pn = 0;
            $arr_error_pn = [];
            if (is_file($csv_file)) {
                $input = fopen($csv_file, 'a+');
                $row = fgetcsv($input, 1024, ','); // here you got the header

                while ($row = fgetcsv($input, 1024, ',')) {
                    //echo $row[0] . " ";     // Refund_Reason
                    //echo $row[1] . " ";     // Lenovo_SO
                    //echo $row[2] . " ";     // SO_Create_Date
                    //echo $row[3] . " ";     // SN
                    //echo $row[4] . " ";     // PN_MTM

                    if(Products::checkPartNumber2($row[4]) == 1){
                        echo $row[4];
                    } else {
                        array_push($arr_error_pn, $row[4]);
                        $count_not_pn++;
                    }
                    //echo $row[5] . " ";     // Product_Group
                   // echo $row[6] . " ";     // Partner_SO_RMA
                    //echo $row[7] . " ";     // Future_Unit_location
                    //echo $row[8] . "<br>";  // Estimated_cost
                }
            }

            if($count_not_pn == 0 ){
                echo "пишем в бд";
            } else {
                echo "ПН не совпадает " .$count_not_pn;
                print_r($arr_error_pn);
            }
        }


        require_once(ROOT . '/views/admin/refund_request/test.php');
        return true;
    }

}