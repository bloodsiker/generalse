<?php

/**
 * Class ExportExcel
 */
class ExportExcel
{
    public static function exportPurchase($data = null){

        /** PHPExcel_IOFactory */
        include_once 'PHPExcel/Classes/PHPExcel.php';

        // Create new PHPExcel object
        $xls = new PHPExcel();

        // Set document properties
        $xls->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $row = [
            0 => [
                'name' => 'Dima',
                'fio' => 'Ovsijchuk',
                'age' => '27',
                'city' => 'Kiev'
            ],
            1 => [
                'name' => 'Olya',
                'fio' => 'Tvink',
                'age' => '18',
                'city' => 'Kiev'
            ],
            2 => [
                'name' => 'Olya',
                'fio' => 'Tvink',
                'age' => '18',
                'city' => 'Kiev'
            ]
        ];


        // Add some data
        $xls->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Name')
            ->setCellValue('B1', 'Fio')
            ->setCellValue('C1', 'Age')
            ->setCellValue('D1', 'City');
        $rowCount = 2;
        foreach ($row as $item){
            $xls->setActiveSheetIndex(0)
                ->setCellValue('A'.$rowCount, $item['name'])
                ->setCellValue('B'.$rowCount, $item['fio'])
                ->setCellValue('C'.$rowCount, $item['age'])
                ->setCellValue('D'.$rowCount, $item['city']);
            $rowCount++;
        }


        // Rename worksheet
        $xls->getActiveSheet()->setTitle('Simple');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Export.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
        $objWriter->save('php://output');


    }
}

?>
