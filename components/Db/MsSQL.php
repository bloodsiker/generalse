<?php
namespace Umbrella\components\Db;

use PDO;

/**
 * Class MsSQL
 */
class MsSQL implements DataBase
{
    protected static $instance = null;

    private function __construct() {}
    protected function __clone() {}

    /**
     * @return null|PDO
     */
    public static function getConnection()
    {
        if (self::$instance === null)
        {
            $paramsPath = ROOT . '/config/db_mssql.php';
            $params = include($paramsPath);

            try {
                self::$instance = new PDO ("dblib:host={$params['host']}:{$params['port']};dbname={$params['dbname']}","{$params['user']}","{$params['password']}");
                //$dbh->exec("set names utf8");
            } catch (\PDOException $e) {
                echo "Failed to get DB handle: " . $e->getMessage() . "\n";
                exit;
            }
        }
        return self::$instance;
    }
}
