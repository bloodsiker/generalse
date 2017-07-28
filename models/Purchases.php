<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Db\MsSQL;

class Purchases
{

    /** Добавляем новую покупку в шапку
     * @param $options
     * @return bool
     */
    public static function addPurchases($options)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gm_purchases '
            . '(site_id, id_user, stock_name, so_number, ready, file_attache)'
            . 'VALUES '
            . '(:site_id, :id_user, :stock_name, :so_number, :ready, :file_attache)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':stock_name', $options['stock_name'], PDO::PARAM_STR);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        $result->bindParam(':file_attache', $options['file_attache'], PDO::PARAM_STR);

        return $result->execute();
    }


    /**
     * Добавляем новую покупкю в покупки
     * @param $options
     * @return bool
     */
    public static function addPurchasesElements($options)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gm_purchases_elements '
            . '(site_id, part_number, goods_name, quantity, price, so_number)'
            . 'VALUES '
            . '(:site_id, :part_number, :goods_name, :quantity, :price, :so_number)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':goods_name', $options['goods_name'], PDO::PARAM_STR);
        $result->bindParam(':quantity', $options['quantity'], PDO::PARAM_INT);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);

        return $result->execute();
    }


    /**
     * Добавляем новую покупку в шапку MsSQL
     * @param $options
     * @return bool
     */
    public static function addPurchasesMsSQL($options)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO dbo.site_gm_purchases '
            . '(site_id, site_account_id, stock_name, so_number, ready)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :stock_name, :so_number, :ready)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':stock_name', $options['stock_name'], PDO::PARAM_STR);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);

        return $result->execute();
    }


    /**
     * добавляем новую покупкю в покупки MsSQL
     * @param $options
     * @return bool
     */
    public static function addPurchasesElementsMsSQL($options)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO dbo.site_gm_purchases_elements '
            . '(site_id, part_number, quantity, price, so_number)'
            . 'VALUES '
            . '(:site_id, :part_number, :quantity, :price, :so_number)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':quantity', $options['quantity'], PDO::PARAM_INT);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);

        return $result->execute();
    }


    /**
     * Получаем последний ID покупки
     * @return mixed
     */
    public static function getLastPurchasesId()
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        //$sql = "SELECT site_id FROM gm_purchases ORDER BY id DESC LIMIT 1";
		$sql = "SELECT site_id FROM site_gm_purchases WHERE site_id = (SELECT MAX(site_id) FROM site_gm_purchases)";

        // Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetch();
        return $row['site_id'];
    }


    /**
     * Получить весь список покупок
     * @param $filter
     * @return array
     */
    public static function getAllPurchases($filter = '')
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $data = $db->query("SELECT
                              gp.id,
                              gp.site_id,
                              gp.id_user,
                              gp.stock_name,
                              gp.file_attache,
                              gp.date_create,
                              gu.name_partner
                            FROM gm_purchases gp
                              INNER JOIN gs_user gu
                                ON gp.id_user = gu.id_user
                              WHERE 1 = 1 {$filter}
                              ORDER BY gp.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
	
	/**
     * Получить весь список покупок MsSql
     * @param string $filter
     * @return array
     */
    public static function getAllPurchasesMsSql($filter = '')
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $data = $db->query("SELECT
                              sgp.site_id,
                              sgp.purchase_id,
                              sgp.site_account_id,
                              sgp.stock_name,
                              sgp.created_on,
                              sgp.status_name,
                              sgu.site_client_name
                            FROM site_gm_purchases sgp
                              INNER JOIN site_gm_users sgu
                                ON sgp.site_account_id = sgu.site_account_id
                              WHERE 1 = 1 {$filter}
                              ORDER BY sgp.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }


    /**
     * Список покупок для партнера
     * @param $id_partner
     * @param $filter
     * @return array
     */
    public static function getPurchasesByPartner($id_partner, $filter = '')
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT
                  gp.id,
                  gp.site_id,
                  gp.id_user,
                  gp.stock_name,
                  gp.file_attache,
                  gp.date_create
                FROM gm_purchases gp
                  WHERE gp.id_user = :id_user {$filter}
                  ORDER BY gp.id DESC";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }
	
	
	/**
     * Список покупок для партнера
     * @param $array_id
     * @param $filter
     * @return array
     */
    public static function getPurchasesByPartnerMsSql($array_id, $filter = '')
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

        // Получение и возврат результатов
        $sql = "SELECT
                sgp.site_id,
                sgp.purchase_id,
                sgp.site_account_id,
                sgp.stock_name,
                sgp.created_on,
                sgp.status_name,
                sgu.site_client_name
                FROM site_gm_purchases sgp
                INNER JOIN site_gm_users sgu
                  ON sgp.site_account_id = sgu.site_account_id
                WHERE sgp.site_account_id IN({$idS}) {$filter}
                ORDER BY sgp.id DESC";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Получить список покупок
     * @param $purchase_id
     * @return array
     */
    public static function getShowDetailsPurchases($purchase_id)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT
				  *
                 FROM dbo.site_gm_purchases_elements
                 WHERE purchase_id = :purchase_id";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':purchase_id', $purchase_id, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }
	


    /**
     * Получаем статус и id заявки
     * @param $site_id
     * @return mixed
     */
    public static function checkStatusRequest($site_id)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                    purchase_id,
                    status_name
                FROM site_gm_purchases
                WHERE site_id = :site_id";

        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->execute();

        // Получаем ассоциативный массив
        $status = $result->fetch();

        return $status;
    }


    /**
     * Экспорт таблицы с покупками
     * @param $id_partner
     * @param $start
     * @param $end
     * @return array
     */
    public static function getExportPurchaseByPartner($id_partner, $start, $end)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                    sgp.purchase_id,
                    sgp.created_on,
                    sgp.stock_name,
                    sgp.status_name,
                    sgpe.part_number,
                    sgpe.goods_name,
                    sgpe.quantity,
                    sgpe.price,
                    sgpe.so_number,
                    sgu.site_client_name
                FROM site_gm_purchases sgp 
                INNER JOIN site_gm_purchases_elements sgpe
                    on sgp.purchase_id = sgpe.purchase_id
                INNER JOIN site_gm_users sgu 
	                ON sgp.site_account_id = sgu.site_account_id
                WHERE sgp.site_account_id = :id_user 
                AND sgp.created_on BETWEEN :start AND :end 
                ORDER BY sgp.id DESC";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->bindParam(':start', $start, PDO::PARAM_STR);
        $result->bindParam(':end', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * Экспорт таблицы с покупками всех партнеров
     * @param $start
     * @param $end
     * @return array
     */
    public static function getExportPurchaseAllPartners($start, $end)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                    sgp.purchase_id,
                    sgp.created_on,
                    sgp.stock_name,
                    sgp.status_name,
                    sgpe.part_number,
                    sgpe.goods_name,
                    sgpe.quantity,
                    sgpe.price,
                    sgpe.so_number,
                    sgu.site_client_name
                FROM site_gm_purchases sgp 
                INNER JOIN site_gm_purchases_elements sgpe
                    on sgp.purchase_id = sgpe.purchase_id
                INNER JOIN site_gm_users sgu 
	                ON sgp.site_account_id = sgu.site_account_id
                WHERE sgp.created_on BETWEEN :start AND :end 
                ORDER BY sgp.id DESC";
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


    public static function getStatusRequest($status)
    {
        switch($status)
        {
            case 'Покупка (принята)':
                return 'green';
                break;
            case 'Покупка (не принята)':
                return 'red';
                break;
            case 'Expect':
                return 'yellow';
                break;
        }

        return true;
    }

}