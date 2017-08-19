<?php
namespace Umbrella\models\ccc;

use PDO;
use Umbrella\components\Db\MySQL;

class KnowledgeArticle
{

    /**
     * Добавление статьи
     * @param $options
     * @return int|string
     */
    public static function addArticle($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_ccc_knowledge_articles '
            . '(id_category, id_user, title, description, text, published) '
            . 'VALUES '
            . '(:id_category, :id_user, :title, :description, :text, :published)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_category', $options['id_category'], PDO::PARAM_INT);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':title', $options['title'], PDO::PARAM_STR);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':text', $options['text'], PDO::PARAM_STR);
        $result->bindParam(':published', $options['published'], PDO::PARAM_INT);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }



    /**
     * Редактируем статью
     * @param $id
     * @param $options
     * @return bool
     */
    public static function updateArticleById($id, $options)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_ccc_knowledge_articles
            SET
                id_category = :id_category,
                title = :title,
                description = :description,
                text = :text,
                published = :published,
                updated_at = :updated_at
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':id_category', $options['id_category'], PDO::PARAM_INT);
        $result->bindParam(':title', $options['title'], PDO::PARAM_STR);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':text', $options['text'], PDO::PARAM_STR);
        $result->bindParam(':published', $options['published'], PDO::PARAM_INT);
        $result->bindParam(':updated_at', $options['updated_at'], PDO::PARAM_STR);
        return $result->execute();
    }



    /**
     * Информация о статье
     * @param $id
     * @return mixed
     */
    public static function getArticlesById($id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                    gcka.id,
                    gcka.id_category,
                    gcka.title,
                    gcka.description,
                    gcka.text,
                    gcka.published,
                    gcka.created_at,
                    gcka.updated_at,
                    gckc.name,
                    gckc.slug
                    FROM gs_ccc_knowledge_articles gcka
                        INNER JOIN gs_ccc_knowledge_category gckc
                            ON gcka.id_category = gckc.id
                WHERE gcka.id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        $all = $result->fetch(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Список популярный статей
     * @return array
     */
    public static function getPopularArticles()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                 gcva.id_article as id,
                 count(gcva.id_article) AS count,
                 gcka.title,
                 gcka.description,
                 gcka.text,
                 gcka.created_at,
                 gcka.updated_at,
                 gckc.customer,
                 gckc.slug,
                 gckc.name
                FROM gs_ccc_view_articles gcva
                    INNER JOIN gs_ccc_knowledge_articles gcka
                        ON gcka.id = gcva.id_article
                    INNER JOIN gs_ccc_knowledge_category gckc
                        ON gckc.id = gcka.id_category
                WHERE gcka.delete_article = 0
                AND gcka.published = 1
                AND gckc.customer != 'lenovo'
                GROUP BY gcva.id_article
                ORDER BY count DESC
                LIMIT 10";

        $result = $db->prepare($sql);
        $result->execute();
        $all = $result->fetchaLL(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Список всех статей
     * @return array
     */
    public static function getAllArticlesAdmin()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  gcka.id,
                  gcka.id_category,
                  gcka.title,
                  gcka.text,
                  gcka.published,
                  gcka.created_at,
                  gcka.updated_at,
                  gckc.name,
                  gckc.customer,
                  gckc.slug,
                  gu.name_partner
                FROM gs_ccc_knowledge_articles gcka
                INNER JOIN gs_ccc_knowledge_category gckc
                  ON gcka.id_category = gckc.id
                INNER JOIN gs_user gu
                    ON gcka.id_user = gu.id_user
                WHERE gcka.delete_article = 0
                ORDER BY gcka.id DESC";

        $result = $db->prepare($sql);
        $result->execute();

        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Список статей в категории
     * @param $id_category
     * @return array
     */
    public static function getArticlesByCategory($id_category)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                    gcka.id,
                    gcka.id_category,
                    gcka.title,
                    gcka.description,
                    gcka.text,
                    gcka.created_at,
                    gcka.updated_at,
                    gckc.name,
                    gckc.customer,
                    gckc.slug
                FROM gs_ccc_knowledge_articles gcka
                INNER JOIN gs_ccc_knowledge_category gckc
                    ON gcka.id_category = gckc.id
                WHERE gcka.published = 1
                AND gcka.id_category = :id_category
                AND gcka.delete_article = 0
                AND gckc.enabled = 0
                ORDER BY gcka.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':id_category', $id_category, PDO::PARAM_INT);
        $result->execute();

        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Удаляем статью(мягкое удаление)
     * @param $id
     * @return bool
     */
    public static function deleteArticleById($id)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_ccc_knowledge_articles
            SET
                delete_article = 1
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }
}