<?php

namespace Umbrella\models\site;

use PDO;
use Umbrella\components\Db\MySQL;

class SeoMeta
{
    /**
     * @param $pagename
     *
     * @return mixed
     */
    public static function getSeoForPage($pagename)
    {
        $db = MySQL::getConnection();
        $sql = 'SELECT * FROM site_seo_meta WHERE pagename = :pagename LIMIT 1';
        $result = $db->prepare($sql);
        $result->bindParam(':pagename', $pagename, PDO::PARAM_STR);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}