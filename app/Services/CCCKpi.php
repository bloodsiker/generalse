<?php
namespace Umbrella\app\Services;

use Umbrella\models\ccc\KPI;

class CCCKpi
{
    const COEFFICIENT_MAPS = 70;
    const COEFFICIENT_CALLS = 90;
    const INC_CALL_RATE = 10;
    const AVG_TALK_TIME = 2.0;


    /**
     * CCCKpi constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Выборка по одной дате
     * @param $date
     * @return array
     */
    public function lastDate($date)
    {
        $list = KPI::getDataBetweenDate($date, $date);

        $list = array_map(function($element) {
            $element['coefficient_maps'] = round(($element['completed_map'] * 100) / $element['inc_call'], 2);
            return $element;
        }, $list);

        $list = array_map(function($element) {
            $element['coefficient_calls'] = 100 - round(($element['call_miss'] * 100) / $element['inc_call'], 2);
            return $element;
        }, $list);

        return $list;
    }



    /**
     * Выборка в интервале дат
     * @param $start_date
     * @param $end_date
     * @return array
     */
    public function getDataInterval($start_date, $end_date)
    {
        $list = KPI::getDataBetweenDate($start_date, $end_date);

        $list = array_map(function($element) {
            $element['coefficient_maps'] = round(($element['completed_map'] * 100) / $element['inc_call'], 2);
            return $element;
        }, $list);

        $list = array_map(function($element) {
            $element['coefficient_calls'] = 100 - round(($element['call_miss'] * 100) / $element['inc_call'], 2);
            return $element;
        }, $list);

        return $list;
    }


    /**
     * @param $result
     * @param $target
     */
    public function controlTargetUp($result, $target){

        if( $result >= ($target)){
            echo "green";
        } else {
            echo "red problem";
        }
    }


    /**
     * @param $result
     * @param $target
     */
    public function controlTargetDown($result, $target){

        if( $result <= ($target)){
            echo "green";
        } else {
            echo "red problem";
        }

    }
}