<?php

namespace Umbrella\models\site;

use PDO;
use Umbrella\components\Db\MySQL;

class Client
{

    /**
     * Registration new Client
     * @param $options
     *
     * @return bool
     */
    public static function registrationClient($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO client '
            . '(country, fio, company, email, login, phone, address, group_products, message, page)'
            . 'VALUES '
            . '(:country, :fio, :company, :email, :login, :phone, :address, :group_products, :message, :page)';

        $result = $db->prepare($sql);
        $result->bindParam(':country', $options['country'], PDO::PARAM_INT);
        $result->bindParam(':fio', $options['fio'], PDO::PARAM_STR);
        $result->bindParam(':company', $options['company'], PDO::PARAM_STR);
        $result->bindParam(':email', $options['email'], PDO::PARAM_STR);
        $result->bindParam(':login', $options['login'], PDO::PARAM_STR);
        $result->bindParam(':phone', $options['phone'], PDO::PARAM_STR);
        $result->bindParam(':address', $options['address'], PDO::PARAM_STR);
        $result->bindParam(':group_products', $options['group_products'], PDO::PARAM_STR);
        $result->bindParam(':message', $options['message'], PDO::PARAM_STR);
        $result->bindParam(':page', $options['page'], PDO::PARAM_STR);
        return $result->execute();
    }
}