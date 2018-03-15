<?php

namespace Umbrella\models\lithographer;

use PDO;
use Umbrella\components\Db\MySQL;

class Lithographer
{

    public static function addVideo($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_lithographer '
            . '(type_row, id_author, published, description, title, text, file_path, file_name) '
            . 'VALUES '
            . '(:type_row, :id_author, :published, :description, :title, :text, :file_path, :file_name)';

        $result = $db->prepare($sql);
        $result->bindParam(':type_row', $options['type_row'], PDO::PARAM_STR);
        $result->bindParam(':id_author', $options['id_author'], PDO::PARAM_INT);
        $result->bindParam(':published', $options['published'], PDO::PARAM_INT);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':title', $options['title'], PDO::PARAM_STR);
        $result->bindParam(':text', $options['text'], PDO::PARAM_STR);
        $result->bindParam(':file_path', $options['file_path'], PDO::PARAM_STR);
        $result->bindParam(':file_name', $options['file_name'], PDO::PARAM_STR);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }

    /**
     * Закрываем просмотр для пользователя
     * @param $user_id
     * @param $id
     * @return bool
     */
    public static function addUserViewClose($user_id, $id)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_lithographer_view_close '
            . '(id_user, id_lithographer) '
            . 'VALUES '
            . '(:id_user, :id_lithographer)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $user_id, PDO::PARAM_INT);
        $result->bindParam(':id_lithographer', $id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Получаем записи для выбраного раздела
     *
     * @param $type_row
     * @param $user_id
     *
     * @return array
     */
    public static function getAllContent($type_row, $user_id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT *
                  FROM gs_lithographer
                  WHERE published = 1
                  AND type_row = :type_row
                  AND id NOT IN(SELECT id_lithographer FROM gs_lithographer_view_close WHERE id_user = :user_id)
                  ORDER BY id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':type_row', $type_row, PDO::PARAM_STR);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Search content
     * @param $search
     *
     * @return array
     */
    public static function getSearchContent($search)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT *
                  FROM gs_lithographer
                  WHERE published = 1
                  AND type_row NOT IN ('video')
                  AND description LIKE ?
                  OR title LIKE ? 
                  OR text LIKE ?
                  ORDER BY id DESC";

        $result = $db->prepare($sql);
        $result->execute(array("%$search%", "%$search%", "%$search%"));
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Получам список пользователей, для которых запрещен просмотр записи
     * @param $id_lithographer
     * @return mixed
     */
    public static function getUsersCloseViewById($id_lithographer)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT id_user
                FROM gs_lithographer_view_close
                WHERE id_lithographer = :id_lithographer";

        $result = $db->prepare($sql);
        $result->bindParam(':id_lithographer', $id_lithographer, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Получаем список статей закрытых для просмотра, для этого пользователя
     * @param $id_user
     * @return array
     */
    public static function getArticleCloseViewByIdUser($id_user)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT id_lithographer
                FROM gs_lithographer_view_close
                WHERE id_user = :id_user";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Получаем информацию о записи
     * @param $id
     * @return mixed
     */
    public static function getContentById($id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT *
                  FROM gs_lithographer
                  WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Получаем весь список публикаций для админа
     * @return array
     */
    public static function getAllContentByAdmin()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                  gl.id,
                  gl.type_row,
                  gl.published,
                  gl.description,
                  gl.title,
                  gl.text,
                  gu.name_partner
                FROM gs_lithographer gl
                  INNER JOIN gs_user gu
                    ON gl.id_author = gu.id_user
                ORDER BY id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Получаем весь список публикаций для партнера
     * @param $id_author
     * @return array
     */
    public static function getAllContentByPartner($id_author)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT *
                  FROM gs_lithographer
                  WHERE id_author = :id_author
                  ORDER BY id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':id_author', $id_author, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Обновляем запись
     * @param $id
     * @param $options
     * @return bool
     */
    public static function updateArticleById($id, $options)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_lithographer
            SET
                published = :published,
                description = :description,
                title = :title,
                text = :text
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':published', $options['published'], PDO::PARAM_INT);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':title', $options['title'], PDO::PARAM_STR);
        $result->bindParam(':text', $options['text'], PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public static function updateViewArticleById($id)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_lithographer
            SET
                count_view = count_view + 1
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Удаляем запись
     * @param $id
     * @return bool
     */
    public static function deleteArticleById($id)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gs_lithographer WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Удаляем пользователей с таблицы, в которой запрещаем просмотр записи
     * @param $id
     * @return bool
     */
    public static function deleteUserInCloseViewArticleById($id)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gs_lithographer_view_close WHERE id_lithographer = :id_lithographer';

        $result = $db->prepare($sql);
        $result->bindParam(':id_lithographer', $id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * @param $status
     *
     * @return bool|string
     */
    public static function getPublished($status)
    {
        switch($status)
        {
            case '1':
                return 'Published';
                break;
            case '0':
                return 'Unpublished';
                break;
        }
        return true;
    }


    /**
     * @param $status
     *
     * @return bool|string
     */
    public static function getClassPublished($status)
    {
        switch($status)
        {
            case '1':
                return 'green';
                break;
            case '0':
                return 'red';
                break;
        }
        return true;
    }

}