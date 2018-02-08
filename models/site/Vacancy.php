<?php

namespace Umbrella\models\site;

use PDO;
use Umbrella\components\Db\MySQL;

class Vacancy
{

    /**
     * @param $published
     *
     * @return array
     */
    public static function getAllVacancy($published = 1)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT * FROM site_vacancy WHERE published = :published ORDER BY id DESC';

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
    public static function getVacancyBySlug($slug)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT * FROM site_vacancy WHERE published = 1 AND slug = :slug LIMIT 1';

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
    public static function addVacancy($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO site_vacancy '
            . '(slug, en_title, en_department, en_location, en_employment, en_text, ru_title, ru_department, 
                ru_location, ru_employment, ru_text, published)'
            . 'VALUES '
            . '(:slug, :en_title, :en_department, :en_location, :en_employment, :en_text, :ru_title, :ru_department, 
                :ru_location, :ru_employment, :ru_text, :published)';

        $result = $db->prepare($sql);
        $result->bindParam(':slug', $options['slug'], PDO::PARAM_STR);
        $result->bindParam(':en_title', $options['en_title'], PDO::PARAM_STR);
        $result->bindParam(':en_department', $options['en_department'], PDO::PARAM_STR);
        $result->bindParam(':en_location', $options['en_location'], PDO::PARAM_STR);
        $result->bindParam(':en_employment', $options['en_employment'], PDO::PARAM_STR);
        $result->bindParam(':en_text', $options['en_text'], PDO::PARAM_STR);
        $result->bindParam(':ru_title', $options['ru_title'], PDO::PARAM_STR);
        $result->bindParam(':ru_department', $options['ru_department'], PDO::PARAM_STR);
        $result->bindParam(':ru_location', $options['ru_location'], PDO::PARAM_STR);
        $result->bindParam(':ru_employment', $options['ru_employment'], PDO::PARAM_STR);
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
    public static function updateVacancy($slug, $options)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE site_vacancy
            SET
                en_title = :en_title,
                en_department = :en_department,
                en_location = :en_location,
                en_employment = :en_employment,
                en_text = :en_text,
                ru_title = :ru_title,
                ru_department = :ru_department,
                ru_location = :ru_location,
                ru_employment = :ru_employment,
                ru_text = :ru_text,
                published = :published
            WHERE slug = :slug";

        $result = $db->prepare($sql);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        $result->bindParam(':en_title', $options['en_title'], PDO::PARAM_STR);
        $result->bindParam(':en_department', $options['en_department'], PDO::PARAM_STR);
        $result->bindParam(':en_location', $options['en_location'], PDO::PARAM_STR);
        $result->bindParam(':en_employment', $options['en_employment'], PDO::PARAM_STR);
        $result->bindParam(':en_text', $options['en_text'], PDO::PARAM_STR);
        $result->bindParam(':ru_title', $options['ru_title'], PDO::PARAM_STR);
        $result->bindParam(':ru_department', $options['ru_department'], PDO::PARAM_STR);
        $result->bindParam(':ru_location', $options['ru_location'], PDO::PARAM_STR);
        $result->bindParam(':ru_employment', $options['ru_employment'], PDO::PARAM_STR);
        $result->bindParam(':ru_text', $options['ru_text'], PDO::PARAM_STR);
        $result->bindParam(':published', $options['published'], PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * @param $slug
     *
     * @return bool
     */
    public static function deleteVacancy($slug)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM site_vacancy WHERE slug = :slug';

        $result = $db->prepare($sql);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        return $result->execute();
    }
}