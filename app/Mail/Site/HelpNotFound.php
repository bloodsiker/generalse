<?php

namespace Umbrella\app\Mail\Site;


class HelpNotFound
{
    private static $instance = null;

    private function __construct() {}

    protected function __clone() {}

    /**
     * @return null|RegisterClient
     */
    public static function getInstance() {
        // Instantiate itself if not instantiated
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * @param $options
     *
     * @return bool
     */
    public function sendEmailPageNotFound($options) {

        $headers = "From: Umbrella@generalse.com\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>ФИО:</b> {$options['fio']} <br>";
        $mailToManager .= "<b>Email:</b> {$options['email']} <br>";
        $mailToManager .= "<b>Логин:</b> {$options['message']} <br>";

        mail('info@generalse.com', 'generalse.com  page not found', $mailToManager, $headers);
        return true;
    }
}