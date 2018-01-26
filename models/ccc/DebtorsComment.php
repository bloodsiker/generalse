<?php
namespace Umbrella\models\ccc;

use PDO;
use Umbrella\components\Db\MySQL;

class DebtorsComment
{
    /**
     * @param $partner_id
     *
     * @param $week
     * @param $year
     *
     * @return mixed
     */
    public static function getCommentsInterval($partner_id, $week, $year)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                gcdc.*,
                gu.name_partner 
                FROM gs_ccc_debtors_comment gcdc
                  INNER JOIN gs_user gu 
                    ON gcdc.user_id = gu.id_user
                WHERE gcdc.partner_id = :partner_id
                AND gcdc.week = :week 
                AND gcdc.year = :year
                ORDER BY gcdc.id DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':partner_id', $partner_id, PDO::PARAM_INT);
        $result->bindParam(':week', $week, PDO::PARAM_INT);
        $result->bindParam(':year', $year, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function addComment($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_ccc_debtors_comment '
            . '(partner_id, comment, user_id, week, year) '
            . 'VALUES '
            . '(:partner_id, :comment, :user_id, :week, :year)';

        $result = $db->prepare($sql);
        $result->bindParam(':partner_id', $options['partner_id'], PDO::PARAM_INT);
        $result->bindParam(':comment', $options['comment'], PDO::PARAM_STR);
        $result->bindParam(':user_id', $options['user_id'], PDO::PARAM_INT);
        $result->bindParam(':week', $options['week'], PDO::PARAM_INT);
        $result->bindParam(':year', $options['year'], PDO::PARAM_INT);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }


    /**
     * @param $id
     *
     * @return bool
     */
    public static function deleteComment($id)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gs_ccc_debtors_comment WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * @param $week
     * @param $year
     *
     * @return mixed
     */
    public static function getAllCommentsForWeek($week, $year)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                    *
                FROM gs_ccc_debtors_comment
                WHERE week = :week 
                AND year = :year";

        $result = $db->prepare($sql);
        $result->bindParam(':week', $week, PDO::PARAM_INT);
        $result->bindParam(':year', $year, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}