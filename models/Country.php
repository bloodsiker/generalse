<?php

class Country
{

    public static function getCountryId($id_country)
    {
        // Соединение с базой данных
        $db = MySQL::getConnection();

        // Делаем запрос к базе данных
        $sql = 'SELECT * FROM gs_country WHERE id_country = :id_country';

        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_country', $id_country, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }


    /**
     * @param $options
     * @return int|string
     */
    public static function addCountry($options)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_country '
            . '(short_name, full_name)'
            . 'VALUES '
            . '(:short_name, :full_name)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':short_name', $options['short_name'], PDO::PARAM_STR);
        $result->bindParam(':full_name', $options['full_name'], PDO::PARAM_STR);

        if ($result->execute()) {
            // Если запрос выполенен успешно, возвращаем id добавленной записи
            return $db->lastInsertId();
        }
        // Иначе возвращаем 0
        return 0;
    }


    /**
     * @param $id
     * @param $options
     * @return bool
     */
    public static function updateCountry($id, $options)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE gs_country
            SET
                short_name = :short_name,
                full_name = :full_name
            WHERE id_country = :id_country";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_country', $id, PDO::PARAM_INT);
        $result->bindParam(':short_name', $options['short_name'], PDO::PARAM_STR);
        $result->bindParam(':full_name', $options['full_name'], PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Получаем весь список стран
     * @return array
     */
    public static function getAllCountry()
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $data = $db->query('SELECT * FROM gs_country ORDER BY full_name')->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }


    /**
     * @param $id
     * @return bool
     */
    public static function deleteCountryById($id)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'DELETE FROM gs_country WHERE id_country = :id_country';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_country', $id, PDO::PARAM_INT);
        return $result->execute();
    }

}