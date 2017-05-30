<?php
include_once ('RequestFunction.php');


function checkRequest(){

    $user = new RequestUser();
    $group = new RequestGroup();

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
        $options['note'] = iconv('UTF-8', 'WINDOWS-1251', $request['note']);
        $options['note_mysql'] = $request['note'];

        //Проверка по складам
        //$stock = iconv('UTF-8', 'WINDOWS-1251', 'OK (Выборгская, 104)');
        $stocks_group = $group->stocksFromGroup($user->idGroupUser($options['id_user']), 'name', 'supply');
        $stock = iconv('UTF-8', 'WINDOWS-1251', $stocks_group[0]);

        $check_part_in_stock = RequestFunction::checkOrdersPartNumberMsSql($options['id_user'], $options['part_number'], $stock);
        // отмечаем как провереная заявка за текущий час
        RequestFunction::updateCheckPerHour($request['id'], 1);
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
            $ok = RequestFunction::updateCheckReserveOrders($request['id'], 1);
            if($ok){
                RequestFunction::updateCheckPerHour($request['id'], 1);
                sleep(10);
                checkRequest();
                break;
            }
        } else {
            //Проверка в поставках
            //Проверяем наличие детали в поставках созданим пользователями с одной группы
            $users_group = $group->usersFromGroup($user->idGroupUser($options['id_user']));
            $status = iconv('UTF-8', 'WINDOWS-1251', 'Подтверждена');
            $check_part_in_supply = RequestFunction::checkPartNumberInSupply($users_group, $options['part_number'], $status);
            // отмечаем как провереная заявка за текущий час
            RequestFunction::updateCheckPerHour($request['id'], 1);
            if($check_part_in_supply && $check_part_in_supply['quantity'] > 0){
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
                $ok = RequestFunction::updateCheckReserveOrders($request['id'], 1);
                if($ok){
                    sleep(10);
                    checkRequest();
                    break;
                }
            }
        }
    }
    // Когда за текущий час проверили все записи, обновялем все актуальные заявки на ноль
    RequestFunction::updateCheckPerHourToZero(0, 0);
}

checkRequest();
