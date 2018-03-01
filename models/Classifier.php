<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MsSQL;

class Classifier
{

    /**
     * @return array
     */
    public static function allClassifiers()
    {
        $db = MsSQL::getConnection();

        $sql = 'SELECT I_D, mName FROM tbl_Classifier WHERE to_site = 1 ORDER BY mName';

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}