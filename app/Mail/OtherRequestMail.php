<?php

namespace Umbrella\app\Mail;

/**
 * Class OtherRequestMail
 */
class OtherRequestMail
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
     * Created request
     * @param $options
     * @param $user_name
     * @param $id
     * @return bool
     */
    public function sendEmailGS($options, $user_name, $id) {

        $headers = "From: Umbrella\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>Request #:</b> {$id}<br>";
        $mailToManager .= "<b>Партнер:</b> {$user_name}<br><hr>";
        $mailToManager .= "<b>Part Number:</b> " . $options['part_number'] . "<br>";
        $mailToManager .= "<b>Part description:</b> " . $options['part_description'] . "<br>";
        $mailToManager .= "<b>SO Number:</b> " . $options['so_number'] . "<br>";
        $mailToManager .= "<b>Type:</b> " . $options['order_type'] . "<br>";
        $mailToManager .= "<b>Address:</b> " . $options['address'] . "<br>";
        $mailToManager .= "<b>Note:</b> " . $options['note'] . "<br>";

        mail('vv@generalse.com', 'Lenovo Request. Новый запрос', $mailToManager, $headers);
        mail('sales@generalse.com', 'Lenovo Request. Новый запрос', $mailToManager, $headers);
        //mail('maldini2@ukr.net', 'Lenovo Request. Новый запрос', $mailToManager, $headers);
        return true;
    }


    /**
     * Новый запрос импортом
     * @param $options
     * @param $user_name
     * @param $count
     * @return bool
     */
    public function sendImportEmailGS($options, $user_name, $count) {

        $headers = "From: Umbrella\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>Партнер:</b> {$user_name} создал запрос импортом на {$count} позиций<br><hr>";
        $mailToManager .= "<b>Type:</b> " . $options['order_type'] . "<br>";
        $mailToManager .= "<b>Address:</b> " . $options['address'] . "<br>";
        $mailToManager .= "<b>Note:</b> " . $options['note'] . "<br>";

        mail('vv@generalse.com', 'Lenovo Request. Новый запрос', $mailToManager, $headers);
        mail('sales@generalse.com', 'Lenovo Request. Новый запрос', $mailToManager, $headers);
        //mail('maldini2@ukr.net', 'Lenovo Request. Новый запрос', $mailToManager, $headers);
        return true;
    }


    /**
     * Согласование цены
     * @param $options
     * @return bool
     */
    public function sendEmailPartnerAlignment($options) {

        $headers = "From: Umbrella\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>Request #:</b> {$options['id']} перешел в статус {$options['status_name']}<br><hr>";
        $mailToManager .= "<b>Part Number:</b> " . $options['part_number'] . "<br>";
        $mailToManager .= "<b>Part description:</b> " . $options['part_description'] . "<br>";
        $mailToManager .= "<b>SO Number:</b> " . $options['so_number'] . "<br>";
        $mailToManager .= "<b>Price:</b> " . $options['price'] . "<br>";

        $emails = explode(',', $options['email']);

        if(is_array($emails)){
            foreach ($emails as $email){
                mail($email, 'Lenovo Request. Согласование цены на вашу заявку', $mailToManager, $headers);
            }
        }
        return true;
    }


    /**
     * Уведомляем партнера об отказе заявки
     * @param $options
     * @return bool
     */
    public function sendEmailPartnerDenied($options) {

        $headers = "From: Umbrella\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>Request #:</b> {$options['id']} перешел в статус {$options['status_name']}<br><hr>";
        $mailToManager .= "<b>Comment:</b> " . $options['comment_disagree'] . "<br>";
        $mailToManager .= "<b>Part Number:</b> " . $options['part_number'] . "<br>";
        $mailToManager .= "<b>Part description:</b> " . $options['part_description'] . "<br>";
        $mailToManager .= "<b>SO Number:</b> " . $options['so_number'] . "<br>";
        $mailToManager .= "<b>Price:</b> " . $options['price'] . "<br>";

        $emails = explode(',', $options['email']);

        if(is_array($emails)){
            foreach ($emails as $email){
                mail($email, 'Lenovo Request. Ваша заявка отклонена', $mailToManager, $headers);
            }
        }
        return true;
    }


    /**
     * Согласия с ценой
     * @param $options
     * @return bool
     */
    public function sendEmailGSRequestAgree($options) {

        $headers = "From: Umbrella\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>Request #:</b> {$options['id']} перешел в статус {$options['status_name']}<br><hr>";
        $mailToManager .= "<b>Part Number:</b> " . $options['part_number'] . "<br>";
        $mailToManager .= "<b>Part description:</b> " . $options['part_description'] . "<br>";
        $mailToManager .= "<b>SO Number:</b> " . $options['so_number'] . "<br>";
        $mailToManager .= "<b>Price:</b> " . $options['price'] . "<br>";

        mail('vv@generalse.com', 'Lenovo Request. Партнер согласился с ценой ', $mailToManager, $headers);
        mail('sales@generalse.com', 'Lenovo Request. Партнер согласился с ценой ', $mailToManager, $headers);
        return true;
    }


    /**
     * Нет согласия на цену
     * @param $options
     * @return bool
     */
    public function sendEmailGSRequestDisagree($options) {

        $headers = "From: Umbrella\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>Request #:</b> {$options['id']} перешел в статус {$options['status_name']}<br><hr>";
        $mailToManager .= "<b>Part Number:</b> " . $options['part_number'] . "<br>";
        $mailToManager .= "<b>Part description:</b> " . $options['part_description'] . "<br>";
        $mailToManager .= "<b>SO Number:</b> " . $options['so_number'] . "<br>";
        $mailToManager .= "<b>Price:</b> " . $options['price'] . "<br>";
        $mailToManager .= "<b>Comment:</b> " . $options['comment_disagree'] . "<br>";

        mail('vv@generalse.com', 'Lenovo Request. Партнер не согласился с ценой', $mailToManager, $headers);
        mail('sales@generalse.com', 'Lenovo Request. Партнер не согласился с ценой', $mailToManager, $headers);
        return true;
    }

}