<?php

namespace Umbrella\models;

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
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
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
     *  Обновляем статус и склад для возврата
     * @param $id_return
     * @param $stock
     * @return bool
     */
    public static function updateStatusReturns($id_return, $stock)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE site_gm_stockreturns
            SET
                update_stock_from_site = :stock,
                update_status_from_site = 2
            WHERE stock_return_id = :id_return";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_return', $id_return, PDO::PARAM_INT);
        $result->bindParam(':stock', $stock, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Обновляем статус и склад для возврата
     * @param $id_return
     * @param $stock
     * @return bool
     */
    public static function updateStatusAndStockReturns($id_return, $stock)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE site_gm_stockreturns
            SET
                stock_name = :stock,
                update_status_from_site = 2
            WHERE stock_return_id = :id_return";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_return', $id_return, PDO::PARAM_INT);
        $result->bindParam(':stock', $stock, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Обновляем статус и склад для возврата
     * @param $options
     * @return bool
     */
    public static function updateStatusImportReturns($options)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE site_gm_stockreturns
            SET
                stock_name = :stock_name,
                update_status_from_site = 2
            WHERE so_number = :so_number 
            AND site_account_id = :id_user";

        // Получение и возврат результатов. Используется подготовленный запрос
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
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                  sgse.order_number
                FROM 
                site_gm_stockreturns sgs
                    INNER JOIN site_gm_stockreturns_elements sgse
                        ON sgs.stock_return_id = sgse.stock_return_id
                WHERE sgs.update_status_from_site = 0 
                AND sgs.so_number = :so_number
                AND sgse.order_number = :order_number";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_INT);
        $result->bindParam(':order_number', $options['order_number'], PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $count = $result->fetchAll(PDO::FETCH_ASSOC);
        return $count;
    }


    /**
     * Фильтр возратов для партнера
     * @param $array_id
     * @param string $filter
     * @return array
     */
    public static function getReturnsByPartner($array_id, $filter = '')
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

        // Получение и возврат результатов
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
                 sgse.so_number,
                 sgu.site_client_name
                FROM site_gm_stockreturns sgs
                INNER JOIN site_gm_stockreturns_elements sgse
                    ON sgs.stock_return_id = sgse.stock_return_id
                INNER JOIN site_gm_users sgu
                    ON sgs.site_account_id = sgu.site_account_id
                WHERE sgs.site_account_id IN({$idS}) {$filter}
                ORDER BY sgs.id DESC";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':idS', $idS, PDO::PARAM_STR);
        //print_r($sql);
        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Фильтр возвратов для админа
     * @param string $filter
     * @return array
     */
    public static function getAllReturns($filter = '')
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
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
                              sgse.part_number,
                              sgse.goods_name,
                              sgse.order_number,
                              sgse.so_number,
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
        // Соединение с БД
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

        // Получение и возврат результатов
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

    public static function getExportReturnsallPartner($start, $end)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
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
     *  Изменяем статус заявки
     * @param $stock_return_id
     * @param $command
     * @param $comment
     * @return bool
     */
    public static function updateStatusReturnsGM($stock_return_id, $command, $comment)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE site_gm_stockreturns
            SET
                command = :command,
                command_text = :comment
            WHERE stock_return_id = :stock_return_id";

        // Получение и возврат результатов. Используется подготовленный запрос
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

    public static function getIntegerStock($stock)
    {
        switch($stock)
        {
            case 'BAD':
                return  '1';
                break;
            case 'Not Used':
                return '2';
                break;
            case 'Restored':
                return '3';
                break;
            case 'Restore Bad':
                return '4';
                break;
            case 'Dismantling':
                return  '5';
                break;
        }
        return true;
    }

}