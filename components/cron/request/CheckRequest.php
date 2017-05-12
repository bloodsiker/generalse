<?php
include_once ('RequestFunction.php');


$listCheckRequest = RequestFunction::getReserveOrders();

$lastId = RequestFunction::getLastOrdersId();
if($lastId == false){
    $lastId = 100;
}

foreach ($listCheckRequest as $request){
    $lastId++;

    $options['id_user'] = $request['id_user'];
    $options['part_number'] = $request['part_number'];
    $options['so_number'] = iconv('UTF-8', 'WINDOWS-1251', $request['so_number']);
    $options['site_id'] = $lastId;
    $options['ready'] = 1;

    //Проверка по складам
    $stock = iconv('UTF-8', 'WINDOWS-1251', 'OK (Выборгская, 104)');
    $check_part_in_stock = RequestFunction::checkOrdersPartNumberMsSql($options['id_user'], $options['part_number'], $stock);
    if($check_part_in_stock){
        // Если есть на складе, создаем заказ
        $options['goods_mysql_name'] = iconv('WINDOWS-1251', 'UTF-8', $check_part_in_stock['goods_name']);
        $options['goods_name'] = $check_part_in_stock['goods_name'];
        $options['stock_name'] = $stock;
        $options['quantity'] = 1;
        // .....

        RequestFunction::addOrdersMsSQL($options);
        RequestFunction::addOrders($options);
        RequestFunction::addOrdersElementsMsSql($options);
        RequestFunction::addOrdersElements($options);
        // Если нашли на складе и выписали заказ, убираем со списка проверяемых заявок
        RequestFunction::updateCheckReserveOrders($request['id'], 1);
    } else {
        //Проверка в поставках
        $check_part_in_supply = RequestFunction::checkPartNumberInSupply($options['id_user'], $options['part_number'], iconv('UTF-8', 'WINDOWS-1251', 'Подтверждена'));
        if($check_part_in_supply){
            //в случае наличия выписывает заказ и резервирует с поставки товар. (Статус «В поставке № указать номер поставки»)
            //$options['supply_id'] = $check_part_in_supply['supply_id'];
            $mName = RequestFunction::checkPurchasesPartNumber($options['part_number']);
            $options['goods_mysql_name'] = iconv('WINDOWS-1251', 'UTF-8', $mName['mName']);
            $options['goods_name'] = $mName['mName'];
            $options['stock_name'] = 'Supply';
            $options['quantity'] = 1;
            $is_supply = 1;
            //....

            RequestFunction::addOrdersMsSQL($options);
            RequestFunction::addOrders($options);
            RequestFunction::addOrdersElementsMsSql($options, $is_supply);
            RequestFunction::addOrdersElements($options);

            // Если нашли на складе и выписали заказ, убираем со списка проверяемых заявок
            RequestFunction::updateCheckReserveOrders($request['id'], 1);

        }
    }
}