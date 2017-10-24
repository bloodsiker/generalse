<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Db\MsSQL;
use Umbrella\components\Functions;

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

        $sql = 'SELECT * FROM gs_user WHERE login = :login AND password = :password';

        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_INT);
        $result->execute();

        $admin = $result->fetch();

        if ($admin) {
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

        $result = $db->prepare($sql);
        $result->bindParam(':name_partner', $user_name, PDO::PARAM_STR);
        $result->execute();

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

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();

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

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_STR);
        $result->execute();

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
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_user_control_accaunt '
            . '(id_user, control_user_id)'
            . 'VALUES '
            . '(:id_user, :control_user_id)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':control_user_id', $control_user_id, PDO::PARAM_INT);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
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
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gs_user_control_accaunt WHERE id_user = :id_user AND control_user_id = :control_user_id';

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':control_user_id', $control_user_id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Список всех пользоватей
     * @param bool $filter
     * @return array
     */
    public static function getAllUsers($filter = true)
    {
        $db = MySQL::getConnection();

        $data = $db->query("SELECT
                                        gu.id_user,
                                        gu.id_role,
                                        gu.name_partner,
                                        gu.id_country,
                                        gu.login,
                                        gu.is_active,
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
                                    WHERE {$filter}
                                    ORDER BY gu.id_user")->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }


    /**
     * список всех партнеров
     * @return array
     */
    public static function getAllPartner()
    {
        $db = MySQL::getConnection();

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
        $db = MySQL::getConnection();

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
        $db = MySQL::getConnection();

        $sql = 'SELECT
                   gu.id_user,
                   gu.id_role,
                   gu.name_partner,
                   gu.email,
                   gu.login,
                   gu.id_country,
                   gu.kpi_coefficient,
                   gu.kpi_view,
                   gu.login_url,
                   gu.is_active,
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

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);

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
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_user '
            . '(id_role, name_partner, id_country, login, email, password, login_url, kpi_view, date_create)'
            . 'VALUES '
            . '(:id_role, :name_partner, :id_country, :login, :email, :password, :login_url, :kpi_view, :date_create)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id_role', $options['id_role'], PDO::PARAM_INT);
        $result->bindParam(':name_partner', $options['name_partner'], PDO::PARAM_STR);
        $result->bindParam(':id_country', $options['id_country'], PDO::PARAM_INT);
        $result->bindParam(':login', $options['login'], PDO::PARAM_STR);
        $result->bindParam(':email', $options['email'], PDO::PARAM_STR);
        $result->bindParam(':password', $options['password'], PDO::PARAM_STR);
        $result->bindParam(':login_url', $options['login_url'], PDO::PARAM_STR);
        $result->bindParam(':kpi_view', $options['kpi_view'], PDO::PARAM_INT);
        $result->bindParam(':date_create', $options['date_create'], PDO::PARAM_STR);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }

    /**
     * @param $id_user
     * @param $options
     * @return bool
     */
    public static function addUserMsSql($id_user, $options)
    {
        $db = MsSQL::getConnection();

        $sql = 'INSERT INTO dbo.site_gm_users '
            . '(site_account_id, site_client_name, name_en, address, address_en, for_ttn, curency_id, abcd_id, to_electrolux, to_mail_send, contract_number, 
                staff_id, stock_place_id, phone, email, region_id)'
            . 'VALUES '
            . '(:site_account_id, :site_client_name, :name_en, :address, :address_en, :for_ttn, :curency_id, :abcd_id, :to_electrolux, :to_mail_send, :contract_number, 
                :staff_id, :stock_place_id, :phone, :email, :region_id)';

        $result = $db->prepare($sql);
        $result->bindParam(':site_account_id', $id_user, PDO::PARAM_INT);
        $result->bindParam(':site_client_name', $options['site_client_name'], PDO::PARAM_STR);
        $result->bindParam(':name_en', $options['name_en'], PDO::PARAM_STR);
        $result->bindParam(':address', $options['address'], PDO::PARAM_STR);
        $result->bindParam(':address_en', $options['address_en'], PDO::PARAM_STR);
        $result->bindParam(':for_ttn', $options['for_ttn'], PDO::PARAM_STR);
        $result->bindParam(':curency_id', $options['curency_id'], PDO::PARAM_INT);
        $result->bindParam(':abcd_id', $options['abcd_id'], PDO::PARAM_INT);
        $result->bindParam(':to_electrolux', $options['to_electrolux'], PDO::PARAM_INT);
        $result->bindParam(':to_mail_send', $options['to_mail_send'], PDO::PARAM_INT);
        $result->bindParam(':contract_number', $options['contract_number'], PDO::PARAM_STR);
        $result->bindParam(':staff_id', $options['staff_id'], PDO::PARAM_INT);
        $result->bindParam(':stock_place_id', $options['stock_place_id'], PDO::PARAM_INT);
        $result->bindParam(':phone', $options['phone'], PDO::PARAM_STR);
        $result->bindParam(':email', $options['gm_email'], PDO::PARAM_STR);
        $result->bindParam(':region_id', $options['region_id'], PDO::PARAM_INT);

        return $result->execute();
    }

    /**
     * Удаляем пользователя
     * @param $id
     * @return bool
     */
    public static function deleteUserById($id)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gs_user WHERE id_user = :id_user';

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
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_user
            SET
                id_role = :id_role,
                name_partner = :name_partner,
                id_country = :id_country,
                login = :login,
                email = :email,
                login_url = :login_url,
                kpi_view = :kpi_view
            WHERE id_user = :id_user";

        $result = $db->prepare($sql);
        $result->bindParam(':id_role', $options['role'], PDO::PARAM_INT);
        $result->bindParam(':id_user', $id, PDO::PARAM_INT);
        $result->bindParam(':login', $options['login'], PDO::PARAM_STR);
        $result->bindParam(':email', $options['email'], PDO::PARAM_STR);
        $result->bindParam(':login_url', $options['login_url'], PDO::PARAM_STR);
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
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_user
            SET
                password = :password
            WHERE id_user = :id_user";

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
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_user
            SET
                date_active = :date_active
            WHERE id_user = :id_user";

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
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_user
            SET
                kpi_coefficient = :kpi_coefficient
            WHERE id_user = :id_user";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id, PDO::PARAM_INT);
        $result->bindParam(':kpi_coefficient', $kpi_coefficient, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Lock user
     * @param $user_id
     * @param $lock
     * @return bool
     */
    public static function updateLockUser($user_id, $lock)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_user
            SET
                is_active = :is_active
            WHERE id_user = :id_user";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $user_id, PDO::PARAM_INT);
        $result->bindParam(':is_active', $lock, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Полуаем список ролей
     * @return array
     */
    public static function getRoleList()
    {
        $db = MySQL::getConnection();

        $result = $db->query("SELECT * FROM gs_role")->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
     * Получаем список валюты из GM
     * @return array
     */
    public static function getCurrencyList()
    {
        $db = MsSQL::getConnection();

        $result = $db->query("SELECT number, shortName FROM tbl_Curency")->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
     * Список цен для партнеров
     * @return array
     */
    public static function getABSDPriceList()
    {
        $db = MsSQL::getConnection();

        $result = $db->query("SELECT number, priceName FROM tbl_ABCD")->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
     * Список ответственных
     * @return array
     */
    public static function getStaffList()
    {
        $db = MsSQL::getConnection();

        $result = $db->query("SELECT i_d, displayName FROM tbl_Users WHERE isDeleted = 0")->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
     * Список местоположений складов
     * @return array
     */
    public static function getStockPlaceList()
    {
        $db = MsSQL::getConnection();

        $result = $db->query("SELECT stockPlaceID, stockPlaceName FROM tbl_2_StockPlaces WHERE isInner = 1 AND isDeleted = 0")->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
     * Список регионов
     * @return array
     */
    public static function getRegionsList()
    {
        $db = MsSQL::getConnection();

        $result = $db->query("SELECT i_d, mname FROM tbl_Regions")->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }



    /*
     * Информация о пользователе из GM
     */
    public static function getInfoGmUser($user_id)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                sgu.*,
                tc.ShortName,
                ta.PriceName,
                tu.DisplayName,
                tbl_2_StockPlaces.StockPlaceName,
                tbl_Regions.mName
                FROM site_gm_users sgu
                    LEFT JOIN tbl_Curency tc
                        ON sgu.curency_id = tc.Number
                    LEFT JOIN tbl_ABCD ta
                        ON sgu.abcd_id = ta.Number
                    LEFT JOIN tbl_Users tu
                        ON sgu.staff_id = tu.I_D
                    LEFT JOIN tbl_2_StockPlaces
                        ON sgu.stock_place_id = tbl_2_StockPlaces.StockPlaceID
                    LEFT JOIN tbl_Regions
                        ON sgu.region_id = tbl_Regions.I_D
                WHERE sgu.site_account_id = :user_id";

        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->execute();
        $user = $result->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

}