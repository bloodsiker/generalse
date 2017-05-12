<?php

class Balance
{
    /**
     * Получаем общий баланс для партнера
     * paid = 0 -обычное состояние балансу, = 1 - выплачено
     * @param $id_user
     * @return mixed
     */
    public static function getBalanceByPartner($id_user)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                SUM(gub.balance) AS balance
                FROM gs_user_balance gub 
                INNER JOIN gs_balance_number gbn
                    ON gub.id_number = gbn.id
                WHERE gub.id_user = :id_user
                AND (gub.paid = 0 OR gub.paid = 1)
                AND gbn.typy_account = 'individual'";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $count = $result->fetch(PDO::FETCH_ASSOC);
        if($count['balance'] == ''){
            $count['balance'] = 0;
        }
        return $count['balance'];
    }

    /**
     * Баланс для партнера за выбранный месяц
     * paid = 0 -обычное состояние балансу, = 1 - выплачено
     * @param $id_user
     * @param $interval
     * @return mixed
     */
    public static function getBalanceMonthByPartner($id_user, $interval)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                  SUM(gub.balance) AS balance
                FROM gs_user_balance gub 
                INNER JOIN gs_balance_number gbn
                    ON gub.id_number = gbn.id
                WHERE gub.id_user = :id_user
                AND gub.date_create LIKE '%$interval%'
                AND (gub.paid = 0 OR gub.paid = 1)
                AND gbn.typy_account = 'individual'";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $count = $result->fetch(PDO::FETCH_ASSOC);
        if($count['balance'] == ''){
            $count['balance'] = 0;
        }
        return $count['balance'];
    }

    /**
     * Узнать номер балансу пользователя
     * @param $id_user
     * @return mixed
     */
    public static function getNumberBalanceByUser($id_user)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

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
     * Запрос на выплату балансу
     * @param $options
     * @return bool
     */
    public static function outputBalance($options)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_user_balance '
            . '(id_user, balance, id_number, action_balance, id_customer, status, date_create, request_paid)'
            . 'VALUES '
            . '(:id_user, :balance, :id_number, :action_balance, :id_customer, :status, :date_create, :request_paid)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':balance', $options['receive_funds'], PDO::PARAM_STR);
        $result->bindParam(':id_number', $options['id_number'], PDO::PARAM_INT);
        $result->bindParam(':action_balance', $options['action_balance'], PDO::PARAM_STR);
        $result->bindParam(':id_customer', $options['id_customer'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_STR);
        $result->bindParam(':date_create', $options['date_create'], PDO::PARAM_STR);
        $result->bindParam(':request_paid', $options['request_paid'], PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Начисление штрафных санций
     * @param $options
     * @return bool
     */
    public static function addPenaltyBalance($options)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_user_balance '
            . '(id_user, balance, id_number, action_balance, id_customer, comment, date_create)'
            . 'VALUES '
            . '(:id_user, :balance, :id_number, :action_balance, :id_customer, :comment, :date_create)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':balance', $options['penalty'], PDO::PARAM_STR);
        $result->bindParam(':id_number', $options['id_number'], PDO::PARAM_INT);
        $result->bindParam(':action_balance', $options['action_balance'], PDO::PARAM_STR);
        $result->bindParam(':id_customer', $options['id_customer'], PDO::PARAM_INT);
        $result->bindParam(':comment', $options['comment'], PDO::PARAM_STR);
        $result->bindParam(':date_create', $options['date_create'], PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Получем детали активности по счету
     * @param $id_user
     * @param $interval
     * @return array
     */
    public static function getDetailsBalanceByPartner($id_user, $interval)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                    gub.id,
                    gub.id_user,
                    gub.balance,
                    gub.action_balance,
                    gub.id_task,
                    gub.section,
                    gub.comment,
                    gub.status,
                    gub.id_row_section,
                    gub.date_create,
                    gub.paid,
                    gc.customer_name
                FROM gs_user_balance gub 
                INNER JOIN gs_customer gc
                    ON gub.id_customer = gc.id
                WHERE gub.id_user = :id_user
                AND gub.date_create LIKE '%$interval%'
                ORDER BY gub.id DESC";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * Заявки на выплату балансу
     * @return array
     */
    public static function getAllRequestPaid()
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                 gub.id,
                 gub.id_user,
                 gub.balance,
                 gub.action_balance,
                 gub.comment,
                 gub.status,
                 gub.date_create,
                 gub.paid,
                 gub.request_paid,
                 gu.name_partner,
                 gc.customer_name
             FROM gs_user_balance gub
             INNER JOIN gs_user gu
                  ON gub.id_user = gu.id_user 
             INNER JOIN gs_customer gc
                 ON gub.id_customer = gc.id
             WHERE gub.request_paid = 1
             ORDER BY gub.id DESC";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Получаем список месяцей и годов из таблицы балансов.
     * @return array
     */
    public static function getDistinctMonthAndYear()
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                DISTINCT MONTH(gub.date_create) AS month,
                YEAR(gub.date_create) AS year,
                MONTHNAME(gub.date_create) as month_name
                FROM gs_user_balance gub
                ORDER BY year DESC, month DESC";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * Возвращает текущий месяц и год
     * @return array
     */
    public static function getCurrentMonthAndYear()
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT 
                MONTH(NOW()) AS current_month,
                MONTHNAME(NOW()) as month,
                YEAR(NOW()) as year";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetch(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * Подтверждаем или отклоняем выплату
     * @param $paid
     * @param $status
     * @param $paid
     * @param null $comment
     * @return bool
     */
    public static function updatePaid($paid_id, $status, $paid, $comment = NULL)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE gs_user_balance
            SET
                status = :status,
                comment = :comment,
                paid = :paid
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $paid_id, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_STR);
        $result->bindParam(':comment', $comment, PDO::PARAM_STR);
        $result->bindParam(':paid', $paid, PDO::PARAM_INT);
        return $result->execute();
    }


    public static function getStatusRequest($balance)
    {
        if($balance >= 0){
            $class =  'block-green';
        } else {
            $class = 'block-red';
        }
        return $class;
    }

    /**
     * Проверяем, если месяц без нуля, прибавляем к нему ноль
     * @param $current_month
     * @return string
     */
    public static function formatMonth($current_month)
    {
        if(strlen($current_month) < 2){
            $month = 0 . $current_month;
        } else {
            $month = $current_month;
        }

        return $month;
    }


    /**
     * Получаем имя месяца
     * @param $year_month
     * @return string
     */
    public static function getNameMonth($year_month)
    {
        $month = $year_month[5] . $year_month[6];

        switch($month)
        {
            case '01':
                $result = 'January';
                break;
            case '02':
                $result = 'February';
                break;
            case '03':
                $result = 'March';
                break;
            case '04':
                $result = 'April';
                break;
            case '05':
                $result = 'May';
                break;
            case '06':
                $result = 'June';
                break;
            case '07':
                $result = 'July';
                break;
            case '08':
                $result = 'August';
                break;
            case '09':
                $result = 'September';
                break;
            case '10':
                $result = 'October';
                break;
            case '11':
                $result = 'November';
                break;
            case '12':
                $result = 'December';
                break;
        }
        return $year_month[0] . $year_month[1] . $year_month[2] . $year_month[3] . ' ' . $result;
    }


    public static function getStatusPaid($status)
    {
        switch($status)
        {
            case 'Подтверждено':
                return 'green';
                break;
            case 'Отказано':
                return 'red';
                break;
            case 'Ожидание':
                return 'yellow';
                break;
        }
        return true;
    }

}