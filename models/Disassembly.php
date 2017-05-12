<?php

/**
 * Разборка устройств
 * Class Disassembly
 */
class Disassembly
{
	
	public static function getAllRequest()
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // tbl_2_StockTraffick.serial_number = 'QB08242887'
        // Получение и возврат результатов
        $sql = "SELECT 
                    sgd.part_number,
                    sgd.serial_number,
                    sgdp.part_number as goods_part,
                    sgdp.stock_name,
                    sgdp.goods_name
                    FROM site_gm_decompiles sgd 
                    INNER JOIN site_gm_decompiles_parts sgdp 
                    ON sgd.decompile_id = sgdp.decompile_id";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
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
        // Соединение с БД
        $db = MsSQL::getConnection();

        // tbl_2_StockTraffick.serial_number = 'QB08242887'
        // Получение и возврат результатов
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
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->bindParam(':serial_number', $serialNumber, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
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
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO dbo.site_gm_decompiles_parts '
            . '(site_id, part_number, stock_name, quantity)'
            . 'VALUES '
            . '(:site_id, :part_number, :stock_name, :quantity)';

        // Получение и возврат результатов. Используется подготовленный запрос
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
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO dbo.site_gm_decompiles '
            . '(site_id, site_account_id, part_number, serial_number, ready, note)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :part_number, :serial_number, :ready, :note)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':serial_number', $options['serial_number'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        $result->bindParam(':note', iconv('UTF-8', 'WINDOWS-1251', $options['note']), PDO::PARAM_STR);

        return $result->execute();
    }

    /**
     * Пишем разборку детали
     * @param $options
     * @return bool
     */
    public static function addDecompilesParts($options)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gm_decompiles_parts '
            . '(site_id, mName, part_number, stock_name, quantity)'
            . 'VALUES '
            . '(:site_id, :mName, :part_number, :stock_name, :quantity)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':mName', $options['mName'], PDO::PARAM_STR);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        //$result->bindParam(':serial_number', $options['serial_number'], PDO::PARAM_STR);
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
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gm_decompiles '
            . '(site_id, id_user, part_number, serial_number, dev_name, stockName, ready, note)'
            . 'VALUES '
            . '(:site_id, :id_user, :part_number, :serial_number, :dev_name, :stockName, :ready, :note)';

        // Получение и возврат результатов. Используется подготовленный запрос
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
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = "SELECT site_id FROM gm_decompiles_parts ORDER BY id DESC LIMIT 1";

        // Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
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
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
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
        // Соединение с БД
        $db = MySQL::getConnection();

        $idS = implode(',', $array_id);

        // Получение и возврат результатов
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
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
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

        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $result->bindParam(':serial_number', $serial_number, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
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
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT
                *
                FROM gm_decompiles_parts gdp
                WHERE gdp.site_id = :site_id";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
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

        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->execute();

        // Получаем ассоциативный массив
        $status = $result->fetch();

        return $status;
    }

    public static function getExportDisassemblyByPartner($array_id, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        $idS = implode(',', $array_id);

        // Получение и возврат результатов
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
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->bindParam(':start', $start, PDO::PARAM_STR);
        $result->bindParam(':end', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    public static function getExportDisassemblyAllPartner($start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
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
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':start', $start, PDO::PARAM_STR);
        $result->bindParam(':end', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
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
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE site_gm_decompiles
            SET
                command = :command,
                command_text = :comment
            WHERE decompile_id = :decompile_id";

        // Получение и возврат результатов. Используется подготовленный запрос
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
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'DELETE gm_decompiles, gm_decompiles_parts 
                FROM gm_decompiles, gm_decompiles_parts 
                WHERE gm_decompiles.site_id = gm_decompiles_parts.site_id AND gm_decompiles.site_id = :site_id';

        // Получение и возврат результатов. Используется подготовленный запрос
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






    public static function getTestMysql($status)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT site_id FROM site_gm_decompiles WHERE status_name = '{$status}' AND site_id IS NOT NULL";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    public static function getCountTestMssql($site_id)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                sgd.site_id,
                sgd.decompile_id
                FROM site_gm_decompiles sgd
                INNER JOIN site_gm_decompiles_parts sgdp
                    ON sgd.decompile_id = sgdp.decompile_id
                WHERE sgd.site_id = :site_id";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    public static function getCountTestMysql($site_id)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "select COUNT(gd.id) as count from gm_decompiles gd
                INNER JOIN gm_decompiles_parts gdp
                    ON gd.site_id = gdp.site_id
                WHERE gd.site_id = :site_id";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

}