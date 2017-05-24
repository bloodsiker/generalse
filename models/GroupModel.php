<?php

/**
 * Class Group
 */
class Group
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
}