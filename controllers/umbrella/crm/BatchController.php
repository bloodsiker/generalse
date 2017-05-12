<?php

/**
 * Class BatchController
 */
class BatchController extends  AdminBase
{

    ##############################################################################
    ##############################      BUTCH        #############################
    ##############################################################################

    /**
     * @return bool
     */
    public function actionExportBatch()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if(isset($_POST['check_butch']) && $_POST['check_butch'] == 'true'){

            if(!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_batch/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                $randomName = $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importBatch($excel_file);
                        // Получаем заголовки таблицы
                        $table_th = array_shift($excelArray);
                        // Получаем строки для табицы
                        //$table_tr = array_splice($excelArray, 1);
                        $table_tr = $excelArray;

                        $i = 0;
                        $new_array = [];
                        $order_array = [];
                        $lastId = Orders::getLastOrdersId();

                        if($lastId == false){
                            $lastId = 100;
                        }

                        if(count($table_tr) > 0){
                            foreach($table_tr as $excel) {
                                $new_array[$i] = $excel;
//                                if(empty($excel['A'])){
//                                    $new_array[$i]['A'] = 'false';
//                                }
                                // если парт номер не пустой, проверяем на наличие на складе
                                if(!empty($excel['AN'])){
                                    $check_AN = Products::checkBatchPartNumberInStocks($user->id_user, $excel['AN']);
                                    if($check_AN){
                                        $lastId++;
                                        // Делаем заказ
                                        $order_array['site_id'] = $lastId;
                                        $order_array['id_user'] = $user->id_user;
                                        //$order_array['id_user'] = $user->id_user;
                                        if(!empty($excel['A'])){
                                            $order_array['so_number'] = $excel['A'];
                                        } else {
                                            $order_array['so_number'] = $excel['V'];
                                        }
                                        $order_array['ready'] = 1;
                                        $order_array['part_number'] = $excel['AN'];
                                        $order_array['goods_mysql_name'] = iconv('WINDOWS-1251', 'UTF-8', $check_AN['mName']);
                                        $order_array['goods_name'] = $check_AN['mName'];
                                        $order_array['stock_name'] = $check_AN['stock_name'];
                                        $order_array['quantity'] = 1;

                                        Orders::addOrdersMsSQL($order_array);
                                        Orders::addOrders($order_array);
                                        Orders::addOrdersElementsMsSql($order_array);
                                        Orders::addOrdersElements($order_array);
//                                        echo "<pre>";
//                                        print_r($order_array);

                                        $new_array[$i]['AO'] = 'L';
                                    } else {
                                        $new_array[$i]['AO'] = 'N';
                                    }
                                }

                                if(!empty($excel['AV'])){
                                    $check_AV = Products::checkBatchPartNumberInStocks($user->id_user, $excel['AV']);
                                    if($check_AV){
                                        $lastId++;
                                        // Делаем заказ
                                        $order_array['site_id'] = $lastId;
                                        $order_array['id_user'] = $user->id_user;
                                        if(!empty($excel['A'])){
                                            $order_array['so_number'] = $excel['A'];
                                        } else {
                                            $order_array['so_number'] = $excel['V'];
                                        }
                                        $order_array['ready'] = 1;
                                        $order_array['part_number'] = $excel['AV'];
                                        $order_array['goods_mysql_name'] = iconv('WINDOWS-1251', 'UTF-8', $check_AV['mName']);
                                        $order_array['goods_name'] = $check_AV['mName'];
                                        $order_array['stock_name'] = $check_AV['stock_name'];
                                        $order_array['quantity'] = 1;

                                        Orders::addOrdersMsSQL($order_array);
                                        Orders::addOrders($order_array);
                                        Orders::addOrdersElementsMsSql($order_array);
                                        Orders::addOrdersElements($order_array);
//                                        echo "<pre>";
//                                        print_r($order_array);

                                        $new_array[$i]['AW'] = 'L';
                                    } else {
                                        $new_array[$i]['AW'] = 'N';
                                    }
                                }

                                if(!empty($excel['BD'])){
                                    $check_BD = Products::checkBatchPartNumberInStocks($user->id_user, $excel['BD']);
                                    if($check_BD){
                                        $lastId++;
                                        // Делаем заказ
                                        $order_array['site_id'] = $lastId;
                                        $order_array['id_user'] = $user->id_user;
                                        if(!empty($excel['A'])){
                                            $order_array['so_number'] = $excel['A'];
                                        } else {
                                            $order_array['so_number'] = $excel['V'];
                                        }
                                        $order_array['ready'] = 1;
                                        $order_array['part_number'] = $excel['BD'];
                                        $order_array['goods_mysql_name'] = iconv('WINDOWS-1251', 'UTF-8', $check_BD['mName']);
                                        $order_array['goods_name'] = $check_BD['mName'];
                                        $order_array['stock_name'] = $check_BD['stock_name'];
                                        $order_array['quantity'] = 1;

                                        Orders::addOrdersMsSQL($order_array);
                                        Orders::addOrders($order_array);
                                        Orders::addOrdersElementsMsSql($order_array);
                                        Orders::addOrdersElements($order_array);
//                                        echo "<pre>";
//                                        print_r($order_array);

                                        $new_array[$i]['BE'] = 'L';
                                    } else {
                                        $new_array[$i]['BE'] = 'N';
                                    }
                                }

                                if(!empty($excel['BL'])){
                                    $check_BL = Products::checkBatchPartNumberInStocks($user->id_user, $excel['BL']);
                                    if($check_BL){
                                        $lastId++;
                                        // Делаем заказ
                                        $order_array['site_id'] = $lastId;
                                        $order_array['id_user'] = $user->id_user;
                                        if(!empty($excel['A'])){
                                            $order_array['so_number'] = $excel['A'];
                                        } else {
                                            $order_array['so_number'] = $excel['V'];
                                        }
                                        $order_array['ready'] = 1;
                                        $order_array['part_number'] = $excel['BL'];
                                        $order_array['goods_mysql_name'] = iconv('WINDOWS-1251', 'UTF-8', $check_BL['mName']);
                                        $order_array['goods_name'] = $check_BL['mName'];
                                        $order_array['stock_name'] = $check_BL['stock_name'];
                                        $order_array['quantity'] = 1;

                                        Orders::addOrdersMsSQL($order_array);
                                        Orders::addOrders($order_array);
                                        Orders::addOrdersElementsMsSql($order_array);
                                        Orders::addOrdersElements($order_array);
//                                        echo "<pre>";
//                                        print_r($order_array);

                                        $new_array[$i]['BM'] = 'L';
                                    } else {
                                        $new_array[$i]['BM'] = 'N';
                                    }
                                }

                                if(!empty($excel['BT'])){
                                    $check_BT = Products::checkBatchPartNumberInStocks($user->id_user, $excel['BT']);
                                    if($check_BT){
                                        $lastId++;
                                        // Делаем заказ
                                        $order_array['site_id'] = $lastId;
                                        $order_array['id_user'] = $user->id_user;
                                        if(!empty($excel['A'])){
                                            $order_array['so_number'] = $excel['A'];
                                        } else {
                                            $order_array['so_number'] = $excel['V'];
                                        }
                                        $order_array['ready'] = 1;
                                        $order_array['part_number'] = $excel['BT'];
                                        $order_array['goods_mysql_name'] = iconv('WINDOWS-1251', 'UTF-8', $check_BT['mName']);
                                        $order_array['goods_name'] = $check_BT['mName'];
                                        $order_array['stock_name'] = $check_BT['stock_name'];
                                        $order_array['quantity'] = 1;

                                        Orders::addOrdersMsSQL($order_array);
                                        Orders::addOrders($order_array);
                                        Orders::addOrdersElementsMsSql($order_array);
                                        Orders::addOrdersElements($order_array);
//                                        echo "<pre>";
//                                        print_r($order_array);

                                        $new_array[$i]['BU'] = 'L';
                                    } else {
                                        $new_array[$i]['BU'] = 'N';
                                    }
                                }
                                $i++;
                            }
                            Logger::getInstance()->log($user->id_user, 'воспользовался функцией Batch');
                        }
                    }
                }
                //header("Location: /adm/crm/supply");
            }
        }

        require_once(ROOT . '/views/admin/crm/export/batch.php');
        return true;
    }

}