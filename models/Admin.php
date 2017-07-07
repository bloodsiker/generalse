<?php

class Admin
{
    /**
     * @param $login
     * @param $password
     * @return bool
     */
    public static function checkAdminData($login, $password)
    {
        $db = MySQL::getConnection();

        //
        $sql = 'SELECT * FROM gs_user WHERE login = :login AND password = :password';

        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_INT);
        $result->execute();

        // Получаем ассоциативный массив
        $admin = $result->fetch();

        if ($admin) {
            // Если существует массив, то возращаем id пользователя
            return $admin['id_user'];
        }
        return false;
    }


    /**
     * @param $login
     * @return mixed
     */
    public static function checkUserLogin($login)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT count(login) as count FROM gs_user WHERE login = :login';

        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->execute();

        $count = $result->fetch();
        return $count['count'];
    }


    /**
     * Получаем id пользователя по имени
     * @param $user_name
     * @return mixed
     */
    public static function getIdUserByName($user_name)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT id_user FROM gs_user WHERE name_partner = :name_partner";

        // P0RM001PUA
        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':name_partner', $user_name, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
        $user = $result->fetch(PDO::FETCH_ASSOC);
        $user_id = $user['id_user'];

        return $user_id;
    }

    /**
     * Имя пользователя по ID
     * @param $id_user
     * @return mixed
     */
    public static function getNameById($id_user)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT name_partner FROM gs_user WHERE id_user = :id_user";

        // P0RM001PUA
        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();

        // Получаем ассоциативный массив
        $user = $result->fetch(PDO::FETCH_ASSOC);
        $name = $user['name_partner'];

        return $name;
    }

    /**
     * Получаем массив пользователе, которым может управлять данный пользователь
     * @param $id_user
     * @return array
     */
    public static function getControlUsersId($id_user)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                 gucs.control_user_id,
                 gu.name_partner
                 FROM gs_user_control_accaunt gucs
                INNER JOIN gs_user gu
                 ON gucs.control_user_id = gu.id_user
                 WHERE gucs.id_user = :id_user";

        // Делаем пдготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_STR);
        $result->execute();

        // Получаем ассоциативный массив
        $user = $result->fetchAll(PDO::FETCH_ASSOC);
        return $user;
    }

    /**
     * Добавляем пользователей в список для управления ими
     * @param $id_user
     * @param $control_user_id
     * @return int|string
     */
    public static function addUserControl($id_user, $control_user_id)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_user_control_accaunt '
            . '(id_user, control_user_id)'
            . 'VALUES '
            . '(:id_user, :control_user_id)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':control_user_id', $control_user_id, PDO::PARAM_INT);

        if ($result->execute()) {
            // Если запрос выполенен успешно, возвращаем id добавленной записи
            return $db->lastInsertId();
        }
        // Иначе возвращаем 0
        return 0;
    }

    /**
     * Удаляем пользователя из списка управляемых пользователем
     * @param $id_user
     * @param $control_user_id
     * @return bool
     */
    public static function deleteUserControl($id_user, $control_user_id)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'DELETE FROM gs_user_control_accaunt WHERE id_user = :id_user AND control_user_id = :control_user_id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':control_user_id', $control_user_id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Список всех пользоватей
     * @return array
     */
    public static function getAllUsers()
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $data = $db->query("SELECT
                                        gu.id_user,
                                        gu.id_role,
                                        gu.name_partner,
                                        gu.id_country,
                                        gu.login,
                                        gu.date_create,
                                        gu.date_active,
                                        gg.group_name,
                                        gr.id_role,
                                        gr.role,
                                        gr.name_role,
                                        gb.id_branch,
                                        gb.branch_name,
                                        gc.short_name,
                                        gc.full_name
                                    FROM gs_user gu
                                        INNER JOIN gs_role gr
                                        ON gu.id_role = gr.id_role
                                        LEFT JOIN gs_branch_users gbu
                                        ON gu.id_user = gbu.id_user
                                        LEFT JOIN gs_branch gb
                                            ON gb.id_branch = gbu.id_branch
                                        LEFT JOIN gs_group_user ggu
                                            ON ggu.id_user = gu.id_user
                                        LEFT JOIN gs_group gg
                                            ON gg.id = ggu.id_group
                                        INNER JOIN gs_country gc
                                            ON gu.id_country = gc.id_country
                                    ORDER BY gu.id_user")->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }


    /**
     * список всех партнеров
     * @return array
     */
    public static function getAllPartner()
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $data = $db->query("SELECT 
                                      * 
                                      FROM gs_user 
                                      INNER JOIN gs_country gc 
                                        ON gs_user.id_country = gc.id_country 
                                      WHERE id_role = 2 ORDER BY name_partner ASC")->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    /**
     * Список партнеров которыми может управлять пользователь
     * @param $users
     * @return array
     */
    public static function getPartnerControlUsers($users)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        $ids = implode(',', $users);

        $data = $db->query("SELECT 
                                      * 
                                      FROM gs_user 
                                      INNER JOIN gs_country gc 
                                        ON gs_user.id_country = gc.id_country 
                                      WHERE id_role = 2
                                      AND id_user IN({$ids}) ORDER BY name_partner ASC")->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    /**
     * Список партнеров, для которых показывать кпи
     * @param int $kpi_view
     * @return array
     */
    public static function getPartnerViewKpi($kpi_view = 0)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $data = $db->query("SELECT 
                                      * 
                                      FROM gs_user 
                                      INNER JOIN gs_country gc 
                                        ON gs_user.id_country = gc.id_country 
                                      WHERE id_role = 2 AND kpi_view = {$kpi_view}")->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }


    /**
     * //Если данные правильные, запоминаем пользователя в сессию
     * @param $adminId
     */
    public static function auth($adminId)
    {
        // После авторизации запоминаем id юзра в сессию.
        $_SESSION['user'] = $adminId;
        $_SESSION['_token'] = Functions::generateCode(30);
    }


    /**
     * Если существует сессия пользователя, возращаем ссесию
     * @return mixed
     */
    public static function checkLogged()
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        } else {
            header("Location: /");
        }
    }


    /**
     * Валидация логина на длину, если меньше двух символов, возращаем false
     * @param $login
     * @return bool
     */
    public static function checkLogin($login)
    {
        if (strlen($login) >= 2) {
            return true;
        }
        return false;
    }


    /**
     * Валидация пароля на длину, если меньше 6 символов, возращаем false
     * @param $password
     * @return bool
     */
    public static function checkPassword($password)
    {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }


    /**
     * Получаем информацию о пользователе
     * @param $id_user
     * @return mixed
     */
    public static function getAdminById($id_user)
    {
        // Соединение с базой данных
        $db = MySQL::getConnection();

        // Делаем запрос к базе данных
        $sql = 'SELECT
                   gu.id_user,
                   gu.id_role,
                   gu.name_partner,
                   gu.login,
                   gu.id_country,
                   gu.kpi_coefficient,
                   gu.kpi_view,
                   gu.date_create,
                   gu.date_active,
                   gr.id_role,
                   gr.role,
                   gr.name_role,
                   gg.id as id_group,
                   gg.group_name,
                   gc.id_country,
                   gc.short_name,
                   gc.full_name
                 FROM gs_user gu
                   INNER JOIN gs_role gr
                     ON gu.id_role = gr.id_role
                   LEFT JOIN gs_group_user ggu
                     ON gu.id_user = ggu.id_user
                   LEFT JOIN gs_group gg
                    ON gg.id = ggu.id_group
                   INNER JOIN gs_country gc
                    ON gu.id_country = gc.id_country
                 WHERE gu.id_user = :id_user';

        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }


    /**
     * Страница добавлния юзера
     * @param $options
     * @return bool
     */
    public static function addUser($options)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO gs_user '
            . '(id_role, name_partner, id_country, login, password, kpi_view, date_create)'
            . 'VALUES '
            . '(:id_role, :name_partner, :id_country, :login, :password, :kpi_view, :date_create)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_role', $options['id_role'], PDO::PARAM_INT);
        $result->bindParam(':name_partner', $options['name_partner'], PDO::PARAM_STR);
        $result->bindParam(':id_country', $options['id_country'], PDO::PARAM_INT);
        $result->bindParam(':login', $options['login'], PDO::PARAM_STR);
        $result->bindParam(':password', $options['password'], PDO::PARAM_STR);
        $result->bindParam(':kpi_view', $options['kpi_view'], PDO::PARAM_INT);
        $result->bindParam(':date_create', $options['date_create'], PDO::PARAM_STR);

        if ($result->execute()) {
            // Если запрос выполенен успешно, возвращаем id добавленной записи
            return $db->lastInsertId();
        }
        // Иначе возвращаем 0
        return 0;
    }

    /**
     * @param $id_user
     * @param $name
     * @return bool
     */
    public static function addUserMsSql($id_user, $name)
    {
        // Соединение с БД
        $db = MsSQL::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO dbo.site_gm_users '
            . '(site_account_id, site_client_name)'
            . 'VALUES '
            . '(:site_account_id, :site_client_name)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':site_account_id', $id_user, PDO::PARAM_INT);
        $result->bindParam(':site_client_name', $name, PDO::PARAM_STR);

        return $result->execute();
    }

    /**
     * Удаляем пользователя
     * @param $id
     * @return bool
     */
    public static function deleteUserById($id)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = 'DELETE FROM gs_user WHERE id_user = :id_user';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * @param $id
     * @param $options
     * @return bool
     */
    public static function updateUserById($id, $options)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE gs_user
            SET
                id_role = :id_role,
                name_partner = :name_partner,
                id_country = :id_country,
                login = :login,
                kpi_view = :kpi_view
            WHERE id_user = :id_user";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_role', $options['role'], PDO::PARAM_INT);
        $result->bindParam(':id_user', $id, PDO::PARAM_INT);
        $result->bindParam(':login', $options['login'], PDO::PARAM_STR);
        $result->bindParam(':name_partner', $options['name_partner'], PDO::PARAM_STR);
        $result->bindParam(':id_country', $options['id_country'], PDO::PARAM_INT);
        $result->bindParam(':kpi_view', $options['kpi_view'], PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Обновление пароля для юзера
     * @param $id
     * @param $options
     * @return bool
     */
    public static function updateUserPassword($id, $options)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE gs_user
            SET
                password = :password
            WHERE id_user = :id_user";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id, PDO::PARAM_INT);
        $result->bindParam(':password', $options['password'], PDO::PARAM_STR);
        return $result->execute();
    }

    /** Последнее активное действие пользователя
     * @param $id_user
     * @param $date_active
     * @return bool
     */
    public static function userLasTimeOnline($id_user, $date_active)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE gs_user
            SET
                date_active = :date_active
            WHERE id_user = :id_user";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':date_active', $date_active, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Обновляем каждый день коеффициент кпи
     * @param $id
     * @param $kpi_coefficient
     * @return bool
     */
    public static function updateUserCoefficient($id, $kpi_coefficient)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE gs_user
            SET
                kpi_coefficient = :kpi_coefficient
            WHERE id_user = :id_user";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id, PDO::PARAM_INT);
        $result->bindParam(':kpi_coefficient', $kpi_coefficient, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Полуаем список ролей
     * @return array
     */
    public static function getRoleList()
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $result = $db->query("SELECT * FROM gs_role")->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

}