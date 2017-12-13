<?php

namespace Umbrella\models\api\hr;

use PDO;
use Umbrella\components\Db\MySQL;

/**
 * Достижения
 * Class FormUserAchievements
 * @package Umbrella\models\hr
 */
class FormUserAchievements
{

    /**
     * @param $options
     * @return int|string
     */
    public static function addAchievementsFormUser($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_hr_user_form_achievements '
            . '(form_user_id, user_id, text)'
            . 'VALUES '
            . '(:form_user_id, :user_id, :text)';

        $result = $db->prepare($sql);
        $result->bindParam(':form_user_id', $options['form_user_id'], PDO::PARAM_INT);
        $result->bindParam(':user_id', $options['user_id'], PDO::PARAM_INT);
        $result->bindParam(':text', $options['text'], PDO::PARAM_STR);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }

    /**
     * @param $form_user_id
     * @return array
     */
    public static function getAchievementsByFormUser($form_user_id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  t1.*,
                  gs_user.name_partner
                FROM gs_hr_user_form_achievements t1
                    INNER JOIN gs_user
                        ON t1.user_id = gs_user.id_user
                WHERE t1.form_user_id = :form_user_id
                ORDER BY t1.id";

        $result = $db->prepare($sql);
        $result->bindParam(':form_user_id', $form_user_id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}