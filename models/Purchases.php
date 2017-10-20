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
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_purchases '
            . '(site_id, id_user, stock_name, so_number, ready, file_attache)'
            . 'VALUES '
            . '(:site_id, :id_user, :stock_name, :so_number, :ready, :file_attache)';

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
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_purchases_elements '
            . '(site_id, part_number, goods_name, quantity, price, so_number)'
            . 'VALUES '
            . '(:site_id, :part_number, :goods_name, :quantity, :price, :so_number)';

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
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO dbo.site_gm_purchases '
            . '(site_id, site_account_id, stock_name, so_number, ready)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :stock_name, :so_number, :ready)';

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
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO dbo.site_gm_purchases_elements '
            . '(site_id, part_number, quantity, price, so_number)'
            . 'VALUES '
            . '(:site_id, :part_number, :quantity, :price, :so_number)';

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
        $db = MsSQL::getConnection();

        //$sql = "SELECT site_id FROM gm_purchases ORDER BY id DESC LIMIT 1";
		$sql = "SELECT site_id FROM site_gm_purchases WHERE site_id = (SELECT MAX(site_id) FROM site_gm_purchases)";

        $result = $db->prepare($sql);
        $result->execute();
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
        $db = MySQL::getConnection();

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
        $db = MsSQL::getConnection();

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
        $db = MySQL::getConnection();

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

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->execute();
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
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

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

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->execute();
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
        $db = MsSQL::getConnection();

        $sql = "SELECT
				  *
                 FROM dbo.site_gm_purchases_elements
                 WHERE purchase_id = :purchase_id";

        $result = $db->prepare($sql);
        $result->bindParam(':purchase_id', $purchase_id, PDO::PARAM_INT);
        $result->execute();
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

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->execute();
        $status = $result->fetch();
        return $status;
    }


    /**
     * Экспорт таблицы с покупками
     * @param $array_id
     * @param $start
     * @param $end
     * @return array
     */
    public static function getExportPurchaseByPartner($array_id, $start, $end, $filter)
    {
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

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
                 LEFT JOIN site_gm_purchases_elements sgpe
                     on sgp.purchase_id = sgpe.purchase_id
                 INNER JOIN site_gm_users sgu 
                    ON sgp.site_account_id = sgu.site_account_id
                 WHERE sgp.site_account_id IN({$idS})
                 AND sgp.created_on BETWEEN :start AND :end {$filter}
                 ORDER BY sgp.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':start', $start, PDO::PARAM_STR);
        $result->bindParam(':end', $end, PDO::PARAM_STR);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Поиск по покупкам
     * @param $search
     * @param string $filter
     * @return array
     */
    public static function getSearchInPurchase($search, $filter = '')
    {
        $db = MsSQL::getConnection();

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
                LEFT JOIN site_gm_purchases_elements sgpe
                   on sgp.purchase_id = sgpe.purchase_id
                INNER JOIN site_gm_users sgu 
                  ON sgp.site_account_id = sgu.site_account_id
                WHERE 1 = 1 {$filter} 
                AND (sgp.purchase_id LIKE ?
                OR sgp.stock_name LIKE ?
                OR sgpe.part_number LIKE ?
                OR sgpe.goods_name LIKE ?
                OR sgpe.so_number LIKE ?
                OR sgu.site_client_name LIKE ?)
                ORDER BY sgp.id DESC";

        $result = $db->prepare($sql);
        $result->execute(array("%$search%", "%$search%", "%$search%", "%$search%", "%$search%", "%$search%"));
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }



    /**
     * @param $status
     * @return bool|string
     */
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