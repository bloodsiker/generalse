<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Db\MsSQL;

class Products
{

	/**
     * Проверка товара по парт номеру  .. mName
     * @param $partNumber
     * @return int
     */
    public static function checkPartNumber($partNumber)
    {
        $db = MsSQL::getConnection();

        $sql = 'SELECT partNumber FROM dbo.tbl_GoodsNames WHERE partNumber = :partNumber';

        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':partNumber', $partNumber, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
        $pn_number = $result->fetch();

        if ($pn_number) {
            // Если существует массив, то возращаем partNumber
            return 1;
        }
        return 2;
    }
	
	
	public static function checkPartNumber2($partNumber)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT partNumber FROM gs_products WHERE partNumber = :partNumber';

        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':partNumber', $partNumber, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
        $pn_number = $result->fetch();

        if ($pn_number) {
            // Если существует массив, то возращаем 1
            return 1;
        }
        return 2;
    }
	
	
	/**
     *  Проверка парт номера в покупках, и возращаем название продукта
     * @param $partNumber
     * @return array|int
     */
	public static function checkPurchasesPartNumber($partNumber)
    {
        $db = MsSQL::getConnection();

        //$sql = 'SELECT partNumber, mName FROM gs_products WHERE partNumber = :partNumber';
        $sql = 'SELECT partNumber, mName FROM dbo.tbl_GoodsNames WHERE partNumber = :partNumber';

        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':partNumber', $partNumber, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
        $pn_number = $result->fetch(PDO::FETCH_ASSOC);

        if ($pn_number) {
            // Если существует массив, то возращаем 1
            return $pn_number;
        }
        return 0;
    }


    /**
     * Получаем цену на товар по парт номеру
     * @param $partNumber
     * @param $user_id
     * @return int|mixed
     */
    public static function getPricePartNumber($partNumber, $user_id)
    {
        $db = MsSQL::getConnection();

        $sql = "select
                       tbl_GoodsNames.partNumber
                       ,tbl_GoodsNames.mName
                       ,dbo.ufn_Curencys_Rate_Output_Cross(1, tbl_Produsers.curency_id, tbl_Clients.curency_id, dbo.ufn_Date_Current_Short()) * convert(float, tbl_ABCDPrices.price) / 100 as price
                from tbl_ABCDPrices
                    inner join tbl_GoodsNames
                        on tbl_GoodsNames.i_d =  tbl_ABCDPrices.goodsNameID
                    inner join tbl_Produsers
                        on tbl_Produsers.i_d = tbl_GoodsNames.produserID
                       inner join tbl_Clients 
                             on tbl_Clients.abcd_id = tbl_ABCDPrices.namePriceID
                       inner join tbl_Users
                             on tbl_Users.client_id = tbl_Clients.i_d
                       inner join site_gm_users
                             on site_gm_users.id = tbl_Users.site_gs_account_id
                where
                    tbl_GoodsNames.partNumber = :partNumber
                       and site_gm_users.site_account_id = :user_id";

        $result = $db->prepare($sql);
        $result->bindParam(':partNumber', $partNumber, PDO::PARAM_STR);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $result->execute();
        $price = $result->fetch(PDO::FETCH_ASSOC);

        if ($price) {
            return $price;
        }
        return 0;
    }
	
	/**
     * В покупках, если выбран сток = Local Source, проверяем есть ли этот партномер на других складах
     * @param $userId
     * @param $partNumber
     * @return int
     */
    public static function checkPurchasesPartNumberInStocks($userId, $partNumber)
    {
        $db = MsSQL::getConnection();

        $sql = "select
                        site_gm_users.site_account_id
                        ,tbl_GoodsNames.PartNumber
                        ,tbl_GoodsNames.mName
                        ,sum(tbl_2_Stock_Available.quantity) as quantity
                        ,tbl_2_StockStruct.stockName
                from tbl_GoodsNames
                        inner join tbl_2_Goods
                                on tbl_2_Goods.GoodsNameID = tbl_GoodsNames.i_d
                        inner join tbl_2_Stock_Available
                                on tbl_2_Stock_Available.GoodsID = tbl_2_Goods.GoodsID
                        inner join tbl_2_StockStruct
                                on tbl_2_StockStruct.StockID = tbl_2_Stock_Available.StockID
                        inner join tbl_2_StockPlaces
                                on tbl_2_StockPlaces.StockPlaceID = tbl_2_StockStruct.StockPlaceID
                             inner join StockPlaces_Users      
                                           on StockPlaces_Users.stockplace_id = tbl_2_StockPlaces.StockPlaceID
                        inner join tbl_Users
                                on tbl_Users.I_D = StockPlaces_Users.[user_id]
                        inner join site_gm_users
                                on site_gm_users.id = tbl_Users.site_gs_account_id
                where
                        site_gm_users.site_account_id = :user_id
                        and tbl_GoodsNames.PartNumber = :partNumber
                        and (tbl_2_StockStruct.stockName = 'Restored'
                        or tbl_2_StockStruct.stockName = 'Dismantling'
                        or tbl_2_StockStruct.stockName = 'Not Used')
                group by
                        site_gm_users.site_account_id
                        ,tbl_GoodsNames.PartNumber
                        ,tbl_GoodsNames.mName
                        ,tbl_2_StockStruct.stockName
                having
                        sum(tbl_2_Stock_Available.quantity) > 0";

        // P0RM001PUA
        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $result->bindParam(':partNumber', $partNumber, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
        $pn_number = $result->fetch(PDO::FETCH_ASSOC);
        $data['mName'] = $pn_number['mName'];
        $data['quantity'] = $pn_number['quantity'];
        $data['stock_name'] = $pn_number['stockName'];

        if ($pn_number) {
            // Если существует массив, то возращаем 1
            return $data;
        }
        return 0;
    }
	
	
	/**
     * Проверяем в заказах на существование по парт номеру
     * @param $partNumber
     * @return int|mixed
     */
    public static function checkOrdersPartNumber($partNumber)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT goods_name, quantity FROM gs_products WHERE partNumber = :partNumber';

        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':partNumber', $partNumber, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
        $pn_number = $result->fetch(PDO::FETCH_ASSOC);

        if ($pn_number) {
            // Если существует массив, то возращаем 1
            return $pn_number;
        }
        return 0;
    }
	
	
	/**
     * Для заказов проверяем наличие детали на складах по партномеру
     * @param $userId
     * @param $partNumber
     * @param $stock
     * @return int
     */
    public static function checkOrdersPartNumberMsSql($userId, $partNumber, $stock)
    {
        $db = MsSQL::getConnection();

        $sql = "select
                        site_gm_users.site_account_id
                        ,tbl_GoodsNames.PartNumber
                        ,tbl_GoodsNames.mName
                        ,sum(tbl_2_Stock_Available.quantity - isnull(Reserv.quantity, 0)) as quantity
                        ,tbl_2_StockStruct.stockName
                from tbl_GoodsNames
                        inner join tbl_2_Goods
                                on tbl_2_Goods.GoodsNameID = tbl_GoodsNames.i_d
                        inner join tbl_2_Stock_Available
                                on tbl_2_Stock_Available.GoodsID = tbl_2_Goods.GoodsID
                        inner join tbl_2_StockStruct
                                on tbl_2_StockStruct.StockID = tbl_2_Stock_Available.StockID
                        inner join tbl_2_StockPlaces
                                on tbl_2_StockPlaces.StockPlaceID = tbl_2_StockStruct.StockPlaceID
                             inner join StockPlaces_Users
                                           on StockPlaces_Users.stockplace_id = tbl_2_StockPlaces.StockPlaceID
                        inner join tbl_Users
                                on tbl_Users.I_D = StockPlaces_Users.[user_id]
                        inner join site_gm_users
                                on site_gm_users.id = tbl_Users.site_gs_account_id
                        left outer join
                                (select
                                    tbl_2_OrdersGoods.goodsID
                                    ,tbl_2_OrdersGoods.stockID
                                    ,sum(tbl_2_OrdersGoods.quantity - tbl_2_OrdersGoods.outQuantity) as quantity
                                from tbl_2_OrdersGoods with (nolock)
                                where
                                    dbo.ufn_Orders_Check_Reserv(tbl_2_OrdersGoods.orderID, dbo.ufn_Date_Current_Short()) = 1
                                group by
                                    tbl_2_OrdersGoods.goodsID
                                    ,tbl_2_OrdersGoods.stockID
                                having
                                    sum(tbl_2_OrdersGoods.quantity - tbl_2_OrdersGoods.outQuantity) > 0
                                )Reserv
                                on Reserv.goodsID = tbl_2_Stock_Available.goodsID
                                and Reserv.stockID = tbl_2_Stock_Available.stockID
                where
                        site_gm_users.site_account_id = :user_id
                        and tbl_GoodsNames.PartNumber = :partNumber
                        and tbl_2_StockStruct.stockName = :stock
                group by
                        site_gm_users.site_account_id
                        ,tbl_GoodsNames.PartNumber
                        ,tbl_GoodsNames.mName
                        ,tbl_2_StockStruct.stockName
                having
                        sum(tbl_2_Stock_Available.quantity - isnull(Reserv.quantity, 0)) > 0";

        // P0RM001PUA
        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $result->bindParam(':partNumber', $partNumber, PDO::PARAM_STR);
        $result->bindParam(':stock', $stock, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
        $pn_number = $result->fetch(PDO::FETCH_ASSOC);
        $data['goods_name'] = iconv('WINDOWS-1251', 'UTF-8', $pn_number['mName']);
        $data['quantity'] = $pn_number['quantity'];

        if ($pn_number) {
            // Если существует массив, то возращаем 1
            return $data;
        }
        return 0;
    }


    /**
     * Проверка наличия парт номера в базе
     * @param $partNumber
     * @return int
     */
    public static function checkPartNumberMoto($partNumber)
    {
        $db = MsSQL::getConnection();

        $sql = 'SELECT mName FROM dbo.tbl_GoodsNames WHERE partNumber = :partNumber';

        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':partNumber', $partNumber, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
        $pn_number = $result->fetch(PDO::FETCH_ASSOC);
        $data['mName'] = $pn_number['mName'];
        if ($pn_number) {
            // Если существует массив, то возращаем id пользователя
            return $data;
        }
        return 0;
    }


    /**
     * Раздел МОТО, проверка по серийнику на существование заявки
     * @param $site_account_id
     * @param $serial_number
     * @return int|mixed
     */
    public static function checkMotoSerialNumber($site_account_id, $serial_number)
    {
        $db = MsSQL::getConnection();

        //$sql = 'SELECT partNumber, mName FROM gs_products WHERE partNumber = :partNumber';
        $sql = 'SELECT TOP 1
                  site_id, serial_number 
                FROM site_gm_service_objects 
                WHERE site_account_id = :site_account_id 
                AND serial_number = :serial_number
                ORDER BY id DESC';

        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_account_id', $site_account_id, PDO::PARAM_INT);
        $result->bindParam(':serial_number', $serial_number, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
        $pn_number = $result->fetch(PDO::FETCH_ASSOC);

        if ($pn_number) {
            // Если существует массив, то возращаем 1
            return $pn_number;
        }
        return 0;
    }


    /**
     * Проверка наличия парт нортномера на складах
     * @param $userId
     * @param $partNumber
     * @return int
     */
    public static function checkBatchPartNumberInStocks($userId, $partNumber)
    {
        $db = MsSQL::getConnection();

        $sql = "select
                       site_gm_users.site_account_id
                       ,tbl_GoodsNames.PartNumber
                       ,tbl_GoodsNames.mName
                       ,sum(tbl_2_Stock_Available.quantity) as quantity
                       ,tbl_2_StockStruct.stockName
                from tbl_GoodsNames
                       inner join tbl_2_Goods
                             on tbl_2_Goods.GoodsNameID = tbl_GoodsNames.i_d
                       inner join tbl_2_Stock_Available
                             on tbl_2_Stock_Available.GoodsID = tbl_2_Goods.GoodsID
                       inner join tbl_2_StockStruct
                             on tbl_2_StockStruct.StockID = tbl_2_Stock_Available.StockID
                       inner join tbl_2_StockPlaces
                             on tbl_2_StockPlaces.StockPlaceID = tbl_2_StockStruct.StockPlaceID
                       inner join tbl_Users
                             on tbl_Users.I_D = tbl_2_StockPlaces.[user_id]
                       inner join site_gm_users
                             on site_gm_users.id = tbl_Users.site_gs_account_id
                where
                       site_gm_users.site_account_id = :user_id
                       and tbl_GoodsNames.PartNumber = :partNumber
                       and (tbl_2_StockStruct.stockName = 'Restored'
                       or tbl_2_StockStruct.stockName = 'Dismantling'
                       or tbl_2_StockStruct.stockName = 'Not Used'
                       or tbl_2_StockStruct.stockName = 'Local Source')
                group by
                       site_gm_users.site_account_id
                       ,tbl_GoodsNames.PartNumber
                       ,tbl_GoodsNames.mName
                       ,tbl_2_StockStruct.stockName
                having
                       sum(tbl_2_Stock_Available.quantity) > 0";

        // P0RM001PUA
        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $result->bindParam(':partNumber', $partNumber, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
        $pn_number = $result->fetch(PDO::FETCH_ASSOC);
        $data['mName'] = $pn_number['mName'];
        $data['quantity'] = $pn_number['quantity'];
        $data['stock_name'] = $pn_number['stockName'];

        if ($pn_number) {
            // Если существует массив, то возращаем 1
            return $data;
        }
        return 0;
    }

}