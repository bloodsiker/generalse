<?php

namespace Umbrella\models\api\hr;

use PDO;
use Umbrella\components\Db\MySQL;

/**
 * Class File
 * @package Umbrella\models\hr
 */
class File
{

    /**
     * @param $options
     * @return int|string
     */
    public static function addFile($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_hr_upload_file '
            . '(form_user_id, file_type, file_name)'
            . 'VALUES '
            . '(:form_user_id, :file_type, :file_name)';

        $result = $db->prepare($sql);
        $result->bindParam(':form_user_id', $options['form_user_id'], PDO::PARAM_INT);
        $result->bindParam(':file_type', $options['file_type'], PDO::PARAM_STR);
        $result->bindParam(':file_name', $options['file_name'], PDO::PARAM_STR);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }

    /**
     * @param $form_user_id
     * @param $file_type
     * @return array
     */
    public static function getFileByFormUserLabel($form_user_id, $file_type)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  *
                FROM gs_hr_upload_file
                WHERE form_user_id = :form_user_id
                AND file_type = :file_type
                ORDER BY id LIMIT 1";

        $result = $db->prepare($sql);
        $result->bindParam(':form_user_id', $form_user_id, PDO::PARAM_INT);
        $result->bindParam(':file_type', $file_type, PDO::PARAM_STR);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Delete file
     * @param $form_user_id
     * @param $file_type
     * @return bool
     */
    public static function deleteFile($form_user_id, $file_type)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gs_hr_upload_file 
                WHERE form_user_id = :form_user_id
                AND file_type = :file_type';

        $result = $db->prepare($sql);
        $result->bindParam(':form_user_id', $form_user_id, PDO::PARAM_INT);
        $result->bindParam(':file_type', $file_type, PDO::PARAM_STR);
        return $result->execute();
    }
}