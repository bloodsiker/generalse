<?php

namespace Umbrella\models\crm;

use PDO;
use Umbrella\components\Db\MsSQL;
use Umbrella\components\Db\MySQL;

class Request
{

    /**
     * Получаем весь список заказов со статусом (Нет в наличии, формируется поставка)
     * @param int $completed
     * @return array
     */
    public static function getAllReserveOrdersMsSQL($completed = 0)
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
                    sgu.site_client_name,
                    sgu.status_name as site_client_status,
                    sgot.name as type_name
                FROM site_gm_ordering_goods sgog
                    INNER JOIN site_gm_users sgu
                        ON sgog.site_account_id = sgu.site_account_id
                    LEFT JOIN site_gm_orders_types sgot
                        ON sgot.id = sgog.order_type_id
                WHERE sgog.processed = :processed
                ORDER BY sgog.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':processed', $completed, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * @param $array_id
     * @param $filter
     * @return array
     */
    public static function getCompletedRequestInOrdersByPartnerMsSQL($array_id, $filter)
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
                 sgog.created_on,
                 sgog.note1,
                 sgog.subtype_name,
                 sgu.site_client_name,
                 sgot.name as type_name,
                 sgo.order_number,
                 sgo.status_name,
                 sgo.command_text
             FROM site_gm_ordering_goods sgog
                 INNER JOIN site_gm_users sgu
                     ON sgog.site_account_id = sgu.site_account_id
                 LEFT JOIN site_gm_orders_types sgot
                     ON sgot.id = sgog.order_type_id
                 LEFT JOIN site_gm_orders sgo
                     ON sgog.id = sgo.request_id
             WHERE sgog.processed = 1
             AND sgog.site_account_id IN({$idS}) {$filter}
             ORDER BY sgog.id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Реквесты которые были выписаны
     * @param string $filter
     * @return array
     */
    public static function getAllCompletedRequestInOrdersMsSQL($filter = '')
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
                    sgu.site_client_name,
                    sgot.name as type_name,
                    sgo.order_number,
                    sgo.status_name,
                    sgo.command_text
                FROM site_gm_ordering_goods sgog
                    INNER JOIN site_gm_users sgu
                        ON sgog.site_account_id = sgu.site_account_id
                    LEFT JOIN site_gm_orders_types sgot
                        ON sgot.id = sgog.order_type_id
                    LEFT JOIN site_gm_orders sgo
                  		ON sgog.id = sgo.request_id
                WHERE sgog.processed = 1 {$filter}
                ORDER BY sgog.id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
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
     * Изменяем статус реквеста
     * @param $id
     * @param $status
     * @return bool
     */
    public static function editStatusFromCheckOrdersById($id, $status)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_ordering_goods
            SET
                status_name = :status_name
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':status_name', $status, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Возвращаем выписанные заказ в реквесты
     * @param $id
     * @return bool
     */
    public static function returnOrderToRequestById($id)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_ordering_goods
            SET
                cancel = 1
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Обновялем статус заявки
     * @param $id
     * @param $check_status
     * @return bool
     */
    public static function updateCheckReserveOrders($id, $check_status)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gm_orders_check
            SET
                check_status = :check_status
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':check_status', $check_status, PDO::PARAM_INT);
        return $result->execute();
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
        $res = $result->fetch(PDO::FETCH_ASSOC);
        return $res;
    }


    /**
     * Delete request by id
     * @param $id
     * @return bool
     */
    public static function deleteRequestById($id)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gm_orders_check WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
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
     * Delete request by id
     * @param $id
     * @return bool
     */
    public static function deleteRequestMsSQLById($id)
    {
        $db = MsSQL::getConnection();

        $sql = 'DELETE FROM site_gm_ordering_goods WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Сохраняем удаленные реквесты
     * @param $remove_request
     * @return bool
     */
    public static function addRemovedRequest($remove_request)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_orders_request_removed '
            . '(remove_request)'
            . 'VALUES '
            . '(:remove_request)';

        $result = $db->prepare($sql);
        $result->bindParam(':remove_request', $remove_request, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Список удаленных реквестов для пользователя
     * @param $array_id
     * @return array
     */
    public static function getRemovedRequestByUser($array_id)
    {
        $db = MySQL::getConnection();

        $idS = implode(',', $array_id);

        $sql = "SELECT
                gorr.remove_request,
                gu.name_partner
                FROM gm_orders_request_removed gorr
                LEFT JOIN gs_user gu
                    ON gu.id_user = JSON_EXTRACT(gorr.remove_request, '$.site_account_id')
                WHERE JSON_EXTRACT(gorr.remove_request, '$.site_account_id') IN ({$idS})";

        $result = $db->prepare($sql);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        $restore = [];
        foreach ($res as $request) {
            $restore[$i] = json_decode($request['remove_request'], true);
            $restore[$i]['name_partner'] = $request['name_partner'];
            $i++;
        }
        return $restore;
    }


    /**
     * Список всех
     * @return array
     */
    public static function getAllRemovedRequest()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                gorr.remove_request,
                gu.name_partner
                FROM gm_orders_request_removed gorr
                LEFT JOIN gs_user gu
                    ON gu.id_user = JSON_EXTRACT(gorr.remove_request, '$.site_account_id')";

        $result = $db->prepare($sql);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        $restore = [];
        foreach ($res as $request) {
            $restore[$i] = json_decode($request['remove_request'], true);
            $restore[$i]['name_partner'] = $request['name_partner'];
            $i++;
        }
        return $restore;
    }

}