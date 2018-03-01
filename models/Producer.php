<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MsSQL;

class Producer
{

    /**
     * @return array
     */
    public static function allProducers()
    {
        $db = MsSQL::getConnection();

        $sql = 'SELECT I_D, ShortName FROM tbl_Produsers WHERE to_site = 1 ORDER BY ShortName';

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}