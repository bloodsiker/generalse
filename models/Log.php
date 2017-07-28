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
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $data = $db->query("SELECT * FROM gs_log gl
                              INNER JOIN gs_user gu
                                ON gl.id_user = gu.id_user
                            ORDER BY gl.id_log DESC LIMIT $num, 30")->fetchAll(PDO::FETCH_ASSOC);

        return $data;
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