<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Db\MsSQL;
use Umbrella\components\Decoder;

class Warranty
{

    /**
     * Получить все заявки
     * @param $num
     * @return array
     */
    public static function getAllRequest($num)
    {
        $db = MySQL::getConnection();

        $data = $db->query("SELECT * FROM gs_warranty gw
                              INNER JOIN gs_user gu
                                ON gw.id_user = gu.id_user
                              INNER JOIN gs_country gc
                                ON gu.id_country = gc.id_country
                            ORDER BY gw.id_warrantry DESC
                            LIMIT {$num}, 30")->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }


    /**
     * Получить заявки для одного партнера
     * @param $id_partner
     * @param $filter
     * @return array
     */
    public static function getRequestByPartner($id_partner, $filter = '')
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                  *
                FROM gs_warranty gw
                  INNER JOIN gs_user gu
                    ON gw.id_user = gu.id_user
                  INNER JOIN gs_country gc
                    ON gu.id_country = gc.id_country
                WHERE gw.id_user = :id_user {$filter}
                ORDER BY gw.id_warrantry DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Добавить новую заявку
     * @param $options
     * @return bool
     */
    public static function addWarrantyRegistration($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_warranty '
            . '(Request_Country, Request_Type, first_name, last_name, requestor_email,
                Refund_Reason, Lenovo_SO, SO_Create_Date, SN, PN_MTM, Product_Group,
                Partner_SO_RMA, Future_Unit_location, Additional_Comment, Estimated_cost, site_id, id_user)'
            . 'VALUES '
            . '(:Request_Country, :Request_Type, :first_name, :last_name, :requestor_email,
                :Refund_Reason, :Lenovo_SO, :SO_Create_Date, :SN, :PN_MTM, :Product_Group,
                :Partner_SO_RMA, :Future_Unit_location, :Additional_Comment, :Estimated_cost, :site_id, :id_user)';

        $result = $db->prepare($sql);
        $result->bindParam(':Request_Country', $options['Request_Country'], PDO::PARAM_STR);
        $result->bindParam(':Request_Type', $options['Request_Type'], PDO::PARAM_STR);
        $result->bindParam(':first_name', $options['Requestor_First_Name'], PDO::PARAM_STR);
        $result->bindParam(':last_name', $options['Requestor_Last_Name'], PDO::PARAM_STR);
        $result->bindParam(':requestor_email', $options['Requestor_Email'], PDO::PARAM_STR);
        $result->bindParam(':Refund_Reason', $options['Refund_Reason'], PDO::PARAM_STR);
        $result->bindParam(':Lenovo_SO', $options['Lenovo_SO'], PDO::PARAM_STR);
        $result->bindParam(':SO_Create_Date', $options['SO_Create_Date'], PDO::PARAM_STR);
        $result->bindParam(':SN', $options['SN'], PDO::PARAM_STR);
        $result->bindParam(':PN_MTM', $options['PN_MTM'], PDO::PARAM_STR);
        $result->bindParam(':Product_Group', $options['Product_Group'], PDO::PARAM_STR);
        $result->bindParam(':Partner_SO_RMA', $options['Partner_SO_RMA'], PDO::PARAM_STR);
        $result->bindParam(':Future_Unit_location', $options['Future_Unit_location'], PDO::PARAM_STR);
        $result->bindParam(':Additional_Comment', $options['Additional_Comment'], PDO::PARAM_STR);
        $result->bindParam(':Estimated_cost', $options['Estimated_cost'], PDO::PARAM_STR);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_STR);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_STR);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }


    /**
     * Запись в gm_manager в mssql
     *
     * @param $options
     *
     * @return int|string
     * @throws \Exception
     */
    public static function addWarrantyRegistrationMsSql($options)
    {
        $db = MsSQL::getConnection();

        $options['Lenovo_SO'] = Decoder::strToWindows($options['Lenovo_SO']);

        $sql = 'INSERT INTO dbo.site_gm_tasks '
            . '(site_id, site_account_id, so_number, mtm, serial_number, ready)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :so_number, :mtm, :serial_number, :ready)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':so_number', $options['Lenovo_SO'], PDO::PARAM_STR);
        $result->bindParam(':mtm', $options['PN_MTM'], PDO::PARAM_STR);
        $result->bindParam(':serial_number', $options['SN'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * @param $serial_number
     * @param $mtm
     * @param $site_id
     * @return int
     */
    public static function checkStatusRequest($serial_number, $mtm, $site_id)
    {
        $db = MsSQL::getConnection();

        $sql = 'SELECT id, status_name, purchase_id, writeoff_status_on, command
                FROM dbo.site_gm_tasks
                WHERE serial_number = :serial_number
                AND mtm = :mtm
                AND site_id = :site_id';

        $result = $db->prepare($sql);
        $result->bindParam(':serial_number', $serial_number, PDO::PARAM_STR);
        $result->bindParam(':mtm', $mtm, PDO::PARAM_STR);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->execute();
        $status = $result->fetch(PDO::FETCH_ASSOC);
        return $status;
    }


    /**
     * Применение фильтров к заявкам
     * @param $filter
     * @return array
     */
    public static function getFilterRequest($filter)
    {
        $db = MySQL::getConnection();

        $result = $db->query("SELECT * FROM gs_warranty gw
                                INNER JOIN gs_user gu
                                  ON gw.id_user = gu.id_user
                                INNER JOIN gs_country gc
                                  ON gu.id_country = gc.id_country
                              WHERE 1=1 {$filter}
                              ORDER BY gw.id_warrantry DESC")->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }


    public static function getStatusRequest($status)
    {
        switch($status)
        {
            case 'подтверждено':
                return 'green';
                break;
            case 'отклонено':
                return 'red';
                break;
            case 'предварительное':
                return 'yellow';
                break;
        }

        return true;
    }


    public static function getLastRequest()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT gw.site_id FROM gs_warranty gw ORDER BY gw.id_warrantry DESC LIMIT 1";

        $result = $db->prepare($sql);
        $result->execute();
        $row = $result->fetch();
        return $row['site_id'];
    }


    /**
     * Отмечаем, если отправленно в Леново
     * @param $id
     * @param $status
     * @return bool
     */
    public static function checkWarrantyById($id, $status)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_warranty
            SET
                lenovo_ok = :lenovo_ok
            WHERE id_warrantry = :id_warrantry";

        $result = $db->prepare($sql);
        $result->bindParam(':id_warrantry', $id, PDO::PARAM_INT);
        $result->bindParam(':lenovo_ok', $status, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Пишем номер с леново
     * @param $id
     * @param $lenovo_num
     * @return bool
     */
    public static function addLenovoNumWarrantyById($id, $lenovo_num)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_warranty
            SET
                lenovo_num = :lenovo_num
            WHERE id_warrantry = :id_warrantry";

        $result = $db->prepare($sql);
        $result->bindParam(':id_warrantry', $id, PDO::PARAM_INT);
        $result->bindParam(':lenovo_num', $lenovo_num, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Изменяем статус заявки
     * @param $refund_id
     * @param $command
     * @param $comment
     * @return bool
     */
    public static function updateStatusRefundGM($refund_id, $command, $comment)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_tasks
            SET
                command = :command,
                command_text = :comment
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $refund_id, PDO::PARAM_INT);
        $result->bindParam(':command', $command, PDO::PARAM_INT);
        $result->bindParam(':comment', $comment, PDO::PARAM_STR);
        return $result->execute();
    }

}