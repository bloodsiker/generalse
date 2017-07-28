<?php
namespace Umbrella\components\Db;

/**
 * Interface DataBase
 */
interface DataBase
{
    /**
     * @return mixed
     */
    public static function getConnection();
}