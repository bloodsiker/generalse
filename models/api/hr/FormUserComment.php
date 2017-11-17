<?php

namespace Umbrella\models\api\hr;

use PDO;
use Umbrella\components\Db\MySQL;

/**
 * Class FormUserComment
 * @package Umbrella\models\hr
 */
class FormUserComment
{

    /**
     * @param $options
     * @return int|string
     */
    public static function addCommentFormUser($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_hr_user_form_comments '
            . '(form_user_id, user_id, comment)'
            . 'VALUES '
            . '(:form_user_id, :user_id, :comment)';

        $result = $db->prepare($sql);
        $result->bindParam(':form_user_id', $options['form_user_id'], PDO::PARAM_INT);
        $result->bindParam(':user_id', $options['user_id'], PDO::PARAM_STR);
        $result->bindParam(':comment', $options['comment'], PDO::PARAM_STR);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }

    /**
     * @param $form_user_id
     * @return array
     */
    public static function getCommentsByFormUser($form_user_id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  t1.*,
                  gs_user.name_partner
                FROM gs_hr_user_form_comments t1
                    INNER JOIN gs_user
                        ON t1.user_id = gs_user.id_user
                WHERE t1.form_user_id = :form_user_id
                ORDER BY t1.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':form_user_id', $form_user_id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}