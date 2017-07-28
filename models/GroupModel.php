<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;

/**
 * Class Group
 */
class GroupModel
{

    /**
     * Список групп для пользователей
     * @return array
     */
    public static function getGroupList()
    {
        $db = MySQL::getConnection();

        $result = $db->query("SELECT * FROM gs_group")->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
     * Название группы
     * @param $id_group
     * @return array
     */
    public static function getNameGroup($id_group)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                gg.group_name
                FROM gs_group gg
                WHERE gg.id = :id_group";

        $result = $db->prepare($sql);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
        $result->execute();

        $user = $result->fetch(PDO::FETCH_ASSOC);
        return $user;
    }


    /**
     * Добавляем новую группу
     * @param $options
     * @return bool
     */
    public static function addGroup($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_group '
            . '(group_name)'
            . 'VALUES '
            . '(:group_name)';

        $result = $db->prepare($sql);
        $result->bindParam(':group_name', $options['group_name'], PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Добавляем пользователя в группу
     * @param $id_group
     * @param $id_user
     * @return bool
     */
    public static function addUserGroup($id_group, $id_user)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_group_user '
            . '(id_group, id_user)'
            . 'VALUES '
            . '(:id_group, :id_user)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Получаем массив пользователей находящихся в группе
     * @param $id_group
     * @return array
     */
    public static function getUsersByGroup($id_group)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                ggu.id_user,
                gu.name_partner
                FROM gs_group_user ggu
                    INNER JOIN gs_user gu
                        ON ggu.id_user = gu.id_user
                WHERE ggu.id_group = :id_group";

        $result = $db->prepare($sql);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
        $result->execute();

        $user = $result->fetchAll(PDO::FETCH_ASSOC);
        return $user;
    }


    /**
     * Список всех пользователей в группах
     * @return array
     */
    public static function getAllUsersGroup()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                ggu.id_user,
                gu.name_partner
                FROM gs_group_user ggu
                    INNER JOIN gs_user gu
                        ON ggu.id_user = gu.id_user";

        $result = $db->prepare($sql);
        $result->execute();

        $user = $result->fetchAll(PDO::FETCH_ASSOC);
        return $user;
    }


    /**
     * Возвращаем ID группы, в которой стостоит пользователь
     * @param $id_user
     * @return mixed
     */
    public static function getIdGroupUser($id_user)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                ggu.id_group
                FROM gs_group_user ggu
                    INNER JOIN gs_user gu
                        ON ggu.id_user = gu.id_user
                WHERE ggu.id_user = :id_user";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();

        $user = $result->fetch(PDO::FETCH_ASSOC);
        return $user;
    }


    /**
     * Delete a user from a group
     * @param $id_group
     * @param $id_user
     * @return bool
     */
    public static function deleteUserFromGroup($id_group, $id_user)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gs_group_user WHERE id_user = :id_user AND id_group = :id_group';

        $result = $db->prepare($sql);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Список складов привязанных к группе
     * @param $id_group
     * @param $section
     * @return array
     */
    public static function getStocksFromGroup($id_group, $section)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                ggs.id as id_row,
                ggs.id_stock,
                gs.stock_name,
                gs.id
                FROM gs_group_stock ggs
                    INNER JOIN gs_stocks gs
                        ON ggs.id_stock = gs.id
                WHERE ggs.id_group = :id_group AND ggs.section = :section";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
        $result->bindParam(':section', $section, PDO::PARAM_STR);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Удаляем склад в группе
     * @param $id
     * @return bool
     */
    public static function deleteStockFromGroup($id)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gs_group_stock WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Добавляем склад в группу
     * @param $id_group
     * @param $id_stock
     * @param $section
     * @return bool
     */
    public static function addStockGroup($id_group, $id_stock, $section)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_group_stock '
            . '(id_group, id_stock, section)'
            . 'VALUES '
            . '(:id_group, :id_stock, :section)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
        $result->bindParam(':id_stock', $id_stock, PDO::PARAM_INT);
        $result->bindParam(':section', $section, PDO::PARAM_STR);
        return $result->execute();
    }
}