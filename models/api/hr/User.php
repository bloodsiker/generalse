<?php

namespace Umbrella\models\api\hr;

use PDO;
use Umbrella\components\Db\MySQL;

/**
 * Class Band
 * @package Umbrella\models\hr
 */
class Staff
{

    /**
     * get all staff
     * @return array
     */
    public static function getAll()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT * FROM gs_hr_staff";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}