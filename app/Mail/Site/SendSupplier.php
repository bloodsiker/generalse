<?php

namespace Umbrella\app\Mail\Site;

class SendSupplier
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
    public function sendEmailSuppliers($options) {

        $headers = "From: Umbrella@generalse.com\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>ФИО:</b> {$options['fio']} <br>";
        $mailToManager .= "<b>Название компании поставщика:</b> {$options['company']} <br>";
        $mailToManager .= "<b>Email:</b> {$options['email']} <br>";
        $mailToManager .= "<b>Сообщение:</b> {$options['message']} <br>";
        if($options['upload_file'] == 200){
            $mailToManager .= "<b>Прайс лист:</b> <a href='{$options['file']}' download>Скачать</a><br>";
        }

        mail('do@generalse.com', 'Нова заявка на сотрудничество', $mailToManager, $headers);
        return true;
    }
}