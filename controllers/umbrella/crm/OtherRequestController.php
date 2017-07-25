<?php

/**
 * Class OtherRequestController
 */
class OtherRequestController extends AdminBase
{

    ##############################################################################
    #########################      Other Request         #########################
    ##############################################################################

    /**
     * OtherRequestController constructor.
     */
    public function __construct()
    {
        self::checkDenied('crm.other.request', 'controller');
    }

    /**
     * @return bool
     */
    public function actionIndex()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $delivery_address = $user->getDeliveryAddress($user->id_user);

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

        require_once(ROOT . '/views/admin/crm/other_request.php');
        return true;
    }


    /**
     * @return bool
     */
    public function actionRequestAjax()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        // Редактируем price
        if ($_REQUEST['action'] == 'edit_price') {
            $id_request = $_REQUEST['id_request'];
            $price = $_REQUEST['request_price'];

            $ok = OtherRequest::editPriceToRequestById($id_request, $price);
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
                $ok = OtherRequest::changeActionAndStatusToRequestById($id_request, $action, 'Отказано');
                if ($ok) {
                    // send mail partner
                    $options = OtherRequest::getRequestById($id_request);
                    OtherRequestMail::getInstance()->sendEmailPartnerAlignment($options);
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