<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MsSQL;

class Backlog
{

    /**
     * Поиск парт номера по складу BAD и подтип
     * @param $id_partner
     * @param $part_number
     * @param $sub_type
     * @return array
     */
    public static function getGoodsInStockByPartner($id_partner, $part_number, $sub_type)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT
                 sgt.stock_name,
                 sgt.goods_name,
                 sgt.part_number,
                 sgt.type_name,
                 sgt.subtype_name,
                 sgt.quantity,
                 sgt.serial_number,
                 sgu.site_client_name
                FROM site_gm_stocks sgt
                INNER JOIN tbl_Users tu with (nolock)
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu with (nolock)
                    ON sgu.id = tu.site_gs_account_id
                WHERE sgu.site_account_id = :id_user
                AND stock_name = 'BAD'
                AND part_number = :part_number
                AND sgt.subtype_name = :sub_type";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $result->bindParam(':sub_type', $sub_type, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * Поиск парт номера по бум листу партнера по складу SWAP
     * @param $user_id
     * @param $part_number
     * @return array
     */
    public static function getPartNumberInBoomListSwap($user_id, $part_number)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // tbl_2_StockTraffick.serial_number = 'QB08242887'
        // Получение и возврат результатов
        $sql = "select
                       ss.serial_number as unit_serial_number
                       ,ss.PartNumber as unit_part_number
                       ,ss.goodsName as unit_goods_name
                       ,tbl_Details.PartNumber as detail_part_number
                       ,tbl_Details.mName as detail_goods_name
                from 
                       (SELECT
                             tbl_2_StockTraffick.StockIDTo AS stock_id
                             ,tbl_GoodsNames.i_d AS goodsNameID
                             ,tbl_GoodsNames.PartNumber
                             ,tbl_GoodsNames.mName AS goodsName
                             ,tbl_2_StockTraffick.serial_number
                             ,1 AS quantity
                       FROM tbl_2_StockTraffick WITH (nolock)
                             INNER JOIN tbl_2_Goods WITH (nolock) ON tbl_2_Goods.goodsID = tbl_2_StockTraffick.GoodsID
                             INNER JOIN tbl_GoodsNames WITH (nolock) ON tbl_GoodsNames.i_d = tbl_2_Goods.goodsNameID 
                       UNION ALL
                       SELECT
                             tbl_2_StockTraffick.StockIDFrom
                             ,tbl_GoodsNames.i_d AS goodsNameID
                             ,tbl_GoodsNames.PartNumber
                             ,tbl_2_StockTraffick.serial_number
                             ,tbl_GoodsNames.mName AS goodsName
                             ,-1 AS quantity
                       FROM tbl_2_StockTraffick WITH (nolock)
                             INNER JOIN tbl_2_Goods WITH (nolock) ON tbl_2_Goods.goodsID = tbl_2_StockTraffick.GoodsID
                             INNER JOIN tbl_GoodsNames WITH (nolock) ON tbl_GoodsNames.i_d = tbl_2_Goods.goodsNameID
                       )ss
                       INNER JOIN tbl_2_StockStruct WITH (nolock) ON tbl_2_StockStruct.StockID = ss.stock_id
                       INNER JOIN tbl_2_StockPlaces WITH (nolock) ON tbl_2_StockPlaces.StockPlaceID = tbl_2_StockStruct.StockPlaceID
                       INNER JOIN tbl_Users WITH (nolock) ON tbl_Users.I_D = tbl_2_StockPlaces.[user_id]
                       INNER JOIN site_gm_users WITH (nolock) ON site_gm_users.id = tbl_Users.site_gs_account_id
                       INNER JOIN tbl_GoodsNamesDetails WITH (nolock) ON tbl_GoodsNamesDetails.UnitGoodsNameID = ss.goodsNameID
                       INNER JOIN tbl_GoodsNames tbl_Details ON tbl_Details.I_D = tbl_GoodsNamesDetails.DetailGoodsNameID
                WHERE
                       site_gm_users.site_account_id = :user_id 
                       AND tbl_2_StockStruct.StockName LIKE '%SWAP%'
                       --and ss.serial_number = '12345678' 
                       and tbl_Details.PartNumber = :part_number
                GROUP BY
                       ss.serial_number
                       ,ss.PartNumber
                       ,ss.goodsName
                       ,ss.stock_id
                       ,ss.goodsNameID
                       ,tbl_Details.PartNumber 
                       ,tbl_Details.mName
                HAVING 
                       SUM(ss.quantity) > 0";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * поиск парт номера по всем складам бум листов
     * @param $part_number
     * @return array
     */
    public static function getPartNumberInBoomListNoStock($part_number)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "select
                       tbl_GoodsNames.PartNumber as unit_part_number
                       ,tbl_GoodsNames.mName as unit_goods_name
                       ,tbl_Details.PartNumber as detail_part_number
                       ,tbl_Details.mName as detail_goods_name
                from tbl_GoodsNames with (nolock)
                       INNER JOIN tbl_GoodsNamesDetails WITH (nolock) ON tbl_GoodsNamesDetails.UnitGoodsNameID = tbl_GoodsNames.i_d
                       INNER JOIN tbl_GoodsNames tbl_Details ON tbl_Details.I_D = tbl_GoodsNamesDetails.DetailGoodsNameID
                WHERE
                       tbl_Details.PartNumber = :part_number";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

}