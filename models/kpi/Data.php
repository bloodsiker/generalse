<?php

namespace Umbrella\models\kpi;

use PDO;
use Umbrella\components\Db\MySQL;

class Data
{

    public static function getAllData()
    {
        $db = MySQL::getConnection();
        $data = $db->query('SELECT * FROM gs_kpi')->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }


    /**
     * Получаем последнюю дату которая есть в базе для даного партнера
     * @param $name_partner
     * @return mixed
     */
    public static function getLastData($name_partner)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  MAX(gk.Service_Complete_Date) as lastDate,
                  MIN(gk.Service_Complete_Date) AS firstDate
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
				AND gk.Service_Complete_Date IS NOT NULL";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }



    /**
     * Получаем последнюю дату которая есть в базе для admin
     * @return mixed
     */
    public static function getLastDataAdmin()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                  MAX(gk.Service_Complete_Date) as lastDate,
                  MIN(gk.Service_Complete_Date) AS firstDate
                FROM gs_kpi gk
				WHERE gk.Service_Complete_Date IS NOT NULL";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
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
        $db = MySQL::getConnection();

        $sql = "SELECT COUNT(DISTINCT gk.SO_NUMBER) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
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
        $db = MySQL::getConnection();

        $sql = "SELECT COUNT(DISTINCT gk.SO_NUMBER) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND gk.Item_Product_ID NOT IN('SWAPLAB', 'PHREPL0', 'DPSTDW3', 'DPSTDW1', 'PHREPL2', 'TPSTDW3',
  'DPSTDW6', 'DPMIDW1', 'DPMIDW2', 'DPMOW02', 'TPSTDW6', 'PHREPL1', 'DPSTDW9')
                AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
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
        $db = MySQL::getConnection();

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
        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }


    /**
     * Получаем номер недели в году
     * @param $date
     * @return mixed
     */
    public static function getCountMountForYear($date)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT WEEK('$date', 1) AS week";
        $result = $db->prepare($sql);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
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
        $db = MySQL::getConnection();

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

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
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
        $db = MySQL::getConnection();

        $sql = "SELECT
                  AVG(gcc.customer_rating_to) AS avg
                FROM gs_call_CSAT gcc
                WHERE gcc.SERVICE_NAME = :SERVICE_NAME
                AND gcc.date_processing BETWEEN :start_date AND :end_date
                AND gcc.status = 'Answered'";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
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
        $db = MySQL::getConnection();

        $sql = "SELECT COUNT(DISTINCT gk.Customer_Email) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND gk.Unit_Received_Date IS NOT NULL
                AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
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
        $db = MySQL::getConnection();

        $sql = "SELECT COUNT(DISTINCT gk.SO_NUMBER) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                  AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                  AND gk.Item_Product_ID NOT IN('SWAPLAB', 'PHREPL0', 'DPSTDW3', 'DPSTDW1', 'PHREPL2', 'TPSTDW3',
              'DPSTDW6', 'DPMIDW1', 'DPMIDW2', 'DPMOW02', 'TPSTDW6', 'PHREPL1', 'DPSTDW9', 'PHREPLS')
                  AND ((SELECT ((DATEDIFF(gk.Part_Order_Date, gk.SO_CREATION_DATE)) -
                  ((WEEK(gk.Part_Order_Date) - WEEK(gk.SO_CREATION_DATE)) * 2) -
                  (case when weekday(gk.Part_Order_Date) = 6 then 1 else 0 end) -
                  (case when weekday(gk.SO_CREATION_DATE) = 5 then 1 else 0 end))) < 2
                    OR
                    (SELECT ((DATEDIFF(gk.Part_Order_Date, gk.SO_CREATION_DATE)) -
                  ((WEEK(gk.Part_Order_Date) - WEEK(gk.SO_CREATION_DATE)) * 2) -
                  (case when weekday(gk.Part_Order_Date) = 6 then 1 else 0 end) -
                  (case when weekday(gk.SO_CREATION_DATE) = 5 then 1 else 0 end))) IS NULL)
              AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
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
        $db = MySQL::getConnection();

        $sql = "SELECT COUNT(DISTINCT gk.SO_NUMBER) AS count
                  FROM gs_kpi gk
                  WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                    AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                    AND gk.Item_Product_ID NOT IN('SWAPLAB', 'PHREPL0', 'DPSTDW3', 'DPSTDW1', 'PHREPL2', 'TPSTDW3',
                  'DPSTDW6', 'DPMIDW1', 'DPMIDW2', 'DPMOW02', 'TPSTDW6', 'PHREPL1', 'DPSTDW9', 'PHREPLS')
                      AND ((SELECT ((DATEDIFF(gk.Service_Complete_Date, gk.Part_Delivery_Date)) -
                      ((WEEK(gk.Service_Complete_Date) - WEEK(gk.Part_Delivery_Date)) * 2) -
                      (case when weekday(gk.Service_Complete_Date) = 6 then 1 else 0 end) -
                      (case when weekday(gk.Part_Delivery_Date) = 5 then 1 else 0 end))) < 2
                        OR
                        (SELECT ((DATEDIFF(gk.Service_Complete_Date, gk.Part_Delivery_Date)) -
                      ((WEEK(gk.Service_Complete_Date) - WEEK(gk.Part_Delivery_Date)) * 2) -
                      (case when weekday(gk.Service_Complete_Date) = 6 then 1 else 0 end) -
                      (case when weekday(gk.Part_Delivery_Date) = 5 then 1 else 0 end))) IS NULL)
                  AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
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
        $db = MySQL::getConnection();

        $sql = "SELECT COUNT(*) AS count
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
                      (case when weekday(gk.SO_CREATION_DATE) = 5 then 1 else 0 end))) < 2
                        OR
                        (SELECT ((DATEDIFF(gk.Service_Complete_Date, gk.SO_CREATION_DATE)) -
                      ((WEEK(gk.Service_Complete_Date) - WEEK(gk.SO_CREATION_DATE)) * 2) -
                      (case when weekday(gk.Service_Complete_Date) = 6 then 1 else 0 end) -
                      (case when weekday(gk.SO_CREATION_DATE) = 5 then 1 else 0 end))) IS NULL)
                  AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }



    public static function SO_Creation_TAT($name_partner, $start, $end)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT COUNT(DISTINCT gk.SO_NUMBER) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                  AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                  AND DATEDIFF(gk.SO_CREATION_DATE, gk.Unit_Received_Date) < 1
                  AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }


    public static function PPl($name_partner, $start, $end)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT COUNT(gk.Item_Product_ID) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND gk.Item_Product_ID NOT IN('SWAPLAB', 'PHREPL0', 'DPSTDW3', 'DPSTDW1', 'PHREPL2', 'TPSTDW3',
  'DPSTDW6', 'DPMIDW1', 'DPMIDW2', 'DPMOW02', 'TPSTDW6', 'PHREPL1', 'DPSTDW9', 'PHREPLS')
                AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
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
        $db = MySQL::getConnection();

        $sql = "SELECT COUNT(DISTINCT gk.SO_NUMBER) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                  AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                  AND DATEDIFF(gk.Service_Complete_Date, gk.SO_CREATION_DATE) < :count_day
                  AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->bindParam(':count_day', $count_day, PDO::PARAM_INT);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
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
        $db = MySQL::getConnection();

        $sql = "SELECT COUNT(DISTINCT gk.SO_NUMBER) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                  AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                  AND DATEDIFF(gk.Service_Complete_Date, gk.SO_CREATION_DATE) > :count_day
                  AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->bindParam(':count_day', $count_day, PDO::PARAM_INT);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }


    /**
     * Получаем количество значений с gk.Unit_Received_Date для данного партнера
     *
     * @param $name_partner
     * @param $start
     * @param $end
     *
     * @return mixed
     */
    public static function LS_Rate($name_partner, $start, $end)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT COUNT(DISTINCT gk.SO_NUMBER) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND gk.PARTS_MODEL_INDICATOR = 'L'
                AND (gk.IRIS_1_Repair NOT LIKE '%Return / reimbursement%')";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }


    /**
     * Количество знаений Return/reimbursement для данного юзера
     *
     * @param $name_partner
     * @param $param
     * @param $start
     * @param $end
     *
     * @return
     */
    public static function Refund_Rate($name_partner, $param, $start, $end)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT COUNT(DISTINCT gk.SO_NUMBER) AS count
                FROM gs_kpi gk
                WHERE gk.SERVICE_PROVIDE_NAME = :SERVICE_PROVIDE_NAME
                AND gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND gk.IRIS_1_Repair LIKE '%$param%'";

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
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
        $db = MySQL::getConnection();

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

        $result = $db->prepare($sql);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $name_partner, PDO::PARAM_STR);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->execute();
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }

    /**
     * Import KPI table
     * @param $options
     * @return bool
     */
    public static function importKPI($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_kpi '
            . '(SO_NUMBER, SO_CREATION_DATE, HEADER_STATUS, SERVICE_PROVIDE_NAME, COUNTRY, Serial_Number, ITEM_STATUS, Service_Complete_Date, Item_Product_ID, 
                Item_Product_Desc, IRIS_1_Repair, Unit_Received_Date, PARTS_MODEL_INDICATOR, Part_Order_Date, Part_Delivery_Date, Customer_Email)'
            . 'VALUES '
            . '(:SO_NUMBER, :SO_CREATION_DATE, :HEADER_STATUS, :SERVICE_PROVIDE_NAME, :COUNTRY, :Serial_Number, :ITEM_STATUS, :Service_Complete_Date, :Item_Product_ID, 
                :Item_Product_Desc, :IRIS_1_Repair, :Unit_Received_Date, :PARTS_MODEL_INDICATOR, :Part_Order_Date, :Part_Delivery_Date, :Customer_Email)';

        $result = $db->prepare($sql);
        $result->bindParam(':SO_NUMBER', $options['SO_NUMBER'], PDO::PARAM_STR);
        $result->bindParam(':SO_CREATION_DATE', $options['SO_CREATION_DATE'], PDO::PARAM_STR);
        $result->bindParam(':HEADER_STATUS', $options['HEADER_STATUS'], PDO::PARAM_STR);
        $result->bindParam(':SERVICE_PROVIDE_NAME', $options['SERVICE_PROVIDE_NAME'], PDO::PARAM_STR);
        $result->bindParam(':COUNTRY', $options['COUNTRY'], PDO::PARAM_STR);
        $result->bindParam(':Serial_Number', $options['Serial_Number'], PDO::PARAM_STR);
        $result->bindParam(':ITEM_STATUS', $options['ITEM_STATUS'], PDO::PARAM_STR);
        $result->bindParam(':Service_Complete_Date', $options['Service_Complete_Date'], PDO::PARAM_STR);
        $result->bindParam(':Item_Product_ID', $options['Item_Product_ID'], PDO::PARAM_STR);
        $result->bindParam(':Item_Product_Desc', $options['Item_Product_Desc'], PDO::PARAM_STR);
        $result->bindParam(':IRIS_1_Repair', $options['IRIS_1_Repair'], PDO::PARAM_STR);
        $result->bindParam(':Unit_Received_Date', $options['Unit_Received_Date'], PDO::PARAM_STR);
        $result->bindParam(':PARTS_MODEL_INDICATOR', $options['PARTS_MODEL_INDICATOR'], PDO::PARAM_STR);
        $result->bindParam(':Part_Order_Date', $options['Part_Order_Date'], PDO::PARAM_STR);
        $result->bindParam(':Part_Delivery_Date', $options['Part_Delivery_Date'], PDO::PARAM_STR);
        $result->bindParam(':Customer_Email', $options['Customer_Email'], PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * @param $options
     *
     * @return bool
     */
    public static function importCallCSAT($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_call_CSAT '
            . '(SO_NUMBER, customer_rating_to, status, SERVICE_NAME, date_processing)'
            . 'VALUES '
            . '(:SO_NUMBER, :customer_rating_to, :status, :SERVICE_NAME, :date_processing)';

        $result = $db->prepare($sql);
        $result->bindParam(':SO_NUMBER', $options['SO_NUMBER'], PDO::PARAM_STR);
        $result->bindParam(':customer_rating_to', $options['customer_rating_to'], PDO::PARAM_STR);
        $result->bindParam(':status', $options['status'], PDO::PARAM_STR);
        $result->bindParam(':SERVICE_NAME', $options['SERVICE_NAME'], PDO::PARAM_STR);
        $result->bindParam(':date_processing', $options['date_processing'], PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * @param $options
     *
     * @return bool
     */
    public static function importEmailCSAT($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_email_CSAT '
            . '(Month, Week, CIN_Location, CIN_Communication, CIN_Technical, CIN_Repair, CIN_Speed, CIN_Quality,
                Name_of_Partner, Serial_Number, OSAT_Comment, Transaction_Number, Sum_of_Wai_Score)'
            . 'VALUES '
            . '(:Month, :Week, :CIN_Location, :CIN_Communication, :CIN_Technical, :CIN_Repair, :CIN_Speed, :CIN_Quality,
                :Name_of_Partner, :Serial_Number, :OSAT_Comment, :Transaction_Number, :Sum_of_Wai_Score)';

        $result = $db->prepare($sql);
        $result->bindParam(':Month', $options['Month'], PDO::PARAM_STR);
        $result->bindParam(':Week', $options['Week'], PDO::PARAM_STR);
        $result->bindParam(':CIN_Location', $options['CIN_Location'], PDO::PARAM_STR);
        $result->bindParam(':CIN_Communication', $options['CIN_Communication'], PDO::PARAM_STR);
        $result->bindParam(':CIN_Technical', $options['CIN_Technical'], PDO::PARAM_STR);
        $result->bindParam(':CIN_Repair', $options['CIN_Repair'], PDO::PARAM_STR);
        $result->bindParam(':CIN_Speed', $options['CIN_Speed'], PDO::PARAM_STR);
        $result->bindParam(':CIN_Quality', $options['CIN_Quality'], PDO::PARAM_STR);
        $result->bindParam(':Name_of_Partner', $options['Name_of_Partner'], PDO::PARAM_STR);
        $result->bindParam(':Serial_Number', $options['Serial_Number'], PDO::PARAM_STR);
        $result->bindParam(':OSAT_Comment', $options['OSAT_Comment'], PDO::PARAM_STR);
        $result->bindParam(':Transaction_Number', $options['Transaction_Number'], PDO::PARAM_STR);
        $result->bindParam(':Sum_of_Wai_Score', $options['Sum_of_Wai_Score'], PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     *
     * @param $id_user
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function getUsageByAdmin($id_user, $start, $end)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                 ANY_VALUE(gk.SERVICE_PROVIDE_NAME) as SERVICE_PROVIDE_NAME,
                 ANY_VALUE(gk.Item_Product_ID) as Item_Product_ID,
                 ANY_VALUE(gk.Item_Product_Desc) as Item_Product_Desc,
                 COUNT(*) AS total
                 FROM gs_kpi gk
                 INNER JOIN gs_user gu
                   ON gk.SERVICE_PROVIDE_NAME = gu.name_partner
                 WHERE gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                 AND gu.id_user = :id_user
                 AND gk.Item_Product_ID NOT IN('SWAPLAB', 'PHREPL0', 'DPSTDW3', 'DPSTDW1', 'PHREPL2', 'TPSTDW3',
                 'DPSTDW6', 'DPMIDW1', 'DPMIDW2', 'DPMOW02', 'TPSTDW6', 'PHREPL1', 'DPSTDW9')
                 GROUP BY gk.Item_Product_ID";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetchAll();
    }


    /**
     * @param $start
     * @param $end
     * @return array
     */
    public static function getAllUsageByAdmin($start, $end)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                 ANY_VALUE(gk.SERVICE_PROVIDE_NAME) as SERVICE_PROVIDE_NAME,
                 ANY_VALUE(gk.Item_Product_ID) as Item_Product_ID,
                 ANY_VALUE(gk.Item_Product_Desc) as Item_Product_Desc,
                COUNT(*) AS total
                FROM gs_kpi gk 
                WHERE gk.Service_Complete_Date BETWEEN :start_date AND :end_date
                AND gk.Item_Product_ID NOT IN('SWAPLAB', 'PHREPL0', 'DPSTDW3', 'DPSTDW1', 'PHREPL2', 'TPSTDW3',
                'DPSTDW6', 'DPMIDW1', 'DPMIDW2', 'DPMOW02', 'TPSTDW6', 'PHREPL1', 'DPSTDW9') 
                GROUP BY gk.Item_Product_ID
                ORDER BY ANY_VALUE(gk.SERVICE_PROVIDE_NAME)";

        $result = $db->prepare($sql);
        $result->bindParam(':start_date', $start, PDO::PARAM_STR);
        $result->bindParam(':end_date', $end, PDO::PARAM_STR);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetchAll();
    }
}