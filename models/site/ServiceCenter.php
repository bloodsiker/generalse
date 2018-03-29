<?php

namespace Umbrella\models\site;

use PDO;
use Umbrella\components\Db\MySQL;

class ServiceCenter
{

    /**
     * @return array
     */
    public static function getCountrySC()
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT DISTINCT country_code, country_ru, country_en FROM site_service_center';

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public static function find($id)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT * FROM site_service_center WHERE id = :id LIMIT 1';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_STR);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * @param string $sort
     *
     * @param string $order
     *
     * @return array
     */
    public static function getAllServiceCenter($sort = 'company_name', $order = 'ASC')
    {
        $db = MySQL::getConnection();

        $sql = "SELECT * FROM site_service_center ORDER BY {$sort} {$order}";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function addServiceCenter($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO site_service_center '
            . '(country_code, country_ru, company_name, city_ru, address_ru, phone, specialization_ru, 
                country_en, company_name_en, city_en, address_en, specialization_en)'
            . 'VALUES '
            . '(:country_code, :country_ru, :company_name, :city_ru, :address_ru, :phone, :specialization_ru,
                :country_en, :company_name_en, :city_en, :address_en, :specialization_en)';

        $result = $db->prepare($sql);
        $result->bindParam(':country_code', $options['country_code'], PDO::PARAM_STR);
        $result->bindParam(':country_ru', $options['country_ru'], PDO::PARAM_STR);
        $result->bindParam(':company_name', $options['company_name'], PDO::PARAM_STR);
        $result->bindParam(':city_ru', $options['city_ru'], PDO::PARAM_STR);
        $result->bindParam(':address_ru', $options['address_ru'], PDO::PARAM_STR);
        $result->bindParam(':phone', $options['phone'], PDO::PARAM_STR);
        $result->bindParam(':specialization_ru', $options['specialization_ru'], PDO::PARAM_STR);
        $result->bindParam(':country_en', $options['country_en'], PDO::PARAM_STR);
        $result->bindParam(':company_name_en', $options['company_name_en'], PDO::PARAM_STR);
        $result->bindParam(':city_en', $options['city_en'], PDO::PARAM_STR);
        $result->bindParam(':address_en', $options['address_en'], PDO::PARAM_STR);
        $result->bindParam(':specialization_en', $options['specialization_en'], PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * @param $id
     * @param $options
     *
     * @return bool
     */
    public static function update($id, $options)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE site_service_center
            SET
                country_code = :country_code,
                country_ru = :country_ru,
                company_name = :company_name,
                city_ru = :city_ru,
                address_ru = :address_ru,
                phone = :phone,
                specialization_ru = :specialization_ru,
                country_en = :country_en,
                company_name_en = :company_name_en,
                city_en = :city_en,
                address_en = :address_en,
                specialization_en = :specialization_en
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':country_code', $options['country_code'], PDO::PARAM_STR);
        $result->bindParam(':country_ru', $options['country_ru'], PDO::PARAM_STR);
        $result->bindParam(':company_name', $options['company_name'], PDO::PARAM_STR);
        $result->bindParam(':city_ru', $options['city_ru'], PDO::PARAM_STR);
        $result->bindParam(':address_ru', $options['address_ru'], PDO::PARAM_STR);
        $result->bindParam(':phone', $options['phone'], PDO::PARAM_STR);
        $result->bindParam(':specialization_ru', $options['specialization_ru'], PDO::PARAM_STR);
        $result->bindParam(':country_en', $options['country_en'], PDO::PARAM_STR);
        $result->bindParam(':company_name_en', $options['company_name_en'], PDO::PARAM_STR);
        $result->bindParam(':city_en', $options['city_en'], PDO::PARAM_STR);
        $result->bindParam(':address_en', $options['address_en'], PDO::PARAM_STR);
        $result->bindParam(':specialization_en', $options['specialization_en'], PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * @param $country_code
     *
     * @return mixed
     */
    public static function swapCountry($country_code)
    {
        switch ($country_code){
            case 'ua':
                $result['ru'] = 'Украина';
                $result['en'] = 'Ukraine';
                break;
            case 'by':
                $result['ru'] = 'Беларусь';
                $result['en'] = 'Belarus';
                break;
            case 'am':
                $result['ru'] = 'Армения';
                $result['en'] = 'Armenia';
                break;
            case 'md':
                $result['ru'] = 'Молдова';
                $result['en'] = 'Moldova';
                break;
            case 'ge':
                $result['ru'] = 'Грузия';
                $result['en'] = 'Georgia';
                break;
            default:
                $result['ru'] = 'Украина';
                $result['en'] = 'Ukraine';
        }
        return $result;
    }


    /**
     * @param $options
     */
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


    /**
     * @param $id
     *
     * @return bool
     */
    public static function delete($id)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM site_service_center WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }
}