<?php
namespace Umbrella\controllers\umbrella\crm;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\components\ImportExcel;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\crm\Backlog;
use Umbrella\models\Products;

class BacklogAnalysisController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * BacklogAnalysisController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.backlog', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     *
     * @return bool
     */
    public function actionIndex()
    {
        $user = $this->user;

        $partnerList = Admin::getAllPartner();

        $this->render('admin/crm/backlog', compact('user', 'partnerList'));
        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function actionExportBacklog()
    {
        $user = $this->user;

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

                        if($user->isPartner()){
                            $id_user = $user->getId();
                        } elseif($user->isAdmin() ||  $user->isManager()){
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
                                    $check_stock_bad = Backlog::getGoodsInStockByPartner($id_user, $excel['part_number'], Decoder::strToWindows('Материнская плата'));
                                    if($check_stock_bad){
                                        $new_array[$i]['comments'] = 'Bad Stock. Try to restore.';
                                    } else {
                                        //Проверяем по складе SWAP по BOM листам
                                        $check_part_in_boom_list = Backlog::getPartNumberInBoomListSwap($id_user, $excel['part_number']);
                                        if($check_part_in_boom_list){
                                            $new_array[$i]['comments'] = "SWAP <br> SN: " . Decoder::strToUtf($check_part_in_boom_list[0]['unit_serial_number'])
                                                                        . "<br>Description: " . Decoder::strToUtf($check_part_in_boom_list[0]['unit_goods_name']);
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
                        Logger::getInstance()->log($user->getId(), 'воспользовался функцией BacklogAnalysis');
                    }
                }
            }
        }

        $this->render('admin/crm/export/backlog', compact('user', 'partnerList', 'new_array'));
        return true;
    }

}