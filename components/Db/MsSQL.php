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
            try {
                self::$instance = new PDO ("dblib:host={$_ENV['DB_MS_HOST']}:{$_ENV['DB_MS_PORT']};dbname={$_ENV['DB_MS_DATABASE']}",
                    "{$_ENV['DB_MS_USERNAME']}","{$_ENV['DB_MS_PASSWORD']}");
                //$dbh->exec("set names utf8");
            } catch (\PDOException $e) {
                echo "Failed to get DB handle: " . $e->getMessage() . "\n";
                exit;
            }
        }
        return self::$instance;
    }
}
