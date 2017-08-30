<?php
namespace Umbrella\controllers\umbrella\crm;

use Umbrella\app\AdminBase;
use Umbrella\app\Mail\OtherRequestMail;
use Umbrella\app\User;
use Umbrella\components\ImportExcel;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\OtherRequest;
use Umbrella\models\Products;

/**
 * Class OtherRequestController
 */
class OtherRequestController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * OtherRequestController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.other.request', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * @return bool
     */
    public function actionIndex()
    {
        $user = $this->user;

        $delivery_address = $user->getDeliveryAddress();

        if(isset($_POST['add_request']) && $_POST['add_request'] == 'true'){
            $options['id_user'] = $user->id_user;
            $options['part_number'] = $_POST['part_number'];
            $part_num = Products::checkPurchasesPartNumber($_POST['part_number']);
            $options['part_description'] = iconv('WINDOWS-1251', 'UTF-8', $part_num['mName']);
            $options['so_number'] = $_POST['so_number'];
            $options['order_type'] = $_POST['order_type'];
            $options['address'] = isset($_POST['address']) ? $_POST['address'] : null;
            $options['note'] = $_POST['note'];
            $options['status_name'] = 'В обработке';

            $id = OtherRequest::addRequestOrders($options);
            if($id){
                OtherRequestMail::getInstance()->sendEmailGS($options, $user->name_partner, $id);
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }

        if($user->role == 'partner' || $user->role == 'manager'){
            $listRequests = OtherRequest::getListRequest($user->controlUsers($user->id_user));
        } elseif($user->role == 'administrator' || $user->role == 'administrator-fin' ){
            $listRequests = OtherRequest::getListRequestAdmin();
        }

        $this->render('admin/crm/other_request', compact('user','delivery_address', 'listRequests'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionRequestImport()
    {
        $user = $this->user;

        if(isset($_POST['import_request']) && $_POST['import_request'] == 'true'){
            if(!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_other_request/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                $randomName = $user->name_partner . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importRequest($excel_file);

                        $note = null;
                        if(isset($_REQUEST['note'])){
                            $note = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['note']);
                        }

                        $options['id_user'] = $user->id_user;
                        $options['note'] = $note;
                        $options['status_name'] = 'В обработке';
                        $options['order_type'] = $_REQUEST['order_type'];
                        $options['address'] = isset($_POST['address']) ? $_REQUEST['address'] : null;

                        foreach ($excelArray as $import){
                            $options['part_number'] = trim($import['part_number']);
                            $options['so_number'] = trim($import['so_number']);

                            $mName = Products::checkPurchasesPartNumber($options['part_number']);
                            $options['part_description'] = iconv('WINDOWS-1251', 'UTF-8', $mName['mName']);

                            if(!empty($options['part_number'])){
                                OtherRequest::addRequestOrders($options);
                            }
                        }

                        OtherRequestMail::getInstance()->sendImportEmailGS($options, $user->name_partner, count($excelArray));

                        Logger::getInstance()->log($user->id_user, ' загрузил массив с excel в Lenovo Request');
                        header("Location: /adm/crm/other-request");
                    }
                }
            }
        }

        return true;
    }


    /**
     * @return bool
     */
    public function actionRequestAjax()
    {
        $user = $this->user;

        // Редактируем price
        if ($_REQUEST['action'] == 'edit_price') {
            $id_request = $_REQUEST['id_request'];
            $price = $_REQUEST['request_price'];

            $ok = OtherRequest::editPriceToRequestById($id_request, str_replace(',', '.', $price));
            if ($ok) {
                Logger::getInstance()->log($user->id_user, ' изменил price в lenovo request #' . $id_request . ' на ' . $price);
                print_r(200);
            }
        }

        // Мягкое удаление
        if ($_REQUEST['action'] == 'request_delete') {
            $id_request = $_REQUEST['id_request'];

            $ok = OtherRequest::deleteRequestById($id_request, 1);
            if ($ok) {
                Logger::getInstance()->log($user->id_user, ' удалил lenovo request #' . $id_request);
                print_r(200);
            }
        }

        // Согласование с партнером
        if ($_REQUEST['action'] == 'action') {
            $id_request = $_REQUEST['id_request'];
            $action = $_REQUEST['user_action'];

            // На согласование партнеру
            if ($action == 1) {
                $ok = OtherRequest::changeActionAndStatusToRequestById($id_request, $action, 'Согласование');
                if ($ok) {
                    // send mail partner
                    $options = OtherRequest::getRequestById($id_request);
                    OtherRequestMail::getInstance()->sendEmailPartnerAlignment($options);
                    print_r(200);
                }
            }

            // Отказ
            if ($action == 2) {
                $comments = $_REQUEST['comment'];
                $ok = OtherRequest::changeActionAndStatusToRequestById($id_request, $action, 'Отказано', $comments);
                if ($ok) {
                    // send mail partner
                    $options = OtherRequest::getRequestById($id_request);
                    OtherRequestMail::getInstance()->sendEmailPartnerDenied($options);
                    print_r(200);
                }
            }

            // Партнер подтвердил цену
            if ($action == 3) {
                $ok = OtherRequest::changeActionAndStatusToRequestById($id_request, $action, 'Отправка');
                if ($ok) {
                    // send mail  gsteam@generalse.com
                    $options = OtherRequest::getRequestById($id_request);
                    OtherRequestMail::getInstance()->sendEmailGSRequestAgree($options);
                    print_r(200);
                }
            }

            // Партнер не согласился с ценой
            if ($action == 4) {
                $comments = $_REQUEST['comment'];
                $ok = OtherRequest::changeActionAndStatusToRequestById($id_request, $action, 'Нет согласия', $comments);
                if ($ok) {
                    // send mail  gsteam@generalse.com
                    $options = OtherRequest::getRequestById($id_request);
                    OtherRequestMail::getInstance()->sendEmailGSRequestDisagree($options);
                    print_r(200);
                }
            }

            // Закрываем заявку
            if ($action == 5) {
                $ok = OtherRequest::changeActionAndStatusToRequestById($id_request, $action, 'Выполненный');
                if ($ok) {
                    print_r(200);
                }
            }
        }

        return true;
    }

}