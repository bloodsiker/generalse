<?php

namespace Umbrella\components;

class Url
{
    // Поиск в строке Url родительского каталога
    public static function Is_url($alias)
    {
        $url = $_SERVER['REQUEST_URI'];

        if(is_array($alias)){
            foreach ($alias as $slug){
                $result = strpos($url, $slug);
                if ($result !== false) {
                    return true;
                }
            }
        } else {
            $result = strpos($url, $alias);
            if ($result === false) {
                return false;
            }
            return true;
        }
    }

    /**
     * Active url
     * @param $alias
     * @param $class
     * @return bool
     */
    public static function IsActive($alias, $class)
    {
        $url = $_SERVER['REQUEST_URI'];
        if(is_array($alias)){
            foreach ($alias as $slug){
                $result = strpos($url, $slug);
                if ($result !== false) {
                    return $class;
                }
            }
        } else {
            $result = strpos($url, $alias);
            if ($result === false) {
                return false;
            }
            return $class;
        }
    }
}