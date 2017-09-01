<?php

namespace Umbrella\models\ccc;

use PDO;
use Umbrella\components\Db\MySQL;

class KPI
{
    /**
     * Получаем данные за последнюю дату
     * @param $created_at
     * @return array
     */
    public static function getLastData($created_at)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  *
                FROM gs_ccc_kpi
                WHERE created_at = :created_at";

        $result = $db->prepare($sql);
        $result->bindParam(':created_at', $created_at, PDO::PARAM_STR);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }



    /**
     * Получаем данные в интервале дат
     * @param $start_date
     * @param $end_date
     * @return array
     */
    public static function getDataBetweenDate($start_date, $end_date)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                    name_manager,
                    ROUND(AVG(out_call),0 ) as out_call,
                    ROUND(AVG(inc_call), 0) as inc_call,
                    ROUND(AVG(inc_call_BY), 0) as inc_call_BY,
                    ROUND(AVG(inc_call_F1), 0) as inc_call_F1,
                    ROUND(AVG(inc_call_rate), 0) as inc_call_rate,
                    ROUND(AVG(avg_talk_time), 1) as avg_talk_time,
                    ROUND(AVG(call_miss), 0) as call_miss,
                    ROUND(AVG(completed_map), 0) as completed_map
                FROM gs_ccc_kpi
                WHERE created_at BETWEEN :start_date AND :end_date
                GROUP BY name_manager";

        $result = $db->prepare($sql);
        $result->bindParam(':start_date', $start_date, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Список уникальных дат для выборки по зяписям
     * @return mixed
     */
    public static function getListImportData()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  created_at
                FROM gs_ccc_kpi
                GROUP BY created_at
                ORDER BY created_at DESC";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $options
     * @return bool
     */
    public static function addKPI($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_ccc_kpi '
            . '(name_manager, out_call, inc_call, inc_call_BY, inc_call_F1, inc_call_rate, avg_talk_time, call_miss, completed_map, created_at) '
            . 'VALUES '
            . '(:name_manager, :out_call, :inc_call, :inc_call_BY, :inc_call_F1, :inc_call_rate, :avg_talk_time, :call_miss, :completed_map, :created_at)';

        $result = $db->prepare($sql);
        $result->bindParam(':name_manager', $options['name_manager'], PDO::PARAM_STR);
        $result->bindParam(':out_call', $options['out_call'], PDO::PARAM_INT);
        $result->bindParam(':inc_call', $options['inc_call'], PDO::PARAM_INT);
        $result->bindParam(':inc_call_BY', $options['inc_call_BY'], PDO::PARAM_INT);
        $result->bindParam(':inc_call_F1', $options['inc_call_F1'], PDO::PARAM_INT);
        $result->bindParam(':inc_call_rate', $options['inc_call_rate'], PDO::PARAM_INT);
        $result->bindParam(':avg_talk_time', $options['avg_talk_time'], PDO::PARAM_INT);
        $result->bindParam(':call_miss', $options['call_miss'], PDO::PARAM_INT);
        $result->bindParam(':completed_map', $options['completed_map'], PDO::PARAM_INT);
        $result->bindParam(':created_at', $options['created_at'], PDO::PARAM_STR);
        return $result->execute();
    }
}