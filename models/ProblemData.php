<?php


class ProblemData
{

    public static function getAllData()
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $data = $db->query('SELECT * FROM gs_kpi')->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }


    /**
     * Получаем последнюю дату которая есть в базе для даного партнера
     * @param $name_partner
     * @param $sort
     * @return mixed
     */
    public static function getLastData($name_partner, $sort)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = "SELECT gk.Service_Complete_Date
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
				AND gk.Service_Complete_Date IS NOT NULL
                ORDER BY gk.Service_Complete_Date $sort LIMIT 1";

        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Выполнение коменды
        $result->execute();

        return $result->fetch();
    }



    /**
     * Получаем последнюю дату которая есть в базе для admin
     * @param $sort
     * @return mixed
     */
    public static function getLastDataAdmin($sort)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Текст запроса к БД
        $sql = "SELECT gk.Service_Complete_Date
                FROM gs_kpi gk
				WHERE gk.Service_Complete_Date IS NOT NULL
                ORDER BY gk.Service_Complete_Date $sort LIMIT 1";

        // Используется подготовленный запрос
        $result = $db->prepare($sql);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Выполнение коменды
        $result->execute();

        return $result->fetch();
    }


    /**
     * КВР Количество всех ремонтов
     * @param $name_partner
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function getKBP($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT COUNT(DISTINCT gk.SO_NUMBER) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetch();
        return $row['count'];
    }


    /**
     * КРЧЗ Количество ремонтов для партнера с использованием запчасти,
     * исключая значение SWAPLAB, PHREPL0, DPSTDW3, DPSTDW1
     * @param $name_partner
     * @return mixed
     */
    public static function getKRZCH($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT COUNT(DISTINCT gk.SO_NUMBER) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND gk.Item_Product_ID NOT IN('SWAPLAB', 'PHREPL0', 'DPSTDW3', 'DPSTDW1', 'PHREPL2', 'TPSTDW3',
  'DPSTDW6', 'DPMIDW1', 'DPMIDW2', 'DPMOW02', 'TPSTDW6', 'PHREPL1', 'DPSTDW9')
                AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetch();
        return $row['count'];

    }

    /**
     * КРБЗ Количество ремонтов для партнера
     * БЕЗ использованием запчасти, и наличие значений PHREPL0, DPSTDW3, DPSTDW1
     * @param $name_partner
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function getKRBZ($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT COUNT(*) AS count
                  FROM gs_kpi gk
                  WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                  AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                  AND gk.SO_NUMBER IN (
                      SELECT gk1.SO_NUMBER
                    FROM gs_kpi gk1
                    GROUP BY gk1.SO_NUMBER
                    HAVING count(gk1.SO_NUMBER) = 1)
                  AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetch();
        return $row['count'];
    }


    /**
     * Получаем номер недели в году
     * @param $date
     * @return mixed
     */
    public static function getCountMountForYear($date)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT WEEK('$date', 1) AS week";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        //$result->bindParam(':date', $date, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetch();
        return $row['week'];
    }


    /*********************************************/

    /**
     * email_CSAT
     * @param $name_partner
     * @param $start
     * @param $end
     * @return array
     */
    public static function email_CSAT($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT
                  gec.CIN_Location,
                  gec.CIN_Communication,
                  gec.CIN_Technical,
                  gec.CIN_Repair,
                  gec.CIN_Speed,
                  gec.CIN_Quality
                FROM gs_email_CSAT gec
                WHERE gec.Name_of_Partner = :SERVICE_PROVIDE_NAME
                AND gec.Week BETWEEN :start_date AND :end_date";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     *  Получаем среднее значение по оценкам клиента
     * @param $name_partner
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function call_CSAT($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT
                  AVG(gcc.customer_rating_to) AS avg
                FROM gs_call_CSAT gcc
                WHERE gcc.SERVICE_NAME = :SERVICE_NAME
                AND gcc.date_processing BETWEEN :start_date AND :end_date
                AND gcc.status = 'Answered'";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['avg'];
    }



    /**
     * Количество уникальных значений колонки Р
     * @param $name_partner
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function ECR($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT *
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND gk.Unit_Received_Date IS NULL
                AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')
                GROUP BY gk.Customer_Email;";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }


    /**
     * Количество значений разницы (Part_Order_Date - SO_CREATION_DATE) < 2
     * @param $name_partner
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function Order_TAT($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT *
                  FROM gs_kpi gk
                  WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                    AND gk.Item_Product_ID NOT IN('SWAPLAB', 'PHREPL0', 'DPSTDW3', 'DPSTDW1', 'PHREPL2', 'TPSTDW3',
                'DPSTDW6', 'DPMIDW1', 'DPMIDW2', 'DPMOW02', 'TPSTDW6', 'PHREPL1', 'DPSTDW9', 'PHREPLS')
                    AND ((SELECT ((DATEDIFF(gk.Part_Order_Date, gk.SO_CREATION_DATE)) -
                    ((WEEK(gk.Part_Order_Date) - WEEK(gk.SO_CREATION_DATE)) * 2) -
                    (case when weekday(gk.Part_Order_Date) = 6 then 1 else 0 end) -
                    (case when weekday(gk.SO_CREATION_DATE) = 5 then 1 else 0 end))) >= 2)
                AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')
                  GROUP BY gk.SO_NUMBER";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }


    /**
     * Количество значений разницы (Service_Complete_Date - Part_Delivery_Date) < 2
     * @param $name_partner
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function Repair_TAT($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT *
                  FROM gs_kpi gk
                  WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                    AND gk.Item_Product_ID NOT IN('SWAPLAB', 'PHREPL0', 'DPSTDW3', 'DPSTDW1', 'PHREPL2', 'TPSTDW3',
                  'DPSTDW6', 'DPMIDW1', 'DPMIDW2', 'DPMOW02', 'TPSTDW6', 'PHREPL1', 'DPSTDW9', 'PHREPLS')
                      AND ((SELECT ((DATEDIFF(gk.Service_Complete_Date, gk.Part_Delivery_Date)) -
                      ((WEEK(gk.Service_Complete_Date) - WEEK(gk.Part_Delivery_Date)) * 2) -
                      (case when weekday(gk.Service_Complete_Date) = 6 then 1 else 0 end) -
                      (case when weekday(gk.Part_Delivery_Date) = 5 then 1 else 0 end))) >= 2)
                  AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')
                 GROUP BY gk.SO_NUMBER";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }


    /**
     * Количество значений разницы (Service_Complete_Date - SO_CREATION_DATE) < 2
     * @param $name_partner
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function SW_Repair_TAT($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT *
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND gk.SO_NUMBER IN (
                    SELECT gk1.SO_NUMBER
                  FROM gs_kpi gk1
                  GROUP BY gk1.SO_NUMBER
                  HAVING count(gk1.SO_NUMBER) = 1)
                AND ((SELECT ((DATEDIFF(gk.Service_Complete_Date, gk.SO_CREATION_DATE)) -
                    ((WEEK(gk.Service_Complete_Date) - WEEK(gk.SO_CREATION_DATE)) * 2) -
                    (case when weekday(gk.Service_Complete_Date) = 6 then 1 else 0 end) -
                    (case when weekday(gk.SO_CREATION_DATE) = 5 then 1 else 0 end))) >= 2)
                AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }



    public static function SO_Creation_TAT($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT *
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                  AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                  AND DATEDIFF(gk.SO_CREATION_DATE, gk.Unit_Received_Date) >= 1
                  AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')
                  GROUP BY gk.SO_NUMBER";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }


    public static function PPl($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT *
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND gk.Item_Product_ID NOT IN('SWAPLAB', 'PHREPL0', 'DPSTDW3', 'DPSTDW1', 'PHREPL2', 'TPSTDW3',
                'DPSTDW6', 'DPMIDW1', 'DPMIDW2', 'DPMOW02', 'TPSTDW6', 'PHREPL1', 'DPSTDW9', 'PHREPLS')
                AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')
                AND gk.SO_NUMBER NOT IN (
                    SELECT gk1.SO_NUMBER
                    FROM gs_kpi gk1
                    WHERE gk1.Item_Product_ID NOT IN('SWAPLAB', 'PHREPL0', 'DPSTDW3', 'DPSTDW1', 'PHREPL2', 'TPSTDW3',
                    'DPSTDW6', 'DPMIDW1', 'DPMIDW2', 'DPMOW02', 'TPSTDW6', 'PHREPL1', 'DPSTDW9', 'PHREPLS')
                    AND (gk1.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')
                    GROUP BY gk1.SO_NUMBER
                    HAVING count(gk1.SO_NUMBER) = 1)";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }


    /**
     * Количество значений разницы  ячеек (Service_Complete_Date - SO_CREATION_DATE) < 15
     * @param $name_partner
     * @param $start
     * @param $end
     * @param $count_day
     * @return mixed
     */
    public static function LongTail_14_Days($name_partner, $start, $end, $count_day)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT *
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                  AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                  AND DATEDIFF(gk.Service_Complete_Date, gk.SO_CREATION_DATE) >= :count_day
                  AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')
                  GROUP BY gk.SO_NUMBER";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->bindParam(':count_day', $count_day, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }


    /**
     *  Количество значений разницы  ячеек (Service_Complete_Date - SO_CREATION_DATE) > 21
     * @param $name_partner
     * @param $start
     * @param $end
     * @param $count_day
     * @return mixed
     */
    public static function LongTail_21_Days($name_partner, $start, $end, $count_day)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT *
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                  AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                  AND DATEDIFF(gk.Service_Complete_Date, gk.SO_CREATION_DATE) <= :count_day
                  AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')
                  GROUP BY gk.SO_NUMBER";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->bindParam(':count_day', $count_day, PDO::PARAM_INT);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }


    /**
     * Получаем количество значений с gk.Unit_Received_Date для данного партнера
     * @param $name_partner
     * @return mixed
     */
    public static function LS_Rate($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT COUNT(DISTINCT gk.SO_NUMBER) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND gk.PARTS_MODEL_INDICATOR = 'L'
                AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }


    /**
     * Количество знаений Return/reimbursement для данного юзера
     * @param $name_partner
     * @param $param
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function Refund_Rate($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT COUNT(DISTINCT gk.SO_NUMBER) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND gk.IRIS_1_Repair LIKE '%Return / reimbursement%'";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $row = $result->fetch();
        return $row['count'];
    }


    /**
     * @param $name_partner
     * @param $start
     * @param $end
     * @return array
     */
    public static function FTF_30_Days($name_partner, $start, $end)
    {
        // Соединение с БД
        $db = MySQL::getConnection();

        // Получение и возврат результатов
        $sql = "SELECT *
                  FROM gs_kpi gk
                  WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                  AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                  AND gk.Serial_Number IN (
                      SELECT gk1.Serial_Number
                      FROM gs_kpi gk1
                      GROUP BY gk1.Serial_Number
                      HAVING count(gk1.Serial_Number) > 1)
                  AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')
                ORDER BY gk.Serial_Number, gk.SO_NUMBER";
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);

        // Выполнение коменды
        $result->execute();

        // Возвращаем значение count - количество
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }
}