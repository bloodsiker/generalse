<?php

namespace Umbrella\models\lithographer;

use PDO;
use Umbrella\components\Db\MySQL;

class File
{

    public static function addFile($id, $file_path, $file_name, $file_name_real)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_lithographer_file '
            . '(id_lithographer, file_path, file_name, file_name_real) '
            . 'VALUES '
            . '(:id_lithographer, :file_path, :file_name, :file_name_real)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_lithographer', $id, PDO::PARAM_INT);
        $result->bindParam(':file_path', $file_path, PDO::PARAM_STR);
        $result->bindParam(':file_name', $file_name, PDO::PARAM_STR);
        $result->bindParam(':file_name_real', $file_name_real, PDO::PARAM_STR);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }


    /**
     *
     * @param $id_lithographer
     *
     * @return array
     */
    public static function getAllFilesById($id_lithographer)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT *
                  FROM gs_lithographer_file
                  WHERE id_lithographer = :id_lithographer
                  ORDER BY id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':id_lithographer', $id_lithographer, PDO::PARAM_STR);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Info file
     * @param $id
     *
     * @return mixed
     */
    public static function getInfoFilesById($id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT *
                  FROM gs_lithographer_file
                  WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_STR);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Удаляем запись
     * @param $id
     * @return bool
     */
    public static function deleteFileById($id)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gs_lithographer_file WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * @param $id
     *
     * @return bool
     */
    public static function addCountDownloadFile($id)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_lithographer_file
            SET
                download = download + 1
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }
}