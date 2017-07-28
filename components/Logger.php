<?php

namespace Umbrella\components;

/**
 * Class Logger
 */
class Logger
{
    private static $instance = NULL;

    private function __construct() {

    }
    protected function __clone() {}

    /**
     * @return Logger|null
     */
    public static function getInstance() {
        // Instantiate itself if not instantiated
        if(self::$instance === NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $id_user
     * @param $log
     * @return bool
     */
    public function log($id_user, $log) {
        return Log::addLog($id_user, $log);
    }
}