<?php

namespace Umbrella\app\Mail;


use Umbrella\components\Decoder;

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
     *
     * @param $id
     * @param $oldStatus
     * @param $newStatus
     * @param $userEmail
     *
     * @return bool
     * @throws \Exception
     */
    public function sendEmailEditStatus($id, $oldStatus, $newStatus, $userEmail) {

        $headers = "From: Umbrella@generalse.com\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>В Request #:</b> {$id} был изменен статус<br>";
        $mailToManager .= "<b>Old status:</b>" . Decoder::strToUtf($oldStatus) . "<br>";
        $mailToManager .= "<b>New status:</b>" . Decoder::strToUtf($newStatus);

        mail('dv@generalse.com', 'Request. Статус изменен', $mailToManager, $headers);

        $emails = explode(',', $userEmail);

        if(is_array($emails)){
            foreach ($emails as $email){
                mail($email, 'Request. Статус изменен', $mailToManager, $headers);
            }
        }
        return true;
    }


    /**
     * Отправка email при отсутсвии партномера в gm
     * @param $partNumber
     * @param $partDesc
     *
     * @return bool
     */
    public function sendEmailContentManager($partNumber, $partDesc) {

        $headers = "From: Umbrella@generalse.com\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "В системе отсутствует партномер:<br>";
        $mailToManager .= "<b>{$partNumber} - {$partDesc}. Необходимо добавить в систему</b><br>";

        mail('gsteam@generalse.com', 'MultiRequest. Внести партномер в систему', $mailToManager, $headers);
        return true;
    }


    public function sendEmailManagerDecompileStock($partNumber, $partDesc) {

        $headers = "From: Umbrella@generalse.com\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "В системе отсутствует партномер:<br>";
        $mailToManager .= "<b>{$partNumber} - {$partDesc}. Необходимо добавить в систему</b><br>";

        mail('gsteam@generalse.com', 'MultiRequest. Внести партномер в систему', $mailToManager, $headers);
        return true;
    }
}