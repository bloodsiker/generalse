<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MsSQL;
use Umbrella\components\Db\MySQL;

/**
 * Class DeliveryAddress
 */
class DeliveryAddress
{
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


    /*************************  MS SQL  *************************************/

    /**
     * get info address
     * @param $id
     *
     * @return mixed
     */
    public static function getAddressByIdMsSQL($id)
    {
        $db = MsSQL::getConnection();

        $sql = 'SELECT * FROM site_gm_clients_stocks WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetch();
    }


    /**
     * Add new address and phone partner
     * @param $options
     *
     * @return bool
     */
    public static function addAddressMsSQL($options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO site_gm_clients_stocks '
            . '(site_account_id, address, phone)'
            . 'VALUES '
            . '(:site_account_id, :address, :phone)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_account_id', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':address', $options['address'], PDO::PARAM_STR);
        $result->bindParam(':phone', $options['phone'], PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * get all address by partner
     * @param $id_user
     *
     * @return array
     */
    public static function getAddressByPartnerMsSQL($id_user)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT 
                  *
                FROM site_gm_clients_stocks
                WHERE site_account_id = :site_account_id";

        $result = $db->prepare($sql);
        $result->bindParam(':site_account_id', $id_user, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * update address or phone by partner
     * @param $options
     *
     * @return bool
     */
    public static function updateAddressMsSQL($options)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_clients_stocks
            SET
                address = :address,
                phone = :phone
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $options['id'], PDO::PARAM_INT);
        $result->bindParam(':address', $options['address'], PDO::PARAM_STR);
        $result->bindParam(':phone', $options['phone'], PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * update default address
     *
     * @param $id
     * @param $isDefault
     *
     * @return bool
     */
    public static function updateDefaultAddressMsSQL($id, $isDefault)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_clients_stocks
            SET
                is_default = :is_default
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':is_default', $isDefault, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * clear default address by user
     * @param $userId
     * @param int $isDefault
     *
     * @return bool
     */
    public static function clearDefaultAddressMsSQL($userId, $isDefault = 0)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_clients_stocks
            SET
                is_default = :is_default
            WHERE site_account_id = :site_account_id";

        $result = $db->prepare($sql);
        $result->bindParam(':site_account_id', $userId, PDO::PARAM_INT);
        $result->bindParam(':is_default', $isDefault, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Delete User address
     * @param $id
     * @return bool
     */
    public static function deleteUserAddressMsSQL($id)
    {
        $db = MsSQL::getConnection();

        $sql = 'DELETE FROM site_gm_clients_stocks WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }
}