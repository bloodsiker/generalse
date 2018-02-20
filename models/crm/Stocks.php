<?php

namespace Umbrella\models\crm;

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
                WHERE sgu.site_account_id = :id_user";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
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
                 sgt.goods_name_id,
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
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * find serial number by stocks
     * @param $id_partner
     * @param $stock_name
     * @param $serial_number
     *
     * @return mixed
     */
    public static function getSerialNumberInStockByPartner($id_partner, $stock_name, $serial_number)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                 sgt.serial_number
                FROM site_gm_stocks sgt
                INNER JOIN tbl_Users tu 
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu 
                    ON sgu.id = tu.site_gs_account_id
                WHERE sgu.site_account_id = :id_user
                AND stock_name = :stock_name
                AND sgt.serial_number = :serial_number";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->bindParam(':stock_name', $stock_name, PDO::PARAM_STR);
        $result->bindParam(':serial_number', $serial_number, PDO::PARAM_STR);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Возвращаем детали по складам для выбранных партнеров и выбранных складов
     *
     * @param $id_partners
     * @param $stocks
     * @param string $filters
     *
     * @return array
     * @throws \Exception
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
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     *
     * @param $id_partner
     * @param $stocks
     * @param $part_number
     * @param string $attr
     * @param string $table
     *
     * @return array
     * @throws \Exception
     */
    public static function checkGoodsInStocksPartners($id_partner, $stocks, $part_number, $attr = 'fetch')
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
                FROM site_gm_stocks sgt
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
        return $result->$attr(PDO::FETCH_ASSOC);
    }

    /**
     *
     * @param $id_partner
     * @param $stock
     * @param $part_number
     *
     * @return mixed
     * @throws \Exception
     */
    public static function checkGoodsInDecompileStocksPartners($id_partner, $stock, $part_number)
    {
        $db = MsSQL::getConnection();

        $stock = Decoder::strToWindows($stock);

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
                FROM site_gm_stocks_decompiles sgt
                INNER JOIN tbl_Users tu
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu
                    ON sgu.id = tu.site_gs_account_id
                WHERE sgu.site_account_id = :id_partner
                AND stock_name = :stock
                AND sgt.part_number = :part_number";

        $result = $db->prepare($sql);
        //2912630403
        $result->bindParam(':id_partner', $id_partner, PDO::PARAM_INT);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $result->bindParam(':stock', $stock, PDO::PARAM_STR);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Find part in stocks decompile
     * @param $id_partner
     * @param $part_number
     *
     * @return mixed
     */
    public static function checkGoodsByIdInDecompileStocksPartners($id_partner, $part_number)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                 sgt.stock_name,
                 sgt.quantity,
                 sgt.price,
                 sgt.stock_id
                FROM site_gm_stocks_decompiles sgt
                INNER JOIN tbl_Users tu
                    ON sgt.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu
                    ON sgu.id = tu.site_gs_account_id
                WHERE sgu.site_account_id = :id_partner
                AND sgt.part_number = :part_number";

        $result = $db->prepare($sql);
        $result->bindParam(':id_partner', $id_partner, PDO::PARAM_INT);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
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
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Получаем все склады
     * @return array
     */
    public static function getAllStocks()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT * FROM gs_stocks";
        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
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
        return $result->fetchAll(PDO::FETCH_ASSOC);
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


    /**
     * @param $stockName
     * @param string $role
     * @return string
     */
    public static function replaceNameStockInResultTable($stockName, $role = 'administrator')
    {
        if($role == 'partner'){
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
                if($role == 'partner'){
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
        return $result->fetchAll(PDO::FETCH_ASSOC);
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


    /**
     * Список продуктов в базе данных
     * @return array
     */
    public static function getListProducts()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                  tbl_GoodsNames.mName,
                  tbl_GoodsNames.PartNumber,
                  tbl_GoodsNames.ClassifierID,
                  GoodsSubType.shortName as subType,
                  tbl_Classifier.mName as classifier
                FROM tbl_GoodsNames
                    INNER JOIN GoodsSubType
                      ON tbl_GoodsNames.subType = GoodsSubType.id
                    INNER JOIN tbl_Classifier
                      ON tbl_GoodsNames.ClassifierID = tbl_Classifier.I_D
                WHERE ClassifierID IN (86, 91)";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $search
     *
     * @return array
     */
    public static function searchListProducts($search)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                  tbl_GoodsNames.mName,
                  tbl_GoodsNames.PartNumber,
                  tbl_GoodsNames.ClassifierID,
                  GoodsSubType.shortName as subType,
                  tbl_Classifier.mName as classifier
                FROM tbl_GoodsNames
                    INNER JOIN GoodsSubType
                      ON tbl_GoodsNames.subType = GoodsSubType.id
                    INNER JOIN tbl_Classifier
                      ON tbl_GoodsNames.ClassifierID = tbl_Classifier.I_D
                WHERE ClassifierID IN (86, 91)
                    AND (tbl_GoodsNames.mName LIKE ?
                    OR tbl_GoodsNames.PartNumber LIKE ?)";

        $result = $db->prepare($sql);
        $result->execute(array("%$search%", "%$search%"));
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}