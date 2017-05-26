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
     * Получем список разделов запрещенных к просмотру группой пользователей
     * @param $id_group
     * @return array
     */
    public static function getDeniedByGroup($id_group)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT * FROM gs_group_denied WHERE id_group = :id_group";

        $result = $db->prepare($sql);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
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
     * @param null $id_group
     * @return bool
     */
    public static function addDeniedSlugInUser($id_user, $name, $slug ,$id_group = null)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_user_denied '
            . '(id_user, name, slug, id_group)'
            . 'VALUES '
            . '(:id_user, :name, :slug, :id_group)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Добавляем раздел для запрета просмотра группой
     * @param $id_group
     * @param $name
     * @param $slug
     * @return bool
     */
    public static function addDeniedSlugInGroup($id_group, $name, $slug)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_group_denied '
            . '(id_group, name, slug)'
            . 'VALUES '
            . '(:id_group, :name, :slug)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
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


    /**
     * Удаляем все запрещенные страницы, которые относяться к группе в которой находился пользователь
     * @param $id_user
     * @param $id_group
     * @return bool
     */
    public static function deleteDeniedUserFromGroup($id_user, $id_group)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'DELETE FROM gs_user_denied WHERE id_user = :id_user AND id_group = :id_group';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * При удалении запретной страницы из группы, она удаляеться у всех членов группы
     * @param $id_group
     * @param $slug
     * @return bool
     */
    public static function deleteUserFromGroupByDenied($id_group, $slug)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'DELETE FROM gs_user_denied WHERE id_group = :id_group AND slug =:slug';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Удаляем раздел из списка запрещенных для просмотра группой
     * @param $id_group
     * @param $name
     * @param $slug
     * @return bool
     */
    public static function deleteSlugInGroup($id_group, $name, $slug)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'DELETE FROM gs_group_denied WHERE id_group = :id_group AND name = :name AND slug = :slug';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        return $result->execute();
    }

}