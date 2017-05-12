<?php

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
    public static function importPurchase($file){

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
    public static function importOrder($file){

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
    public static function importSupply($file){

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
            $pushArray[$i]['so_number'] = $data['B'];
            $pushArray[$i]['quantity'] = $data['C'];
            $pushArray[$i]['tracking_number'] = $data['D'];
            $pushArray[$i]['partner'] = $data['E'];

            if($data['E'] == ''){
                $pushArray[$i]['price'] = 0;
            } else {
                $pushArray[$i]['price'] = $data['F'];
            }
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
    public static function importReturn($file){

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
            $pushArray[$i]['so_number'] = $data['A'];
            $pushArray[$i]['stock_name'] = $data['B'];
            $i++;
        }
        return $pushArray;
    }


    /**
     * Импорт с раздела Rquest
     * @param $file
     * @return array
     */
    public static function importRequest($file){

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
            $pushArray[$i]['so_number'] = $data['B'];
            $i++;
        }
        return $pushArray;
    }


    /**
     * Импорт с раздела Batch
     * @param $file
     * @return array
     */
    public static function importBatch($file){

        /** PHPExcel_IOFactory */
        include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';


        //$inputFileName = $_SERVER['DOCUMENT_ROOT'] . '/upload/attach_purchase/goods-31c52.xls';
        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . $file;
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // Убираем нулевой елемент массива - заголовки
        $newArray = array_splice($sheetData, 1);

        return $newArray;
    }


    public static function importBacklog($file){

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
            $pushArray[$i]['Part_Description'] = $data['B'];
            $pushArray[$i]['SO_Number'] = $data['C'];
            $pushArray[$i]['customer_name'] = $data['D'];
            $pushArray[$i]['comments'] = $data['E'];
            $i++;
        }

        return $pushArray;
    }



    public static function importKpi($file){

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


    public static function importCallCSAT($file){

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
            $pushArray[$i]['SO_NUMBER'] = $data['A'];
            $pushArray[$i]['customer_rating_to'] = $data['B'];
            $pushArray[$i]['status'] = $data['C'];
            $pushArray[$i]['SERVICE_NAME'] = $data['D'];
            $pushArray[$i]['date_processing'] = $data['E'];
            $i++;
        }
        return $pushArray;
    }

    public static function importEmailCallCSAT($file){

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

}

//$ddd = ImportExcel::importPurchase(true);
//echo "<pre>";
//print_r($ddd);

?>
