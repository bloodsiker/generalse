<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;

class Log
{

    /**
     * @param $num
     * @return array
     */
    public static function getAllLog($num)
    {
        $db = MySQL::getConnection();

        $data = $db->query("SELECT * FROM gs_log gl
                              INNER JOIN gs_user gu
                                ON gl.id_user = gu.id_user
                            ORDER BY gl.id_log DESC LIMIT $num, 50")->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }


    /**
     * Select logs by userId and between date
     * @param $user_id
     * @param $fromDate
     * @param $toDate
     *
     * @return array
     */
    public static function getLogByUserId($id_user, $fromDate, $toDate)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT * FROM gs_log 
                WHERE id_user = :id_user 
                AND date_log 
                BETWEEN :fromDate AND :toDate 
                ORDER BY id_log DESC';

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':fromDate', $fromDate, PDO::PARAM_INT);
        $result->bindParam(':toDate', $toDate, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Пишем логи пользователей
     * @param $id_user
     * @param $log
     * @return bool
     */
    public static function addLog($id_user, $log)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_log '
            . '(id_user, log_text, ip_user, user_agent)'
            . 'VALUES '
            . '(:id_user, :log_text, :ip_user, :user_agent)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':log_text', $log, PDO::PARAM_STR);
        $result->bindParam(':ip_user', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $result->bindParam(':user_agent', $_SERVER['HTTP_USER_AGENT'], PDO::PARAM_STR);

        return $result->execute();
    }

}