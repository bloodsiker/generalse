<?php

namespace Umbrella\models\hr;

use PDO;
use Umbrella\components\Db\MySQL;

/**
 * Class Band
 * @package Umbrella\models\hr
 */
class Band
{

    /**
     * get all band
     * @return array
     */
    public static function getAllBand()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT * FROM gs_hr_band";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * get info band by id
     * @param $id
     * @return mixed
     */
    public static function getBandById($id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  *
                FROM gs_hr_band
                WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}