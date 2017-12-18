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
}