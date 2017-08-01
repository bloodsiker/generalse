<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Db\MsSQL;

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
        $db = MsSQL::getConnection();
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

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->bindParam(':stock_name', $stock_name, PDO::PARAM_STR);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Возвращаем детали по складам для выбранных партнеров и выбранных складов
     * @param $id_partners
     * @param $stocks
     * @return array
     */
    public static function getGoodsInStocksPartners($id_partners, $stocks)
    {
        $db = MsSQL::getConnection();

        $stocks_name = implode('\' , \'', $stocks);
        $stock_iconv = iconv('UTF-8', 'WINDOWS-1251', $stocks_name);
        $ids_partner = implode(',', $id_partners);

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
                WHERE sgu.site_account_id IN ({$ids_partner})
                AND stock_name IN ('{$stock_iconv}')";

        $result = $db->prepare($sql);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * @param $id_partner
     * @param $stocks
     * @param $part_number
     * @return array
     */
    public static function checkGoodsInStocksPartners($id_partner, $stocks, $part_number)
    {
        $db = MsSQL::getConnection();

        $stocks_name = implode('\' , \'', $stocks);
        $stock_iconv = iconv('UTF-8', 'WINDOWS-1251', $stocks_name);

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
                WHERE sgu.site_account_id = :id_partner
                AND stock_name IN ('{$stock_iconv}')
                AND sgt.part_number = :part_number";

        $result = $db->prepare($sql);
        //2912630403
        $result->bindParam(':id_partner', $id_partner, PDO::PARAM_INT);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $result->execute();
        $all = $result->fetch(PDO::FETCH_ASSOC);
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
        $db = MsSQL::getConnection();

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
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Получаем все склады
     * @return array
     */
    public static function getAllStocks()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT * FROM gs_stocks";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Проверяем, есть ли склад в массиве
     * @param $array_stock
     * @param $stock
     * @return bool
     */
    public static function checkStocks($array_stock, $stock)
    {
        $found_key = in_array($stock, $array_stock);
        if($found_key){
            return true;
        }
        return false;
    }

    /**
     * Проверяем, есть ли id_user в массиве
     * @param $array_user
     * @param $user
     * @return bool
     */
    public static function checkUser($array_user, $user)
    {
        $found_key = in_array($user, $array_user);
        if($found_key){
            return true;
        }
        return false;
    }
}