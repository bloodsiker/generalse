<?php
namespace Umbrella\models\engineer;

use PDO;
use Umbrella\components\Db\MsSQL;

class Dashboard
{

    /**
     * @param $month
     * @param $year
     *
     * @return array
     */
    public static function getMovementDevicesProducer($month, $year)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                    produser_name,
                    SUM(quantity_in) as quantity_in,
                    SUM(quantity_out) as quantity_out,
                    SUM(quantity_stock) as quantity_stock,
                    SUM(quantity_decompile) as quantity_decompile
                FROM site_gm_depot_data_01
                WHERE month = :month 
                AND year = :year
                GROUP BY produser_name";

        $result = $db->prepare($sql);
        $result->bindParam(':month', $month, PDO::PARAM_INT);
        $result->bindParam(':year', $year, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $month
     * @param $year
     *
     * @return array
     */
    public static function getMovementDevicesClassifier($month, $year)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                    classifier_name,
                    SUM(quantity_in) as quantity_in,
                    SUM(quantity_out) as quantity_out,
                    SUM(quantity_stock) as quantity_stock,
                    SUM(quantity_decompile) as quantity_decompile
                FROM site_gm_depot_data_01
                WHERE month = :month 
                AND year = :year
                GROUP BY classifier_name";

        $result = $db->prepare($sql);
        $result->bindParam(':month', $month, PDO::PARAM_INT);
        $result->bindParam(':year', $year, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public static function getMonths()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                  DISTINCT month
                FROM site_gm_depot_data_02
                ORDER BY month";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getYears()
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                  DISTINCT year
                FROM site_gm_depot_data_02
                ORDER BY year DESC";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $month
     *
     * @return string
     */
    public static function nameMonth($month)
    {
        switch ($month){
            case 1:
                $name = 'Январь';
                break;
            case 2:
                $name = 'Февраль';
                break;
            case 3:
                $name = 'Март';
                break;
            case 4:
                $name = 'Апрель';
                break;
            case 5:
                $name = 'Май';
                break;
            case 6:
                $name = 'Июнь';
                break;
            case 7:
                $name = 'Июль';
                break;
            case 8:
                $name = 'Август';
                break;
            case 9:
                $name = 'Сентябрь';
                break;
            case 10:
                $name = 'Октябрь';
                break;
            case 11:
                $name = 'Ноябрь';
                break;
            case 12:
                $name = 'Декабрь';
                break;
            default:
                $name = 'Не изместный месяц';
        }
        return $name;
    }


    /*************  Разборка   *******************/


     /*
     * Разборка
     * @param $month
     * @param $year
     *
     * @return array
     */
    public static function getDisassemblyProducer($month, $year)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                    produser_name,
                    SUM(quantity_prev) as quantity_prev,
                    SUM(quantity_decompiled) as quantity_decompiled,
                    SUM(quantity_shipped) as quantity_shipped,
                    SUM(quantity_ok) as quantity_ok,
                    SUM(quantity_bad) as quantity_bad
                FROM site_gm_depot_data_02
                WHERE month = :month 
                AND year = :year
                GROUP BY produser_name";

        $result = $db->prepare($sql);
        $result->bindParam(':month', $month, PDO::PARAM_INT);
        $result->bindParam(':year', $year, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Разборка
     * @param $month
     * @param $year
     *
     * @return array
     */
    public static function getDisassemblyClassifier($month, $year)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                    class_name,
                    SUM(quantity_prev) as quantity_prev,
                    SUM(quantity_decompiled) as quantity_decompiled,
                    SUM(quantity_shipped) as quantity_shipped,
                    SUM(quantity_ok) as quantity_ok,
                    SUM(quantity_bad) as quantity_bad
                FROM site_gm_depot_data_02
                WHERE month = :month 
                AND year = :year
                GROUP BY class_name";

        $result = $db->prepare($sql);
        $result->bindParam(':month', $month, PDO::PARAM_INT);
        $result->bindParam(':year', $year, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /*************  Ремонт   *******************/


    /**
     * @param $month
     * @param $year
     *
     * @return array
     */
    public static function getRepairs($month, $year)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                    class_name,
                    SUM(quantity_open) as quantity_open,
                    SUM(quantity_close) as quantity_close
                FROM site_gm_depot_data_03
                WHERE month = :month 
                AND year = :year
                GROUP BY class_name";

        $result = $db->prepare($sql);
        $result->bindParam(':month', $month, PDO::PARAM_INT);
        $result->bindParam(':year', $year, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}