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

        $sql = 'SELECT 
                  sgur.*
                FROM site_gm_users_risks sgur
                INNER JOIN tbl_Users tu
                  ON sgur.site_account_id = tu.site_gs_account_id
                INNER JOIN site_gm_users sgu
                  ON sgu.id = tu.site_gs_account_id
                WHERE sgu.site_account_id = :site_account_id 
                ORDER BY id DESC';

        $result = $db->prepare($sql);
        $result->bindParam(':site_account_id', $user_id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $status
     *
     * @return bool|string
     */
    public static function getStatusBlockAccount($status)
    {
        switch($status)
        {
            case 0:
                return 'Активный';
                break;
            case 1:
                return 'Блокирование завтра';
                break;
            case 2:
                return 'Заблокирован';
                break;
            default;
                return 'Не известный статус';
        }
        return true;
    }
}