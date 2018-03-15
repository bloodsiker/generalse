<?php

namespace Umbrella\models\psr;

use PDO;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Db\MsSQL;

class Psr
{

    /**
     * Создаем новый ПСР
     * @param $options
     * @return bool
     */
    public static function addPsr($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_psr '
            . '(id_user, serial_number, part_number, device_name, manufacture_date, purchase_date, defect_description, device_condition, 
                complectation, note, declaration_number, status_name)'
            . 'VALUES '
            . '(:id_user, :serial_number, :part_number, :device_name, :manufacture_date, :purchase_date, :defect_description, :device_condition, 
                :complectation, :note, :declaration_number, :status_name)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':serial_number', $options['serial_number'], PDO::PARAM_STR);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':device_name', $options['device_name'], PDO::PARAM_STR);
        $result->bindParam(':manufacture_date', $options['manufacture_date'], PDO::PARAM_STR);
        $result->bindParam(':purchase_date', $options['purchase_date'], PDO::PARAM_STR);
        $result->bindParam(':defect_description', $options['defect_description'], PDO::PARAM_STR);
        $result->bindParam(':device_condition', $options['device_condition'], PDO::PARAM_STR);
        $result->bindParam(':complectation', $options['complectation'], PDO::PARAM_STR);
        $result->bindParam(':note', $options['note'], PDO::PARAM_STR);
        $result->bindParam(':declaration_number', $options['declaration_number'], PDO::PARAM_STR);
        $result->bindParam(':status_name', $options['status_name'], PDO::PARAM_STR);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }


    public static function addPsrMsSQL($options)
    {
        $db = MsSQL::getConnection();

        $options['ready'] = 1;

        $sql = 'INSERT INTO site_gm_psr '
            . '(id, id_user, serial_number, part_number, device_name, manufacture_date, purchase_date, defect_description, device_condition, 
                complectation, note, declaration_number, status_name, ready, created_at)'
            . 'VALUES '
            . '(:id, :id_user, :serial_number, :part_number, :device_name, :manufacture_date, :purchase_date, :defect_description, :device_condition, 
                :complectation, :note, :declaration_number, :status_name, :ready, :created_at)';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $options['id'], PDO::PARAM_INT);
        $result->bindParam(':id_user', $options['id_user'], PDO::PARAM_INT);
        $result->bindParam(':serial_number', $options['serial_number'], PDO::PARAM_STR);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':device_name', $options['device_name'], PDO::PARAM_STR);
        $result->bindParam(':manufacture_date', $options['manufacture_date'], PDO::PARAM_STR);
        $result->bindParam(':purchase_date', $options['purchase_date'], PDO::PARAM_STR);
        $result->bindParam(':defect_description', $options['defect_description'], PDO::PARAM_STR);
        $result->bindParam(':device_condition', $options['device_condition'], PDO::PARAM_STR);
        $result->bindParam(':complectation', $options['complectation'], PDO::PARAM_STR);
        $result->bindParam(':note', $options['note'], PDO::PARAM_STR);
        $result->bindParam(':declaration_number', $options['declaration_number'], PDO::PARAM_STR);
        $result->bindParam(':status_name', $options['status_name'], PDO::PARAM_STR);
        $result->bindParam(':ready', $options['ready'], PDO::PARAM_INT);
        $result->bindParam(':created_at', $options['created_at'], PDO::PARAM_STR);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }



    /**
     * Получаем последний id_site
     * @return mixed
     */
    public static function getLasId()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT TOP 1 id FROM site_gm_psr ORDER BY id DESC";
        $result = $db->prepare($sql);
        $result->execute();
        $row = $result->fetch();
        return $row['id'];
    }


    /**
     * Список заявок на регистрацию ПСР для конкретного парнера и пользователей которыми он может управлять
     *
     * @param $array_id
     * @param string $filter
     *
     * @return array
     */
    public static function getPsrByPartnerMsSQL($array_id, $filter = '')
    {
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

        $sql = "SELECT
                 sgp.*,
                 sgu.site_client_name,
                 (SELECT 
                    count(sgpd.id) 
                 FROM site_gm_psr_documents sgpd
                 WHERE sgpd.id_psr = sgp.id) as count_file
                FROM site_gm_psr sgp
                INNER JOIN site_gm_users sgu
                    ON sgp.id_user = sgu.site_account_id
                WHERE sgp.id_user IN({$idS}) {$filter}
                ORDER BY sgp.id DESC";

        $result = $db->prepare($sql);
        //$result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Search by Psr
     * @param $search
     * @param string $filter
     * @return array
     */
    public static function getSearchInPsr($search, $filter = '')
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                 sgp.*,
                 sgu.site_client_name,
                 (SELECT 
                    count(sgpd.id) 
                 FROM site_gm_psr_documents sgpd
                 WHERE sgpd.id_psr = sgp.id) as count_file
             FROM site_gm_psr sgp
                 INNER JOIN site_gm_users sgu
                    ON sgp.id_user = sgu.site_account_id
             WHERE 1 = 1 {$filter}
             AND(sgp.serial_number LIKE ?
             OR sgp.part_number LIKE ?
             OR sgp.device_name LIKE ?
             OR sgp.declaration_number LIKE ?
             OR sgp.declaration_number_return LIKE ?
             OR sgu.site_client_name LIKE ?)
             ORDER BY sgp.id DESC";

        $result = $db->prepare($sql);
        $result->execute(array("%$search%", "%$search%", "%$search%", "%$search%", "%$search%", "%$search%"));
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $filter
     *
     * @return array
     */
    public static function getAllPsrMsSQL($filter)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                  sgp.*,
                  sgu.site_client_name,
                  (SELECT 
                        count(sgpd.id) 
                    FROM site_gm_psr_documents sgpd
                    WHERE sgpd.id_psr = sgp.id) as count_file
                 FROM site_gm_psr sgp
                 INNER JOIN site_gm_users sgu
                    ON sgp.id_user = sgu.site_account_id
                 WHERE 1 = 1 {$filter}
                 ORDER BY sgp.id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * find PSR by ID
     * @param $id_psr
     *
     * @return array
     */
    public static function findPsrById($id_psr)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                  sgp.*,
                  sgu.site_client_name
                 FROM site_gm_psr sgp
                 INNER JOIN site_gm_users sgu
                    ON sgp.id_user = sgu.site_account_id
                 WHERE sgp.id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id_psr, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Обновляем номер декларации для ПСР
     * @param $id_psr
     * @param $declaration_number
     * @param $name_column
     * @return bool
     */
    public static function addNumberDeclarationByPsr($id_psr, $declaration_number, $name_column, $ready)
    {
        $db = MsSQL::getConnection();

        $sql = "UPDATE site_gm_psr
            SET
                {$name_column} = :declaration_number,
                ready = :ready
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id_psr, PDO::PARAM_INT);
        $result->bindParam(':declaration_number', $declaration_number, PDO::PARAM_STR);
        $result->bindParam(':ready', $ready, PDO::PARAM_INPUT_OUTPUT);
        return $result->execute();
    }


    /**
     * @param $id_psr
     * @param $file_path
     * @param $file_name
     * @param $ready
     * @return bool
     */
    public static function addDocumentInPsrMsSQL($id_psr, $file_path, $file_name, $ready)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO site_gm_psr_documents '
            . '(id_psr, file_path, file_name, ready)'
            . 'VALUES '
            . '(:id_psr, :file_path, :file_name, :ready)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_psr', $id_psr, PDO::PARAM_INT);
        $result->bindParam(':file_path', $file_path, PDO::PARAM_STR);
        $result->bindParam(':file_name', $file_name, PDO::PARAM_STR);
        $result->bindParam(':ready', $ready, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Получаем список файлов прикрепленных к данному ПСР
     * @param $psr_id
     * @return array
     */
    public static function getAllDocumentsInPsr($psr_id)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT 
                  * 
                FROM site_gm_psr_documents
                WHERE id_psr = :psr_id
                ORDER BY id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':psr_id', $psr_id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $status
     * @return bool|string
     */
    public static function getStatusRequest($status)
    {
        switch($status)
        {
            case 'Выдан':
                return 'green';
                break;
            case 'Зарегистрирован':
                return 'orange';
                break;
            case 'Принят в ремонт':
                return 'yellow';
                break;
            case 'Ремонт завершен':
                return 'aqua';
                break;
        }
        return true;
    }

}