<?php
namespace Umbrella\components\cron\request;

include_once ('RequestFunction.php');


// Список реквестов
$listCheckRequest = RequestFunction::getReserveOrders();

foreach ($listCheckRequest as $request){
    if(empty($request['part_description'])){
        $part_description = RequestFunction::checkPurchasesPartNumber($request['part_number']);
        $mName = iconv('WINDOWS-1251', 'UTF-8', $part_description['mName']);
        RequestFunction::updateNameProduct($request['id'], $mName);
    }

    $price = RequestFunction::getPricePartNumber($request['part_number']);
    RequestFunction::updatePriceProduct($request['id'], $price['price']);
}