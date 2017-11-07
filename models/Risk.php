<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MsSQL;

class Risk
{

    /**
     * Просроченные счета
     * @param $user_id
     * @return mixed
     */
    public static function getUserRisks($user_id)
    {
        $db = MsSQL::getConnection();

        $sql = 'SELECT * FROM site_gm_users_risks WHERE site_account_id = :site_account_id ORDER BY id DESC';

        $result = $db->prepare($sql);
        $result->bindParam(':site_account_id', $user_id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}