<?php

namespace Umbrella\models\repair_ree;

use PDO;
use Umbrella\components\Db\MsSQL;

/**
 * Class Mds
 * @package Umbrella\models
 */
class Mds
{

    /**
     * @param $options
     *
     * @return bool
     */
    public static function insertMds($options)
    {
        if(is_array($options) && count($options) > 0){
            $column = implode(', ', array_keys($options));
            $value = '';
            foreach ($options as $key => $val){
                $value .= "'{$val}', ";
            }
            $value = substr($value, 0 , -2);

            $db = MsSQL::getConnection();

            $sql = "INSERT INTO site_gm_mds ($column) VALUES ($value)";
            $result = $db->prepare($sql);
            return $result->execute();
        }
    }


    /**
     * @param $filter
     *
     * @return array
     */
    public static function getAll($filter)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT 
                sgm.created_on,
                sgm.error,
                sgm.error_text,
                sgm.so,
                sgm.PartnerJobOrder,
                sgm.SOStatus,
                sgm.IMEIorSN,
                sgm.RepairFinishiTime,
                sgu.site_client_name
                FROM site_gm_mds sgm
                  INNER JOIN site_gm_users sgu
                    ON sgm.site_account_id = sgu.site_account_id
                WHERE 1 = 1 {$filter}
                ORDER BY sgm.id DESC";
        //print_r($sql);

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $array_id
     *
     * @return array
     */
    public static function getAllByPartner($array_id, $filter)
    {
        $db = MsSQL::getConnection();

        $idS = implode(',', $array_id);

        $sql = "SELECT 
                sgm.created_on,
                sgm.error,
                sgm.error_text,
                sgm.so,
                sgm.PartnerJobOrder,
                sgm.SOStatus,
                sgm.IMEIorSN,
                sgm.RepairFinishiTime,
                sgu.site_client_name
                FROM site_gm_mds sgm
                  INNER JOIN site_gm_users sgu
                    ON sgm.site_account_id = sgu.site_account_id
                WHERE sgm.site_account_id IN({$idS}) {$filter}
                ORDER BY sgm.id DESC";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}