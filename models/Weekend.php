<?php


class Weekend
{

    public static function getWeekendById($id)
    {
        // Соединение с базой данных
        $db = MySQL::getConnection();

        // Делаем запрос к базе данных
        $sql = 'SELECT * FROM gs_user_weekend WHERE id = :id';

        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }


    public static function updateWeekend($id, $options)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE gs_user_weekend
            SET
                date_weekend = :date_weekend,
                description = :description
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':date_weekend', $options['date_weekend'], PDO::PARAM_STR);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        return $result->execute();
    }


    public static function getWeekendByUser($id_user)
    {
        // Соединение с базой данных
        $db = MySQL::getConnection();

        // Делаем запрос к базе данных
        $sql = 'SELECT * FROM gs_user_weekend WHERE id_user = :id_user';

        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function addWeekend($id_user, $date_weekend, $description)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_user_weekend '
            . '(id_user, date_weekend, description)'
            . 'VALUES '
            . '(:id_user, :date_weekend, :description)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':date_weekend', $date_weekend, PDO::PARAM_STR);
        $result->bindParam(':description', $description, PDO::PARAM_STR);

        if ($result->execute()) {
            // Если запрос выполенен успешно, возвращаем id добавленной записи
            return $db->lastInsertId();
        }
        // Иначе возвращаем 0
        return 0;
    }


    public static function deleteWeekendById($id)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'DELETE FROM gs_user_weekend WHERE id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

}