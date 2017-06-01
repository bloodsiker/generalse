<?php

/**
 * Class SupplyController
 */
class SupplyController extends AdminBase
{

    ##############################################################################
    ##############################      Supply       #############################
    ##############################################################################

    /**
     * SupplyController constructor.
     */
    public function __construct()
    {
        self::checkDenied('crm.supply', 'controller');
    }

    /**
     * Создание поставки с excel файла
     * @return bool
     */
    public function actionSupply()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if(isset($_SESSION['error_supply'])){
            $supply_error_part = $_SESSION['error_supply'];
            unset($_SESSION['error_supply']);
        }

        if($user->role == 'partner') {
            //$allSupply = Supply::getAllSupply();
            $allSupply = Supply::getSupplyByPartner($user->idUsersInGroup($user->id_user));
        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){
            $allSupply = Supply::getAllSupply();
        }

        if(isset($_POST['add_supply']) && $_POST['add_supply'] == 'true'){
            if(!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_supply/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                $randomName = $user->name_partner . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importSupply($excel_file);

                        $lastId = Supply::getLastSupplyId();
                        if($lastId == false){
                            $lastId = 100;
                        }
                        $lastId++;
                        $options['site_id'] = $lastId;
                        $options['site_account_id'] = $user->id_user;
                        $options['supply_name'] = iconv('UTF-8', 'WINDOWS-1251', $_POST['supply_name']);
                        $options['arriving_date'] = $_POST['arriving_date'];
                        $options['ready'] = 1;
                        Supply::addSupplyMSSQL($options);

                        $supply_error_part = [];
                        if(count($excelArray) > 0){
                            foreach($excelArray as $excel) {
                                $insert['site_id'] = $lastId;
                                $insert['part_number'] = $excel['part_number'];
                                $insert['so_number'] = $excel['so_number'];
                                $insert['price'] = $excel['price'];
                                $insert['quantity'] = $excel['quantity'];
                                $insert['tracking_number'] = $excel['tracking_number'];
                                $insert['manufacture_country'] = $excel['manufacture_country'];
                                $insert['partner'] = $excel['partner'];
                                $insert['manufacturer'] = iconv('WINDOWS-1251', 'UTF-8', $excel['manufacturer']);

                                $part_num = Products::checkPurchasesPartNumber($insert['part_number']);
                                if($part_num != 0){
                                    Supply::addSupplyPartsMSSQL($insert);
                                } else {
                                    array_push($supply_error_part, $insert['part_number']);
                                }
                            }
                        }
                        Logger::getInstance()->log($user->id_user, 'Загрузил excel файл с поставками');
                        // Пишем в сессию массив с ненайденными партномерами
                        $_SESSION['error_supply'] = $supply_error_part;
//                        echo "<pre>";
//                        print_r($error_part);
                    }
                }
                header("Location: /adm/crm/supply");
            }
        }

        require_once(ROOT . '/views/admin/crm/supply.php');
        return true;
    }


    /**
     * Распределяем детали по складам
     * @return bool
     */
    public function actionSupplyAjax()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        $user = new User($userId);
        $group = new Group();
        // Получаем массив с site_id
        $json = json_decode($_REQUEST['json'], true);

        // делаем с массива сторку с site_id разделенными запятыми
        $site_id_array = array_column($json, 'site_id');
        $site_idS = implode(',', $site_id_array);
        $array_supply = Supply::getSupplyPartsByIdS($site_idS);
        //print_r($array_supply);

        foreach ($array_supply as $item){

            if($item['manufacturer'] == 'Lenovo' || $item['manufacturer'] == 'lenovo'){
                // Проверяем на наличие в таблице КПИ
                $count_kpi = Supply::getCountSoNumberOnKpi($item['so_number']);
                if($count_kpi > 0){
                    $stock = iconv('UTF-8', 'WINDOWS-1251', 'not used');
                } else {
                    // Если в таблице КПИ не найден, ищем в таблице Refund Request
                    $stock = 'transit';
                    $status = iconv('UTF-8', 'WINDOWS-1251', 'подтверждено');
                    $count_refund = Supply::getCountSoNumberOnRefund($item['so_number'], $status);
                    if($count_refund > 0){
                        $stock = iconv('UTF-8', 'WINDOWS-1251', 'not used');
                        //Supply::updateCommand($item['site_id'], 1);
                    } else {
                        $stock = iconv('UTF-8', 'WINDOWS-1251', 'transit');
                    }
                }
            } else {
                // Используем для поставки склад, который привязанный к группе в которой находиться пользователь создавший поствку
                $stocks_group = $group->stocksFromGroup($user->idGroupUser($item['site_account_id']), 'name', 'supply');
                $stock = iconv('UTF-8', 'WINDOWS-1251', $stocks_group[0]);
            }


//            if ($item['manufacturer'] == 'Electrolux' || $item['manufacturer'] == 'electrolux'){
//                $stock = iconv('UTF-8', 'WINDOWS-1251', 'OK (Выборгская, 104)');
//            } elseif ($item['manufacturer'] == 'Electrolux GE' || $item['manufacturer'] == 'electrolux GE'){
//                $stock = iconv('UTF-8', 'WINDOWS-1251', 'OK');
//            } else {
//                // Проверяем на наличие в таблице КПИ
//                $count_kpi = Supply::getCountSoNumberOnKpi($item['so_number']);
//                if($count_kpi > 0){
//                    $stock = iconv('UTF-8', 'WINDOWS-1251', 'not used');
//                } else {
//                    // Если в таблице КПИ не найден, ищем в таблице Refund Request
//                    $stock = 'transit';
//                    $status = iconv('UTF-8', 'WINDOWS-1251', 'подтверждено');
//                    $count_refund = Supply::getCountSoNumberOnRefund($item['so_number'], $status);
//                    if($count_refund > 0){
//                        $stock = iconv('UTF-8', 'WINDOWS-1251', 'not used');
//                        //Supply::updateCommand($item['site_id'], 1);
//                    } else {
//                        $stock = iconv('UTF-8', 'WINDOWS-1251', 'transit');
//                    }
//                }
//            }
            Supply::updateStock($item['id'], $stock);
        }
        Supply::updateCommand($site_idS, 1);
        Logger::getInstance()->log($userId, 'в поставках нажал Accept');
        return true;
    }

    /**
     * Показываем детали поставки в модальном окне
     * @return bool
     */
    public function actionShowDetailSupply()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        $site_id = $_REQUEST['site_id'];
        $data = Supply::getShowDetailsSupply($site_id);
        //print_r($data);
        $html = "";
        foreach($data as $item){
            $color = Functions::compareQuantity($item['quantity'], $item['quantity_reserv']);
            $quantity = "style=color:" . $color ;
            $html .= "<tr>";
            $html .= "<td>" . $item['part_number'] . "</td>";
            $html .= "<td>" . iconv('WINDOWS-1251', 'UTF-8', $item['goods_name']) . "</td>";
            $html .= "<td>" . iconv('WINDOWS-1251', 'UTF-8', $item['so_number']) . "</td>";
            $html .= "<td {$quantity}><b>" . $item['quantity'] . "</b></td>";
            $html .= "<td {$quantity}><b>" . $item['quantity_reserv'] . "</b></td>";
            $html .= "<td>" . round($item['price'], 2) . "</td>";
            $html .= "<td>" . $item['tracking_number'] . "</td>";
            $html .= "<td>" . iconv('WINDOWS-1251', 'UTF-8', $item['manufacture_country']) . "</td>";
            $html .= "<td>" . iconv('WINDOWS-1251', 'UTF-8', $item['partner']) . "</td>";
            $html .= "</tr>";
        }
        print_r($html);
        return true;
    }

}