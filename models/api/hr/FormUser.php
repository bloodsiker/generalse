<?php

namespace Umbrella\models\api\hr;

use PDO;
use Umbrella\components\Db\MySQL;

/**
 * Class FormUser
 * @package Umbrella\models\hr
 */
class FormUser
{

    /**
     * List forms user by department
     * @param $filter
     * @return array
     */
    public static function getFormsUserByDepartment($filter)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                    ghuf.id,
                    ghuf.form,
                    ghs.name as company,
                    ghs1.name as department,
                    ghs2.name as branch,
                    ghb.band_desc,
                    ghb.general_competency,
                    ghb.salary,
                    ghb.performance,
                    ghb.language_lvl,
                    ghb.trigger_action
                FROM gs_hr_users_form ghuf
                INNER JOIN gs_hr_structure ghs
                    ON ghuf.company_id = ghs.id
                INNER JOIN gs_hr_structure ghs1
                    ON ghuf.department_id = ghs1.id
                LEFT JOIN gs_hr_structure ghs2
                    ON ghuf.branch_id = ghs2.id
                INNER JOIN gs_hr_band ghb
                    ON ghuf.band_id = ghb.id
                WHERE 1 = 1 {$filter}";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }



    /**
     * @param $id_user
     * @return array
     */
    public static function getFormUserById($id_user)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                    ghuf.form,
                    ghs.name as company,
                    ghs1.name as department,
                    ghs2.name as branch,
                    ghb.band_desc,
                    ghb.general_competency,
                    ghb.salary,
                    ghb.performance,
                    ghb.language_lvl,
                    ghb.trigger_action
                FROM gs_hr_users_form ghuf
                INNER JOIN gs_hr_structure ghs
                    ON ghuf.company_id = ghs.id
                INNER JOIN gs_hr_structure ghs1
                    ON ghuf.department_id = ghs1.id
                LEFT JOIN gs_hr_structure ghs2
                    ON ghuf.branch_id = ghs2.id
                INNER JOIN gs_hr_band ghb
                    ON ghuf.band_id = ghb.id
                WHERE ghuf.id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id_user, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * @param $id_user
     *
     * @return mixed
     */
    public static function getNewFormUserById($id_user)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                    ghuf.form,
                    ghuf.staff_id,
                    ghuf.company_id,
                    ghuf.department_id,
                    ghuf.branch_id
                FROM gs_hr_users_form ghuf
                WHERE ghuf.id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id_user, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }



    /**
     * @param $options
     * @return int|string
     */
    public static function addFormUser($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_hr_users_form '
            . '(name, surname, email, phone, phone_2, photo, company_id, legal_entity, department_id, branch_id, position, band_id, func_group)'
            . 'VALUES '
            . '(:name, :surname, :email, :phone, :phone_2, :photo, :company_id, :legal_entity, :department_id, :branch_id, :position, :band_id, :func_group)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':surname', $options['surname'], PDO::PARAM_STR);
        $result->bindParam(':email', $options['email'], PDO::PARAM_STR);
        $result->bindParam(':phone', $options['phone'], PDO::PARAM_STR);
        $result->bindParam(':phone_2', $options['phone_2'], PDO::PARAM_STR);
        $result->bindParam(':photo', $options['photo'], PDO::PARAM_STR);
        $result->bindParam(':company_id', $options['company_id'], PDO::PARAM_INT);
        $result->bindParam(':legal_entity', $options['legal_entity'], PDO::PARAM_STR);
        $result->bindParam(':department_id', $options['department_id'], PDO::PARAM_INT);
        $result->bindParam(':branch_id', $options['branch_id'], PDO::PARAM_INT);
        $result->bindParam(':position', $options['position'], PDO::PARAM_STR);
        $result->bindParam(':band_id', $options['band_id'], PDO::PARAM_INT);
        $result->bindParam(':func_group', $options['func_group'], PDO::PARAM_STR);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }

    public static function addNewFormUser($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_hr_users_form '
            . '(user_id, form, company_id, staff_id, department_id, branch_id, band_id)'
            . 'VALUES '
            . '(:user_id, :form, :company_id, :staff_id, :department_id, :branch_id, :band_id)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $options['user_id'], PDO::PARAM_INT);
        $result->bindParam(':form', $options['form'], PDO::PARAM_STR);
        $result->bindParam(':staff_id', $options['staff_id'], PDO::PARAM_INT);
        $result->bindParam(':company_id', $options['company_id'], PDO::PARAM_INT);
        $result->bindParam(':department_id', $options['department_id'], PDO::PARAM_INT);
        $result->bindParam(':branch_id', $options['branch_id'], PDO::PARAM_INT);
        $result->bindParam(':band_id', $options['band_id'], PDO::PARAM_INT);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }


    /**
     * Edit form user info
     * @param $options
     * @return bool
     */
    public static function updateFormUser($options)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_hr_users_form
            SET
                name = :name,
                surname = :surname,
                email = :email,
                phone = :phone,
                phone_2 = :phone_2,
                photo = :photo,
                company_id = :company_id,
                legal_entity = :legal_entity,
                department_id = :department_id,
                branch_id = :branch_id,
                position = :position,
                band_id = :band_id,
                func_group = :func_group,
                user_fire = :user_fire
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $options['id'], PDO::PARAM_INT);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':surname', $options['surname'], PDO::PARAM_STR);
        $result->bindParam(':email', $options['email'], PDO::PARAM_STR);
        $result->bindParam(':phone', $options['phone'], PDO::PARAM_STR);
        $result->bindParam(':phone_2', $options['phone_2'], PDO::PARAM_STR);
        $result->bindParam(':photo', $options['photo'], PDO::PARAM_STR);
        $result->bindParam(':company_id', $options['company_id'], PDO::PARAM_INT);
        $result->bindParam(':legal_entity', $options['legal_entity'], PDO::PARAM_STR);
        $result->bindParam(':department_id', $options['department_id'], PDO::PARAM_INT);
        $result->bindParam(':branch_id', $options['branch_id'], PDO::PARAM_INT);
        $result->bindParam(':position', $options['position'], PDO::PARAM_STR);
        $result->bindParam(':band_id', $options['band_id'], PDO::PARAM_INT);
        $result->bindParam(':func_group', $options['func_group'], PDO::PARAM_STR);
        $result->bindParam(':user_fire', $options['user_fire'], PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Edit form user info
     * @param $options
     *
     * @return bool
     */
    public static function savedNewFormUser($options)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_hr_users_form
            SET
                form = :form
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $options['id'], PDO::PARAM_INT);
        $result->bindParam(':form', $options['form'], PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Apply form user
     * @param $options
     *
     * @return bool
     */
    public static function updateNewFormUser($options)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_hr_users_form
            SET
                form = :form,
                staff_id = :staff_id,
                company_id = :company_id,
                department_id = :department_id,
                branch_id = :branch_id,
                band_id = :band_id
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $options['id'], PDO::PARAM_INT);
        $result->bindParam(':form', $options['form'], PDO::PARAM_STR);
        $result->bindParam(':staff_id', $options['staff_id'], PDO::PARAM_INT);
        $result->bindParam(':company_id', $options['company_id'], PDO::PARAM_INT);
        $result->bindParam(':department_id', $options['department_id'], PDO::PARAM_INT);
        $result->bindParam(':branch_id', $options['branch_id'], PDO::PARAM_INT);
        $result->bindParam(':band_id', $options['band_id'], PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * @param $id
     * @param $key_1
     * @param $key_2
     * @param $value_1
     * @param $value_2
     * @return bool
     */
    public static function updateAttrFormUser($id, $key_1, $value_1, $key_2, $value_2)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_hr_users_form
            SET
                {$key_1} = :value_1,
                {$key_2} = :value_2
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':value_1', $value_1, PDO::PARAM_STR);
        $result->bindParam(':value_2', $value_2, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * @param $id
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public static function updateSelectAttrFormUser($id, $key, $value)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_hr_users_form
            SET
                {$key} = :value
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':value', $value, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * Delete form user
     * @param $id
     * @return bool
     */
    public static function deleteFormUserById($id)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gs_hr_users_form WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Add ljg change info form user
     * @param $options
     * @return int|string
     */
    public static function addLog($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_hr_users_form_log '
            . '(form_user_id, user_id, key_form, title, value_form, state, comment, updated_at)'
            . 'VALUES '
            . '(:form_user_id, :user_id, :key_form, :title, :value_form, :state, :comment, :updated_at)';

        $result = $db->prepare($sql);
        $result->bindParam(':form_user_id', $options['form_user_id'], PDO::PARAM_INT);
        $result->bindParam(':user_id', $options['user_id'], PDO::PARAM_INT);
        $result->bindParam(':key_form', $options['key_form'], PDO::PARAM_STR);
        $result->bindParam(':title', $options['title'], PDO::PARAM_STR);
        $result->bindParam(':value_form', $options['value_form'], PDO::PARAM_STR);
        $result->bindParam(':state', $options['state'], PDO::PARAM_STR);
        $result->bindParam(':comment', $options['comment'], PDO::PARAM_STR);
        $result->bindParam(':updated_at', $options['updated_at'], PDO::PARAM_STR);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }


    /**
     * History edit user form
     * @param $id_form
     * @return array
     */
    public static function getLogsByFormUserId($id_form)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                 gs_hr_users_form_log.*,
                 gu.name_partner
                FROM gs_hr_users_form_log 
                    INNER JOIN gs_user gu
                        ON gs_hr_users_form_log.user_id = gu.id_user
                WHERE form_user_id = :id_form";

        $result = $db->prepare($sql);
        $result->bindParam(':id_form', $id_form, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}