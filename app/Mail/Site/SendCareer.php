<?php

namespace Umbrella\app\Mail\Site;

class SendCareer
{
    private static $instance = null;

    private function __construct() {}

    protected function __clone() {}

    /**
     * @return null|RegisterClient
     */
    public static function getInstance() {
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
    public function sendEmailCareer($options) {

        $headers = "From: Umbrella@generalse.com\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>ФИО:</b> {$options['fio']} <br>";
        $mailToManager .= "<b>Наименование компании:</b> {$options['company']} <br>";
        $mailToManager .= "<b>Email:</b> {$options['email']} <br>";
        $mailToManager .= "<b>Телефон:</b> {$options['phone']} <br>";
        $mailToManager .= "<b>Вакансия:</b> {$options['vacancy']} <br>";
        $mailToManager .= "<b>Сообщение:</b> {$options['message']} <br>";

        mail('sales@generalse.com', 'Новый отклик на вакансию', $mailToManager, $headers);
        mail('do@generalse.com', 'Новый отклик на вакансию', $mailToManager, $headers);
        return true;
    }
}