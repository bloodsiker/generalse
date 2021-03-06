<?php

namespace Umbrella\models\api\hr;

use PDO;
use Umbrella\components\Db\MySQL;

/**
 * Class Band
 * @package Umbrella\models\hr
 */
class User
{

    /**
     * get all users
     *
     * @param $project
     *
     * @return array
     */
    public static function getAll($project)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                id_user,
                name_partner
                FROM gs_user 
                WHERE is_active = 1 AND project LIKE ?";

        $result = $db->prepare($sql);
        $result->execute(array("%$project%"));
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Список пользователей привязанных к форме
     * @return array
     */
    public static function getUsersRelativeForm()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT user_id FROM gs_hr_users_form WHERE user_id IS NOT NULL";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $id_user
     *
     * @return mixed
     */
    public static function getUserById($id_user)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT
                   gu.id_user,
                   gu.name_partner,
                   gu.token,
                   ghuf.id as form_user_id,
                   ghuf.staff_id
                 FROM gs_user gu
                 	LEFT JOIN gs_hr_users_form ghuf
                 		ON gu.id_user = ghuf.user_id
                 WHERE gu.id_user = :id_user';

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}