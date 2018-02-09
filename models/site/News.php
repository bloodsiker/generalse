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
    public static function getAllNewsFoSite($published = 1)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT * FROM site_news WHERE published = :published ORDER BY created_at DESC';

        $result = $db->prepare($sql);
        $result->bindParam(':published', $published, PDO::PARAM_STR);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public static function getAllNews()
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT * FROM site_news ORDER BY id DESC';

        $result = $db->prepare($sql);
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

        $sql = 'SELECT * FROM site_news WHERE slug = :slug LIMIT 1';

        $result = $db->prepare($sql);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * @param $options
     *
     * @return bool
     */
    public static function add($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO site_news '
            . '(image, slug, en_title, en_description, en_text, ru_title, ru_description, 
                ru_text, published)'
            . 'VALUES '
            . '(:image, :slug, :en_title, :en_description, :en_text, :ru_title, :ru_description, 
                :ru_text, :published)';

        $result = $db->prepare($sql);
        $result->bindParam(':image', $options['image'], PDO::PARAM_STR);
        $result->bindParam(':slug', $options['slug'], PDO::PARAM_STR);
        $result->bindParam(':en_title', $options['en_title'], PDO::PARAM_STR);
        $result->bindParam(':en_description', $options['en_description'], PDO::PARAM_STR);
        $result->bindParam(':en_text', $options['en_text'], PDO::PARAM_STR);
        $result->bindParam(':ru_title', $options['ru_title'], PDO::PARAM_STR);
        $result->bindParam(':ru_description', $options['ru_description'], PDO::PARAM_STR);
        $result->bindParam(':ru_text', $options['ru_text'], PDO::PARAM_STR);
        $result->bindParam(':published', $options['published'], PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * @param $slug
     * @param $options
     *
     * @return bool
     */
    public static function update($slug, $options)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE site_news
            SET
                image = :image,
                en_title = :en_title,
                en_description = :en_description,
                en_text = :en_text,
                ru_title = :ru_title,
                ru_description = :ru_description,
                ru_text = :ru_text,
                published = :published
            WHERE slug = :slug";

        $result = $db->prepare($sql);
        $result->bindParam(':image', $options['image'], PDO::PARAM_STR);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        $result->bindParam(':en_title', $options['en_title'], PDO::PARAM_STR);
        $result->bindParam(':en_description', $options['en_description'], PDO::PARAM_STR);
        $result->bindParam(':en_text', $options['en_text'], PDO::PARAM_STR);
        $result->bindParam(':ru_title', $options['ru_title'], PDO::PARAM_STR);
        $result->bindParam(':ru_description', $options['ru_description'], PDO::PARAM_STR);
        $result->bindParam(':ru_text', $options['ru_text'], PDO::PARAM_STR);
        $result->bindParam(':published', $options['published'], PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * @param $slug
     *
     * @return bool
     */
    public static function delete($slug)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM site_news WHERE slug = :slug';

        $result = $db->prepare($sql);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        return $result->execute();
    }
}