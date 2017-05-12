<?php

/**
 * Запрет к разделам
 * Class Access
 */
class Denied
{

    /**
     * Получаем список страниц в системе
     * @param int $p_id
     * @param int $main 1 - меню в шапке
     * @param $button
     * @return array
     */
    public static function getListPageInSystem($p_id = 0, $main = 1, $button = 0)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT * FROM gs_list_page WHERE p_id = :p_id AND main = :main AND button = :button ORDER BY sort";

        $result = $db->prepare($sql);
        $result->bindParam(':p_id', $p_id, PDO::PARAM_INT);
        $result->bindParam(':main', $main, PDO::PARAM_INT);
        $result->bindParam(':button', $button, PDO::PARAM_INT);
        $result->execute();
        $denied = $result->fetchAll(PDO::FETCH_ASSOC);

        return $denied;
    }

    /**
     * Получем список разделов запрещенных к просмотру пользователем
     * @param $id_user
     * @return array
     */
    public static function getDeniedByUser($id_user)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT * FROM gs_user_denied WHERE id_user = :id_user";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();
        $denied = $result->fetchAll(PDO::FETCH_ASSOC);

        return $denied;
    }

    /**
     *
     * @param $id_user
     * @param $name
     * @return array
     */
    public static function getCountDeniedByUser($id_user, $name)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT count(*) as count FROM gs_user_denied WHERE id_user = :id_user AND name = :name";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->execute();
        $denied = $result->fetch(PDO::FETCH_ASSOC);

        return $denied;
    }

    /**
     * Добавляем раздел для запрета просмотра пользователем
     * @param $id_user
     * @param $name
     * @param $slug
     * @return bool
     */
    public static function addDeniedSlugInUser($id_user, $name, $slug)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_user_denied '
            . '(id_user, name, slug)'
            . 'VALUES '
            . '(:id_user, :name, :slug)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Удаляем раздел из списка запрещенных для просмотра юзером
     * @param $id_user
     * @param $name
     * @param $slug
     * @return bool
     */
    public static function deleteSlugInUser($id_user, $name, $slug)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'DELETE FROM gs_user_denied WHERE id_user = :id_user AND name = :name AND slug = :slug';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        return $result->execute();
    }

}