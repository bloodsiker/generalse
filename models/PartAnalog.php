<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;

class PartAnalog
{

    /**
     * Добавляем новые парт номера и аналоги
     * @param $part_number
     * @param $part_analog
     * @return bool
     */
    public static function addPartAnalog($part_number, $part_analog)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gm_part_analog '
            . '(part_number, part_analog)'
            . 'VALUES '
            . '(:part_number, :part_analog)';

        $result = $db->prepare($sql);
        $result->bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $result->bindParam(':part_analog', $part_analog, PDO::PARAM_STR);

        return $result->execute();
    }


    /**
     * Получаем список парт номеров и аналогов
     * @return array
     */
    public static function getListPartAnalog()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                 *
                 FROM gm_part_analog
                 ORDER BY id DESC";

        $result = $db->prepare($sql);
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
                 part_analog
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