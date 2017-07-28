<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;

class File
{

    public static function addWarrantyFile($options)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_upload_file '
            . '(id_warranty, name_real, file_path, file_name) '
            . 'VALUES '
            . '(:id_warranty, :name_real, :file_path, :file_name)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_warranty', $options['id_warranty'], PDO::PARAM_INT);
        $result->bindParam(':name_real', $options['name_real'], PDO::PARAM_STR);
        $result->bindParam(':file_path', $options['file_path'], PDO::PARAM_STR);
        $result->bindParam(':file_name', $options['file_name'], PDO::PARAM_STR);
        return $result->execute();
    }


    public static function fileByWarranty($id_warranty)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT *
                  FROM gs_upload_file
                  WHERE id_warranty = :id_warranty";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_warranty', $id_warranty, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

}