<?php

namespace Umbrella\models;

use PDO;
use Umbrella\app\User;
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
                 sgt.goods_name_id,
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
                 sgt.goods_name_id,
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
     * @param string $filters
     * @return array
     */
    public static function getGoodsInStocksPartners($id_partners, $stocks, $filters = '')
    {
        $db = MsSQL::getConnection();

        $stocks_name = implode('\' , \'', $stocks);
        $stock_iconv = Decoder::strToWindows($stocks_name);
        $ids_partner = implode(',', $id_partners);

        $sql = "SELECT
                 sgt.site_account_id,
                 sgt.stock_name,
                 sgt.goods_name,
                 sgt.goods_name_id,
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
                AND sgt.stock_name IN ('{$stock_iconv}') {$filters}";

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
                 sgt.goods_name_id,
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
                 sgt.goods_name_id,
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
                 sgt.goods_name_id,
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
                WHERE sgt.stock_name = :stock_name";
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
                    sgt.goods_name_id,
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
                OR sgt.subtype_name LIKE ?
                OR sgt.serial_number LIKE ?)";

        $result = $db->prepare($sql);
        $result->execute(array("%$search%", "%$search%", "%$search%", "%$search%", "%$search%"));
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
     * Получаем из GM список складов к привязанных к метсорасположению
     * @return array
     */
    public static function getAllStocksToPartner()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT * FROM site_stocks_to_partners";
        $result = $db->prepare($sql);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Добавляем связь склад - пользователь
     * @param $user_id
     * @param $stock_id
     * @return bool
     */
    public static function addStockToPartner($user_id, $stock_id)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO site_gm_users_stocks '
            . '(site_account_id, stock_id)'
            . 'VALUES '
            . '(:site_account_id, :stock_id)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_account_id', $user_id, PDO::PARAM_INT);
        $result->bindParam(':stock_id', $stock_id, PDO::PARAM_INT);
        return $result->execute();
    }



    public static function checkInStockAndReplaceName($user_id, $stocks_group, $part_number, User $user)
    {
        $stocks = [];
        $i = 0;

        foreach ($stocks_group as $stock){
            if(!$user->isPartner()){
                $product = self::checkGoodsInStocksPartners($user_id, $stock, $part_number, 'fetchAll', 'site_gm_stocks');
                if(is_array($product)){
                    foreach ($product as $prodStock){
                        $stock = Decoder::strToUtf($prodStock['stock_name']);
                        $stocks[$stock] = $prodStock;
                    }
                }
            } else {
                $product = self::checkGoodsInStocksPartners($user_id, $stock, $part_number, 'fetch', 'site_gm_stocks');
                // PEX, Киев\ОК или PEX, Киев\Квазар
                if($product){
                    if(trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\OK')
                        || trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\Квазар')
                        || trim($product['stock_name']) == Decoder::strToWindows('KVAZAR, Киев\OK')){
                        if($product['quantity'] > 0){
                            if(isset($stocks['НОВЫЕ(UA)'])){
                                if($stocks['НОВЫЕ(UA)']['quantity'] < $product['quantity']){
                                    $stocks['НОВЫЕ(UA)'] = $product;
                                }
                            } else {
                                //$product['stock_nam'] = "НОВЫЕ(UA)";
                                $stocks['НОВЫЕ(UA)'] = $product;
                            }
                        }
                    } elseif (trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\б/у')
                        || trim($product['stock_name']) == Decoder::strToWindows('KVAZAR, Киев\б/у')){
                        if($product['quantity'] > 0){
                            if(isset($stocks['БУ(UA)'])){
                                if($stocks['БУ(UA)']['quantity'] < $product['quantity']){
                                    $stocks['БУ(UA)'] = $product;
                                }
                            } else {
                                //$product['stock_nam'] = 'БУ(UA)';
                                $stocks['БУ(UA)'] = $product;
                            }
                        }
                    } else {
                        //$product['stock_nam'] = $stock;
                        $stocks[$stock] = $product;
                    }
                }
            }

            $i++;
        }


        // БЛИЖАЙШАЯ ПОСТАВКА
        // PEX, Киев\ОК или PEX, Киев\б/у

//        foreach ($stocks_group as $stock){
//            $product = self::checkGoodsInStocksPartners($user_id, $stock, $part_number, 'fetch', 'site_gm_stocks_decompiles');
//            if($product){
//                if(trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\OK')
//                    || trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\Квазар')
//                    || trim($product['stock_name']) == Decoder::strToWindows('KVAZAR, Киев\OK')){
//                    if($product['quantity'] > 0){
//                        if(isset($stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ'])){
//                            if($stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ']['quantity'] < $product['quantity']){
//                                $stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ'] = $product;
//                            }
//                        } else {
//                            $product['stock_nam'] = 'БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ';
//                            $stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ'] = $product;
//                        }
//                    }
//                }
//            }
//
//            if($product){
//                if(trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\б/у')
//                    || trim($product['stock_name']) == Decoder::strToWindows('KVAZAR, Киев\б/у')){
//                    if($product['quantity'] > 0){
//                        if(isset($stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ'])){
//                            if($stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ']['quantity'] < $product['quantity']){
//                                $stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ'] = $product;
//                            }
//                        } else {
//                            $product['stock_nam'] = 'БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ';
//                            $stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ'] = $product;
//                        }
//                    }
//                }
//            }
//            $i++;
//        }

        //return array_reverse($stocks);
        return $stocks;
    }


    /**
     * @param $stockName
     * @param string $role
     * @return string
     */
    public static function replaceNameStockInResultTable($stockName, $role = 'administrator')
    {
        if($role != 'administrator'){
            if($stockName == 'KVAZAR, Киев\OK'
                || $stockName == 'PEX, Киев\OK'
                || $stockName == 'PEX, Киев\Квазар'){
                $stockReplace = 'НОВЫЙ(UA)';
            } elseif($stockName == 'KVAZAR, Киев\б/у'
                || $stockName == 'PEX, Киев\б/у') {
                $stockReplace = 'БУ(UA)';
            } elseif ($stockName == 'OK (Выборгская, 104)' || $stockName == 'OK (KVAZAR)'){
                $stockReplace = 'Electrolux';
            } else {
                $stockReplace = $stockName;
            }
        } else {
            $stockReplace = $stockName;
        }

        return $stockReplace;
    }


    /**
     * Подмена названий складов в результатирующей таблице
     * @param $stockName
     * @param string $role
     * @return array
     */
    public static function replaceArrayNameStockInResultTable($stockName, $role = 'administrator')
    {
        $stockReplace = [];

        if(is_array($stockName)){
            foreach ($stockName as $stock){
                if($role != 'administrator'){
                    if($stock == 'KVAZAR, Киев\OK'
                        || $stock == 'PEX, Киев\OK'
                        || $stock == 'PEX, Киев\Квазар'){
                        $stockReplace[] = 'НОВЫЙ(UA)';
                    } elseif($stock == 'KVAZAR, Киев\б/у'
                        || $stock == 'PEX, Киев\б/у') {
                        $stockReplace[] = 'БУ(UA)';
                    } elseif ($stockName == 'OK (Выборгская, 104)' ||  $stockName == 'OK (KVAZAR)'){
                        $stockReplace[] = 'Electrolux';
                    } else {
                        $stockReplace[] = $stock;
                    }
                } else {
                    $stockReplace[] = $stock;
                }
            }
        }
        return array_unique($stockReplace);
    }



    /**
     * Получаем список подтипов
     * @return array
     */
    public static function getListSubType()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT id, shortName FROM GoodsSubType ORDER BY shortName";

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