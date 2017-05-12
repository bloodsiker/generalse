<?php

class Branch
{

    /**
     * Полуаем список бранчей
     * @return array
     */
    public static function getBranchList()
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $result = $db->query("SELECT * FROM gs_branch")->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Добавляем новый бранч
     * @param $options
     * @return int|string
     */
    public static function addBranch($options)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_branch '
            . '(branch_name)'
            . 'VALUES '
            . '(:branch_name)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':branch_name', $options['branch_name'], PDO::PARAM_STR);

        if ($result->execute()) {
            // Если запрос выполенен успешно, возвращаем id добавленной записи
            return $db->lastInsertId();
        }
        // Иначе возвращаем 0
        return 0;
    }

    /**
     * Список партнеров находящиеся в бранче
     * @param $id_branch
     * @return mixed
     */
    public static function getPartnerByBranch($id_branch)
    {
        // Соединение с базой данных
        $db = MySQL::getConnection();

        // Делаем запрос к базе данных
        $sql = "SELECT 
                    gu.id_user,
                    gu.id_role,
                    gu.name_partner,
                    gr.role,
                    gr.name_role
                FROM gs_branch_users gbu
                INNER JOIN gs_user gu
                    ON gbu.id_user = gu.id_user
                INNER JOIN gs_branch gb
                    ON gbu.id_branch = gb.id_branch
                INNER JOIN gs_role gr
                    ON gu.id_role = gr.id_role
                WHERE gbu.id_branch = :id_branch";

        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_branch', $id_branch, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Список пользователей входящих в бранч исключая бранч-фин
     * @param $id_branch
     * @return array
     */
    public static function getPartnerByBranchNotInFin($id_branch)
    {
        // Соединение с базой данных
        $db = MySQL::getConnection();

        // Делаем запрос к базе данных
        $sql = "SELECT 
                    gu.id_user,
                    gu.id_role,
                    gu.name_partner,
                    gu.date_create,
                    gr.role,
                    gr.name_role
                FROM gs_branch_users gbu
                INNER JOIN gs_user gu
                    ON gbu.id_user = gu.id_user
                INNER JOIN gs_branch gb
                    ON gbu.id_branch = gb.id_branch
                INNER JOIN gs_role gr
                    ON gu.id_role = gr.id_role
                WHERE gbu.id_branch = :id_branch
                AND gr.role <> 'branch-fin' ";

        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_branch', $id_branch, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Все пользователи которые находяться в бранчах
     * @return array
     */
    public static function getAllUserBranch()
    {
        // Соединение с базой данных
        $db = MySQL::getConnection();

        // Делаем запрос к базе данных
        $sql = "SELECT 
                   gbu.id_branch,
                   gu.id_user
                 FROM gs_branch_users gbu
                 INNER JOIN gs_user gu
                     ON gbu.id_user = gu.id_user
                 INNER JOIN gs_branch gb
                     ON gbu.id_branch = gb.id_branch";

        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':id_branch', $id_branch, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Добавляем пользователя в бранч
     * @param $id_user
     * @param $id_branch
     * @return int|string
     */
    public static function addUserInBranch($id_user, $id_branch)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_branch_users '
            . '(id_user, id_branch)'
            . 'VALUES '
            . '(:id_user, :id_branch)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':id_branch', $id_branch, PDO::PARAM_INT);

        if ($result->execute()) {
            // Если запрос выполенен успешно, возвращаем id добавленной записи
            return $db->lastInsertId();
        }
        // Иначе возвращаем 0
        return 0;
    }

    /**
     * Удаляем связь пользователя и бранча
     * @param $id_user
     * @param $id_branch
     * @return bool
     */
    public static function deleteUserInBranch($id_user, $id_branch)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'DELETE FROM gs_branch_users WHERE id_user = :id_user AND id_branch = :id_branch';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':id_branch', $id_branch, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Проверяем, принадлежит ли пользователь к любому бранчу
     * @param $array_user
     * @param $id_user
     * @return bool
     */
    public static function checkUserInBranch($array_user, $id_user)
    {
        $found_key = in_array($id_user, array_column($array_user, 'id_user'));
        if($found_key){
            return true;
        }
        return false;
    }

}