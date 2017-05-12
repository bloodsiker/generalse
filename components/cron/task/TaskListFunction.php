<?php
require_once dirname(__FILE__) . "/../CronDb.php";

class TaskListFunction
{
    /**
     * Получаем список заданий на выполнения
     * @return array
     */
    public static function getTaskList()
    {
        // Соединение с базой данных
        $db = CronDb::getConnection();

        // Делаем запрос к базе данных
        $sql = "SELECT * FROM gs_task_list WHERE is_active = 1";

        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':section', $section, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Получаем массив id за которые уже начислена выплата
     * @param $section
     * @param $id_task_list
     * @return array
     */
    public static function getCompletedRowSection($section, $id_task_list)
    {
        // Соединение с базой данных
        $db = CronDb::getConnection();

        // Делаем запрос к базе данных
        $sql = "SELECT 
                  DISTINCT(id_row)
                FROM gs_task_list_completed 
                WHERE section = :section 
                AND id_task_list = :id_task_list 
                ORDER BY id DESC LIMIT 500";

        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':section', $section, PDO::PARAM_STR);
        $result->bindParam(':id_task_list', $id_task_list, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Получаем наибольший ID из таблицы выполненных заданий
     * @param $section
     * @param $id_task
     * @return array
     */
    public static function getMaxIdIsCompleted($section, $id_task)
    {
        // Соединение с базой данных
        $db = CronDb::getConnection();

        // Делаем запрос к базе данных
        $sql = "SELECT max(id_row) AS max_id FROM gs_task_list_completed WHERE section = :section AND id_task_list = :id_task";

        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':section', $section, PDO::PARAM_INT);
        $result->bindParam(':id_task', $id_task, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        $max_id = $result->fetch(PDO::FETCH_ASSOC);
        return $max_id['max_id'];
    }

    /**
     * Добавляем id за которые уже произвелись начисления
     * @param $section
     * @param $id_row
     * @return bool
     */
    public static function addCompletedRowSection($section, $id_row, $id_task_list)
    {
        // Соединение с БД
        $db = CronDb::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_task_list_completed '
            . '(section, id_row, id_task_list)'
            . 'VALUES '
            . '(:section, :id_row, :id_task_list)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':section', $section, PDO::PARAM_STR);
        $result->bindParam(':id_row', $id_row, PDO::PARAM_INT);
        $result->bindParam(':id_task_list', $id_task_list, PDO::PARAM_INT);

        return $result->execute();
    }

    /**
     * ********** PURCHASE  **************
     * Получаем последних N покупок
     * @param $users
     * @param $status
     * @param $filter
     * @param $max_id
     * @return array
     */
    public static function getPurchasesList($users, $status, $filter, $max_id)
    {
        // Соединение с БД
        $db = CronDb::getConnectionMsSQL();

        // Получение и возврат результатов
        $sql = "SELECT TOP 50
                   sgp.purchase_id,
                   sgp.site_account_id,
                   sgp.stock_name,
                   sgp.created_on,
                   sgp.status_name
                 FROM site_gm_purchases sgp
                  WHERE sgp.site_account_id IN({$users})
                  AND sgp.status_name = :status
                  AND sgp.purchase_id <> ''
                  AND sgp.purchase_id > :max_id
                  {$filter}
                   ORDER BY sgp.id ASC";

        $result = $db->prepare($sql);
        //$result->bindParam(':users', $users, PDO::PARAM_STR);
        $result->bindParam(':status', $status, PDO::PARAM_STR);
        $result->bindParam(':max_id', $max_id, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * ********** PURCHASE  **************
     * Получить список елементов в покупке под таким purchase_id
     * @param $purchase_id
     * @return array
     */
    public static function getPurchasesElementList($purchase_id)
    {
        // Соединение с БД
        $db = CronDb::getConnectionMsSQL();

        // Получение и возврат результатов
        $sql = "SELECT 
                    sgpe.purchase_id,
                    sgpe.part_number,
                    sgpe.goods_name,
                    sgpe.quantity,
                    sgpe.price,
                    sgp.stock_name,
                     tbl_Classifier.mName as classifier,
                     GoodsSubType.shortName as goods_sub_type
                    FROM site_gm_purchases_elements sgpe
                        INNER JOIN site_gm_purchases sgp
                            ON sgpe.purchase_id = sgp.purchase_id
                        INNER JOIN tbl_GoodsNames
                            ON sgpe.part_number = tbl_GoodsNames.partNumber
                        INNER JOIN tbl_Classifier
                            ON tbl_Classifier.i_d = tbl_GoodsNames.classifierID
                        INNER JOIN GoodsSubType
                            on GoodsSubType.id = tbl_GoodsNames.subType
                    WHERE sgpe.purchase_id = :purchase_id";

        $result = $db->prepare($sql);
        //$result->bindParam(':users', $users, PDO::PARAM_STR);
        $result->bindParam(':purchase_id', $purchase_id, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * ********** DISASSEMBLY  **************
     * Получаем последних N разборок
     * @param $users
     * @param $status
     * @param $filter
     * @param $max_id
     * @return array
     */
    public static function getDisassemblyList($users, $status, $filter, $max_id)
    {
        // Соединение с БД
        $db = CronDb::getConnectionMsSQL();

        // Получение и возврат результатов
        $sql = "SELECT TOP 10
                    sgd.decompile_id,
                    sgd.site_account_id,
                    sgd.status_name
                FROM site_gm_decompiles sgd 
                WHERE sgd.site_account_id IN({$users})
                AND sgd.status_name = :status 
                AND sgd.decompile_id > :max_id
                {$filter}
                ORDER BY sgd.id ASC";

        $result = $db->prepare($sql);
        //$result->bindParam(':users', $users, PDO::PARAM_STR);
        $result->bindParam(':status', $status, PDO::PARAM_STR);
        $result->bindParam(':max_id', $max_id, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * ********** DISASSEMBLY  **************
     * Получить список елементов в разборке под таким disassembly_id
     * @param $disassembly_id
     * @return array
     */
    public static function getDisassemblyElementList($disassembly_id)
    {
        // Соединение с БД
        $db = CronDb::getConnectionMsSQL();

        // Получение и возврат результатов
        $sql = "SELECT
                 sgdp.part_number,
                 sgdp.goods_name,
                 sgdp.quantity,
                 sgdp.stock_name,
                 tbl_Classifier.mName as classifier,
                 GoodsSubType.shortName as goods_sub_type
                FROM site_gm_decompiles_parts sgdp 
                    INNER JOIN tbl_GoodsNames
                      ON sgdp.part_number = tbl_GoodsNames.partNumber
                    INNER JOIN tbl_Classifier
                      ON tbl_Classifier.i_d = tbl_GoodsNames.classifierID
                    INNER JOIN GoodsSubType
                      on GoodsSubType.id = tbl_GoodsNames.subType
                WHERE sgdp.decompile_id = :disassembly_id";

        $result = $db->prepare($sql);
        //$result->bindParam(':users', $users, PDO::PARAM_STR);
        $result->bindParam(':disassembly_id', $disassembly_id, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * ********** RETURNS  **************
     * Получаем последних N возвратов
     * @param $users
     * @param $status
     * @param $filter
     * @param $max_id
     * @return array
     */
    public static function getReturnList($users, $status, $filter, $max_id)
    {
        // Соединение с БД
        $db = CronDb::getConnectionMsSQL();

        // Получение и возврат результатов
        $sql = "SELECT TOP 50
                    sgs.stock_return_id,
                    sgs.site_account_id,
                    sgs.stock_name,
                    sgse.part_number,
                    sgse.goods_name,
                    sgse.quantity,
                    tbl_Classifier.mName as classifier,
                    GoodsSubType.shortName as goods_sub_type
                    FROM site_gm_stockreturns sgs
                        INNER JOIN site_gm_stockreturns_elements sgse
                            ON sgs.stock_return_id = sgse.stock_return_id
                        INNER JOIN tbl_GoodsNames
                           ON sgse.part_number = tbl_GoodsNames.partNumber
                       INNER JOIN tbl_Classifier
                           ON tbl_Classifier.i_d = tbl_GoodsNames.classifierID
                       INNER JOIN GoodsSubType
             					on GoodsSubType.id = tbl_GoodsNames.subType
                    WHERE sgs.site_account_id IN({$users})
                    AND sgs.stock_return_id > :max_id
                    AND sgs.status_name = :status
                    {$filter}
                    ORDER BY sgs.id ASC";

        $result = $db->prepare($sql);
        //$result->bindParam(':users', $users, PDO::PARAM_STR);
        $result->bindParam(':status', $status, PDO::PARAM_STR);
        $result->bindParam(':max_id', $max_id, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * ********** RETURNS  **************
     * Получаем последних N заказов
     * @param $users
     * @param $status
     * @param $filter
     * @param $max_id
     * @return array
     */
    public static function getOrdersList($users, $status, $filter, $max_id)
    {
        // Соединение с БД
        $db = CronDb::getConnectionMsSQL();

        // Получение и возврат результатов
        $sql = "SELECT TOP 50
                    sgo.order_id,
                    sgo.site_account_id,
                    sgoe.part_number,
                    sgoe.goods_name,
                    sgoe.stock_name,
                    sgoe.quantity,
                    tbl_Classifier.mName as classifier,
                    GoodsSubType.shortName as goods_sub_type
                FROM site_gm_orders sgo
                    INNER JOIN site_gm_orders_elements sgoe
                        ON sgo.order_id = sgoe.order_id
                    INNER JOIN tbl_GoodsNames
                      ON sgoe.part_number = tbl_GoodsNames.partNumber
                   INNER JOIN tbl_Classifier
                      ON tbl_Classifier.i_d = tbl_GoodsNames.classifierID
                   INNER JOIN GoodsSubType
                        on GoodsSubType.id = tbl_GoodsNames.subType
                WHERE sgo.order_id > :max_id
                AND sgo.status_name = :status
                AND sgo.site_account_id IN({$users})
                {$filter}
                ORDER BY sgo.id ASC";

        $result = $db->prepare($sql);
        //$result->bindParam(':users', $users, PDO::PARAM_STR);
        $result->bindParam(':status', $status, PDO::PARAM_STR);
        $result->bindParam(':max_id', $max_id, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * Показать елементы записи по которой было начисление
     * @param $section
     * @param $id_row
     * @return array
     */
    public static function getListCompletedElements($section, $id_row, $id_task)
    {
        // Соединение с БД
        $db = CronDb::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT * FROM gs_task_list_completed_elements WHERE section = :section AND id_row = :id_row AND id_task_list = :id_task";

        $result = $db->prepare($sql);
        //$result->bindParam(':users', $users, PDO::PARAM_STR);
        $result->bindParam(':section', $section, PDO::PARAM_STR);
        $result->bindParam(':id_row', $id_row, PDO::PARAM_INT);
        $result->bindParam(':id_task', $id_task, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * Узнать номер балансу пользователя
     * @param $id_user
     * @return mixed
     */
    public static function getNumberBalanceByUser($id_user)
    {
        // Соединение с БД
        $db = CronDb::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT
                 gsnb.id_user,
                 gbn.id,
                 gbn.number,
                 gbn.typy_account
                 FROM gs_user_number_balance gsnb
                    INNER JOIN gs_balance_number gbn
                        ON gsnb.id_number = gbn.id
                WHERE gsnb.id_user = :id_user 
                AND gbn.typy_account = 'individual'";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $balance = $result->fetch(PDO::FETCH_ASSOC);
        return $balance;
    }


    /**
     * Начисление балансу за выполнение задания
     * @param $options
     * @return bool
     */
    public static function accrualBalance($options)
    {
        // Соединение с БД
        $db = CronDb::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_user_balance '
            . '(id_user, balance, id_number, action_balance, id_task, id_customer, section, id_row_section, date_create)'
            . 'VALUES '
            . '(:id_user, :balance, :id_number, :action_balance, :id_task, :id_customer, :section, :id_row_section, :date_create)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':balance', $options['balance'], PDO::PARAM_STR);
        $result->bindParam(':id_number', $options['id_number'], PDO::PARAM_INT);
        $result->bindParam(':action_balance', $options['action_balance'], PDO::PARAM_STR);
        $result->bindParam(':id_task', $options['id_task'], PDO::PARAM_INT);
        $result->bindParam(':id_customer', $options['id_customer'], PDO::PARAM_INT);
        $result->bindParam(':section', $options['section'], PDO::PARAM_STR);
        $result->bindParam(':id_row_section', $options['id_row_section'], PDO::PARAM_INT);
        $result->bindParam(':date_create', $options['date_create'], PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Пишем лог, за какие операции іли произведені начисления
     * @param $options
     * @return bool
     */
    public static function addCompletedElements($options)
    {
        // Соединение с БД
        $db = CronDb::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_task_list_completed_elements '
            . '(section, id_row, id_task_list, part_number, goods_name, quantity, stock_name, classifier, goods_sub_type, price, add_pay)'
            . 'VALUES '
            . '(:section, :id_row, :id_task_list, :part_number, :goods_name, :quantity, :stock_name, :classifier, :goods_sub_type, :price, :add_pay)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':section', $options['section'], PDO::PARAM_STR);
        $result->bindParam(':id_row', $options['id_row'], PDO::PARAM_INT);
        $result->bindParam(':id_task_list', $options['id_task_list'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':goods_name', $options['goods_name'], PDO::PARAM_STR);
        $result->bindParam(':quantity', $options['quantity'], PDO::PARAM_INT);
        $result->bindParam(':stock_name', $options['stock_name'], PDO::PARAM_STR);
        $result->bindParam(':classifier', $options['classifier'], PDO::PARAM_STR);
        $result->bindParam(':goods_sub_type', $options['goods_sub_type'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':add_pay', $options['add_pay'], PDO::PARAM_STR);
        return $result->execute();
    }
}