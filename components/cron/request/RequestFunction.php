<?php
require_once dirname(__FILE__) . "/../CronDb.php";

class RequestFunction
{
    /**
     * Получаем список заказов со статусом (Нет в наличии, формируется поставка)
     * @return array
     */
    public static function getReserveOrders()
    {
        // Соединение с БД
        $db = CronDb::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT
                *
                FROM gm_orders_check
                WHERE check_status = 0
                ORDER BY id DESC";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':site_id', $site_id, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * Получаем последний ID покупки
     * @return mixed
     */
    public static function getLastOrdersId()
    {
        // Соединение с БД
        $db = CronDb::getConnectionMsSQL();
        //$db = Db::getConnection();

        // Текст запроса к БД
        //$sql = "SELECT site_id FROM site_gm_orders ORDER BY id DESC LIMIT 1";
        $sql = "SELECT site_id FROM site_gm_orders WHERE site_id = (SELECT MAX(site_id) FROM site_gm_orders)";

        // Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetch();
        return $row['site_id'];
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
        $db = CronDb::getConnectionMsSQL();

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
               inner join tbl_Users
                     on tbl_Users.I_D = tbl_2_StockPlaces.[user_id]
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
     * Обновялем статус заявки
     * @param $id
     * @param $check_status
     * @return bool
     */
    public static function updateCheckReserveOrders($id, $check_status)
    {
        // Соединение с БД
        $db = CronDb::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE gm_orders_check
            SET
                check_status = :check_status
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':check_status', $check_status, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Обновляем название товара
     * @param $id
     * @param $part_description
     * @return bool
     */
    public static function updateNameProduct($id, $part_description)
    {
        // Соединение с БД
        $db = CronDb::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE gm_orders_check
            SET
                part_description = :part_description
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':part_description', $part_description, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Обновляем цены на продукты
     * @param $id
     * @param $price
     * @return bool
     */
    public static function updatePriceProduct($id, $price)
    {
        // Соединение с БД
        $db = CronDb::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE gm_orders_check
            SET
                price = :price
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':price', $price, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Получаем цену на товар по парт номеру
     * @param $partNumber
     * @return int|mixed
     */
    public static function getPricePartNumber($partNumber)
    {
        $db = CronDb::getConnectionMsSQL();

        $sql = "select
                       tbl_GoodsNames.partNumber
                       ,tbl_GoodsNames.mName
                       ,dbo.ufn_Curencys_Rate_Output_Cross(1, tbl_Produsers.curency_id, dbo.ufn_Curencys_Rate_IsBuh(), dbo.ufn_Date_Current_Short()) * convert(float, tbl_ABCDPrices.price) / 100 as price
                from tbl_ABCDPrices
                       inner join tbl_GoodsNames
                             on tbl_GoodsNames.i_d =  tbl_ABCDPrices.goodsNameID
                       inner join tbl_Produsers
                             on tbl_Produsers.i_d = tbl_GoodsNames.produserID
                where
                       tbl_ABCDPrices.namePriceID = 5
                       and tbl_GoodsNames.partNumber = :partNumber";

        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':partNumber', $partNumber, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
        $price = $result->fetch(PDO::FETCH_ASSOC);

        if ($price) {
            // Если существует массив, то возращаем 1
            return $price;
        }
        return 0;
    }


    /**
     * Проверяем наличие парт номера в поставках, возвращаем id_supply
     * @param $users_group
     * @param $part_number
     * @param $status
     * @return array
     */
    public static function checkPartNumberInSupply($users_group, $part_number, $status)
    {
        // Соединение с БД
        $db = CronDb::getConnectionMsSQL();

        $iDs = implode(',', $users_group);

        // Получение и возврат результатов
        $sql = "SELECT
                *
                FROM site_gm_supplies sgs
                    INNER JOIN site_gm_supplies_parts sgsp
                        ON sgs.site_id = sgsp.site_id
                WHERE sgs.site_account_id IN ({$iDs})
                AND sgsp.part_number = :part_number
                AND sgs.status_name != :status
                ORDER BY sgsp.id DESC";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetch(PDO::FETCH_ASSOC);
        return $all['supply_id'];
    }

    /**
     * Добавляем заказы  MSSQL
     * @param $options
     * @return bool
     */
    public static function addOrdersMsSQL($options)
    {
        // Соединение с БД
        $db = CronDb::getConnectionMsSQL();

        // Текст запроса к БД
        $sql = 'INSERT INTO site_gm_orders '
            . '(site_id, site_account_id, so_number, ready, note)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :so_number, :ready, :note)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        $result->bindParam(':note', $options['note'], PDO::PARAM_STR);

        return $result->execute();
    }

    /**
     * Добавляем заказы
     * @param $options
     * @return bool
     */
    public static function addOrders($options)
    {
        // Соединение с БД
        $db = CronDb::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gm_orders '
            . '(site_id, id_user, so_number, ready, note)'
            . 'VALUES '
            . '(:site_id, :id_user, :so_number, :ready, :note)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':so_number', $options['so_number'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        $result->bindParam(':note', $options['note_mysql'], PDO::PARAM_STR);

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
        // Соединение с БД
        $db = CronDb::getConnectionMsSQL();

        // Текст запроса к БД
        $sql = 'INSERT INTO site_gm_orders_elements '
            . '(site_id, part_number, goods_name, so_number, stock_name, quantity, is_supply)'
            . 'VALUES '
            . '(:site_id, :part_number, :goods_name, :so_number, :stock_name, :quantity, :is_supply)';

        // Получение и возврат результатов. Используется подготовленный запрос
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
     * Добавляем подробности о заказе
     * @param $options
     * @return bool
     */
    public static function addOrdersElements($options)
    {
        // Соединение с БД
        $db = CronDb::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gm_orders_elements '
            . '(site_id, part_number, goods_name, so_number, stock_name, quantity)'
            . 'VALUES '
            . '(:site_id, :part_number, :goods_name, :so_number, :stock_name, :quantity)';

        // Получение и возврат результатов. Используется подготовленный запрос
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
     *  Проверка парт номера в базе, и возращаем название продукта
     * @param $partNumber
     * @return array|int
     */
    public static function checkPurchasesPartNumber($partNumber)
    {
        $db = CronDb::getConnectionMsSQL();

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


    /************   GROUP MODEL  *****************/

    public static function getUsersByGroup($id_group)
    {
        $db = CronDb::getConnection();

        $sql = "SELECT 
                ggu.id_user,
                gu.name_partner
                FROM gs_group_user ggu
                    INNER JOIN gs_user gu
                        ON ggu.id_user = gu.id_user
                WHERE ggu.id_group = :id_group";

        $result = $db->prepare($sql);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
        $result->execute();

        $user = $result->fetchAll(PDO::FETCH_ASSOC);
        return $user;
    }


    /**
     * Возвращаем ID группы, в которой стостоит пользователь
     * @param $id_user
     * @return mixed
     */
    public static function getIdGroupUser($id_user)
    {
        $db = CronDb::getConnection();

        $sql = "SELECT 
                ggu.id_group
                FROM gs_group_user ggu
                    INNER JOIN gs_user gu
                        ON ggu.id_user = gu.id_user
                WHERE ggu.id_user = :id_user";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();

        $user = $result->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    /**
     * Список складов привязанных к группе
     * @param $id_group
     * @param $section
     * @return array
     */
    public static function getStocksFromGroup($id_group, $section)
    {
        $db = CronDb::getConnection();

        $sql = "SELECT 
                ggs.id as id_row,
                ggs.id_stock,
                gs.stock_name,
                gs.id
                FROM gs_group_stock ggs
                    INNER JOIN gs_stocks gs
                        ON ggs.id_stock = gs.id
                WHERE ggs.id_group = :id_group AND ggs.section = :section";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
        $result->bindParam(':section', $section, PDO::PARAM_STR);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

}




class RequestGroup
{

    /**
     * ID пользователей состоящий в группе
     * @param $id_group
     * @return array
     */
    public function usersFromGroup($id_group)
    {
        $users_group = RequestFunction::getUsersByGroup($id_group);
        return array_column($users_group, 'id_user');
    }

    /**
     * Возращаем список id складов
     * @param $id_group
     * @param string $key
     * @param $section
     * @return array
     */
    public function stocksFromGroup($id_group, $key = 'id', $section)
    {
        $list_stock = RequestFunction::getStocksFromGroup($id_group, $section);
        if($key == 'id'){
            return array_column($list_stock, 'id_stock');
        } elseif($key == 'name'){
            return array_column($list_stock, 'stock_name');
        }
    }
}


class RequestUser
{
    /**
     * ID группы в которой состоит пользователь
     * @param $id_user
     * @return mixed
     */
    public function idGroupUser($id_user)
    {
        $id_group = RequestFunction::getIdGroupUser($id_user);
        return $id_group['id_group'];
    }

}


