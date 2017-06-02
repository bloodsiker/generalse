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
        $delivery_address = $user->getDeliveryAddress($user->id_user);

        if(isset($_POST['add_request']) && $_POST['add_request'] == 'true'){

            $lastId = Orders::getLastOrdersId();
            if($lastId == false){
                $lastId = 100;
            }
            $lastId++;

            $note = null;
            $note_mysql = null;
            if(isset($_POST['note'])){
                $note = iconv('UTF-8', 'WINDOWS-1251', $_POST['note']);
                $note_mysql = $_POST['note'];
            }

            $options['id_user'] = $user->id_user;
            $options['part_number'] = $_POST['part_number'];
            $options['so_number'] = $_POST['so_number'];
            $options['note'] = $note;
            $options['note_mysql'] = $note_mysql;
            $options['site_id'] = $lastId;
            $options['ready'] = 1;

            //Проверка по складам
            //$stock = iconv('UTF-8', 'WINDOWS-1251', 'OK (Выборгская, 104)');
            $stocks_group = $group->stocksFromGroup($user->idGroupUser($user->id_user), 'name', 'supply');
            $stock = iconv('UTF-8', 'WINDOWS-1251', $stocks_group[0]);
            $check_part_in_stock = Products::checkOrdersPartNumberMsSql($user->id_user, $options['part_number'], $stock);
            if($check_part_in_stock){
                // Если есть на складе, создаем заказ
                $options['goods_mysql_name'] = iconv('WINDOWS-1251', 'UTF-8', $check_part_in_stock['goods_name']);
                $options['goods_name'] = $check_part_in_stock['goods_name'];
                $options['stock_name'] = $stock;
                $options['quantity'] = 1;
                // .....

                Orders::addOrdersMsSQL($options);
                Orders::addOrders($options);
                Orders::addOrdersElementsMsSql($options);
                Orders::addOrdersElements($options);
                $_SESSION['add_request'] = 'Order created';
            } else {
                //Последний шаг
                $mName = Products::checkPurchasesPartNumber($options['part_number']);
                $price = Products::getPricePartNumber($options['part_number']);
                $options['status_name'] = 'Нет в наличии, формируется поставка';
                $options['part_description'] = iconv('WINDOWS-1251', 'UTF-8', $mName['mName']);
                $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;
                $ok = Orders::addReserveOrders($options);
                if($ok){
                    $_SESSION['add_request'] = 'Out of stock, delivery is forming';
                }

//                //Проверка в поставках
//                $status = iconv('UTF-8', 'WINDOWS-1251', 'Подтверждена');
//                //Проверяем наличие детали в поставках созданим пользователями с одной группы
//                $users_group = $group->usersFromGroup($user->idGroupUser($user->id_user));
//                $check_part_in_supply = Supply::checkPartNumberInSupply($users_group, $options['part_number'], $status);
//                if($check_part_in_supply){
//                    //в случае наличия выписывает заказ и резервирует с поставки товар. (Статус «В поставке № указать номер поставки»)
//                    $mName = Products::checkPurchasesPartNumber($options['part_number']);
//                    $options['goods_mysql_name'] = iconv('WINDOWS-1251', 'UTF-8', $mName['mName']);
//                    $options['goods_name'] = $mName['mName'];
//                    $options['stock_name'] = 'Supply';
//                    $options['quantity'] = 1;
//                    $options['supply_id'] = $check_part_in_supply['supply_id'];
//                    $is_supply = 1;
//                    //....
//
//                    Orders::addOrdersMsSQL($options);
//                    Orders::addOrders($options);
//                    Orders::addOrdersElementsMsSql($options, $is_supply);
//                    Orders::addOrdersElements($options);
//                    $_SESSION['add_request'] = 'Order created';
//
//                } else {
//                    //Последний шаг
//                    $mName = Products::checkPurchasesPartNumber($options['part_number']);
//                    $price = Products::getPricePartNumber($options['part_number']);
//                    $options['status_name'] = 'Нет в наличии, формируется поставка';
//                    $options['part_description'] = iconv('WINDOWS-1251', 'UTF-8', $mName['mName']);
//                    $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;
//                    $ok = Orders::addReserveOrders($options);
//                    if($ok){
//                        $_SESSION['add_request'] = 'Out of stock, delivery is forming';
//                    }
//                }
            }
            Logger::getInstance()->log($user->id_user, 'создал новый запрос в Request');
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }

        if($user->role == 'partner' && $user->name_partner != 'GS Electrolux'){
            $listCheckOrders = Orders::getReserveOrdersByPartner($user->id_user);
        } elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager' || $user->name_partner == 'GS Electrolux'){
            $listCheckOrders = Orders::getAllReserveOrders();
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
//                        echo "<pre>";
//                        print_r($excelArray);
                        $lastId = Orders::getLastOrdersId();

                        foreach ($excelArray as $import){

                            if($lastId == false){
                                $lastId = 100;
                            }
                            $lastId++;

                            $note = null;
                            $note_mysql = null;
                            if(isset($_POST['note'])){
                                $note = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['note']);
                                $note_mysql = $_REQUEST['note'];
                            }

                            $options['id_user'] = $user->id_user;
                            $options['part_number'] = $import['part_number'];
                            $options['so_number'] = $import['so_number'];
                            $options['note'] = $note;
                            $options['note_mysql'] = $note_mysql;
                            $options['site_id'] = $lastId;
                            $options['ready'] = 1;

                            //Проверка по складам
                            //$stock = iconv('UTF-8', 'WINDOWS-1251', 'OK (Выборгская, 104)');
                            $stocks_group = $group->stocksFromGroup($user->idGroupUser($user->id_user), 'name', 'supply');
                            $stock = iconv('UTF-8', 'WINDOWS-1251', $stocks_group[0]);
                            $check_part_in_stock = Products::checkOrdersPartNumberMsSql($user->id_user, $options['part_number'], $stock);
                            if($check_part_in_stock){
                                // Если есть на складе, создаем заказ
                                $options['goods_mysql_name'] = iconv('WINDOWS-1251', 'UTF-8', $check_part_in_stock['goods_name']);
                                $options['goods_name'] = $check_part_in_stock['goods_name'];
                                $options['stock_name'] = $stock;
                                $options['quantity'] = 1;
                                // .....

                                Orders::addOrdersMsSQL($options);
                                Orders::addOrders($options);
                                Orders::addOrdersElementsMsSql($options);
                                Orders::addOrdersElements($options);
                                //$_SESSION['add_request'] = 'Orders created';
                            } else {
                                //Последний шаг
                                $mName = Products::checkPurchasesPartNumber($options['part_number']);
                                $price = Products::getPricePartNumber($options['part_number']);
                                $options['status_name'] = 'Нет в наличии, формируется поставка';
                                $options['part_description'] = iconv('WINDOWS-1251', 'UTF-8', $mName['mName']);
                                $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;
                                $ok = Orders::addReserveOrders($options);
                                if($ok){
                                    $_SESSION['add_request'] = 'Out of stock, delivery is forming';
                                }

//                                //Проверка в поставках
//                                //Проверяем наличие детали в поставках созданим пользователями с одной группы
//                                $users_group = $group->usersFromGroup($user->idGroupUser($user->id_user));
//                                $check_part_in_supply = Supply::checkPartNumberInSupply($users_group, $options['part_number'], iconv('UTF-8', 'WINDOWS-1251', 'Подтверждена'));
//                                if($check_part_in_supply){
//                                    //в случае наличия выписывает заказ и резервирует с поставки товар. (Статус «В поставке № указать номер поставки»)
//                                    $mName = Products::checkPurchasesPartNumber($options['part_number']);
//                                    $options['goods_mysql_name'] = iconv('WINDOWS-1251', 'UTF-8', $mName['mName']);
//                                    $options['goods_name'] = $mName['mName'];
//                                    $options['stock_name'] = 'Supply';
//                                    $options['quantity'] = 1;
//                                    $options['supply_id'] = $check_part_in_supply['supply_id'];
//                                    $is_supply = 1;
//                                    //....
//
//                                    Orders::addOrdersMsSQL($options);
//                                    Orders::addOrders($options);
//                                    Orders::addOrdersElementsMsSql($options, $is_supply);
//                                    Orders::addOrdersElements($options);
//                                    //$_SESSION['add_request'] = 'Orders created';
//                                } else {
//                                    //Последний шаг
//                                    $mName = Products::checkPurchasesPartNumber($options['part_number']);
//                                    $price = Products::getPricePartNumber($options['part_number']);
//                                    $options['status_name'] = 'Нет в наличии, формируется поставка';
//                                    $options['part_description'] = iconv('WINDOWS-1251', 'UTF-8', $mName['mName']);
//                                    $options['price'] = ($price['price'] != 0) ? $price['price'] : 0;
//                                    $ok = Orders::addReserveOrders($options);
//                                    if($ok){
//                                        $_SESSION['add_request'] = 'Out of stock, delivery is forming';
//                                    }
//                                }
                            }
                        }
                        Logger::getInstance()->log($user->id_user, 'загрузил массив с excel в Request');
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
        $part_number = $_REQUEST['part_number'];

            $result = Products::getPricePartNumber($part_number);
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

        $ok = Orders::deleteRequestById($id);

        if($ok){
            Logger::getInstance()->log($user->id_user, 'удалил request #' . $id);
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
        return true;
    }
}