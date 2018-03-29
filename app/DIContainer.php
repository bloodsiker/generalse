<?php

namespace Umbrella\app;


class DIContainer
{
    /**
     * @var array
     */
    protected static $register = [];


    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function bind($key, $value)
    {
        return static::$register[$key] = $value;
    }


    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public static function get($key)
    {
        if(!array_key_exists($key, static::$register)){
            throw new \Exception("No {$key} is bound in the container");
        }
        return static::$register[$key];
    }
}