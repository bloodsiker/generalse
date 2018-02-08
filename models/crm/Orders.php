<?php

namespace Umbrella\models\crm;

use PDO;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Db\MsSQL;

class Orders
{

    /**
     * Добавляем заказы
     * @param $options
     * @return bool
     */
    public static function addOrders($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_orders '
            . '(site_id, id_user, so_number, ready, note)'
            . 'VALUES '
            . '(:site_id, :id_user, :so_number, :ready, :note)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        $result->bindParam(':note', $options['note_mysql'], PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Добавляем подробности о заказе
     * @param $options
     * @return bool
     */
    public static function addOrdersElements($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_orders_elements '
            . '(site_id, part_number, goods_name, so_number, stock_name, quantity)'
            . 'VALUES '
            . '(:site_id, :part_number, :goods_name, :so_number, :stock_name, :quantity)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':goods_name', $options['goods_mysql_name'], PDO::PARAM_STR);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':stock_name', $options['stock_name'], PDO::PARAM_INT);
        $result->bindParam(':quantity', $options['quantity'], PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Добавляем заказы  MSSQL
     * @param $options
     * @return bool
     */
    public static function addOrdersMsSQL($options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO site_gm_orders '
            . '(site_id, site_account_id, so_number, ready, note, order_type_id)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :so_number, :ready, :note, :order_type_id)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        $result->bindParam(':note', $options['note'], PDO::PARAM_STR);
        $result->bindParam(':order_type_id', $options['order_type_id'], PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Добавляем подробности о заказе MSSQL
     * @param $options
     * @param $is_supply = 1 - резервируеться из поставки
     * @return bool
     */
    public static function addOrdersElementsMsSql($options, $is_supply = 0)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO site_gm_orders_elements '
            . '(site_id, part_number, goods_name, so_number, stock_name, quantity, is_supply)'
            . 'VALUES '
            . '(:site_id, :part_number, :goods_name, :so_number, :stock_name, :quantity, :is_supply)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':goods_name', $options['goods_name'], PDO::PARAM_STR);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':stock_name', $options['stock_name'], PDO::PARAM_STR);
        $result->bindParam(':quantity', $options['quantity'], PDO::PARAM_INT);
        $result->bindParam(':is_supply', $is_supply, PDO::PARAM_INT);

        return $result->execute();
    }


    /**
     * Получаем список типоп гарантии для заказов
     * @return array
     */
    public static function getAllOrderTypes()
    {
        $db = MsSQL::getConnection();
        $sql = "SELECT * FROM site_gm_orders_types";
        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
	
	/**
     * Получить список всех заказов MSSQL
     * @param string $filter
     * @return array
     */
    public static function getAllOrdersMsSql($filter = '')
    {
        $db = MsSQL::getConnection();

        $data = $db->query("SELECT
                              sgo.*,
                              sgu.site_client_name,
                              sgu.status_name as site_client_status,
                              sgot.name as type_name
                            FROM site_gm_orders sgo
                              INNER JOIN site_gm_users sgu
                                ON sgo.site_account_id = sgu.site_account_id
                              LEFT JOIN site_gm_orders_types sgot
                                ON sgot.id = sgo.order_type_id
                              WHERE 1 = 1 {$filter}
                              ORDER BY sgo.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }


    /**
     * Поиск по заказам
     * @param $search
     * @param $filter
     * @return array
     */
    public static function getSearchInOrders($search, $filter = '')
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                   sgo.*,
                   sgu.site_client_name,
                   sgu.status_name as site_client_status,
                   sgot.name as type_name,
                   sgoe.part_number,
                   sgoe.goods_name
                FROM site_gm_orders sgo
                   INNER JOIN site_gm_users sgu
                     ON sgo.site_account_id = sgu.site_account_id
                   INNER JOIN site_gm_orders_elements sgoe
                    ON sgo.order_id = sgoe.order_id
                   LEFT JOIN site_gm_orders_types sgot
                     ON sgot.id = sgo.order_type_id
                WHERE 1 = 1 {$filter}
                AND (sgo.order_number LIKE ?
                OR sgoe.so_number LIKE ?
                OR sgoe.part_number LIKE ?
                OR sgu.site_client_name LIKE ?
                OR sgo.request_id LIKE ?)
                ORDER BY sgo.id DESC";

        $result = $db->prepare($sql);
        $result->execute(array("%$search%", "%$search%", "%$search%", "%$search%", "%$search%"));
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

	
	/** 
     * Просмотр заказов для партнеров MsSql
     * @param $array_id
     * @param string $filter
     * @return array
     */
    public static function getOrdersByPartnerMsSql($array_id, $filter = '')
    {
        $db = MsSQL::getConnection();
        $idS = implode(',', $array_id);
        $sql = "SELECT
                   sgo.*,
                   sgu.site_client_name,
                   sgu.status_name as site_client_status,
                   sgot.name as type_name
                 FROM site_gm_orders sgo
                   INNER JOIN site_gm_users sgu
                      ON sgo.site_account_id = sgu.site_account_id
                    LEFT JOIN site_gm_orders_types sgot
                      ON sgot.id = sgo.order_type_id
                   WHERE sgo.site_account_id IN({$idS}) {$filter}
                   ORDER BY sgo.id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
	


    /**
     * Получаем последний ID покупки
     * @return mixed
     */
    public static function getLastOrdersId()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT MAX(site_id) as site_id FROM site_gm_orders";

        $result = $db->prepare($sql);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['site_id'];
    }


    /**
     * Получить список покупок в модальном окне
     *
     * @param $order_id
     * @param string $table
     *
     * @param string $attr
     *
     * @return array
     */
    public static function getShowDetailsOrdersMsSql($order_id, $table = 'site_gm_orders_elements', $attr = 'fetchAll')
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                 *
                 FROM {$table}
                 WHERE order_id = :order_id";

        $result = $db->prepare($sql);
        $result->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $result->execute();
        return $result->{$attr}(PDO::FETCH_ASSOC);
    }


    /**
     * @param $array_id
     * @param $start
     * @param $end
     * @param string $filter
     * @return array
     */
    public static function getExportOrdersByPartner($array_id, $start, $end, $filter = null)
    {
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

        $sql = "SELECT 
                    sgo.order_id,
                    sgo.order_number,
                    sgo.so_number,
                    sgo.status_name,
                    sgo.created_on,
                    sgo.shipped_on,
                    sgo.request_date,
                    sgo.note1,
                    sgo.request_id,
                    sgoe.part_number,
                    sgoe.goods_name,
                    sgoe.stock_name,
                    sgoe.quantity,
                    sgoe.price,
                    sgu.site_client_name,
                    sgu.status_name as site_client_status,
                    sgot.name as type_name
                    FROM site_gm_orders sgo
                    INNER JOIN site_gm_orders_elements sgoe
                        ON sgo.order_id = sgoe.order_id
                    INNER JOIN site_gm_users sgu 
                        ON sgo.site_account_id = sgu.site_account_id
                    LEFT JOIN site_gm_orders_types sgot
                        ON sgot.id = sgo.order_type_id
                    WHERE sgo.site_account_id IN({$idS}) {$filter}
                    ORDER BY sgo.id DESC";

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
    public static function getExportOrdersAllPartner($start, $end)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT 
                    sgo.order_id,
                    sgo.order_number,
                    sgo.so_number,
                    sgo.status_name,
                    sgo.created_on,
                    sgo.shipped_on,
                    sgo.request_date,
                    sgo.note1,
                    sgoe.part_number,
                    sgoe.goods_name,
                    sgoe.stock_name,
                    sgoe.quantity,
                    sgoe.price,
                    sgu.site_client_name,
                    sgot.name as type_name
                FROM site_gm_orders sgo
                INNER JOIN site_gm_orders_elements sgoe
                   ON sgo.order_id = sgoe.order_id
                INNER JOIN site_gm_users sgu 
                    ON sgo.site_account_id = sgu.site_account_id
                LEFT JOIN site_gm_orders_types sgot
                    ON sgot.id = sgo.order_type_id
                WHERE sgo.created_on BETWEEN :start AND :end
                ORDER BY sgo.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':start', $start, PDO::PARAM_STR);
        $result->bindParam(':end', $end, PDO::PARAM_STR);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Изменяем статус заявки
     * @param $id_order
     * @param $command
     * @param $comment
     * @return bool
     */
    public static function updateStatusOrders($id_order, $command, $comment)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_orders
            SET
                command = :command,
                command_text = :comment
            WHERE order_id = :id_order";

        $result = $db->prepare($sql);
        $result->bindParam(':id_order', $id_order, PDO::PARAM_INT);
        $result->bindParam(':command', $command, PDO::PARAM_INT);
        $result->bindParam(':comment', $comment, PDO::PARAM_STR);
        return $result->execute();
    }


    public static function getStatusRequest($status)
    {
        switch($status)
        {
            case 'Выдан':
                return 'green';
                break;
            case 'Отказано':
                return 'red';
                break;
            case 'Предварительный':
                return 'orange';
                break;
            case 'В обработке':
                return 'yellow';
                break;
            case 'Резерв':
                return 'aqua';
                break;
        }
        return true;
    }


    /**
     * Добавляем заказ со статусом (Нет в наличии, формируется поставка),
     * и каждый час проверяем эту заявку на наличие на складах и в поставках
     * @param $options
     * @return bool
     */
    public static function addReserveOrders($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_orders_check '
            . '(id_user, request_id, part_number, so_number, price, note, note1, status_name, order_type_id)'
            . 'VALUES '
            . '(:id_user, :request_id, :part_number, :so_number, :price, :note, :note1, :status_name, :order_type_id)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':request_id', $options['request_id'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':note', $options['note_mysql'], PDO::PARAM_STR);
        $result->bindParam(':note1', $options['note1_mysql'], PDO::PARAM_STR);
        $result->bindParam(':status_name', $options['status_name_mysql'], PDO::PARAM_STR);
        $result->bindParam(':order_type_id', $options['order_type_id'], PDO::PARAM_INT);
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