<?php

namespace Umbrella\app;


class DIContainer
{
    /**
     * @var array
     */
    protected $register = [];


    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function bind($key, $value)
    {
        return $this->register[$key] = $value;
    }


    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function get($key)
    {
        if(!array_key_exists($key, $this->register)){
            throw new \Exception("No {$key} is bound in the container");
        }
        return $this->register[$key];
    }
}