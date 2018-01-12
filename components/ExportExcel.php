<?php

namespace Umbrella\components;

use ExportDataExcel;
use ExportDataExcelTest;
use FluidXml\FluidXml;
use PHPExcel;
use PHPExcel_IOFactory;
use Umbrella\models\crm\Stocks;

/**
 * Class ExportExcel
 */
class ExportExcel
{

    /**
     * Export all price
     *
     * @param $listPrice
     *
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public static function exportRequestAllPrice($listPrice){

        include_once 'PHPExcel/Classes/PHPExcel.php';
        require_once('PHPExcel/Classes/PHPExcel/Writer/Excel5.php');

        $xls = new PHPExcel();

        // Set document properties
        $xls->getProperties()->setCreator("Umbrella")
            ->setLastModifiedBy("Umbrella")
            ->setTitle("Office 2007 XLSX All price Document")
            ->setSubject("Office 2007 XLSX All price Document")
            ->setDescription("All price document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("All price result file");

        // Add some data
        $xls->setActiveSheetIndex(0)
            ->setCellValue('A1', 'PartNumber')
            ->setCellValue('B1', 'Название')
            ->setCellValue('C1', 'Устройство(классификатор)')
            ->setCellValue('D1', 'Подтип')
            ->setCellValue('E1', 'Производитель')
            ->setCellValue('F1', 'Тип')
            ->setCellValue('G1', 'Розница NEW')
            ->setCellValue('H1', 'Розница (б\у)')
            ->setCellValue('I1', 'Партнер')
            ->setCellValue('J1', 'Партнер (б\у)')
            ->setCellValue('K1', 'Оптовик')
            ->setCellValue('L1', 'Оптовик (б\у)')
            ->setCellValue('M1', 'VIP')
            ->setCellValue('N1', 'VIP (б\у)');
        $rowCount = 2;
        foreach ($listPrice as $item){
            $xls->setActiveSheetIndex(0)
                ->setCellValue('A'.$rowCount, $item['PartNumber'])
                ->setCellValue('B'.$rowCount, $item['mName'])
                ->setCellValue('C'.$rowCount, $item['class_name'])
                ->setCellValue('D'.$rowCount, $item['subType'])
                ->setCellValue('E'.$rowCount, $item['producer'])
                ->setCellValue('F'.$rowCount, $item['goodsType'])
                ->setCellValue('G'.$rowCount, $item['rozNew'])
                ->setCellValue('H'.$rowCount, $item['rozBu'])
                ->setCellValue('I'.$rowCount, $item['partnerNew'])
                ->setCellValue('J'.$rowCount, $item['partnerBu'])
                ->setCellValue('K'.$rowCount, $item['optNew'])
                ->setCellValue('L'.$rowCount, $item['optBu'])
                ->setCellValue('M'.$rowCount, $item['vipNew'])
                ->setCellValue('N'.$rowCount, $item['vipBu']);
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

    public static function xml()
    {
        require_once 'fluidxml/source/FluidXml.php';

        $myFile = ROOT . "/storage/test.xml";
        $fh = fopen($myFile, 'w') or die("can't open file");

        $productsInStock = Stocks::getGoodsInStockByPartner(15, 'OK (KVAZAR)');

        $arrayToXml = [];
        $i = 0;
        foreach ($productsInStock as $product){
            $arrayToXml[$i] = [
                'item' => [
                    'id'                => ['@' => $product['goods_name_id']],
                    'categoryId'        => ['@' => $product['stock_id']],
                    'art'               => ['@' => $product['part_number']],
                    'vendor'            => ['@' => ''],
                    'name'              => ['@' => Decoder::strToUtf($product['goods_name'])],
                    'price'             => ['@' => round($product['price'], 0)],
                    'priceCurrency'     => ['@' => 'UAH'],
                    'amount'            => ['@' => $product['quantity']],
                ],
            ];
            $i++;
        }

        echo "<pre>";
        print_r($arrayToXml);

        $book = new FluidXml(null);

        $book->add([
            'date' => date('d.m.Y H:i:s'),
            'firmName' => 'Generalse Services Inc.',
            'currencies' => [
                'currency' => [
                    '@id' => 'USD',
                    '@rate' => 27
                ]
            ],
            'items' => [
                $arrayToXml
            ]]);

        fwrite($fh, $book->xml());
        fclose($fh);
    }
}