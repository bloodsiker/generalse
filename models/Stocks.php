<?php

class Stocks
{

    /**
     *  Показываем всетовары на складах
     * @param $id_partner
     * @return array
     */
    public static function getAllGoodsByPartner($id_partner)
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
                 sgt.price,
                 sgu.site_client_name
                FROM site_gm_stocks sgt
                INNER JOIN tbl_Users tu with (nolock)
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu with (nolock)
                    ON sgu.id = tu.site_gs_account_id
                WHERE sgu.site_account_id = :id_user";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // 
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Показываем товары на определенном складе
     * @param $id_partner
     * @param $stock_name
     * @return array
     */
    public static function getGoodsInStockByPartner($id_partner, $stock_name)
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
                 sgt.price,
                 sgu.site_client_name
                FROM site_gm_stocks sgt
                INNER JOIN tbl_Users tu with (nolock)
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu with (nolock)
                    ON sgu.id = tu.site_gs_account_id
                WHERE sgu.site_account_id = :id_user
                AND stock_name = :stock_name";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->bindParam(':stock_name', $stock_name, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // 
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     *  товары со всех складов всех партнеров
     * @return array
     */
    public static function getAllGoodsAllPartner()
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
                 sgt.price,
                 sgu.site_client_name
                FROM site_gm_stocks sgt
                INNER JOIN tbl_Users tu with (nolock)
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu with (nolock)
                    ON sgu.id = tu.site_gs_account_id";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        //
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Показываем все детали для всех партнеров на данном складе
     * @param $stock_name
     * @return array
     */
    public static function getGoodsAllPartnerByStock($stock_name)
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
                 sgt.price,
                 sgu.site_client_name
                FROM site_gm_stocks sgt
                INNER JOIN tbl_Users tu with (nolock)
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu with (nolock)
                    ON sgu.id = tu.site_gs_account_id
                WHERE stock_name = :stock_name";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':stock_name', $stock_name, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        //
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

}