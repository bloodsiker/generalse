<?php

namespace Umbrella\models;

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
            . '(site_id, site_account_id, so_number, ready, note)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :so_number, :ready, :note)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        $result->bindParam(':note', $options['note'], PDO::PARAM_STR);

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
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * Получить список всех заказов
     * @param string $filter
     * @return array
     */
    public static function getAllOrders($filter = '')
    {
        $db = MySQL::getConnection();

        $data = $db->query("SELECT
                              go.id,
                              go.site_id,
                              go.id_user,
                              go.so_number,
                              go.date_create,
                              gu.name_partner
                            FROM gm_orders go
                              INNER JOIN gs_user gu
                                ON go.id_user = gu.id_user
                              WHERE 1 = 1 {$filter}
                              ORDER BY go.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        return $data;
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
                              sgo.site_id,
                              sgo.order_id,
                              sgo.order_number,
                              sgo.site_account_id,
                              sgo.so_number,
                              sgo.status_name,
                              sgo.created_on,
                              sgo.shipped_on,
                              sgo.command,
                              sgo.note,
                              sgo.note1,
                              sgo.command_text,
                              sgo.request_id,
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
     * Просмотр заказов для партнеров
     * @param $id_partner
     * @param string $filter
     * @return array
     */
    public static function getOrdersByPartner($id_partner, $filter = '')
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                  go.id,
                  go.site_id,
                  go.id_user,
                  go.date_create
                FROM gm_orders go
                  WHERE go.id_user = :id_user {$filter}
                  ORDER BY go.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
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
                   sgo.site_id,
                   sgo.order_id,
                   sgo.order_number,
                   sgo.site_account_id,
                   sgo.so_number,
                   sgo.status_name,
                   sgo.created_on,
                   sgo.shipped_on,
                   sgo.note,
                   sgo.note1,
                   sgo.command_text,
                   sgo.request_id,
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
        //$result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }
	


    /**
     * Получаем последний ID покупки
     * @return mixed
     */
    public static function getLastOrdersId()
    {
        $db = MsSQL::getConnection();

        //$sql = "SELECT site_id FROM site_gm_orders WHERE site_id = (SELECT MAX(site_id) FROM site_gm_orders)";
        $sql = "SELECT MAX(site_id) as site_id FROM site_gm_orders";

        $result = $db->prepare($sql);
        $result->execute();
        $row = $result->fetch();
        return $row['site_id'];
    }


    /**
     * Получить детальный список заказов
     * @param $site_id
     * @return array
     */
    public static function getShowDetailsOrders($site_id)
    {
        $db = MySQL::getConnection();
        $sql = "SELECT
                *
                FROM gm_orders_elements
                WHERE site_id = :site_id";

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }
	
	/**
     * Получить список покупок в модальном окне
     * @param $order_id
     * @return array
     */
    public static function getShowDetailsOrdersMsSql($order_id)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                 *
                 FROM site_gm_orders_elements
                 WHERE order_id = :order_id";

        $result = $db->prepare($sql);
        $result->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
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
                    WHERE sgo.site_account_id IN({$idS})
                    AND sgo.created_on BETWEEN :start AND :end {$filter}
                    ORDER BY sgo.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':start', $start, PDO::PARAM_STR);
        $result->bindParam(':end', $end, PDO::PARAM_STR);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
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
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
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
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gm_orders_check '
            . '(id_user, request_id, part_number, so_number, price, note, note1, status_name, order_type_id)'
            . 'VALUES '
            . '(:id_user, :request_id, :part_number, :so_number, :price, :note, :note1, :status_name, :order_type_id)';

        // Получение и возврат результатов. Используется подготовленный запрос
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
     * Получаем список заказов для партнера со статусом (Нет в наличии, формируется поставка)
     * @param $id_user
     * @return array
     */
    public static function getReserveOrdersByPartner($id_user)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                *
                FROM gm_orders_check
                WHERE check_status = 0
                AND id_user = :id_user
                ORDER BY id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Получаем список заказов для партнера со статусом (Нет в наличии, формируется поставка)
     * @param $array_id
     * @param $completed
     * @return array
     */
    public static function getReserveOrdersByPartnerMsSQL($array_id, $completed = 0)
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
             ORDER BY sgog.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':processed', $completed, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Получаем весь список заказов со статусом (Нет в наличии, формируется поставка)
     * @return array
     */
    public static function getAllReserveOrders()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                 goc.id,
                 goc.id_user,
                 goc.part_number,
                 goc.part_description,
                 goc.so_number,
                 goc.price,
                 goc.note,
                 goc.status_name,
                 goc.date_create,
                 gu.name_partner
                 FROM gm_orders_check goc
                 INNER JOIN gs_user gu
                    ON goc.id_user = gu.id_user
                 WHERE goc.check_status = 0
                 ORDER BY goc.id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


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
}