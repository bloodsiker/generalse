<?php

namespace Umbrella\models\site;

use PDO;
use Umbrella\components\Db\MySQL;

class ServiceCenter
{

    public static function getCountrySC()
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT DISTINCT country_code, country_ru FROM site_service_center';

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllServiceCenter ()
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT * FROM site_service_center ORDER BY company_name';

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function addData($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO site_service_center '
            . '(country_code, country_ru, company_name, city_ru, address_ru, phone, specialization_ru) '
            . 'VALUES '
            . '(:country_code, :country_ru, :company_name, :city_ru, :address_ru, :phone, :specialization_ru)';

        $result = $db->prepare($sql);
        foreach ($options as $value){
            $result->execute([
                'country_code' => 'ua',
                'country_ru' => 'Украина',
                'company_name' => $value['company_name'],
                'city_ru' => $value['city'],
                'address_ru' => $value['address'],
                'phone' => $value['phone'],
                'specialization_ru' => $value['specialization']
            ]);
        }
    }
}