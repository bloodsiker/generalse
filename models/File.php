<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;

class File
{

    /**
     * @param $options
     * @return bool
     */
    public static function addWarrantyFile($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_upload_file '
            . '(id_warranty, name_real, file_path, file_name) '
            . 'VALUES '
            . '(:id_warranty, :name_real, :file_path, :file_name)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_warranty', $options['id_warranty'], PDO::PARAM_INT);
        $result->bindParam(':name_real', $options['name_real'], PDO::PARAM_STR);
        $result->bindParam(':file_path', $options['file_path'], PDO::PARAM_STR);
        $result->bindParam(':file_name', $options['file_name'], PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * @param $id_warranty
     * @return array
     */
    public static function fileByWarranty($id_warranty)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT *
                  FROM gs_upload_file
                  WHERE id_warranty = :id_warranty";

        $result = $db->prepare($sql);
        $result->bindParam(':id_warranty', $id_warranty, PDO::PARAM_INT);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Пишем новый загруженный файл с ценами
     * @param $file_path
     * @param $file_name
     * @param $id_group
     * @param $partner_status
     * @param $created_at
     * @return bool
     */
    public static function addNewPriceFile($file_path, $file_name, $id_group, $partner_status, $created_at)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_upload_file '
            . '(file_path, file_name, id_group, partner_status, created_at) '
            . 'VALUES '
            . '(:file_path, :file_name, :id_group, :partner_status, :created_at)';

        $result = $db->prepare($sql);
        $result->bindParam(':file_path', $file_path, PDO::PARAM_STR);
        $result->bindParam(':file_name', $file_name, PDO::PARAM_STR);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
        $result->bindParam(':partner_status', $partner_status, PDO::PARAM_STR);
        $result->bindParam(':created_at', $created_at, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Последний загруженный файл для группы
     * @param $id_group
     * @param $partner_status
     * @return mixed
     */
    public static function getLastUploadFileForGroup($id_group, $partner_status)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                  *
                  FROM gs_upload_file
                  WHERE id_group = :id_group
                  AND partner_status = :partner_status
                  ORDER BY id_record DESC
                  LIMIT 1";

        $result = $db->prepare($sql);
        $result->bindParam(':id_group', $id_group, PDO::PARAM_INT);
        $result->bindParam(':partner_status', $partner_status, PDO::PARAM_STR);
        $result->execute();
        $all = $result->fetch(PDO::FETCH_ASSOC);
        return $all;
    }

}