<?php

namespace Umbrella\app\Mail\Site;


class RegisterClient
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
     * Registration new client
     * @param $options
     *
     * @return bool
     */
    public function sendEmailWithNewClient($options) {

        $headers = "From: Umbrella@generalse.com\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>Страна:</b> {$options['country']} <br>";
        $mailToManager .= "<b>ФИО:</b> {$options['fio']} <br>";
        $mailToManager .= "<b>Наименование компании:</b> {$options['company']} <br>";
        $mailToManager .= "<b>Email:</b> {$options['email']} <br>";
        $mailToManager .= "<b>Логин:</b> {$options['login']} <br>";
        $mailToManager .= "<b>Телефон:</b> {$options['phone']} <br>";
        $mailToManager .= "<b>Адрес:</b> {$options['address']} <br>";
        $mailToManager .= "<b>Какие интересуют группы товаров:</b> {$options['group_products']} <br>";
        $mailToManager .= "<b>Комментарий:</b> {$options['message']} <br>";

        mail('sales@generalse.com', 'Новая регистрация в ПСР', $mailToManager, $headers);
        mail('do@generalse.com', 'Новая регистрация на получение аккаунта в Umbrella', $mailToManager, $headers);
        return true;
    }
}