<?php

namespace Umbrella\models\hr;

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
}