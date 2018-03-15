<?php

namespace Umbrella\models\crm;

use PDO;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Db\MsSQL;

class Supply
{

    /**
     *  Последний номер site_id
     * @return mixed
     */
    public static function getLastSupplyId()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT MAX(site_id) as site_id FROM site_gm_supplies";

        $result = $db->prepare($sql);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['site_id'];
    }


    /**
     * Пишем поставку в шапку
     * @param $options
     * @return bool
     */
    public static function addSupplyMSSQL($options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO site_gm_supplies '
            . '(site_id, site_account_id, name, expected_arriving_date, ready, tracking_number, manufacture_country, partner, paydesk_id)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :name, :expected_arriving_date, :ready, :tracking_number, :manufacture_country, :partner, :paydesk_id)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['site_account_id'], PDO::PARAM_INT);
        $result->bindParam(':name', $options['supply_name'], PDO::PARAM_STR);
        $result->bindParam(':expected_arriving_date', $options['arriving_date'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        $result->bindParam(':tracking_number', $options['tracking_number'], PDO::PARAM_STR);
        $result->bindParam(':manufacture_country', $options['manufacture_country'], PDO::PARAM_STR);
        $result->bindParam(':partner', $options['partner'], PDO::PARAM_STR);
        $result->bindParam(':paydesk_id', $options['paydesk_id'], PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * @param $site_id
     * @param $ready
     * @return bool
     */
    public static function updateReady($site_id, $ready)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_supplies
            SET
                ready = :ready
            WHERE site_id = :site_id";

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->bindParam(':ready', $ready, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * @param $options
     * @return bool
     */
    public static function addSupplyPartsMSSQL($options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO site_gm_supplies_parts '
            . '(site_id, part_number, quantity, price, so_number, tracking_number, manufacture_country, partner, manufacturer)'
            . 'VALUES '
            . '(:site_id, :part_number, :quantity, :price, :so_number, :tracking_number, :manufacture_country, :partner, :manufacturer)';

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
        $db = MsSQL::getConnection();

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

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $array_id
     * @return array
     */
    public static function getSupplyByPartner($array_id)
    {
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

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

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Получаем товары находящиеся в поставке
     * @param $site_idS
     * @return array
     */
    public static function getSupplyPartsByIdS($site_idS)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                  sgsp.id,
                  sgsp.site_id,
                  sgsp.part_number,
                  sgsp.so_number,
                  sgsp.manufacturer,
                  sgs.site_account_id
                 FROM site_gm_supplies_parts sgsp
                    INNER JOIN site_gm_supplies sgs
                        ON sgsp.site_id = sgs.site_id
                 WHERE sgsp.site_id IN ($site_idS)";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $site_id
     * @return array
     */
    public static function getShowDetailsSupply($site_id)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
				  *
                 FROM dbo.site_gm_supplies_parts
                 WHERE site_id = :site_id";

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Кол-во деталей в поставке
     * @param $site_id
     * @return array
     */
    public static function getCountDetailsInSupply($site_id)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
				  sum(quantity) as count,
                  sum(quantity_reserv) as count_reserv
                 FROM dbo.site_gm_supplies_parts
                 WHERE site_id = :site_id";

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Проверяем наличие SO_NUMBER на наличие в табице КПИ
     * @param $so_number
     * @return mixed
     */
    public static function getCountSoNumberOnKpi($so_number)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT COUNT(gk.SO_NUMBER) AS count FROM gs_kpi gk WHERE gk.SO_NUMBER = :so_number";

        $result = $db->prepare($sql);
        $result->bindParam(':so_number', $so_number, PDO::PARAM_INT);
        $result->execute();
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
        $db = MsSQL::getConnection();

        $sql = "SELECT COUNT(so_number) as count FROM site_gm_tasks WHERE so_number = :so_number AND status_name = :status";

        $result = $db->prepare($sql);
        $result->bindParam(':so_number', $so_number, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_STR);
        $result->execute();
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
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_supplies
            SET
                command = :command
            WHERE site_id = :site_id";

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
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
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_supplies_parts
            SET
                stock_name = :stock
            WHERE id = :id";

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
     * @param $site_id
     * @return mixed
     */
    public static function getInfoSupply($site_id)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
				  *
                 FROM dbo.site_gm_supplies
                 WHERE site_id = :site_id";

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Delete supply
     * @param $id
     * @return bool
     */
    public static function deleteSupplyBySiteId($id)
    {
        $db = MsSQL::getConnection();

        $sql = 'DELETE FROM site_gm_supplies WHERE site_id = :site_id';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Delete supply parts
     * @param $id
     * @return bool
     */
    public static function deleteSupplyPartsBySiteId($id)
    {
        $db = MsSQL::getConnection();

        $sql = 'DELETE FROM site_gm_supplies_parts WHERE site_id = :site_id';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Список касс
     * @return array
     */
    public static function getListPaydesk()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT id, name_external FROM Paydesk WHERE NOT name_external IS NULL";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

}