<?php

namespace Umbrella\components\cron;

class CronDb
{

    public static function getConnection()
    {
        //$params = include_once dirname(__FILE__) . '/../../config/db_params.php';

        $params = array(
//            'host' => 'localhost',
//            'dbname' => 'h4146_generalse',
//            'user' => 'h4146_generalse',
//            'password' => 'germaniya88dd',

            'host' => 'localhost',
            'dbname' => 'generalse',
            'user' => 'homestead',
            'password' => 'secret',
        );

        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $db = new \PDO($dsn, $params['user'], $params['password']);
        $db->exec("set names utf8");

        return $db;
    }

    public static function getConnectionMsSQL()
    {
        //$params = include_once dirname(__FILE__) . '/../../config/db_mssql.php';

        $params = array(
            'host' => 'erp_gs.programmers.com.ua',
            'port' => '41434',
            'dbname' => 'GSDB',
            'user' => 'gs_site',
            'password' => 'dfhbfwbz#2016',
        );

        try {
            $dbh = new \PDO ("dblib:host={$params['host']}:{$params['port']};dbname={$params['dbname']}","{$params['user']}","{$params['password']}");
            //$dbh->exec("set names utf8");
        } catch (\PDOException $e) {
            echo "Failed to get DB handle: " . $e->getMessage() . "\n";
            exit;
        }
        return $dbh;
    }

}