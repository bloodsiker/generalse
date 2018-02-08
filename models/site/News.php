<?php

namespace Umbrella\models\site;

use PDO;
use Umbrella\components\Db\MySQL;

class News
{

    /**
     * @param $published
     *
     * @return array
     */
    public static function getAllNews($published = 1)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT * FROM site_news WHERE published = :published ORDER BY created_at DESC';

        $result = $db->prepare($sql);
        $result->bindParam(':published', $published, PDO::PARAM_STR);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $slug
     *
     * @return mixed
     */
    public static function getNewBySlug($slug)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT * FROM site_news WHERE published = 1 AND slug = :slug LIMIT 1';

        $result = $db->prepare($sql);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}