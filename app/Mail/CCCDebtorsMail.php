<?php

namespace Umbrella\app\Mail;


class CCCDebtorsMail
{
    private static $instance = NULL;

    private function __construct() {}

    protected function __clone() {}

    /**
     * @return CCCDebtorsMail
     */
    public static function getInstance() {
        // Instantiate itself if not instantiated
        if(self::$instance === NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * @param $text
     *
     * @return bool
     */
    public function sendCallIsOver($text) {

        $headers = "From: Umbrella@generalse.com\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "$text";

        mail('atk@generalse.com', 'Еженедельный обзвон Debtors EL UA', $mailToManager, $headers);
        mail('ata@generalse.com', 'Еженедельный обзвон Debtors EL UA', $mailToManager, $headers);
        mail('asv@generalse.com', 'Еженедельный обзвон Debtors EL UA', $mailToManager, $headers);
        //mail('do@generalse.com', 'Еженедельный обзвон Debtors EL UA', $mailToManager, $headers);
        return true;
    }
}