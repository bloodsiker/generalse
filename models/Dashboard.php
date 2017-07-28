<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Db\MsSQL;

class Dashboard
{
    /**
     * Кол-во заявок за последние 7 дней со статусом Выполнено
     * @param $id_user
     * @param $status
     * @return mixed
     */
    public static function countSuccessPurchaseRequest($id_user, $status)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                count(*) as count 
                FROM site_gm_purchases 
                WHERE site_account_id = :id_user
                AND status_name = :status_name
                AND created_on >= DATEADD(day, -7, GETDATE())";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':status_name', $status, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $count = $result->fetch(PDO::FETCH_ASSOC);
        return $count['count'];
    }

    /**
     * Кол-во всез запросов за все время
     * @param $id_user
     * @return mixed
     */
    public static function countAllPurchaseRequest($id_user)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                count(*) as count 
                FROM site_gm_purchases 
                WHERE site_account_id = :id_user
                AND status_name IS NOT NULL";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $count = $result->fetch(PDO::FETCH_ASSOC);
        return $count['count'];
    }

    /**
     * Кол-во заявок за последние 7 дней со статусом Выполнено
     * @param $id_user
     * @param $status_name
     * @return mixed
     */
    public static function countSuccessOrdersRequest($id_user, $status_name)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                count(*) as count 
                FROM site_gm_orders 
                WHERE site_account_id = :id_user
                AND status_name = :status_name
                AND created_on >= DATEADD(day, -7, GETDATE())";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':status_name', $status_name, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $count = $result->fetch(PDO::FETCH_ASSOC);
        return $count['count'];
    }

    /**
     * Кол-во всез запросов за все время
     * @param $id_user
     * @return mixed
     */
    public static function countAllOrdersRequest($id_user)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                count(*) as count 
                FROM site_gm_orders 
                WHERE site_account_id = :id_user";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $count = $result->fetch(PDO::FETCH_ASSOC);
        return $count['count'];
    }

    /**
     * Кол-во заявок за последние 7 дней со статусом Выполнено
     * @param $id_user
     * @param $status_name
     * @return mixed
     */
    public static function countSuccessReturnsRequest($id_user, $status_name)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                count(*) as count 
                FROM site_gm_stockreturns 
                WHERE site_account_id = :id_user
                AND status_name = :status_name
                AND created_on >= DATEADD(day, -7, GETDATE())";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':status_name', $status_name, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $count = $result->fetch(PDO::FETCH_ASSOC);
        return $count['count'];
    }

    /**
     * Кол-во всез запросов за все время
     * @param $id_user
     * @return mixed
     */
    public static function countAllReturnsRequest($id_user)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                count(*) as count 
                FROM site_gm_stockreturns 
                WHERE site_account_id = :id_user
                AND created_on >= DATEADD(day, -7, GETDATE())";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $count = $result->fetch(PDO::FETCH_ASSOC);
        return $count['count'];
    }

    /**
     * Кол-во заявок за последние 7 дней со статусом Выполнено
     * @param $id_user
     * @return mixed
     */
    public static function countAllDecompilesRequest($id_user)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                count(*) as count 
                FROM site_gm_stockreturns 
                WHERE site_account_id = :id_user";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $count = $result->fetch(PDO::FETCH_ASSOC);
        return $count['count'];
    }

    /**
     * Кол-во всез запросов за все время
     * @param $id_user
     * @param $status_name
     * @return mixed
     */
    public static function countSuccessDecompilesRequest($id_user, $status_name)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                count(*) as count 
                FROM site_gm_stockreturns 
                WHERE site_account_id = :id_user
                AND status_name = :status_name";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':status_name', $status_name, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $count = $result->fetch(PDO::FETCH_ASSOC);
        return $count['count'];
    }

    /**
     * Кол-во заявок за последние 7 дней со статусом подтверждено
     * @param $id_user
     * @param $status_name
     * @return mixed
     */
    public static function countSuccessRefundRequest($id_user, $status_name)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                 count(*) as count 
                 FROM site_gm_tasks 
                 WHERE site_account_id = :id_user
                 AND status_name = :status_name
                 AND created_on >= DATEADD(day, -7, GETDATE())";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':status_name', $status_name, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $count = $result->fetch(PDO::FETCH_ASSOC);
        return $count['count'];
    }

    /**
     * Кол-во всех запросов за все время
     * @param $id_user
     * @return mixed
     */
    public static function countAllRefundRequest($id_user)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                 count(*) as count 
                 FROM site_gm_tasks 
                 WHERE site_account_id = :id_user";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $count = $result->fetch(PDO::FETCH_ASSOC);
        return $count['count'];
    }

    /**
     * Получаем список заказчиков
     * @return array
     */
    public static function getAllCustomer()
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT * FROM gs_customer";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }
}