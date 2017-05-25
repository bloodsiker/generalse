<?php

class Supply
{

    /**
     *  Последний номер site_id
     * @return mixed
     */
    public static function getLastSupplyId()
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        //$sql = "SELECT site_id FROM gm_purchases ORDER BY id DESC LIMIT 1";
        $sql = "SELECT site_id FROM site_gm_supplies WHERE site_id = (SELECT MAX(site_id) FROM site_gm_supplies)";

        // Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetch();
        return $row['site_id'];
    }


    /**
     * Пишем поставку в шапку
     * @param $options
     * @return bool
     */
    public static function addSupplyMSSQL($options)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO site_gm_supplies '
            . '(site_id, site_account_id, name, expected_arriving_date, ready, tracking_number, manufacture_country, partner)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :name, :expected_arriving_date, :ready, :tracking_number, :manufacture_country, :partner)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['site_account_id'], PDO::PARAM_INT);
        $result->bindParam(':name', $options['supply_name'], PDO::PARAM_STR);
        $result->bindParam(':expected_arriving_date', $options['arriving_date'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        $result->bindParam(':tracking_number', $options['tracking_number'], PDO::PARAM_STR);
        $result->bindParam(':manufacture_country', $options['manufacture_country'], PDO::PARAM_STR);
        $result->bindParam(':partner', $options['partner'], PDO::PARAM_STR);

        return $result->execute();
    }

    public static function addSupplyPartsMSSQL($options)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO site_gm_supplies_parts '
            . '(site_id, part_number, quantity, price, so_number, tracking_number, manufacture_country, partner, manufacturer)'
            . 'VALUES '
            . '(:site_id, :part_number, :quantity, :price, :so_number, :tracking_number, :manufacture_country, :partner, :manufacturer)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':quantity', $options['quantity'], PDO::PARAM_INT);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':tracking_number', $options['tracking_number'], PDO::PARAM_STR);
        $result->bindParam(':manufacture_country', $options['manufacture_country'], PDO::PARAM_STR);
        $result->bindParam(':partner', $options['partner'], PDO::PARAM_STR);
        $result->bindParam(':manufacturer', $options['manufacturer'], PDO::PARAM_STR);

        return $result->execute();
    }


    /**
     * Получаем все поставки
     * @return array
     */
    public static function getAllSupply()
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                  sgs.site_id,
                  sgs.supply_id,
                  sgs.site_account_id,
                  sgs.name,
                  sgs.expected_arriving_date,
                  sgs.command,
                  sgs.status_name,
                  sgu.site_client_name
                 FROM site_gm_supplies sgs
                 INNER JOIN site_gm_users sgu
                     ON sgs.site_account_id = sgu.site_account_id
                 ORDER BY sgs.id DESC";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':type_row', $type_row, PDO::PARAM_STR);
        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * @param $array_id
     * @return array
     */
    public static function getSupplyByPartner($array_id)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

        // Получение и возврат результатов
        $sql = "SELECT 
                    sgs.site_id,
                    sgs.supply_id,
                    sgs.site_account_id,
                    sgs.name,
                    sgs.expected_arriving_date,
                    sgs.command,
                    sgs.status_name
                FROM site_gm_supplies sgs
                WHERE sgs.site_account_id IN({$idS})
                ORDER BY sgs.id DESC";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * Получаем товары находящиеся в поставке
     * @param $site_idS
     * @return array
     */
    public static function getSupplyPartsByIdS($site_idS)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT
                    id,
                    site_id,
                    part_number,
                    so_number,
                    manufacturer
                FROM site_gm_supplies_parts 
                where site_id IN ($site_idS)";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':site_id', $site_id, PDO::PARAM_STR);
        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * @param $site_id
     * @return array
     */
    public static function getShowDetailsSupply($site_id)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT
				  *
                 FROM dbo.site_gm_supplies_parts
                 WHERE site_id = :site_id";
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
     * Получаем id партнера по имени
     * @param $name_partner
     * @return mixed
     */
    public static function getUsersIdByName($name_partner)
    {
        // Соединение с базой данных
        $db = MySQL::getConnection();

        // Делаем запрос к базе данных
        $sql = "SELECT id_user FROM gs_user WHERE name_partner = :name_partner";

        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':name_partner', $name_partner, PDO::PARAM_INT);
        $result->execute();

        // Получаем ассоциативный массив
        $user = $result->fetch(PDO::FETCH_ASSOC);
        return $user['id_user'];
    }

    /**
     * Проверяем наличие SO_NUMBER на наличие в табице КПИ
     * @param $so_number
     * @return mixed
     */
    public static function getCountSoNumberOnKpi($so_number)
    {
        // Соединение с базой данных
        $db = MySQL::getConnection();

        // Делаем запрос к базе данных
        $sql = "SELECT COUNT(gk.SO_NUMBER) AS count FROM gs_kpi gk WHERE gk.SO_NUMBER = :so_number";

        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':so_number', $so_number, PDO::PARAM_INT);
        $result->execute();

        // Получаем ассоциативный массив
        $data = $result->fetch(PDO::FETCH_ASSOC);
        return $data['count'];
    }

    /**
     * Проверяем наличие SO_NUMBER на наличие в табице Refund Request со статусом = 'подтверждено'
     * @param $so_number
     * @param $status
     * @return mixed
     */
    public static function getCountSoNumberOnRefund($so_number, $status)
    {
        // Соединение с базой данных
        $db = MsSQL::getConnection();

        // Делаем запрос к базе данных
        $sql = "SELECT COUNT(so_number) as count FROM site_gm_tasks WHERE so_number = :so_number AND status_name = :status";
        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':so_number', $so_number, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
        $data = $result->fetch(PDO::FETCH_ASSOC);
        return $data['count'];
    }


    /**
     * Обновляем склад и команду check
     * @param $site_id
     * @param $command
     * @return bool
     */
    public static function updateCommand($site_id, $command)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE site_gm_supplies
            SET
                command = :command
            WHERE site_id IN ($site_id)";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->bindParam(':command', $command, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * @param $id
     * @param $stock
     * @return bool
     */
    public static function updateStock($id, $stock)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE site_gm_supplies_parts
            SET
                stock_name = :stock
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':stock', $stock, PDO::PARAM_STR);
        return $result->execute();
    }

    public static function getStatusSupply($status)
    {
        switch($status)
        {
            case 'Подтверждена':
                return 'green';
                break;
            case 'Отказано':
                return 'red';
                break;
            case 'Предварительная':
                return 'yellow';
                break;
            case 'предварительная':
                return 'yellow';
                break;
            case 'В обработке':
                return 'yellow';
                break;
        }

        return true;
    }

    /**
     * Проверяем наличие парт номера в поставках, возвращаем id_supply
     * @param $id_user
     * @param $part_number
     * @param $status
     * @return array
     */
    public static function checkPartNumberInSupply($id_user, $part_number, $status)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT
                *
                FROM site_gm_supplies sgs
                    INNER JOIN site_gm_supplies_parts sgsp
                        ON sgs.site_id = sgsp.site_id
                WHERE sgs.site_account_id = :id_user
                AND sgsp.part_number = :part_number
                AND sgs.status_name != :status
                ORDER BY sgsp.id DESC";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetch(PDO::FETCH_ASSOC);
        return $all['supply_id'];
    }
}