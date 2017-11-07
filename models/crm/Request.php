<?php

namespace Umbrella\models\crm;

use PDO;
use Umbrella\components\Db\MsSQL;

class Request
{

    /**
     * Создаем новый реквест
     * @param $options
     * @return bool
     */
    public static function addReserveOrdersMsSQL($options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO site_gm_ordering_goods '
            . '(site_account_id, part_number, goods_name, so_number, price, note, status_name, created_on, order_type_id, note1)'
            . 'VALUES '
            . '(:site_account_id, :part_number, :goods_name, :so_number, :price, :note, :status_name, :created_on, :order_type_id, :note1)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_account_id', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':goods_name', $options['goods_name'], PDO::PARAM_STR);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':note', $options['note'], PDO::PARAM_STR);
        $result->bindParam(':status_name', $options['status_name'], PDO::PARAM_STR);
        $result->bindParam(':created_on', $options['created_on'], PDO::PARAM_STR);
        $result->bindParam(':order_type_id', $options['order_type_id'], PDO::PARAM_INT);
        $result->bindParam(':note1', $options['note1'], PDO::PARAM_INT);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }


    /**
     * Создаем новый мульти-реквест
     * @param $options
     * @return int|string
     */
    public static function addMultiRequestMsSQL($options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO site_gm_ordering_goods '
            . '(site_account_id, part_number, goods_name, price, status_name, created_on, note1, number, active, period, stock_id)'
            . 'VALUES '
            . '(:site_account_id, :part_number, :goods_name, :price, :status_name, :created_on, :note1, :number, :active, :period, :stock_id)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_account_id', $options['site_account_id'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':goods_name', $options['goods_name'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':status_name', $options['status_name'], PDO::PARAM_STR);
        $result->bindParam(':note1', $options['note1'], PDO::PARAM_STR);
        $result->bindParam(':created_on', $options['created_on'], PDO::PARAM_STR);
        $result->bindParam(':number', $options['number'], PDO::PARAM_INT);
        $result->bindParam(':active', $options['active'], PDO::PARAM_INT);
        $result->bindParam(':period', $options['period'], PDO::PARAM_INT);
        $result->bindParam(':stock_id', $options['stock_id'], PDO::PARAM_INT);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }


    /**
     * Получаем список заказов для партнера со статусом (Нет в наличии, формируется поставка)
     * @param $array_id
     * @param int $completed
     * @param int $active
     * @return array
     */
    public static function getReserveOrdersByPartnerMsSQL($array_id, $completed = 0, $active = 1)
    {
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

        $sql = "SELECT
                 sgog.id,
                 sgog.site_account_id,
                 sgog.part_number,
                 sgog.goods_name,
                 sgog.so_number,
                 sgog.price,
                 sgog.note,
                 sgog.status_name,
                 sgog.created_on,
                 sgog.note1,
                 sgog.subtype_name,
                 sgog.period,
                 sgog.number,
                 sgu.site_client_name,
                 sgu.status_name as site_client_status,
                 sgot.name as type_name
             FROM site_gm_ordering_goods sgog
                 INNER JOIN site_gm_users sgu
                     ON sgog.site_account_id = sgu.site_account_id
                 LEFT JOIN site_gm_orders_types sgot
                     ON sgot.id = sgog.order_type_id
             WHERE sgog.processed = :processed
             AND sgog.active = :active
             AND sgog.site_account_id IN({$idS})
             ORDER BY sgog.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':processed', $completed, PDO::PARAM_INT);
        $result->bindParam(':active', $active, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Получаем весь список заказов со статусом (Нет в наличии, формируется поставка)
     * @param int $completed
     * @param int $active
     * @return array
     */
    public static function getAllReserveOrdersMsSQL($completed = 0, $active = 1)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                    sgog.id,
                    sgog.site_account_id,
                    sgog.part_number,
                    sgog.goods_name,
                    sgog.so_number,
                    sgog.price,
                    sgog.note,
                    sgog.status_name,
                    sgog.created_on,
                    sgog.note1,
                    sgog.subtype_name,
                    sgog.period,
                    sgog.number,
                    sgu.site_client_name,
                    sgu.status_name as site_client_status,
                    sgot.name as type_name
                FROM site_gm_ordering_goods sgog
                    INNER JOIN site_gm_users sgu
                        ON sgog.site_account_id = sgu.site_account_id
                    LEFT JOIN site_gm_orders_types sgot
                        ON sgot.id = sgog.order_type_id
                WHERE sgog.processed = :processed
                AND sgog.active = :active
                ORDER BY sgog.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':processed', $completed, PDO::PARAM_INT);
        $result->bindParam(':active', $active, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Ищем совпадение number
     * @param $number
     * @return mixed
     */
    public static function findNumber($number)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT TOP 1 number FROM site_gm_ordering_goods WHERE number = :number";

        $result = $db->prepare($sql);
        $result->bindParam(':number', $number, PDO::PARAM_INT);
        $result->execute();
        $row = $result->fetch();
        return $row;
    }


    /**
     * Генерируем уникальный number
     * @return int
     */
    public static function generateNumber()
    {
        $number = random_int(0, 999999);
        $checkNumber = self::findNumber($number);
        if($checkNumber){
            self::generateNumber();
        }
        return (int)$number;
    }


    /**
     * Формирование массива на експорт в excel
     * @param $array_id
     * @param $start
     * @param $end
     * @param int $processed
     * @return array
     */
    public static function getExportRequestsByPartners($array_id, $start, $end, $processed = 0)
    {
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

        $sql = "SELECT
                 sgog.id,
                 sgog.site_account_id,
                 sgog.part_number,
                 sgog.goods_name,
                 sgog.so_number,
                 sgog.price,
                 sgog.note,
                 sgog.status_name,
                 sgog.created_on,
                 sgog.note1,
                 sgog.subtype_name,
                 sgog.period,
                 sgog.number,
                 sgu.site_client_name,
                 sgu.status_name as site_client_status,
                 sgot.name as type_name
             FROM site_gm_ordering_goods sgog
                 INNER JOIN site_gm_users sgu
                     ON sgog.site_account_id = sgu.site_account_id
                 LEFT JOIN site_gm_orders_types sgot
                     ON sgot.id = sgog.order_type_id
             WHERE sgog.processed = :processed
             AND sgog.site_account_id IN({$idS})
             AND sgog.created_on BETWEEN :start AND :end
             ORDER BY sgog.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':start', $start, PDO::PARAM_STR);
        $result->bindParam(':end', $end, PDO::PARAM_STR);
        $result->bindParam(':processed', $processed, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Переместить в корзину
     * @param $id
     * @param $active
     * @return bool
     */
    public static function moveRequest($id, $active)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_ordering_goods
            SET
                active = :active
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':active', $active, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Вернуть с корзины в реквест с новымой атой и периодом
     * @param $id
     * @param $options
     * @return bool
     */
    public static function moveRequestInList($id, $options)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_ordering_goods
            SET
                active = :active,
                created_on = :created_on,
                period = :period
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':active', $options['active'], PDO::PARAM_INT);
        $result->bindParam(':created_on', $options['created_on'], PDO::PARAM_STR);
        $result->bindParam(':period', $options['period'], PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Возвращаем реквесты с мультиреквеста
     * @param $number
     * @return array
     */
    public static function getMultiRequestsByNumber($number)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT * FROM site_gm_ordering_goods WHERE processed = 0 AND active = 1 AND number = :number";

        $result = $db->prepare($sql);
        $result->bindParam(':number', $number, PDO::PARAM_INT);
        $result->execute();
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
}