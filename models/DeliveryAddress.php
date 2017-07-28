<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;

/**
 * Class DeliveryAddress
 */
class DeliveryAddress
{

    public static function getAddressByPartner($id_user)
    {
        // Соединение с базой данных
        $db = MySQL::getConnection();

        // Делаем запрос к базе данных
        $sql = "SELECT 
                  *
                FROM gs_user_delivery_address guda
                WHERE guda.id_user = :id_user";

        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

}