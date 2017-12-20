<?php

namespace Umbrella\components;

use ExportDataExcel;
use PHPExcel;
use PHPExcel_IOFactory;
use Umbrella\models\Admin;
use Umbrella\models\Price;

/**
 * Class ExportExcel
 */
class ExportExcel
{

    /**
     *
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public static function exportPurchase(){

        include_once 'PHPExcel/Classes/PHPExcel.php';
        require_once('PHPExcel/Classes/PHPExcel/Writer/Excel5.php');

        $xls = new PHPExcel();

        // Set document properties
        $xls->getProperties()->setCreator("Umbrella")
            ->setLastModifiedBy("Umbrella")
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

        //$listPrice = Price::getAllPriceMsSQL();
        $listPrice = Admin::getAllPartner();

        // Add some data
        $xls->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Name')
            ->setCellValue('B1', 'Fio')
            ->setCellValue('C1', 'Age')
            ->setCellValue('D1', 'City');
        $rowCount = 2;
        foreach ($listPrice as $item){
            $xls->setActiveSheetIndex(0)
                ->setCellValue('A'.$rowCount, $item['name_partner'])
                ->setCellValue('B'.$rowCount, $item['name_partner'])
                ->setCellValue('C'.$rowCount, $item['name_partner'])
                ->setCellValue('D'.$rowCount, $item['name_partner']);
            $rowCount++;
        }

        // Rename worksheet
        $xls->getActiveSheet()->setTitle('Page 1');

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="All-Price.xls"');
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


    public static function exportTest()
    {
        require_once 'PHPExportData/php-export-data.class.php';

        // 'browser' tells the library to stream the data directly to the browser.
        // other options are 'file' or 'string'
        // 'test.xls' is the filename that the browser will use when attempting to
        // save the download
        $exporter = new ExportDataExcel('browser', 'test.xls');//test.xls

        $exporter->initialize(); // starts streaming data to web browser

        // pass addRow() an array and it converts it to Excel XML format and sends
        // it to the browser
        $exporter->addRow(array("This", "is", "a", "test"));
        $exporter->addRow(array(1, 2, 3, "123-456-7890"));

        // doesn't care how many columns you give it
        $exporter->addRow(array("foo"));

        $exporter->finalize(); // writes the footer, flushes remaining data to browser.
        exit(); // all done
    }
}