<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;

class Innovation
{

    /**
     * @param $user_id
     * @return array
     */
    public static function getListNewInnovation($user_id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                    gi.id,
                    gi.new_content,
                    gi.created_at,
                    gu.id_user,
                    gu.name_partner
                FROM gs_innovation gi
                    INNER JOIN gs_group_user ggu
                        ON gi.group_id = ggu.id_group
                    INNER JOIN gs_user gu
                        ON ggu.id_user = gu.id_user
                WHERE gi.id NOT IN (
                    SELECT
                        giv.innovation_id
                    FROM gs_innovation_view giv
                    WHERE giv.user_id = :user_id
                )
                AND gu.id_user = :user_id";

        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Добавляем просмотренные нововедение для пользователя
     * @param $innovation_id
     * @param $user_id
     * @return bool
     */
    public static function viewInnovation($innovation_id, $user_id)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_innovation_view '
            . '(innovation_id, user_id)'
            . 'VALUES '
            . '(:innovation_id, :user_id)';

        $result = $db->prepare($sql);
        $result->bindParam(':innovation_id', $innovation_id, PDO::PARAM_STR);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        return $result->execute();
    }
}