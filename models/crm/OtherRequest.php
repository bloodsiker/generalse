<?php

namespace Umbrella\models\crm;

use PDO;
use Umbrella\components\Db\MySQL;

class OtherRequest
{

    /**
     * Добавляем новый реквест
     * @param $options
     * @return bool
     */
    public static function addRequestOrders($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_other_request '
            . '(id_user, part_number, part_description, so_number, quantity, note, address, status_name, order_type)'
            . 'VALUES '
            . '(:id_user, :part_number, :part_description, :so_number, :quantity, :note, :address, :status_name, :order_type)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':part_description', $options['part_description'], PDO::PARAM_STR);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':quantity', $options['quantity'], PDO::PARAM_INT);
        $result->bindParam(':note', $options['note'], PDO::PARAM_STR);
        $result->bindParam(':address', $options['address'], PDO::PARAM_STR);
        $result->bindParam(':status_name', $options['status_name'], PDO::PARAM_STR);
        $result->bindParam(':order_type', $options['order_type'], PDO::PARAM_STR);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }


    /**
     * Получаем список заказов в реквестах
     * @param $array_id
     * @return array
     */
    public static function getListRequest($array_id)
    {
        $db = MySQL::getConnection();

        $idS = implode(',', $array_id);

        $sql = "SELECT
                    gor.*,
                    gu.name_partner
                FROM gs_other_request gor
                    INNER JOIN gs_user gu
                        ON gor.id_user = gu.id_user
                WHERE gor.delete_r = 0
                AND gor.id_user IN({$idS})
                ORDER BY gor.id DESC";

        $result = $db->prepare($sql);
        //$result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Получаем список заказов в реквестах для админа
     * @return array
     */
    public static function getListRequestAdmin()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                    gor.*,
                    gu.name_partner
                FROM gs_other_request gor
                    INNER JOIN gs_user gu
                        ON gor.id_user = gu.id_user
                WHERE gor.delete_r = 0
                ORDER BY gor.id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * find request by id
     * @param $id
     * @return mixed
     */
    public static function getRequestById($id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                    gor.id,
                    gor.part_number,
                    gor.part_description,
                    gor.so_number,
                    gor.quantity,
                    gor.price,
                    gor.status_name,
                    gor.comment_disagree,
                    gu.name_partner,
                    gu.email
                FROM gs_other_request gor
                    INNER JOIN gs_user gu
                        ON gor.id_user = gu.id_user
                WHERE gor.id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Редактируем price
     * @param $id
     * @param $price
     * @return bool
     */
    public static function editPriceToRequestById($id, $price)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_other_request
            SET
                price = :price
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':price', $price, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Мягкое удаление
     * @param $id
     * @param $delete_r
     * @return bool
     */
    public static function deleteRequestById($id, $delete_r)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_other_request
            SET
                delete_r = :delete_r
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':delete_r', $delete_r, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Update action and status
     * @param $id
     * @param $action
     * @param $status
     * @param null $comment
     * @return bool
     */
    public static function changeActionAndStatusToRequestById($id, $action, $status, $comment = null)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_other_request
            SET
                status_name = :status_name,
                action = :action,
                comment_disagree = :comment_disagree
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':action', $action, PDO::PARAM_INT);
        $result->bindParam(':status_name', $status, PDO::PARAM_STR);
        $result->bindParam(':comment_disagree', $comment, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * @param $status
     * @return bool|string
     */
    public static function getStatusRequest($status)
    {
        switch($status)
        {
            case 'Выполненный':
                return 'green';
                break;
            case 'Отказано':
                return 'red';
                break;
            case 'Нет согласия':
                return 'red';
                break;
            case 'Отправка':
                return 'orange';
                break;
            case 'В обработке':
                return 'yellow';
                break;
            case 'Согласование':
                return 'aqua';
                break;
        }
        return true;
    }

}