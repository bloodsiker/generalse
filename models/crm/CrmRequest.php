<?php

namespace Umbrella\models\crm;

use PDO;
use Umbrella\components\Db\MsSQL;
use Umbrella\components\Db\MySQL;

class CrmRequest
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
            . '(site_account_id, part_number, goods_name, so_number, price, note, status_name, created_on, 
                order_type_id, note1, created_by, expected_date, is_npc)'
            . 'VALUES '
            . '(:site_account_id, :part_number, :goods_name, :so_number, :price, :note, :status_name, :created_on, 
                :order_type_id, :note1, :created_by, :expected_date, :is_npc)';

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
        $result->bindParam(':created_by', $options['created_by'], PDO::PARAM_INT);
        $result->bindParam(':expected_date', $options['expected_date'], PDO::PARAM_STR);
        $result->bindParam(':is_npc', $options['is_npc'], PDO::PARAM_INT);

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
            . '(site_account_id, part_number, goods_name, price, status_name, created_on, note1, used, number, active, period, stock_id, created_by)'
            . 'VALUES '
            . '(:site_account_id, :part_number, :goods_name, :price, :status_name, :created_on, :note1, :used, :number, :active, :period, :stock_id, :created_by)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_account_id', $options['site_account_id'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':goods_name', $options['goods_name'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':status_name', $options['status_name'], PDO::PARAM_STR);
        $result->bindParam(':note1', $options['note1'], PDO::PARAM_STR);
        $result->bindParam(':created_on', $options['created_on'], PDO::PARAM_STR);
        $result->bindParam(':used', $options['used'], PDO::PARAM_STR);
        $result->bindParam(':number', $options['number'], PDO::PARAM_INT);
        $result->bindParam(':active', $options['active'], PDO::PARAM_INT);
        $result->bindParam(':period', $options['period'], PDO::PARAM_INT);
        $result->bindParam(':stock_id', $options['stock_id'], PDO::PARAM_INT);
        $result->bindParam(':created_by', $options['created_by'], PDO::PARAM_INT);

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
                 sgog.expected_date,
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
        return $result->fetchAll(PDO::FETCH_ASSOC);
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
                    sgog.expected_date,
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
        return $result->fetchAll(PDO::FETCH_ASSOC);
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
        return $result->fetch(PDO::FETCH_ASSOC);
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
     *
     * @param $array_id
     * @param $start
     * @param $end
     * @param $filter
     *
     * @return array
     */
    public static function getExportRequestsByPartners($array_id, $start, $end, $filter)
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
                 sgog.processed,
                 sgog.note1,
                 sgog.subtype_name,
                 sgog.period,
                 sgog.number,
                 sgog.expected_date,
                 sgu.site_client_name,
                 sgu.status_name as site_client_status,
                 sgot.name as type_name
             FROM site_gm_ordering_goods sgog
                 INNER JOIN site_gm_users sgu
                     ON sgog.site_account_id = sgu.site_account_id
                 LEFT JOIN site_gm_orders_types sgot
                     ON sgot.id = sgog.order_type_id
             WHERE sgog.active = 1 
             {$filter}
             AND sgog.site_account_id IN({$idS})
             AND sgog.created_on BETWEEN :start AND :end
             ORDER BY sgog.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':start', $start, PDO::PARAM_STR);
        $result->bindParam(':end', $end, PDO::PARAM_STR);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
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
     * add mysql deleted request_id
     * @param $user_id
     * @param $request_id
     *
     * @return bool
     */
    public static function deleteRequestMySQL($user_id, $request_id)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_request_deleted '
            . '(user_id, request_id)'
            . 'VALUES '
            . '(:user_id, :request_id)';

        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->bindParam(':request_id', $request_id, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Список удаленных реквестов из mysql
     *
     * @return array
     */
    public static function getDeletedRequestMySQL()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                grd.*,
                gu.name_partner 
                FROM gm_request_deleted grd
                  LEFT JOIN gs_user gu 
                    ON grd.user_id = gu.id_user
                WHERE grd.restore = 0 
                ORDER BY grd.id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $request_id
     * @param $restore
     *
     * @return bool
     */
    public static function restoreRequestMySQL($request_id, $restore)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gm_request_deleted
            SET
                restore = :restore
            WHERE request_id = :request_id";

        $result = $db->prepare($sql);
        $result->bindParam(':request_id', $request_id, PDO::PARAM_INT);
        $result->bindParam(':restore', $restore, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Вернуть с корзины в реквест с новымой датой и периодом
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
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Информация о реквесте
     * @param $id_order
     * @return mixed
     */
    public static function getOrderRequestInfo($id_order)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                *
                FROM site_gm_ordering_goods
                WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id_order, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Изменяем статус реквеста
     * @param $id
     * @param $status
     * @return bool
     */
    public static function editStatusFromCheckOrdersById($id, $status, $expected_date)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_ordering_goods
            SET
                status_name = :status_name,
                expected_date = :expected_date
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':status_name', $status, PDO::PARAM_STR);
        $result->bindParam(':expected_date', $expected_date, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Чистим описание детали
     * @param $id
     * @param $goods_name
     * @return bool
     */
    public static function clearGoodsNameFromCheckOrdersById($id, $goods_name)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_ordering_goods
            SET
                goods_name = :goods_name
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':goods_name', $goods_name, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Редактируем so номер в таблице резерва
     * @param $id
     * @param $so_number
     * @return bool
     */
    public static function editSoNumberFromCheckOrdersById($id, $so_number)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_ordering_goods
            SET
                so_number = :so_number
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':so_number', $so_number, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Редактируем парт номер в таблице резерва
     * @param $id
     * @param $part_number
     * @return bool
     */
    public static function editPartNumberFromCheckOrdersById($id, $part_number)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_ordering_goods
            SET
                part_number = :part_number
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_STR);
        return $result->execute();
    }
}