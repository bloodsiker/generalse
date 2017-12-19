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
     * @return array
     */
    public static function getAll()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                id_user,
                name_partner
                FROM gs_user 
                WHERE is_active = 1 AND (id_role = 1 OR id_role = 3)";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Список пользователей не привязанных к форме
     * @return array
     */
    public static function getUsersNotRelativeForm()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                    id_user,
                    name_partner
                FROM gs_user 
                WHERE is_active = 1 AND (id_role = 1 OR id_role = 3)
                AND id_user NOT IN(SELECT user_id FROM gs_hr_users_form WHERE user_id IS NOT NULL)";

        $result = $db->prepare($sql);
        $result->execute();
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
                   gu.name_partner
                 FROM gs_user gu
                 WHERE gu.id_user = :id_user';

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetch();
    }
}