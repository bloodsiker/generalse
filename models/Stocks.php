<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Db\MsSQL;
use Umbrella\components\Decoder;

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
                 sgt.stock_id,
                 sgu.site_client_name
                FROM site_gm_stocks sgt
                INNER JOIN tbl_Users tu
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu
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
                 sgt.stock_id,
                 sgu.site_client_name
                FROM site_gm_stocks sgt
                INNER JOIN tbl_Users tu 
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu 
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
                 sgt.stock_id,
                 sgu.site_client_name
                FROM site_gm_stocks sgt
                INNER JOIN tbl_Users tu
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu
                    ON sgu.id = tu.site_gs_account_id
                WHERE sgu.site_account_id IN ({$ids_partner})
                AND stock_name IN ('{$stock_iconv}')";

        $result = $db->prepare($sql);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     *
     * @param $id_partner
     * @param $stocks
     * @param $part_number
     * @param string $attr
     * @param string $table
     * @return array
     */
    public static function checkGoodsInStocksPartners($id_partner, $stocks, $part_number, $attr = 'fetch', $table = 'site_gm_stocks')
    {
        $db = MsSQL::getConnection();

        if(is_array($stocks)){
            $stocks_name = implode('\' , \'', $stocks);
        } else {
            $stocks_name = $stocks;
        }

        $stock_iconv = Decoder::strToWindows($stocks_name);

        $sql = "SELECT
                 sgt.stock_name,
                 sgt.goods_name,
                 sgt.part_number,
                 sgt.type_name,
                 sgt.subtype_name,
                 sgt.quantity,
                 sgt.serial_number,
                 sgt.price,
                 sgt.stock_id,
                 sgu.site_client_name
                FROM {$table} sgt
                INNER JOIN tbl_Users tu
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu
                    ON sgu.id = tu.site_gs_account_id
                WHERE sgu.site_account_id = :id_partner
                AND stock_name IN ('{$stock_iconv}')
                AND sgt.part_number = :part_number";

        $result = $db->prepare($sql);
        //2912630403
        $result->bindParam(':id_partner', $id_partner, PDO::PARAM_INT);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $result->execute();
        $all = $result->$attr(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     *  товары со всех складов всех партнеров
     * @return array
     */
    public static function getAllGoodsAllPartner()
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
                 sgt.stock_id,
                 sgu.site_client_name
                FROM site_gm_stocks sgt
                INNER JOIN tbl_Users tu
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu
                    ON sgu.id = tu.site_gs_account_id";

        $result = $db->prepare($sql);
        $result->execute();
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
                 sgt.stock_id,
                 sgu.site_client_name
                FROM site_gm_stocks sgt
                INNER JOIN tbl_Users tu
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu
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
     * Поиск по складам
     * @param $search
     * @param string $filter
     * @return array
     */
    public static function getSearchInStocks($search, $filter = '')
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
                    sgt.stock_id,
                    sgu.site_client_name
                FROM site_gm_stocks sgt
                INNER JOIN tbl_Users tu
                  ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu
                  ON sgu.id = tu.site_gs_account_id
                WHERE 1 = 1 {$filter}
                AND (sgu.site_client_name LIKE ?
                OR sgt.goods_name LIKE ?
                OR sgt.part_number LIKE ?
                OR sgt.serial_number LIKE ?)";

        $result = $db->prepare($sql);
        $result->execute(array("%$search%", "%$search%", "%$search%", "%$search%"));
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



    public static function checkInStockAndReplaceName($user_id, $stocks_group, $part_number)
    {
        $stocks = [];
        $i = 0;

        foreach ($stocks_group as $stock){
            $product = self::checkGoodsInStocksPartners($user_id, $stock, $part_number, 'fetch', 'site_gm_stocks');
            // PEX, Киев\ОК или PEX, Киев\Квазар
            if($product){
                if(trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\OK')
                    || trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\Квазар')
                    || trim($product['stock_name']) == Decoder::strToWindows('KVAZAR, Киев\OK')){
                    if($product['quantity'] > 0){
                        if(isset($stocks['НОВЫЕ'])){
                            if($stocks['НОВЫЕ']['quantity'] < $product['quantity']){
                                $stocks['НОВЫЕ'] = $product;
                            }
                        } else {
                            $product['stock_nam'] = 'НОВЫЕ';
                            $stocks['НОВЫЕ'] = $product;
                        }
                    }
                }
            }
            $i++;
        }


        // БУ Склад //PEX, Киев\б/у
        foreach ($stocks_group as $stock){
            $product = self::checkGoodsInStocksPartners($user_id, $stock, $part_number, 'fetch', 'site_gm_stocks');
            // PEX, Киев\ОК или PEX, Киев\Квазар
            if($product){
                if(trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\б/у')
                    || trim($product['stock_name']) == Decoder::strToWindows('KVAZAR, Киев\б/у')){
                    if($product['quantity'] > 0){
                        if(isset($stocks['БУ'])){
                            if($stocks['БУ']['quantity'] < $product['quantity']){
                                $stocks['БУ'] = $product;
                            }
                        } else {
                            $product['stock_nam'] = 'БУ';
                            $stocks['БУ'] = $product;
                        }
                    }
                }
            }
            $i++;
        }

        // БЛИЖАЙШАЯ ПОСТАВКА
        // PEX, Киев\ОК или PEX, Киев\б/у

        foreach ($stocks_group as $stock){
            $product = self::checkGoodsInStocksPartners($user_id, $stock, $part_number, 'fetch', 'site_gm_stocks_decompiles');
            if($product){
                if(trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\OK')
                    || trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\Квазар')
                    || trim($product['stock_name']) == Decoder::strToWindows('KVAZAR, Киев\OK')){
                    if($product['quantity'] > 0){
                        if(isset($stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ'])){
                            if($stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ']['quantity'] < $product['quantity']){
                                $stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ'] = $product;
                            }
                        } else {
                            $product['stock_nam'] = 'БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ';
                            $stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ'] = $product;
                        }
                    }
                }
            }

            if($product){
                if(trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\б/у')
                    || trim($product['stock_name']) == Decoder::strToWindows('KVAZAR, Киев\б/у')){
                    if($product['quantity'] > 0){
                        if(isset($stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ'])){
                            if($stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ']['quantity'] < $product['quantity']){
                                $stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ'] = $product;
                            }
                        } else {
                            $product['stock_nam'] = 'БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ';
                            $stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ'] = $product;
                        }
                    }
                }
            }
            $i++;
        }

        //return array_reverse($stocks);
        return $stocks;
    }


    /**
     * Подмена названий складов в фильтрации
     * @param $arrayStocks
     * @param string $replace  replace || back_replace
     * @param string $role
     * @return array
     */
    public static function replaceNameStockInFilter($arrayStocks, $replace = 'replace', $role = 'administrator')
    {
        $newArrayStocks = [];

        if($role != 'administrator') {
            if($replace == 'replace'){
                if(is_array($arrayStocks)){
                    foreach ($arrayStocks as $stock){
                        if($stock == 'KVAZAR, Киев\OK'
                            || $stock == 'PEX, Киев\OK'
                            || $stock == 'PEX, Киев\Квазар'){
                            $newArrayStocks[] = 'НОВЫЙ';
                        } elseif($stock == 'KVAZAR, Киев\б/у'
                            || $stock == 'PEX, Киев\б/у') {
                            $newArrayStocks[] = 'БУ';
                        } else {
                            $newArrayStocks[] = $stock;
                        }
                    }
                }
            } elseif ($replace == 'back_replace'){
                if(is_array($arrayStocks)){
                    foreach ($arrayStocks as $stock){
                        if($stock == 'НОВЫЙ'){
                            $newArrayStocks[] = 'KVAZAR, Киев\OK';
                            $newArrayStocks[] = 'PEX, Киев\OK';
                            $newArrayStocks[] = 'PEX, Киев\Квазар';
                        } elseif($stock == 'БУ') {
                            $newArrayStocks[] = 'KVAZAR, Киев\б/у';
                            $newArrayStocks[] = 'PEX, Киев\б/у';
                        } else {
                            $newArrayStocks[] = $stock;
                        }
                    }
                }
            }

        } else {
            $newArrayStocks = $arrayStocks;
        }
        return array_unique($newArrayStocks);
    }


    /**
     * Подмена названий складов в результатирующей таблице
     * @param $stockName
     * @param string $role
     * @return null|string
     */
    public static function replaceNameStockInResultTable($stockName, $role = 'administrator')
    {
        $stock = null;
        $stockName = Decoder::strToUtf($stockName);
        if($role != 'administrator'){
            if($stockName == 'KVAZAR, Киев\OK'
                || $stockName == 'PEX, Киев\OK'
                || $stockName == 'PEX, Киев\Квазар'){
                $stock = 'НОВЫЙ';
            } elseif($stockName == 'KVAZAR, Киев\б/у'
                || $stockName == 'PEX, Киев\б/у') {
                $stock = 'БУ';
            } else {
                $stock = $stockName;
            }
        } else {
            $stock = $stockName;
        }
        return $stock;
    }



    /**
     * Получаем список подтипов
     * @return array
     */
    public static function getListSubType()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT id, shortName FROM GoodsSubType";

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