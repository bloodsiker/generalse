<?php

namespace Umbrella\models\crm;

use PDO;
use Umbrella\components\Db\MsSQL;

class Returns
{

    /**
     * Получаем список всех возвратов для партнера
     * @param $id_partner
     * @return array
     */
    public static function getAllReturnsByPartner($id_partner)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT 
                 sgs.id,
                 sgs.site_id,
                 sgs.stock_return_id,
                 sgs.created_on,
                 sgs.stock_name,
                 sgs.status_name,
                 sgs.so_number,
                 sgs.update_status_from_site,
                 sgs.update_stock_from_site,
                 sgse.part_number,
                 sgse.goods_name,
                 sgse.order_number,
                 sgse.so_number
                FROM site_gm_stockreturns sgs
                INNER JOIN site_gm_stockreturns_elements sgse
                    ON sgs.stock_return_id = sgse.stock_return_id
                WHERE sgs.site_account_id = :id_user
                ORDER BY sgs.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * find return by id
     * @param $return_id
     *
     * @return array
     */
    public static function getReturnById($return_id)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT TOP 1
                 document
                FROM site_gm_stockreturns
                WHERE stock_return_id = :stock_return_id";

        $result = $db->prepare($sql);
        $result->bindParam(':stock_return_id', $return_id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     *  Обновляем статус и склад для возврата
     * @param $id_return
     * @param $stock
     * @return bool
     */
    public static function updateStatusReturns($id_return, $stock)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_stockreturns
            SET
                update_stock_from_site = :stock,
                update_status_from_site = 2
            WHERE stock_return_id = :id_return";

        $result = $db->prepare($sql);
        $result->bindParam(':id_return', $id_return, PDO::PARAM_INT);
        $result->bindParam(':stock', $stock, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Обновляем статус и склад для возврата
     *
     * @param $id_return
     * @param $stock
     * @param $note
     *
     * @return bool
     */
    public static function updateStatusAndStockReturns($id_return, $stock, $note)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_stockreturns
            SET
                stock_name = :stock,
                update_status_from_site = 2,
                note = :note
            WHERE stock_return_id = :id_return";

        $result = $db->prepare($sql);
        $result->bindParam(':id_return', $id_return, PDO::PARAM_INT);
        $result->bindParam(':stock', $stock, PDO::PARAM_INT);
        $result->bindParam(':note', $note, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Обновляем статус и склад для возврата
     * @param $options
     * @return bool
     */
    public static function updateStatusImportReturns($options)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_stockreturns
            SET
                stock_name = :stock_name,
                update_status_from_site = 2
            WHERE so_number = :so_number 
            AND site_account_id = :id_user";

        $result = $db->prepare($sql);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':stock_name', $options['stock_name'], PDO::PARAM_STR);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Поиск на существование SO number
     * @param $options
     * @return array
     */
    public static function getSoNumberByPartnerInReturn($options)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT 
                  sgse.order_number
                FROM 
                site_gm_stockreturns sgs
                    INNER JOIN site_gm_stockreturns_elements sgse
                        ON sgs.stock_return_id = sgse.stock_return_id
                WHERE sgs.update_status_from_site = 0 
                AND sgs.so_number = :so_number
                AND sgse.order_number = :order_number";

        $result = $db->prepare($sql);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_INT);
        $result->bindParam(':order_number', $options['order_number'], PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Фильтр возратов для партнера
     * @param $array_id
     * @param string $filter
     * @return array
     */
    public static function getReturnsByPartner($array_id, $filter = '')
    {
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

        $sql = "SELECT 
                 sgs.id,
                 sgs.site_id,
                 sgs.stock_return_id,
                 sgs.created_on,
                 sgs.stock_name,
                 sgs.status_name,
                 sgs.so_number,
                 sgs.update_status_from_site,
                 sgs.update_stock_from_site,
                 sgs.note,
                 sgs.document,
                 sgse.part_number,
                 sgse.goods_name,
                 sgse.order_number,
                 sgse.so_number,
                 sgse.order_type,
                 sgu.site_client_name
                FROM site_gm_stockreturns sgs
                INNER JOIN site_gm_stockreturns_elements sgse
                    ON sgs.stock_return_id = sgse.stock_return_id
                INNER JOIN site_gm_users sgu
                    ON sgs.site_account_id = sgu.site_account_id
                WHERE sgs.site_account_id IN({$idS}) {$filter}
                ORDER BY sgs.id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Фильтр возвратов для админа
     * @param string $filter
     * @return array
     */
    public static function getAllReturns($filter = '')
    {
        $db = MsSQL::getConnection();

        $data = $db->query("SELECT 
                              sgs.id,
                              sgs.site_id,
                              sgs.stock_return_id,
                              sgs.created_on,
                              sgs.site_account_id,
                              sgs.stock_name,
                              sgs.status_name,
                              sgs.so_number,
                              sgs.update_status_from_site,
                              sgs.update_stock_from_site,
                              sgs.command,
                              sgs.note,
                              sgs.document,
                              sgse.part_number,
                              sgse.goods_name,
                              sgse.order_number,
                              sgse.so_number,
                              sgse.order_type,
                              sgu.site_client_name
                             FROM site_gm_stockreturns sgs
                             INNER JOIN site_gm_stockreturns_elements sgse
                                 ON sgs.stock_return_id = sgse.stock_return_id
                             INNER JOIN site_gm_users sgu
                                    ON sgs.site_account_id = sgu.site_account_id
                             WHERE 1 = 1 {$filter}
                             ORDER BY sgs.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }


    public static function getExportReturnsByPartner($array_id, $start, $end)
    {
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

        $sql = "SELECT 
                    sgs.stock_return_id,
                    sgs.created_on,
                    sgs.stock_name,
                    sgs.status_name,
                    sgse.part_number,
                    sgse.goods_name,
                    sgse.quantity,
                    sgse.order_number,
                    sgse.so_number,
                    sgu.site_client_name
                    FROM site_gm_stockreturns sgs
                    INNER JOIN site_gm_stockreturns_elements sgse
                        ON sgs.stock_return_id = sgse.stock_return_id
                    INNER JOIN site_gm_users sgu 
                        ON sgs.site_account_id = sgu.site_account_id
                    WHERE sgs.site_account_id IN({$idS})
                    AND sgs.created_on BETWEEN :start AND :end
                    ORDER BY sgs.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':start', $start, PDO::PARAM_STR);
        $result->bindParam(':end', $end, PDO::PARAM_STR);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $start
     * @param $end
     * @return array
     */
    public static function getExportReturnsallPartner($start, $end)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT 
                    sgs.stock_return_id,
                    sgs.created_on,
                    sgs.stock_name,
                    sgs.status_name,
                    sgse.part_number,
                    sgse.goods_name,
                    sgse.quantity,
                    sgse.order_number,
                    sgse.so_number,
                    sgu.site_client_name
                FROM site_gm_stockreturns sgs
                INNER JOIN site_gm_stockreturns_elements sgse
                   ON sgs.stock_return_id = sgse.stock_return_id
                INNER JOIN site_gm_users sgu 
                    ON sgs.site_account_id = sgu.site_account_id
                WHERE sgs.created_on BETWEEN :start AND :end
                ORDER BY sgs.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':start', $start, PDO::PARAM_STR);
        $result->bindParam(':end', $end, PDO::PARAM_STR);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Attach document for return_id
     * @param $stock_return_id
     * @param $document
     *
     * @return bool
     */
    public static function attachDocumentReturnsGM($stock_return_id, $document)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_stockreturns
            SET
                document = :document
            WHERE stock_return_id = :stock_return_id";

        $result = $db->prepare($sql);
        $result->bindParam(':stock_return_id', $stock_return_id, PDO::PARAM_INT);
        $result->bindParam(':document', $document, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     *  Изменяем статус заявки
     * @param $stock_return_id
     * @param $command
     * @param $comment
     * @return bool
     */
    public static function updateStatusReturnsGM($stock_return_id, $command, $comment)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_stockreturns
            SET
                command = :command,
                command_text = :comment
            WHERE stock_return_id = :stock_return_id";

        $result = $db->prepare($sql);
        $result->bindParam(':stock_return_id', $stock_return_id, PDO::PARAM_INT);
        $result->bindParam(':command', $command, PDO::PARAM_INT);
        $result->bindParam(':comment', $comment, PDO::PARAM_STR);
        return $result->execute();
    }


    public static function getStatusRequest($status)
    {
         switch($status)
        {
            case 'Принят':
                return 'green';
                break;
            case 'Отказано':
                return 'red';
                break;
            case 'Предварительный':
                return 'yellow';
                break;
            case 'В обработке':
                return 'yellow';
                break;
        }
        return true;
    }


    /**
     * Поиск по возвратам
     * @param $search
     * @param string $filter
     * @return array
     */
    public static function getSearchInReturns($search, $filter = '')
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT 
                    sgs.*,
                    sgse.part_number,
                    sgse.goods_name,
                    sgse.order_number,
                    sgse.so_number,
                    sgse.order_type,
                    sgu.site_client_name
                FROM site_gm_stockreturns sgs
                INNER JOIN site_gm_stockreturns_elements sgse
                   ON sgs.stock_return_id = sgse.stock_return_id
                INNER JOIN site_gm_users sgu
                      ON sgs.site_account_id = sgu.site_account_id
                WHERE 1 = 1 {$filter}
                AND (sgs.stock_return_id LIKE ?
                OR sgse.part_number LIKE ?
                OR sgs.so_number LIKE ?
                OR sgse.goods_name LIKE ?
                OR sgse.order_number LIKE ?
                OR sgu.site_client_name LIKE ?)
                ORDER BY sgs.id DESC";

        $result = $db->prepare($sql);
        $result->execute(array("%$search%", "%$search%", "%$search%", "%$search%", "%$search%", "%$search%"));
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

}