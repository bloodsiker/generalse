<?php

namespace Umbrella\app\Mail;


class RequestMail
{
    private static $instance = NULL;

    private function __construct() {}

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
     * Изменение парт номера на аналог
     * @param $id
     * @param $analogPrice
     * @param $originPrice
     * @param $userEmail
     * @return bool
     */
    public function sendEmailAnalogPartNumber($id, $analogPrice, $originPrice, $userEmail) {

        $headers = "From: Umbrella@generalse.com\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>В Request #:</b> {$id} парт номер был изменен на аналог<br>";
        $mailToManager .= "<b>Part Number:</b> {$originPrice['partNumber']} - Price: " . round($originPrice['price'], 2) . "<br>";
        $mailToManager .= "<b>Analog:</b> {$analogPrice['partNumber']} - Price: " . round($analogPrice['price'], 2) ;

        mail('do@generalse.com', 'Request. Замена парт номера на аналог', $mailToManager, $headers);
        mail('dv@generalse.com', 'Request. Замена парт номера на аналог', $mailToManager, $headers);

        $emails = explode(',', $userEmail);

        if(is_array($emails)){
            foreach ($emails as $email){
                mail($email, 'Request. Замена парт номера на аналог', $mailToManager, $headers);
            }
        }
        return true;
    }


    /**
     * При изменении статуса реквеста
     * @param $id
     * @param $oldStatus
     * @param $newStatus
     * @param $userEmail
     * @return bool
     */
    public function sendEmailEditStatus($id, $oldStatus, $newStatus, $userEmail) {

        $headers = "From: Umbrella@generalse.com\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>В Request #:</b> {$id} был изменен статус<br>";
        $mailToManager .= "<b>Old status:</b>" . iconv('WINDOWS-1251', 'UTF-8', $oldStatus) . "<br>";
        $mailToManager .= "<b>New status:</b>" . iconv('WINDOWS-1251', 'UTF-8', $newStatus);

        mail('do@generalse.com', 'Request. Статус изменен', $mailToManager, $headers);
        mail('dv@generalse.com', 'Request. Статус изменен', $mailToManager, $headers);

        $emails = explode(',', $userEmail);

        if(is_array($emails)){
            foreach ($emails as $email){
                mail($email, 'Request. Статус изменен', $mailToManager, $headers);
            }
        }
        return true;
    }
}