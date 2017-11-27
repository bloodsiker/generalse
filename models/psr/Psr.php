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

        $sql = 'INSERT INTO site_gm_psr '
            . '(id, id_user, serial_number, part_number, device_name, manufacture_date, purchase_date, defect_description, device_condition, 
                complectation, note, declaration_number, status_name, ready)'
            . 'VALUES '
            . '(:id, :id_user, :serial_number, :part_number, :device_name, :manufacture_date, :purchase_date, :defect_description, :device_condition, 
                :complectation, :note, :declaration_number, :status_name, :ready)';

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

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }



    /**
     * Получаем последний id_site
     * @return mixed
     */
    public static function getLastMotoId()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT TOP 1 site_id FROM site_gm_service_objects ORDER BY site_id DESC";
        $result = $db->prepare($sql);
        $result->execute();
        $row = $result->fetch();
        return $row['site_id'];
    }


    /**
     * Список заявок на регистрацию ПСР для конкретного парнера и пользователей которыми он может управлять
     * @param $array_id
     * @return array
     */
    public static function getPsrByPartner($array_id)
    {
        $db = MySQL::getConnection();

        $idS = implode(',', $array_id);

        $sql = "SELECT
                    gp.*,
                    gu.name_partner,
                    (SELECT 
                        count(gpd.id) 
                    FROM gm_psr_documents gpd
                    WHERE gpd.id_psr = gp.id) as count_file
                FROM gm_psr gp
                INNER JOIN gs_user gu
                  ON gp.id_user = gu.id_user
                WHERE gp.id_user IN({$idS})
                ORDER BY gp.id DESC";

        $result = $db->prepare($sql);
        //$result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Search by Psr
     * @param $search
     * @param string $filter
     * @return array
     */
    public static function getSearchInPsr($search, $filter = '')
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                 gp.*,
                 gu.name_partner,
                 (SELECT 
                     count(gpd.id) 
                 FROM gm_psr_documents gpd
                 WHERE gpd.id_psr = gp.id) as count_file
             FROM gm_psr gp
             INNER JOIN gs_user gu
               ON gp.id_user = gu.id_user
             WHERE 1 = 1 {$filter}
             AND(gp.serial_number LIKE ?
             OR gp.part_number LIKE ?
             OR gp.device_name LIKE ?
             OR gp.declaration_number LIKE ?
             OR gp.declaration_number_return LIKE ?
             OR gu.name_partner LIKE ?)
             ORDER BY gp.id DESC";

        $result = $db->prepare($sql);
        $result->execute(array("%$search%", "%$search%", "%$search%", "%$search%", "%$search%", "%$search%"));
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Список всех заявок на регистрацию ПСР
     * @return array
     */
    public static function getAllPsr()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                  gp.*,
                  gu.name_partner,
                  (SELECT 
                        count(gpd.id) 
                    FROM gm_psr_documents gpd
                    WHERE gpd.id_psr = gp.id) as count_file
                 FROM gm_psr gp
                 INNER JOIN gs_user gu
                    ON gp.id_user = gu.id_user
                 ORDER BY gp.id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    public static function getAllPsrMsSQL()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                  *
                 FROM gm_psr gp
                 ORDER BY gp.id DESC LIMIT 2";

        $result = $db->prepare($sql);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Поиск ПСР по ID
     * @param $psr_id
     * @param $id_user
     * @return mixed
     */
    public static function getPsrById($psr_id, $id_user)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                  *
                 FROM gm_psr
                 WHERE id = :id
                 AND id_user = :id_user";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $psr_id, PDO::PARAM_INT);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();
        $find = $result->fetch(PDO::FETCH_ASSOC);
        return $find;
    }


    /**
     * Обновляем номер декларации для ПСР
     * @param $id_psr
     * @param $declaration_number
     * @param $name_column
     * @return bool
     */
    public static function addNumberDeclarationByPsr($id_psr, $declaration_number, $name_column)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gm_psr
            SET
                {$name_column} = :declaration_number
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id_psr, PDO::PARAM_INT);
        $result->bindParam(':declaration_number', $declaration_number, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Оновялем SO Number
     * @param $id_psr
     * @param $so_number
     * @return bool
     */
    public static function editSoNumberByPsr($id_psr, $so_number)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gm_psr
            SET
                so_number = :so_number
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id_psr, PDO::PARAM_INT);
        $result->bindParam(':so_number', $so_number, PDO::PARAM_STR);
        return $result->execute();
    }



    /**
     * Изменяем Status в ПСР
     * @param $id_psr
     * @param $status
     * @return bool
     */
    public static function editStatusByPsr($id_psr, $status)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gm_psr
            SET
                status_name = :status_name
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id_psr, PDO::PARAM_INT);
        $result->bindParam(':status_name', $status, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * @param $id_psr
     * @param $file_name
     * @param $file_path
     * @return bool
     */
    public static function addDocumentInPsr($id_psr, $file_path, $file_name)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_psr_documents '
            . '(id_psr, file_path, file_name)'
            . 'VALUES '
            . '(:id_psr, :file_path, :file_name)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_psr', $id_psr, PDO::PARAM_INT);
        $result->bindParam(':file_path', $file_path, PDO::PARAM_STR);
        $result->bindParam(':file_name', $file_name, PDO::PARAM_STR);
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
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  * 
                FROM gm_psr_documents
                WHERE id_psr = :psr_id
                ORDER BY id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':psr_id', $psr_id, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    public static function getAllDocuments()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  * 
                FROM gm_psr_documents
                ORDER BY id DESC LIMIT 2";

        $result = $db->prepare($sql);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
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
            case 'Отказано':
                return 'red';
                break;
            case 'Зарегистрирован':
                return 'orange';
                break;
            case 'Принято, в обработке':
                return 'yellow';
                break;
        }
        return true;
    }

}