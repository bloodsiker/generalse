<?php
namespace Umbrella\models\engineer;

use PDO;
use Umbrella\components\Db\MsSQL;

class Kpi
{
    /*************  KPI   *******************/

    /**
     * @param $month
     * @param $year
     *
     * @return array
     */
    public static function getKPI($month, $year)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                    *
                FROM site_gm_depot_data_04
                WHERE month = :month 
                AND year = :year
                ORDER BY name";

        $result = $db->prepare($sql);
        $result->bindParam(':month', $month, PDO::PARAM_INT);
        $result->bindParam(':year', $year, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Список уникальных инжеренеров
     * @param $month
     * @param $year
     *
     * @return array
     */
    public static function getNameEngineer($month, $year)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT
                    DISTINCT worker_id,
                    worker_name
                FROM site_gm_depot_data_04
                WHERE month = :month 
                AND year = :year";

        $result = $db->prepare($sql);
        $result->bindParam(':month', $month, PDO::PARAM_INT);
        $result->bindParam(':year', $year, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

}