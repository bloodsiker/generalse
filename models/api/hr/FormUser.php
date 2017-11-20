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
                    ghuf.*,
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
                    ghuf.*,
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
     * @param $options
     * @return int|string
     */
    public static function addFormUser($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_hr_users_form '
            . '(name, surname, email, phone, photo, company_id, legal_entity, department_id, branch_id, position, band_id, func_group)'
            . 'VALUES '
            . '(:name, :surname, :email, :phone, :photo, :company_id, :legal_entity, :department_id, :branch_id, :position, :band_id, :func_group)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':surname', $options['surname'], PDO::PARAM_STR);
        $result->bindParam(':email', $options['email'], PDO::PARAM_STR);
        $result->bindParam(':phone', $options['phone'], PDO::PARAM_STR);
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
                photo = :photo,
                company_id = :company_id,
                legal_entity = :legal_entity,
                department_id = :department_id,
                branch_id = :branch_id,
                position = :position,
                band_id = :band_id,
                func_group = :func_group
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $options['id'], PDO::PARAM_INT);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':surname', $options['surname'], PDO::PARAM_STR);
        $result->bindParam(':email', $options['email'], PDO::PARAM_STR);
        $result->bindParam(':phone', $options['phone'], PDO::PARAM_STR);
        $result->bindParam(':photo', $options['photo'], PDO::PARAM_STR);
        $result->bindParam(':company_id', $options['company_id'], PDO::PARAM_INT);
        $result->bindParam(':legal_entity', $options['legal_entity'], PDO::PARAM_STR);
        $result->bindParam(':department_id', $options['department_id'], PDO::PARAM_INT);
        $result->bindParam(':branch_id', $options['branch_id'], PDO::PARAM_INT);
        $result->bindParam(':position', $options['position'], PDO::PARAM_STR);
        $result->bindParam(':band_id', $options['band_id'], PDO::PARAM_INT);
        $result->bindParam(':func_group', $options['func_group'], PDO::PARAM_STR);
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
}