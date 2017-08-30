<?php
namespace Umbrella\controllers\umbrella\crm;

use Umbrella\app\AdminBase;
use Umbrella\app\Group;
use Umbrella\app\Mail\RequestMail;
use Umbrella\app\User;
use Umbrella\components\Functions;
use Umbrella\components\ImportExcel;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\Orders;
use Umbrella\models\Products;
use Umbrella\models\Stocks;

/**
 * Class RequestController
 */
class RequestController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * RequestController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.request', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * @param string $filter
     * @return bool
     */
    public function actionIndex($filter = '')
    {
        $user = $this->user;
        $group = new Group();

        if(isset($_SESSION['add_request'])){
            $request_message = $_SESSION['add_request'];
            unset($_SESSION['add_request']);
        } else {
            $request_message = '';
        }

        $partnerList = Admin::getAllPartner();
        $order_type = Orders::getAllOrderTypes();
        $delivery_address = $user->getDeliveryAddress();
        $arrayPartNumber = $this->partArray();

        if(isset($_POST['add_request']) && $_POST['add_request'] == 'true'){

            $note = null;
            $note_mysql = null;
            if(isset($_POST['note'])){
                $note = iconv('UTF-8', 'WINDOWS-1251', $_POST['note']);
                $note_mysql = $_POST['note'];
            }
            $options['id_user'] = $user->id_user;
            $options['part_number'] = iconv('UTF-8', 'WINDOWS-1251', trim($_POST['part_number']));
            $options['so_number'] = iconv('UTF-8', 'WINDOWS-1251', trim($_POST['so_number']));
            $options['note'] = $note;
            $options['note_mysql'] = $note_mysql;
            $mName = Products::checkPurchasesPartNumber($options['part_number']);
            $price = Products::getPricePartNumber($options['part_number'], $user->id_user);
            $options['goods_name'] = $mName['mName'];
            $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;
            if($user->name_partner == 'Servisexpress'
                || $user->name_partner == 'Technoservice'
                || $user->name_partner == 'Techpoint'){
                $options['status_name'] = iconv('UTF-8', 'WINDOWS-1251', 'Нет в наличии, формируется поставка.');
            } else {
                $options['status_name'] = iconv('UTF-8', 'WINDOWS-1251', 'Нет в наличии, формируется поставка, ориентировочная дата поставки на наш склад ' . Functions::whatDayOfTheWeekAndAdd(date('Y-m-d')));
            }

            $options['status_name_mysql'] = 'Нет в наличии, формируется поставка, ориентировочная дата поставки на наш склад ' . Functions::whatDayOfTheWeekAndAdd(date('Y-m-d'));
            $options['created_on'] = date('Y-m-d H:i:s');
            $options['order_type_id'] = $_POST['order_type_id'];
            $options['note1'] = isset($_POST['note1']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['note1']): null;
            $options['note1_mysql'] = isset($_POST['note1']) ? $_POST['note1']: null;

            $ok = Orders::addReserveOrdersMsSQL($options);
            if($ok){
                $options['request_id'] = $ok;
                //Пишем в mysql
                Orders::addReserveOrders($options);
                $_SESSION['add_request'] = 'Out of stock, delivery is forming';
                Logger::getInstance()->log($user->id_user, ' создал новый запрос в Request');
            }
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }

        if($user->role == 'partner'){

            $listCheckOrders = Orders::getReserveOrdersByPartnerMsSQL($user->controlUsers($user->id_user), 0);
        } elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $listCheckOrders = Orders::getAllReserveOrdersMsSQL(0);
        }

        $this->render('admin/crm/request', compact('user','group', 'partnerList', 'order_type',
            'delivery_address', 'listCheckOrders', 'request_message', 'arrayPartNumber'));
        return true;
    }



    /**
     * Completed request
     * @return bool
     */
    public function actionCompletedRequest()
    {
        $user = $this->user;

        if($user->role == 'partner'){

            $filter = "";
            $interval = " AND sgog.created_on >= DATEADD(day, -30, GETDATE())";

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgog.created_on BETWEEN '$start' AND '$end'";
                $interval = '';
            }
            $filter .= $interval;

            $listCheckOrders = Orders::getCompletedRequestInOrdersByPartnerMsSQL($user->controlUsers($user->id_user), $filter);
        } elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $filter = "";
            $interval = " AND sgog.created_on >= DATEADD(day, -30, GETDATE())";

            if(!empty($_GET['end']) && !empty($_GET['start'])){
                $start = $_GET['start']. " 00:00";
                $end = $_GET['end']. " 23:59";
                $filter .= " AND sgog.created_on BETWEEN '$start' AND '$end'";
                $interval = '';
            }
            $filter .= $interval;

            $listCheckOrders = Orders::getAllCompletedRequestInOrdersMsSQL($filter);
        }

        $this->render('admin/crm/request_completed', compact('user','listCheckOrders'));
        return true;
    }

    /**
     * @return bool
     */
    public function actionRequestImport()
    {
        $user = $this->user;
        $group = new Group();

        if(isset($_POST['import_request']) && $_POST['import_request'] == 'true'){
            if(!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_request/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                $randomName = $user->name_partner . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importRequest($excel_file);

                        foreach ($excelArray as $import){
                            $note = null;
                            if(isset($_REQUEST['note'])){
                                $note = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['note']);
                            }
                            $options['id_user'] = $user->id_user;
                            $options['part_number'] = iconv('UTF-8', 'WINDOWS-1251', trim($import['part_number']));
                            $options['so_number'] = iconv('UTF-8', 'WINDOWS-1251', trim($import['so_number']));
                            $options['note'] = $note;
                            $mName = Products::checkPurchasesPartNumber($options['part_number']);
                            $price = Products::getPricePartNumber($options['part_number'], $user->id_user);
                            $options['goods_name'] = $mName['mName'];
                            $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;

                            if($user->name_partner == 'Servisexpress'
                                || $user->name_partner == 'Technoservice'
                                || $user->name_partner == 'Techpoint'){
                                $options['status_name'] = iconv('UTF-8', 'WINDOWS-1251', 'Нет в наличии, формируется поставка.');
                            } else {
                                $options['status_name'] = iconv('UTF-8', 'WINDOWS-1251', 'Нет в наличии, формируется поставка, ориентировочная дата поставки на наш склад ' . Functions::whatDayOfTheWeekAndAdd(date('Y-m-d')));
                            }

                            $options['created_on'] = date('Y-m-d H:i:s');
                            $options['order_type_id'] = $_REQUEST['order_type_id'];
                            $options['note1'] = isset($_POST['note1']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['note1']): null;

                            if(!empty($options['part_number'])){
                                Orders::addReserveOrdersMsSQL($options);
                            }
                        }
                        $_SESSION['add_request'] = 'Out of stock, delivery is forming';

                        Logger::getInstance()->log($user->id_user, ' загрузил массив с excel в Request');
                        header("Location: /adm/crm/request");
                    }
                }
            }
        }

        if(isset($_POST['edit_status_from_excel']) && $_POST['edit_status_from_excel'] == 'true'){
            if(!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_request/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                $randomName = $user->name_partner . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importStatusInRequest($excel_file);

                        foreach ($excelArray as $import){

                            $options['id'] = $import['id'];
                            $options['status_name'] = iconv('UTF-8', 'WINDOWS-1251', $import['status_name']);

                            Orders::addReserveOrdersMsSQL($options);
                        }
                        Logger::getInstance()->log($user->id_user, ' изменил(а) статусы в Request с excel');
                        header("Location: /adm/crm/request");
                    }
                }
            }
        }
        return true;
    }


    /**
     * Получаем цену продукта по парт номеру
     * @return bool
     */
    public function actionPricePartNumAjax()
    {
        $user = $this->user;
        $group = new Group();
        $part_number = $_REQUEST['part_number'];

        $stocks_group = $group->stocksFromGroup($user->idGroupUser($user->id_user), 'name', 'request');

        $result = Products::getPricePartNumber($part_number, $user->id_user);
        $partInStock = Stocks::checkGoodsInStocksPartners($user->id_user, $stocks_group, $part_number);
        if($result == 0){
            $data['result'] = 0;
            $data['action'] = 'not_found';
            print_r(json_encode($data));
        } else {
            $data['result'] = 1;
            $data['action'] = 'purchase';
            $data['price'] = round($result['price'], 2);
            $data['mName'] = iconv('WINDOWS-1251', 'UTF-8', $result['mName']);
            $data['stock'] = iconv('WINDOWS-1251', 'UTF-8', $partInStock['stock_name']);
            $data['quantity'] = $partInStock['quantity'] . ' Units';
            print_r(json_encode($data));
        }

        return true;
    }


    /**
     * @return bool
     */
    public function actionRequestAjax()
    {
        $user = $this->user;

        // Редактируем парт номер
        if($_REQUEST['action'] == 'edit_pn'){
            $id_order = $_REQUEST['id_order'];
            $order_pn = trim($_REQUEST['order_pn']);

            $requestInfo = Orders::getOrderRequestInfo($id_order);

            $ok = Orders::editPartNumberFromCheckOrdersById($id_order, $order_pn);
            if($ok){
                $analogPrice = Products::getPricePartNumber($order_pn, $requestInfo['site_account_id']);
                $originPrice = Products::getPricePartNumber($requestInfo['part_number'], $requestInfo['site_account_id']);

                $userRequest = new User($requestInfo['site_account_id']);

                RequestMail::getInstance()->sendEmailAnalogPartNumber($id_order, $analogPrice, $originPrice, $userRequest->email);

                Logger::getInstance()->log($user->id_user, ' изменил part number в request #' . $id_order . ' на ' . $order_pn);
                print_r(200);
            }
        }

        // Редактируем СО_номер номер
        if($_REQUEST['action'] == 'edit_so'){
            $id_order = $_REQUEST['id_order'];
            $order_so = trim(iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['order_so']));

            $ok = Orders::editSoNumberFromCheckOrdersById($id_order, $order_so);
            if($ok){
                Logger::getInstance()->log($user->id_user, ' изменил so number в request #' . $id_order . ' на ' . $order_so);
                print_r(200);
            }
        }

        // Очищаем название парт номера
        if($_REQUEST['action'] == 'clear_goods_name'){
            $id_order = $_REQUEST['id_order'];
            $goods_name = null;

            $ok = Orders::clearGoodsNameFromCheckOrdersById($id_order, $goods_name);
            if($ok){
                Logger::getInstance()->log($user->id_user, ' очистил(а) название part_number в request #' . $id_order);
                print_r(200);
            }
        }

        if($_REQUEST['action'] == 'edit_status'){
            $id_order = $_REQUEST['id_order'];
            $order_status = trim(iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['order_status']));

            $requestInfo = Orders::getOrderRequestInfo($id_order);

            $ok = Orders::editStatusFromCheckOrdersById($id_order, $order_status);
            if($ok){
                $userRequest = new User($requestInfo['site_account_id']);

                RequestMail::getInstance()->sendEmailEditStatus($id_order, $requestInfo['status_name'], $order_status, $userRequest->email);

                Logger::getInstance()->log($user->id_user, ' изменил Status в request #' . $id_order);
                print_r(200);
            }
        }

        return true;
    }


    /**
     * Edit status from import excel
     * @return bool
     */
    public function actionEditStatusFromExcel()
    {
        $user = $this->user;

        if(isset($_POST['edit_status_from_excel']) && $_POST['edit_status_from_excel'] == 'true'){
            if(!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_request/edit_status/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                $randomName = $user->name_partner . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importStatusInRequest($excel_file);

                        foreach ($excelArray as $import){

                            $id = $import['id'];
                            $status_name = iconv('UTF-8', 'WINDOWS-1251', $import['status_name']);
                            $requestInfo = Orders::getOrderRequestInfo($id);
                            $ok = Orders::editStatusFromCheckOrdersById($id, $status_name);
                            if($ok){
                                $userRequest = new User($requestInfo['site_account_id']);
                                RequestMail::getInstance()->sendEmailEditStatus($id, $requestInfo['status_name'], $status_name, $userRequest->email);
                            }
                        }
                        Logger::getInstance()->log($user->id_user, ' изменил(а) статусы в Request с excel');
                        header("Location: /adm/crm/request");
                    }
                }
            }
        }
        return true;
    }


    /**
     * Delete request
     * @param $id
     * @return bool
     */
    public function actionRequestDelete($id)
    {
        $user = $this->user;
        self::checkDenied('crm.request.delete', 'controller');

        $ok = Orders::deleteRequestMsSQLById($id);

        if($ok){
            Logger::getInstance()->log($user->id_user, 'удалил request #' . $id);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
        return true;
    }


    public function partArray(){
        return [
            531018438733,
            5616751219,
            3300361551,
            2081165058,
            3578772042,
            1366060000,
            2273112041,
            1321419408,
            4055185542,
            4071377388,
            140025891015,
            50250773004,
            1366253217,
            2192212013,
            1099025098,
            1325064234,
            8078222075,
            8078222083,
            8078222174,
            8078222091,
            2415775028,
            3570563019,
            3570563068,
            1526492200,
            3570448153,
            2425751274,
            1325560009,
            1097170003,
            140002162018,
            111332180,
            8087979038,
            8087979053,
            140013306034,
            807010408,
            1082474816,
            1099025049,
            2247620244,
            26750016105,
            1097153504,
            1325064218,
            2426355141,
            3152666016,
            140002039042,
            2426448151,
            1360166001,
            4071424230,
            3792260709,
            3879680027,
            2062390154,
            3305623047,
            2425191026,
            4055264826,
            3315058002,
            140011633157,
            140011633405,
            3570358063,
            1360064065,
            1260458201,
            140002162042,
            2426484016,
            8996619277792,
            113140062,
            1131402628,
            2426448037,
            2426448110,
            1469077018,
            2198217172,
            2426357121,
            1462229202,
            4055010179,
            1325277026,
            2149562023,
            2426294167,
            50280071007,
            140002388068,
            140011633215,
            2271033454,
            2247086024,
            3256240304,
            3305630844,
            2426354060,
            1297479055,
            140011633132,
            2142142088,
            140000570014,
            3570291025,
            1327615306,
            5611824706,
            4055166070,
            4055183422,
            2193704018,
            4071388005,
            140000549067,
            1240162105,
            3874318029,
            2673007015,
            3423981061,
            2647030010,
            3540177064,
            3540160037,
            3540160052,
            8087939016,
            3302697002,
            3878684004,
            8996619265052,
            2230088847,
            50115330008,
            2230088144,
            2231019478,
            2231024353,
            1322001304,
            2425876014,
            50228062001,
            2062991001,
            140033817010,
            2063890038,
            5611824698,
            2649010010,
            4055328233,
            2651123032,
            2060798044,
            3546433024,
            3546433016,
            3423984016,
            3879671000,
            3879671018,
            50212178003,
            2211208018,
            2212188045,
            2367192412,
            2915025007,
            2230099141,
            2248007748,
            3565189119,
            3570794010,
            3541677021,
            3556039026,
            2425738115,
            2128268014,
            1327372007,
            50201504003,
            9029791614,
            2426270001,
            140013306034,
            3570698013,
            140013306067,
            3577348125,
            3570698021,
            3195183003,
            2062986175,
            2262380104,
            50082124004,
            2425775059,
            2234308019,
            2234346019,
            8118628034,
            4055179321,
            3581991217,
            1328195019,
            1509566103,
            140013820018,
            3792709507,
            2198841203,
            2198841161,
            2198841211,
            1328469018,
            8079148030,
            2426445066,
            1366510509,
            2367130297,
            2211202029,
            140000406243,
            1327614242,
            2199185022,
            140002039174,
            140002039398,
            140002039372,
            140028579013,
            118197007,
            1181970110,
            8074592018,
            1184056016,
            3300362930,
            1360077372,
            1360077554,
            1360077380,
            8070104180,
            140000549091,
            2425827066,
            2425827090,
            4055125936,
            3540160052,
            8088493112,
            1560107979,
            140043274095,
            2061606295,
            2063763185,
            3890793221,
            4055050456,
            2211201021,
            140004857011,
            140000733067,
            3792417101,
            1119226114,
            8082280010,
            50278101006,
            3792785028,
            1560631044,
            3561501010,
            5611824656,
            5611824680,
            140002162042,
            3570140016,
            3570461016,
            4055179354,
            8078226019,
            1099903302,
            1171265232,
            807547217,
            140039004712
        ];
    }
}