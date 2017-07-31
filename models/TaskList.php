<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\cron\CronDb;

class TaskList
{
    /**
     * Получаем список заданий
     * @return array
     */
    public static function getTaskList()
    {
        // Соединение с базой данных
        $db = CronDb::getConnection();
        // Делаем запрос к базе данных
        $sql = "SELECT 
                    gtl.id,
                    gtl.step_0,
                    gtl.step_2,
                    gtl.is_active,
                    gc.customer_name
                FROM gs_task_list gtl
                INNER JOIN gs_customer gc
                    ON gtl.step_8 = gc.id";
        // Делаем подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':section', $section, PDO::PARAM_INT);
        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}