<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Db\MsSQL;

/**
 * Разборка устройств
 * Class Disassembly
 */
class Disassembly
{
	
	public static function getAllRequest()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT 
                    sgd.part_number,
                    sgd.serial_number,
                    sgdp.part_number as goods_part,
                    sgdp.stock_name,
                    sgdp.goods_name
                    FROM site_gm_decompiles sgd 
                    INNER JOIN site_gm_decompiles_parts sgdp 
                    ON sgd.decompile_id = sgdp.decompile_id";

        $result = $db->prepare($sql);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * Получение bom-листа для устройства
     * @param $user_id
     * @param $serialNumber
     * @return array
     */
    public static function getRequestByPartner($user_id, $serialNumber)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT 
                     tbl_GoodsNames.PartNumber 
                    ,tbl_GoodsNames.mName
                    ,tbl_Units.PartNumber AS dev_part_number
                    ,tbl_Units.mName AS dev_mName
                FROM tbl_GoodsNames WITH (nolock)
                INNER JOIN tbl_GoodsNamesDetails WITH (nolock) ON tbl_GoodsNamesDetails.DetailGoodsNameID = tbl_GoodsNames.i_d
                INNER JOIN tbl_GoodsNames tbl_Units ON tbl_Units.I_D = tbl_GoodsNamesDetails.UnitGoodsNameID
                WHERE
                 tbl_GoodsNamesDetails.UnitGoodsNameID IN (
                 (
                SELECT
                 ss.goodsNameID
                FROM
                 (
                SELECT
                 tbl_2_StockTraffick.StockIDTo AS stock_id
                ,tbl_GoodsNames.i_d AS goodsNameID
                ,tbl_GoodsNames.PartNumber
                ,tbl_GoodsNames.mName AS goodsName
                ,tbl_2_StockTraffick.serial_number 
                ,1 AS quantity
                FROM tbl_2_StockTraffick WITH (nolock)
                INNER JOIN tbl_2_Goods WITH (nolock) ON tbl_2_Goods.goodsID = tbl_2_StockTraffick.GoodsID
                INNER JOIN tbl_GoodsNames WITH (nolock) ON tbl_GoodsNames.i_d = tbl_2_Goods.goodsNameID UNION ALL
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
                WHERE
                 ss.serial_number = :serial_number AND site_gm_users.site_account_id = :user_id AND tbl_2_StockStruct.StockName LIKE '%SWAP%'
                GROUP BY
                 ss.serial_number 
                ,ss.PartNumber 
                ,ss.goodsName 
                ,ss.stock_id
                ,ss.goodsNameID
                HAVING SUM(ss.quantity) > 0))";

        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->bindParam(':serial_number', $serialNumber, PDO::PARAM_STR);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Пишем разборку детали в MsSql
     * @param $options
     * @return bool
     */
    public static function addDecompilesPartsMsSql($options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO dbo.site_gm_decompiles_parts '
            . '(site_id, part_number, stock_name, quantity)'
            . 'VALUES '
            . '(:site_id, :part_number, :stock_name, :quantity)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':stock_name', $options['stock_name'], PDO::PARAM_STR);
        $result->bindParam(':quantity', $options['quantity'], PDO::PARAM_INT);

        return $result->execute();
    }


    /**
     * Разборка детали - шапка в MsSql
     * @param $options
     * @return bool
     */
    public static function addDecompilesMsSql($options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO dbo.site_gm_decompiles '
            . '(site_id, site_account_id, part_number, serial_number, ready, note)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :part_number, :serial_number, :ready, :note)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':serial_number', $options['serial_number'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        $result->bindParam(':note', $options['note_ms'], PDO::PARAM_STR);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }


    /**
     * @param $id
     * @param $ready
     * @return bool
     */
    public static function decompileIsReady($id, $ready)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE dbo.site_gm_decompiles
            SET
                ready = :ready
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':ready', $ready, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Пишем разборку детали
     * @param $options
     * @return bool
     */
    public static function addDecompilesParts($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_decompiles_parts '
            . '(site_id, mName, part_number, stock_name, quantity)'
            . 'VALUES '
            . '(:site_id, :mName, :part_number, :stock_name, :quantity)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':mName', $options['mName'], PDO::PARAM_STR);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':stock_name', $options['stock_name'], PDO::PARAM_STR);
        $result->bindParam(':quantity', $options['quantity'], PDO::PARAM_INT);

        return $result->execute();
    }


    /**
     * Разборка детали - шапка
     * @param $options
     * @return bool
     */
    public static function addDecompiles($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_decompiles '
            . '(site_id, id_user, part_number, serial_number, dev_name, stockName, ready, note)'
            . 'VALUES '
            . '(:site_id, :id_user, :part_number, :serial_number, :dev_name, :stockName, :ready, :note)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':serial_number', $options['serial_number'], PDO::PARAM_STR);
        $result->bindParam(':dev_name', $options['dev_name'], PDO::PARAM_STR);
        $result->bindParam(':stockName', $options['stockName'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        $result->bindParam(':note', $options['note'], PDO::PARAM_INT);

        return $result->execute();
    }


    /**
     * Получаем последний ID разборки
     * @return mixed
     */
    public static function getLastDecompileId()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT TOP 1 site_id FROM site_gm_decompiles WHERE site_id IS NOT NULL ORDER BY id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        $row = $result->fetch();
        return $row['site_id'];
    }


    /**
     * Получить весь список разборок
     * @param $filter
     * @return array
     */
    public static function getAllDisassembly($filter)
    {
        $db = MySQL::getConnection();

        $data = $db->query("SELECT
                                gd.site_id,
                                gd.id_user,
                                gd.part_number,
                                gd.serial_number,
                                gd.dev_name,
                                gd.stockName,
                                gd.date_create,
                                gu.name_partner
                              FROM gm_decompiles gd
                                INNER JOIN gs_user gu
                                  ON gd.id_user = gu.id_user
                              WHERE 1 = 1 {$filter}
                                ORDER BY gd.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }


    /**
     * Получить список разборок для партнера
     * @param $array_id
     * @param $filter
     * @return array
     */
    public static function getDisassemblyByPartner($array_id, $filter)
    {
        $db = MySQL::getConnection();

        $idS = implode(',', $array_id);

        $sql = "SELECT
                  gd.site_id,
                  gd.part_number,
                  gd.serial_number,
                  gd.dev_name,
                  gd.stockName,
                  gd.date_create,
                  gu.name_partner
                  FROM gm_decompiles gd
                    INNER JOIN gs_user gu
	                  ON gd.id_user = gu.id_user
                  WHERE gd.id_user IN({$idS}) {$filter}
                    ORDER BY gd.id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Search in disassemble
     * @param $search
     * @param $filter
     * @return array
     */
    public static function getSearchInDisassemble($search, $filter = '')
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                gd.site_id,
                gd.part_number,
                gd.serial_number,
                gd.dev_name,
                gd.stockName,
                gd.date_create,
                gu.name_partner
                FROM gm_decompiles gd
                  INNER JOIN gs_user gu
                   ON gd.id_user = gu.id_user
                WHERE 1 = 1 {$filter}
                AND (gu.name_partner LIKE ?
                 OR gd.dev_name LIKE ?
                 OR gd.part_number LIKE ?
                 OR gd.serial_number LIKE ?)
                  ORDER BY gd.id DESC";

        $result = $db->prepare($sql);
        $result->execute(array("%$search%", "%$search%", "%$search%", "%$search%"));
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Получаем статус и ID из GS
     * @param $part_number
     * @param $serial_number
     * @return mixed
     */
    public static function checkStatusRequest($part_number, $serial_number)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                    decompile_id,
                    status_name,
                    command
                FROM site_gm_decompiles
                WHERE part_number = :part_number
                AND serial_number = :serial_number
                ORDER BY id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $result->bindParam(':serial_number', $serial_number, PDO::PARAM_STR);
        $result->execute();
        $status = $result->fetch();
        return $status;
    }


    /**
     * Получаем статус и ID из GS
     * @param $site_id
     * @return mixed
     */
    public static function checkStatusRequestMSSQL($site_id)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                    decompile_id,
                    status_name,
                    command
                FROM site_gm_decompiles
                WHERE site_id = :site_id
                ORDER BY id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->execute();
        $status = $result->fetch();
        return $status;
    }

    /**
     * Получаем список комплектуючих в разборке
     * @param $site_id
     * @return array
     */
	public static function getShowDetailsDisassembly($site_id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                *
                FROM gm_decompiles_parts gdp
                WHERE gdp.site_id = :site_id";

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     *  Получаем комментарий к разборке
     * @param $site_id
     * @return mixed
     */
    public static function getShowCommentDisassembly($site_id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT gd.note FROM gm_decompiles gd WHERE gd.site_id = :site_id";

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->execute();
        $status = $result->fetch();
        return $status;
    }

    public static function getExportDisassemblyByPartner($array_id, $start, $end)
    {
        $db = MySQL::getConnection();

        $idS = implode(',', $array_id);

        $sql = "SELECT
                  gd.site_id,
                  gd.part_number,
                  gd.serial_number,
                  gd.dev_name,
                  gd.stockName,
                  gd.date_create,
                  gdp.mName,
                  gdp.part_number AS goods_part,
                  gdp.stock_name,
                  gdp.quantity,
                  gu.name_partner
                  FROM gm_decompiles gd
                    INNER JOIN gm_decompiles_parts gdp
                      ON gd.site_id = gdp.site_id
                    INNER JOIN gs_user gu
	                  ON gd.id_user = gu.id_user
                  WHERE gd.id_user IN({$idS})
                  AND gd.date_create BETWEEN :start AND :end
                    ORDER BY gd.id DESC";

        $result = $db->prepare($sql);
        //$result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->bindParam(':start', $start, PDO::PARAM_STR);
        $result->bindParam(':end', $end, PDO::PARAM_STR);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    public static function getExportDisassemblyAllPartner($start, $end)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                    gd.site_id,
                    gd.part_number,
                    gd.serial_number,
                    gd.dev_name,
                    gd.stockName,
                    gd.date_create,
                    gdp.mName,
                    gdp.part_number AS goods_part,
                    gdp.stock_name,
                    gdp.quantity,
                    gu.name_partner
                FROM gm_decompiles gd
                   INNER JOIN gm_decompiles_parts gdp
                        ON gd.site_id = gdp.site_id
                   INNER JOIN gs_user gu
                      ON gd.id_user = gu.id_user
                WHERE gd.date_create BETWEEN :start AND :end
                  ORDER BY gd.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':start', $start, PDO::PARAM_STR);
        $result->bindParam(':end', $end, PDO::PARAM_STR);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Изменяем статус заявки
     * @param $decompile_id
     * @param $command
     * @param $comment
     * @return bool
     */
    public static function updateStatusDisassemblyGM($decompile_id, $command, $comment)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_decompiles
            SET
                command = :command,
                command_text = :comment
            WHERE decompile_id = :decompile_id";

        $result = $db->prepare($sql);
        $result->bindParam(':decompile_id', $decompile_id, PDO::PARAM_INT);
        $result->bindParam(':command', $command, PDO::PARAM_INT);
        $result->bindParam(':comment', $comment, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Удаляем разборку с базы данных MySQL
     * @param $site_id
     * @return bool
     */
    public static function deleteDecompileById($site_id)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE gm_decompiles, gm_decompiles_parts 
                FROM gm_decompiles, gm_decompiles_parts 
                WHERE gm_decompiles.site_id = gm_decompiles_parts.site_id AND gm_decompiles.site_id = :site_id';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        return $result->execute();
    }

	
	public static function getStatusRequest($status)
    {
        switch($status)
        {
            case 'Подтверждена':
                return 'green';
                break;
            case 'Отклонена':
                return 'red';
                break;
            case 'Предварительная':
                return 'yellow';
                break;
        }

        return true;
    }
}