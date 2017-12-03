<?php

namespace Umbrella\components;

use \PHPExcel_IOFactory;
use Umbrella\models\Admin;

/**
 * Class ImportExcel
 */
class ImportExcel
{
    /**
     * Import покупок в purchase
     * @param $file
     * @return array
     */
    public static function importPurchase($file)
    {
        /** PHPExcel_IOFactory */
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        //$inputFileName = $_SERVER['DOCUMENT_ROOT'] . '/upload/attach_purchase/goods-31c52.xls';
        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);
        // Формируем новый массив с ассоциативныи ключами
        $pushArray = [];
        $i = 0;
        foreach($newArray as $data){
            $pushArray[$i]['part_number'] = $data['A'];
            if($data['B'] == ''){
                $pushArray[$i]['quantity'] = 1;
            } else {
                $pushArray[$i]['quantity'] = $data['B'];
            }

            if($data['C'] == ''){
                $pushArray[$i]['price'] = 0;
            } else {
                $pushArray[$i]['price'] = $data['C'];
            }

            if($data['D'] == ''){
                $pushArray[$i]['so_number'] = ' ';
            } else {
                $pushArray[$i]['so_number'] = $data['D'];
            }
            $i++;
        }
        return $pushArray;
    }


    /**
     * Import покупок в purchase
     * @param $file
     * @return array
     */
    public static function importOrder($file)
    {
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);
        // Формируем новый массив с ассоциативныи ключами
        $pushArray = [];
        $i = 0;
        foreach($newArray as $data){
            $pushArray[$i]['part_number'] = $data['A'];
            if($data['B'] == ''){
                $pushArray[$i]['quantity'] = 1;
            } else {
                $pushArray[$i]['quantity'] = $data['B'];
            }

            if($data['C'] == ''){
                $pushArray[$i]['so_number'] = ' ';
            } else {
                $pushArray[$i]['so_number'] = $data['C'];
            }
            $i++;
        }
        return $pushArray;
    }


    /**
     * Импорт в раздел Supply
     * @param $file
     * @return array
     */
    public static function importSupply($file)
    {
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);
        // Формируем новый массив с ассоциативныи ключами
        $pushArray = [];
        $i = 0;
        foreach($newArray as $data){
            $pushArray[$i]['part_number'] = $data['A'];
            $pushArray[$i]['so_number'] = $data['B'];
            $pushArray[$i]['quantity'] = $data['C'];
            $pushArray[$i]['tracking_number'] = $data['D'];
            $pushArray[$i]['partner'] = $data['E'];

//            if($data['E'] == ''){
//                $pushArray[$i]['price'] = 0;
//            } else {
//                $pushArray[$i]['price'] = $data['F'];
//            }
            $pushArray[$i]['price'] = $data['F'];
            $pushArray[$i]['manufacture_country'] = $data['G'];
            $pushArray[$i]['manufacturer'] = $data['H'];

            $i++;
        }
        return $pushArray;
    }

    /**
     * Импорт возратов
     * @param $file
     * @return array
     */
    public static function importReturn($file)
    {
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);
        // Формируем новый массив с ассоциативныи ключами
        $pushArray = [];
        $i = 0;
        foreach($newArray as $data){
            $pushArray[$i]['so_number'] = $data['A'];
            $pushArray[$i]['stock_name'] = $data['B'];
            $pushArray[$i]['order_number'] = $data['C'];
            $i++;
        }
        return $pushArray;
    }


    /**
     * Импорт с раздела Request
     * @param $file
     * @return array
     */
    public static function importRequest($file)
    {
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);
        // Формируем новый массив с ассоциативныи ключами
        $pushArray = [];
        $i = 0;
        foreach($newArray as $data){
            $pushArray[$i]['part_number'] = $data['A'];
            $pushArray[$i]['so_number'] = $data['B'];
            $pushArray[$i]['quantity'] = $data['C'];
            $i++;
        }
        return $pushArray;
    }


    /**
     * @param $file
     * @return array
     */
    public static function importStatusInRequest($file)
    {
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);
        // Формируем новый массив с ассоциативныи ключами
        $pushArray = [];
        $i = 0;
        foreach($newArray as $data){
            $pushArray[$i]['id'] = $data['A'];
            $pushArray[$i]['status_name'] = $data['B'];
            $i++;
        }
        return $pushArray;
    }


    /**
     * Импорт парт аналогов
     * @param $file
     * @return array
     */
    public static function importPartNumberAnalog($file)
    {
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);
        // Формируем новый массив с ассоциативныи ключами
        $pushArray = [];
        $i = 0;
        foreach($newArray as $data){
            $pushArray[$i]['part_number'] = $data['A'];
            $pushArray[$i]['part_analog'] = $data['B'];
            $pushArray[$i]['comment'] = $data['C'];
            $i++;
        }
        return $pushArray;
    }


    /**
     * Импорт с раздела Batch
     * @param $file
     * @return array
     */
    public static function importBatch($file)
    {
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);

        return $newArray;
    }


    /**
     * @param $file
     * @return array
     */
    public static function importBacklog($file)
    {
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);
        // Формируем новый массив с ассоциативныи ключами
        $pushArray = [];
        $i = 0;
        foreach($newArray as $data){
            $pushArray[$i]['part_number'] = $data['A'];
            $pushArray[$i]['Part_Description'] = $data['B'];
            $pushArray[$i]['SO_Number'] = $data['C'];
            $pushArray[$i]['customer_name'] = $data['D'];
            $pushArray[$i]['comments'] = $data['E'];
            $i++;
        }
        return $pushArray;
    }



    public static function importKpi($file)
    {
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);
        // Формируем новый массив с ассоциативныи ключами
        $pushArray = [];
        $i = 0;
        foreach($newArray as $data){
            $pushArray[$i]['SO_NUMBER'] = $data['A'];
            $pushArray[$i]['SO_CREATION_DATE'] = $data['B'];
            $pushArray[$i]['HEADER_STATUS'] = $data['C'];
            $pushArray[$i]['SERVICE_PROVIDE_NAME'] = $data['D'];
            $pushArray[$i]['COUNTRY'] = $data['E'];
            $pushArray[$i]['Serial_Number'] = $data['F'];
            $pushArray[$i]['ITEM_STATUS'] = $data['G'];
            $pushArray[$i]['Service_Complete_Date'] = $data['H'];
            $pushArray[$i]['Item_Product_ID'] = $data['I'];
            $pushArray[$i]['Item_Product_Desc'] = $data['J'];
            $pushArray[$i]['IRIS_1_Repair'] = $data['K'];
            $pushArray[$i]['Unit_Received_Date'] = $data['L'];
            $pushArray[$i]['PARTS_MODEL_INDICATOR'] = $data['M'];
            $pushArray[$i]['Part_Order_Date'] = $data['N'];
            $pushArray[$i]['Part_Delivery_Date'] = $data['O'];
            $pushArray[$i]['Customer_Email'] = $data['P'];
            $i++;
        }
        return $pushArray;
    }


    public static function importCallCSAT($file)
    {
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);
        // Формируем новый массив с ассоциативныи ключами
        $pushArray = [];
        $i = 0;
        foreach($newArray as $data){
            $pushArray[$i]['SO_NUMBER'] = $data['A'];
            $pushArray[$i]['customer_rating_to'] = $data['B'];
            $pushArray[$i]['status'] = $data['C'];
            $pushArray[$i]['SERVICE_NAME'] = $data['D'];
            $pushArray[$i]['date_processing'] = $data['E'];
            $i++;
        }
        return $pushArray;
    }

    public static function importEmailCallCSAT($file)
    {
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);
        // Формируем новый массив с ассоциативныи ключами
        $pushArray = [];
        $i = 0;
        foreach($newArray as $data){
            $pushArray[$i]['Month'] = $data['A'];
            $pushArray[$i]['Week'] = $data['B'];
            $pushArray[$i]['CIN_Location'] = $data['C'];
            $pushArray[$i]['CIN_Communication'] = $data['D'];
            $pushArray[$i]['CIN_Technical'] = $data['E'];
            $pushArray[$i]['CIN_Repair'] = $data['F'];
            $pushArray[$i]['CIN_Speed'] = $data['G'];
            $pushArray[$i]['CIN_Quality'] = $data['H'];
            $pushArray[$i]['Name_of_Partner'] = $data['I'];
            $pushArray[$i]['Serial_Number'] = $data['J'];
            $pushArray[$i]['OSAT_Comment'] = $data['K'];
            $pushArray[$i]['Transaction_Number'] = $data['L'];
            $pushArray[$i]['Sum_of_Wai_Score'] = $data['M'];
            $i++;
        }
        return $pushArray;
    }


    /**
     * Импорт в CCC KPI
     * @param $file
     * @return array
     */
    public static function importCCCKpi($file)
    {
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        //$newArray = array_splice($sheetData, 1);
        $newArray = $sheetData;
        // Формируем новый массив с ассоциативныи ключами
        $pushArray = [];
        $pushArray[0]['name_manager'] = $newArray[1]['B'];
        $pushArray[0]['out_call'] = $newArray[2]['B'];
        $pushArray[0]['inc_call'] = $newArray[3]['B'];
        $pushArray[0]['inc_call_BY'] = $newArray[4]['B'];
        $pushArray[0]['inc_call_F1'] = $newArray[5]['B'];
        $pushArray[0]['inc_call_rate'] = intval($newArray[8]['B']);
        $pushArray[0]['avg_talk_time'] = floatval(str_replace(',', '.', $newArray[9]['B']));
        $pushArray[0]['call_miss'] = $newArray[10]['B'];
        $pushArray[0]['completed_map'] = $newArray[11]['B'];
        $pushArray[0]['created_at'] = $newArray[12]['B'];

        $pushArray[1]['name_manager'] = $newArray[1]['C'];
        $pushArray[1]['out_call'] = $newArray[2]['C'];
        $pushArray[1]['inc_call'] = $newArray[3]['C'];
        $pushArray[1]['inc_call_BY'] = $newArray[4]['C'];
        $pushArray[1]['inc_call_F1'] = $newArray[5]['C'];
        $pushArray[1]['inc_call_rate'] = intval($newArray[8]['C']);
        $pushArray[1]['avg_talk_time'] = floatval(str_replace(',', '.', $newArray[9]['C']));
        $pushArray[1]['call_miss'] = $newArray[10]['C'];
        $pushArray[1]['completed_map'] = $newArray[11]['C'];
        $pushArray[1]['created_at'] = $newArray[12]['C'];

        $pushArray[2]['name_manager'] = $newArray[1]['D'];
        $pushArray[2]['out_call'] = $newArray[2]['D'];
        $pushArray[2]['inc_call'] = $newArray[3]['D'];
        $pushArray[2]['inc_call_BY'] = $newArray[4]['D'];
        $pushArray[2]['inc_call_F1'] = $newArray[5]['D'];
        $pushArray[2]['inc_call_rate'] = intval($newArray[8]['D']);
        $pushArray[2]['avg_talk_time'] = floatval(str_replace(',', '.', $newArray[9]['D']));
        $pushArray[2]['call_miss'] = $newArray[10]['D'];
        $pushArray[2]['completed_map'] = $newArray[11]['D'];
        $pushArray[2]['created_at'] = $newArray[12]['D'];

        $pushArray[3]['name_manager'] = $newArray[1]['E'];
        $pushArray[3]['out_call'] = $newArray[2]['E'];
        $pushArray[3]['inc_call'] = $newArray[3]['E'];
        $pushArray[3]['inc_call_BY'] = $newArray[4]['E'];
        $pushArray[3]['inc_call_F1'] = $newArray[5]['E'];
        $pushArray[3]['inc_call_rate'] = intval($newArray[8]['E']);
        $pushArray[3]['avg_talk_time'] = floatval(str_replace(',', '.', $newArray[9]['E']));
        $pushArray[3]['call_miss'] = $newArray[10]['E'];
        $pushArray[3]['completed_map'] = $newArray[11]['E'];
        $pushArray[3]['created_at'] = $newArray[12]['E'];

        return $pushArray;
    }


    public static function importUsers($file)
    {
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);

        $pushArray = [];
        $i = 0;
        foreach($newArray as $data){
            // Umbrella
            $pushArray[$i]['id_role'] = 2;
            $pushArray[$i]['name_partner'] = $data['A'];
            $pushArray[$i]['id_country'] = 0; // Ukraine
            $pushArray[$i]['login'] = ucfirst(Functions::strUrl(Functions::crop_str($data['A'], 12))) . random_int(0, 9);
            $pushArray[$i]['email'] = '';
            $genPass = random_int(0, 999) . Functions::strUrl(Functions::crop_str($data['A'], 5)) . '#2017';
            $pushArray[$i]['v_password'] = $genPass;
            $pushArray[$i]['password'] = Functions::hashPass($genPass);
            $pushArray[$i]['login_url'] = 'adm/crm';
            $pushArray[$i]['kpi_view'] = 0;
            $pushArray[$i]['date_create'] = date("Y-m-d H:i");

            //GM
            $id_user = 1;
            if($id_user){
                $pushArray[$i]['site_account_id'] = $id_user;
                $pushArray[$i]['site_client_name'] = Decoder::strToWindows($data['A']);
                $pushArray[$i]['name_en'] = null;
                $pushArray[$i]['address'] = null;
                $pushArray[$i]['address_en'] = null;
                $pushArray[$i]['for_ttn'] = null;
                $pushArray[$i]['curency_id'] = Admin::getCurrencyIdByName($data['E'])['number'];//
                $pushArray[$i]['abcd_id'] = null;
                $pushArray[$i]['to_electrolux'] = 0;
                $pushArray[$i]['to_mail_send'] = 0;
                $pushArray[$i]['contract_number'] = null;
                $pushArray[$i]['staff_id'] = null;

                $pushArray[$i]['stock_place_id'] = null;

                $pushArray[$i]['phone'] = null;
                $pushArray[$i]['gm_email'] = null;
                $pushArray[$i]['region_id'] = null;
            }

            $i++;
        }
        return $pushArray;
    }

}

//$ddd = ImportExcel::importPurchase(true);
//echo "<pre>";
//print_r($ddd);

?>
