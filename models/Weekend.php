<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;

class Weekend
{

    /**
     * @param $id
     * @return mixed
     */
    public static function getWeekendById($id)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT * FROM gs_user_weekend WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetch();
    }


    /**
     * @param $id
     * @param $options
     * @return bool
     */
    public static function updateWeekend($id, $options)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_user_weekend
            SET
                date_weekend = :date_weekend,
                description = :description
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':date_weekend', $options['date_weekend'], PDO::PARAM_STR);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * @param $id_user
     * @return array
     */
    public static function getWeekendByUser($id_user)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT * FROM gs_user_weekend WHERE id_user = :id_user';

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $id_user
     * @param $date_weekend
     * @param $description
     * @return int|string
     */
    public static function addWeekend($id_user, $date_weekend, $description)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_user_weekend '
            . '(id_user, date_weekend, description)'
            . 'VALUES '
            . '(:id_user, :date_weekend, :description)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':date_weekend', $date_weekend, PDO::PARAM_STR);
        $result->bindParam(':description', $description, PDO::PARAM_STR);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }


    /**
     * @param $id
     * @return bool
     */
    public static function deleteWeekendById($id)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gs_user_weekend WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

}