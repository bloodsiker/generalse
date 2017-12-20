<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;

/**
 * Class DeliveryAddress
 */
class DeliveryAddress
{

    /**
     * @param $id_user
     * @return array
     */
    public static function getAddressByPartner($id_user)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  *
                FROM gs_user_delivery_address guda
                WHERE guda.id_user = :id_user";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Find Address by id
     * @param $id
     * @return mixed
     */
    public static function getAddressById($id)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT * FROM gs_user_delivery_address WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetch();
    }


    /**
     * @param $id_user
     * @param $address
     * @param $phone
     *
     * @return int|string
     */
    public static function addAddress($id_user, $address, $phone)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_user_delivery_address '
            . '(address, phone, id_user)'
            . 'VALUES '
            . '(:address, :phone, :id_user)';

        $result = $db->prepare($sql);
        $result->bindParam(':address', $address, PDO::PARAM_STR);
        $result->bindParam(':phone', $phone, PDO::PARAM_STR);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Update user address
     *
     * @param $id
     * @param $address
     * @param $phone
     *
     * @return bool
     */
    public static function updateAddress($id, $address, $phone)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_user_delivery_address
            SET
                address = :address,
                phone = :phone
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':address', $address, PDO::PARAM_STR);
        $result->bindParam(':phone', $phone, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Delete User address
     * @param $id
     * @return bool
     */
    public static function deleteUserAddress($id)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gs_user_delivery_address WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

}