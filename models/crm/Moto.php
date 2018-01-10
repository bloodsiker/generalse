<?php

namespace Umbrella\models\crm;

use PDO;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Db\MsSQL;

class Moto
{

    /**
     *  Пишем в заявку CREATE NE REPAIR в MSSQL
     * @param $options
     * @return bool
     */
    public static function addServiceObjectsMsSQL($options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO dbo.site_gm_service_objects '
            . '(site_id, site_account_id, client_name, client_phone, client_email, serial_number, part_number, problem_description, purchase_date, carry_in_date, ready)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :client_name, :client_phone, :client_email, :serial_number, :part_number, :problem_description, :purchase_date, :carry_in_date, :ready)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['site_account_id'], PDO::PARAM_INT);
        $result->bindParam(':client_name', $options['client_name'], PDO::PARAM_STR);
        $result->bindParam(':client_phone', $options['client_phone'], PDO::PARAM_STR);
        $result->bindParam(':client_email', $options['client_email'], PDO::PARAM_STR);
        $result->bindParam(':serial_number', $options['serial_number'], PDO::PARAM_STR);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':problem_description', $options['problem_description'], PDO::PARAM_STR);
        $result->bindParam(':purchase_date', $options['purchase_date'], PDO::PARAM_STR);
        $result->bindParam(':carry_in_date', $options['carry_in_date'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Пишем в заявку CREATE NE REPAIR в MySQL
     * @param $options
     * @return bool
     */
    public static function addServiceObjects($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_service_objects '
            . '(site_id, site_account_id, client_name, client_phone, client_email, serial_number, part_number, goods_name, problem_description, purchase_date, carry_in_date)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :client_name, :client_phone, :client_email, :serial_number, :part_number, :goods_name, :problem_description, :purchase_date, :carry_in_date)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['site_account_id'], PDO::PARAM_INT);
        $result->bindParam(':client_name', $options['client_name'], PDO::PARAM_STR);
        $result->bindParam(':client_phone', $options['client_phone'], PDO::PARAM_STR);
        $result->bindParam(':client_email', $options['client_email'], PDO::PARAM_STR);
        $result->bindParam(':serial_number', $options['serial_number'], PDO::PARAM_STR);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':goods_name', $options['goods_name'], PDO::PARAM_STR);
        $result->bindParam(':problem_description', $options['problem_description'], PDO::PARAM_STR);
        $result->bindParam(':purchase_date', $options['purchase_date'], PDO::PARAM_STR);
        $result->bindParam(':carry_in_date', $options['carry_in_date'], PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Пишем бинарник в базу MS SQL
     * @param $options
     * @return bool
     */
    public static function addDocumentServiceObjectsMsSql($options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO dbo.site_gm_service_objects_documents '
            . '(site_id, file_name, file_data)'
            . 'VALUES '
            . '(:site_id, :file_name, :file_data)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':file_name', $options['file_name'], PDO::PARAM_STR);
        $result->bindParam(':file_data', $options['file_data']);


        return $result->execute();
    }


    /**
     * Запись изображения
     * @param $options
     * @return bool
     */
    public static function addDocumentServiceObjects($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_service_objects_documents '
            . '(site_id, file_path, file_name, real_file_name)'
            . 'VALUES '
            . '(:site_id, :file_path, :file_name, :real_file_name)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':file_path', $options['file_path'], PDO::PARAM_STR);
        $result->bindParam(':file_name', $options['file_name'], PDO::PARAM_STR);
        $result->bindParam(':real_file_name', $options['real_file_name'], PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Add parts MsSQL
     * @param $options
     * @return bool
     */
    public static function addPartsMsSQL($options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO dbo.site_gm_service_objects_elements '
            . '(site_id, site_account_id, part_number, serial_number, operation_type, ready)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :part_number, :serial_number, :operation_type,  :ready)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['site_account_id'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':serial_number', $options['serial_number'], PDO::PARAM_STR);
        $result->bindParam(':operation_type', $options['operation_type'], PDO::PARAM_INT);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Add parts MYSQL
     * @param $options
     * @return bool
     */
    public static function addParts($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_service_objects_elements '
            . '(site_id, site_account_id, part_number, price)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :part_number, :price)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['site_account_id'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Add local source
     * @param $options
     * @return bool
     */
    public static function addLocalSourceMsSQL($options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO dbo.site_gm_service_objects_elements '
            . '(site_id, site_account_id, part_number, serial_number, price, operation_type, ready)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :part_number, :serial_number, :price, :operation_type,  :ready)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['site_account_id'], PDO::PARAM_INT);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':serial_number', $options['serial_number'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':operation_type', $options['operation_type'], PDO::PARAM_INT);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Close Repair
     * @param $options
     * @return bool
     */
    public static function closeRepairMsSQL($options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO dbo.site_gm_service_objects_elements '
            . '(site_id, site_account_id, complete_date, serial_number, repair_level, operation_type, ready)'
            . 'VALUES '
            . '(:site_id, :site_account_id, :complete_date, :serial_number, :repair_level, :operation_type,  :ready)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $options['site_id'], PDO::PARAM_INT);
        $result->bindParam(':site_account_id', $options['site_account_id'], PDO::PARAM_INT);
        $result->bindParam(':complete_date', $options['complete_date'], PDO::PARAM_STR);
        $result->bindParam(':serial_number', $options['serial_number'], PDO::PARAM_STR);
        $result->bindParam(':repair_level', $options['repair_level'], PDO::PARAM_STR);
        $result->bindParam(':operation_type', $options['operation_type'], PDO::PARAM_INT);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);

        return $result->execute();
    }


    /**
     * Получаем последний id_site
     * @return mixed
     */
    public static function getLastMotoId()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT MAX(site_id) FROM site_gm_service_objects";

        $result = $db->prepare($sql);
        $result->execute();
        $row = $result->fetch();
        return $row['site_id'];
    }

    /**
     *
     * @param $id_partner
     * @param $status
     * @return array
     */
    public static function getAllMotoByPartner($id_partner, $status)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                 sgso.site_id,
                 sgso.site_account_id,
                 sgso.service_object_id,
                 sgso.client_name,
                 sgso.client_phone,
                 sgso.client_email,
                 sgso.serial_number,
                 sgso.part_number,
                 sgso.goods_name,
                 sgso.problem_description,
                 sgso.purchase_date,
                 sgso.carry_in_date,
                 sgso.status_name,
                 sgu.site_client_name
                 FROM site_gm_service_objects sgso
                 INNER JOIN site_gm_users sgu
                    ON sgso.site_account_id = sgu.site_account_id
                 WHERE sgso.site_account_id = :id_user
                 AND sgso.object_type = 0 {$status}
                 ORDER BY sgso.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_partner, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Показать все заявки
     * @param $filter
     * @return array
     */
    public static function getAllMoto($filter = '')
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                 sgso.site_id,
                 sgso.site_account_id,
                 sgso.service_object_id,
                 sgso.client_name,
                 sgso.client_phone,
                 sgso.client_email,
                 sgso.serial_number,
                 sgso.part_number,
                 sgso.goods_name,
                 sgso.problem_description,
                 sgso.purchase_date,
                 sgso.carry_in_date,
                 sgso.status_name,
                 sgu.site_client_name
                 FROM site_gm_service_objects sgso
                 INNER JOIN site_gm_users sgu
                    ON sgso.site_account_id = sgu.site_account_id
                 WHERE sgso.object_type = 0 {$filter}
                ORDER BY sgso.id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Показваем детально заявку
     * @param $site_id
     * @return array
     */
    public static function getShowMoto($site_id)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT * FROM site_gm_service_objects_elements WHERE site_id = :site_id";

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $site_id
     * @return array
     */
    public static function getShowDocumentByMoto($site_id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT * FROM gm_service_objects_documents WHERE site_id = :site_id";

        $result = $db->prepare($sql);
        $result->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

}