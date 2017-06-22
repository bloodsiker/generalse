<?php

/**
 * Class RequestController
 */
class RequestController extends AdminBase
{

    ##############################################################################
    ##############################      Request         #############################
    ##############################################################################

    /**
     * RequestController constructor.
     */
    public function __construct()
    {
        self::checkDenied('crm.request', 'controller');
    }

    /**
     * @param string $filter
     * @return bool
     */
    public function actionIndex($filter = '')
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);
        $group = new Group();

        if(isset($_SESSION['add_request'])){
            $request_message = $_SESSION['add_request'];
            unset($_SESSION['add_request']);
        } else {
            $request_message = '';
        }

        $partnerList = Admin::getAllPartner();
        $order_type = Orders::getAllOrderTypes();
        $delivery_address = $user->getDeliveryAddress($user->id_user);

        if(isset($_POST['add_request']) && $_POST['add_request'] == 'true'){

            $note = null;
            if(isset($_POST['note'])){
                $note = iconv('UTF-8', 'WINDOWS-1251', $_POST['note']);
            }
            $options['id_user'] = $user->id_user;
            $options['part_number'] = iconv('UTF-8', 'WINDOWS-1251', $_POST['part_number']);
            $options['so_number'] = iconv('UTF-8', 'WINDOWS-1251', $_POST['so_number']);
            $options['note'] = $note;
            $mName = Products::checkPurchasesPartNumber($options['part_number']);
            $price = Products::getPricePartNumber($options['part_number'], $user->id_user);
            $options['goods_name'] = $mName['mName'];
            $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;
            $options['status_name'] = iconv('UTF-8', 'WINDOWS-1251', 'Нет в наличии, формируется поставка');
            $options['created_on'] = date('Y-m-d H:i:s');
            $options['order_type_id'] = $_POST['order_type_id'];
            $options['note1'] = isset($_POST['note1']) ? iconv('UTF-8', 'WINDOWS-1251', $_POST['note1']): null;

            $ok = Orders::addReserveOrdersMsSQL($options);
            if($ok){
                $_SESSION['add_request'] = 'Out of stock, delivery is forming';
            }
            Logger::getInstance()->log($user->id_user, ' создал новый запрос в Request');
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }

        if($user->role == 'partner'){
            //$listCheckOrders = Orders::getReserveOrdersByPartner($user->id_user);
            $listCheckOrders = Orders::getReserveOrdersByPartnerMsSQL($user->controlUsers($user->id_user));
        } elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){
            //$listCheckOrders = Orders::getAllReserveOrders();
            $listCheckOrders = Orders::getAllReserveOrdersMsSQL();
        }

        require_once(ROOT . '/views/admin/crm/request.php');
        return true;
    }


    /**
     * @return bool
     */
    public function actionRequestImport()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);
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
                            $options['part_number'] = iconv('UTF-8', 'WINDOWS-1251', $import['part_number']);
                            $options['so_number'] = iconv('UTF-8', 'WINDOWS-1251', $import['so_number']);
                            $options['note'] = $note;
                            $mName = Products::checkPurchasesPartNumber($options['part_number']);
                            $price = Products::getPricePartNumber($options['part_number'], $user->id_user);
                            $options['goods_name'] = $mName['mName'];
                            $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;
                            $options['status_name'] = iconv('UTF-8', 'WINDOWS-1251', 'Нет в наличии, формируется поставка');
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

        return true;
    }


    /**
     * Получаем цену продукта по парт номеру
     * @return bool
     */
    public function actionPricePartNumAjax()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $part_number = $_REQUEST['part_number'];

            $result = Products::getPricePartNumber($part_number, $userId);
            if($result == 0){
                $data['result'] = 0;
                $data['action'] = 'not_found';
                print_r(json_encode($data));
            } else {
                $data['result'] = 1;
                $data['action'] = 'purchase';
                $data['price'] = round($result['price'], 2);
                $data['mName'] = iconv('WINDOWS-1251', 'UTF-8', $result['mName']);
                print_r(json_encode($data));
            }

        return true;
    }


    public function actionRequestAjax()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        // Пишем номер с леново
        if($_REQUEST['action'] == 'edit_pn'){
            $id_order = $_REQUEST['id_order'];
            $order_pn = trim($_REQUEST['order_pn']);

            $ok = Orders::editPartNumberFromCheckOrdersById($id_order, $order_pn);
            if($ok){
                Logger::getInstance()->log($user->id_user, ' изменил part number в request #' . $id_order . ' на ' . $order_pn);
                print_r(200);
            }
        }

        if($_REQUEST['action'] == 'edit_so'){
            $id_order = $_REQUEST['id_order'];
            $order_so = trim(iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['order_so']));

            $ok = Orders::editSoNumberFromCheckOrdersById($id_order, $order_so);
            if($ok){
                Logger::getInstance()->log($user->id_user, ' изменил so number в request #' . $id_order . ' на ' . $order_so);
                print_r(200);
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
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('crm.request.delete', 'controller');

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        $ok = Orders::deleteRequestMsSQLById($id);

        if($ok){
            Logger::getInstance()->log($user->id_user, 'удалил request #' . $id);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
        return true;
    }
}