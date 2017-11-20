<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;

class PartAnalog
{

    /**
     * Добавляем новые парт номера и аналоги
     * @param $options
     * @return bool
     */
    public static function addPartAnalog($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_part_analog '
            . '(part_number, part_analog, type_part, comment)'
            . 'VALUES '
            . '(:part_number, :part_analog, :type_part, :comment)';

        $result = $db->prepare($sql);
        $result->bindParam(':part_number', $options['part_number'], PDO::PARAM_STR);
        $result->bindParam(':part_analog', $options['part_analog'], PDO::PARAM_STR);
        $result->bindParam(':type_part', $options['type_part'], PDO::PARAM_STR);
        $result->bindParam(':comment', $options['comment'], PDO::PARAM_STR);

        return $result->execute();
    }


    /**
     * Получаем список парт номеров и аналогов
     * @param $type_part
     * @return array
     */
    public static function getListPartAnalog($type_part)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                 *
                 FROM gm_part_analog
                 WHERE type_part = :type_part
                 ORDER BY id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':type_part', $type_part, PDO::PARAM_STR);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Получаем конкретный аналог по парт номеру
     * @param $part_number
     * @return mixed
     */
    public static function getAnalogByPartNumber($part_number)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                 part_number,
                 part_analog,
                 type_part,
                 comment
                 FROM gm_part_analog
                 WHERE part_number = :part_number
                 ORDER BY id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $result->execute();
        $all = $result->fetch(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Обновляем парт номер и аналог
     * @param $id_record
     * @param $part_number
     * @param $part_analog
     * @return bool
     */
    public static function updatePartNumberAndAnalog($id_record, $part_number, $part_analog)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gm_part_analog
            SET
                part_number = :part_number,
                part_analog = :part_analog
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id_record, PDO::PARAM_INT);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $result->bindParam(':part_analog', $part_analog, PDO::PARAM_STR);
        return $result->execute();
    }
}