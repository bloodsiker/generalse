<?php

class BacklogAnalysisController extends AdminBase
{

    ##############################################################################
    #########################      BacklogAnalysis          ######################
    ##############################################################################

    public function __construct()
    {
        self::checkDenied('crm.backlog', 'controller');
    }

    /**
     *
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
        $partnerList = Admin::getAllPartner();

        require_once(ROOT . '/views/admin/crm/backlog.php');
        return true;
    }

    /**
     * @return bool
     */
    public function actionExportBacklog()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if(isset($_POST['check_backlog']) && $_POST['check_backlog'] == 'true'){

            if(!empty($_FILES['excel_file']['name'])) {

                $options['name_real'] = $_FILES['excel_file']['name'];
                // Все загруженные файлы помещаются в эту папку
                $options['file_path'] = "/upload/attach_backlog/";
                $randomName = substr_replace(sha1(microtime(true)), '', 5);

                $randomName = $user->name_partner . '-' . $randomName . "-" . $options['name_real'];
                $options['file_name'] = $randomName;

                if (is_uploaded_file($_FILES["excel_file"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $options['file_path'] . $options['file_name'])) {
                        $excel_file = $options['file_path'] . $options['file_name'];
                        // Получаем массив данных из файла
                        $excelArray = ImportExcel::importBacklog($excel_file);

                        $i = 0;
                        $new_array = [];

                        if($user->role == 'partner'){
                            $id_user = $user->id_user;
                        } elseif($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){
                            $id_user = (isset($_POST['id_partner']) ? $_POST['id_partner'] : false);
                        }

                        foreach ($excelArray as $excel){
                            $new_array[$i] = $excel;
                            if(!empty($excel['part_number'])){
                                // Проверяем по складам Restored, Dismantling, Not Used
                                $check_part = Products::checkPurchasesPartNumberInStocks($id_user, $excel['part_number']);
                                if($check_part){
                                    $new_array[$i]['comments'] = $check_part['stock_name'];
                                } else {
                                    //Проверяем по складу Bad подтип = Материнская плата
                                    $check_stock_bad = Backlog::getGoodsInStockByPartner($id_user, $excel['part_number'], iconv('UTF-8', 'WINDOWS-1251', 'Материнская плата'));
                                    if($check_stock_bad){
                                        $new_array[$i]['comments'] = 'Bad Stock. Try to restore.';
                                    } else {
                                        //Проверяем по складе SWAP по BOM листам
                                        $check_part_in_boom_list = Backlog::getPartNumberInBoomListSwap($id_user, $excel['part_number']);
                                        if($check_part_in_boom_list){
                                            $new_array[$i]['comments'] = "SWAP <br> SN: " . iconv('WINDOWS-1251', 'UTF-8', $check_part_in_boom_list[0]['unit_serial_number'])
                                                                        . "<br>Description: " . iconv('WINDOWS-1251', 'UTF-8', $check_part_in_boom_list[0]['unit_goods_name']);
                                        } else {
                                            $not_found_or_boom_list = Backlog::getPartNumberInBoomListNoStock($excel['part_number']);
                                            $list = array_column($not_found_or_boom_list, 'unit_part_number');
                                            $comma_separated = implode(",", $list);
                                            $new_array[$i]['comments'] = "<span style='color: red'>Not Found</span> <br>" . $comma_separated;
                                        }
                                    }
                                }
                            }
                            $i++;
                        }
                        Logger::getInstance()->log($user->id_user, 'воспользовался функцией BacklogAnalysis');
//                        echo "<pre>";
//                        print_r($new_array);
                    }
                }
                //header("Location: /adm/crm/supply");
            }
        }

        require_once(ROOT . '/views/admin/crm/export/backlog.php');
        return true;
    }

}