<?php

namespace Umbrella\components;


class Decoder
{

    /**
     * Iconv string/integer/float from (WINDOWS-1251/CP-1251) to UTF-8
     * @param $value
     * @return string
     * @throws \Exception
     */
    public static function strToUtf($value)
    {
        if(empty($value) || is_null($value)){
            return null;
        }
        return iconv('WINDOWS-1251', 'UTF-8', $value);
    }


    /**
     * Iconv array from (WINDOWS-1251/CP-1251) to UTF
     * @param $array
     * @return array
     * @throws \Exception
     */
    public static function arrayToUtf(array $array)
    {
        if(!is_array($array)){
            throw new \Exception("{$array} is not an array");
        }
        if(sizeof($array) > 0) {
            array_walk_recursive($array, function(&$value,$key){
                $value = self::strToUtf($value);
            });
            return $array;
        }
        return [];
    }



    /**
     * Iconv string/integer/float from UTF-8( to WINDOWS-1251/CP-1251)
     * @param $value
     * @return string
     * @throws \Exception
     */
    public static function strToWindows($value)
    {
        if(empty($value) || is_null($value)){
            return null;
        }
        return iconv('UTF-8','WINDOWS-1251', $value);
    }


    /**
     * Iconv array from UTF-8( to WINDOWS-1251/CP-1251)
     * @param $array
     * @return array
     * @throws \Exception
     */
    public static function arrayToWindows(array $array)
    {
        if(!is_array($array)){
            throw new \Exception("{$array} is not an array");
        }

        if(sizeof($array) > 0) {
            array_walk_recursive($array, function(&$value,$key){
                $value = self::strToWindows($value);
            });
            return $array;
        }
        return [];
    }
}