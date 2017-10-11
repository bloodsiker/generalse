<?php

namespace Umbrella\app\Mail;


class PsrMail
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
     * Уведомление при новой регистрации ПСР
     * @param $id
     * @param $userName
     * @param $options
     * @return bool
     */
    public function sendEmailWithNewPsr($id, $userName, $options) {

        $headers = "From: Umbrella@generalse.com\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        $mailToManager = "<b>Новая регистрация в ПСР #{$id}</b><br>";
        $mailToManager .= "<b>Партнер:</b> {$userName} <br>";
        $mailToManager .= "<b>SN number:</b> {$options['serial_number']} <br>";
        $mailToManager .= "<b>MTM:</b> {$options['part_number']} <br>";
        $mailToManager .= "<b>Device:</b> {$options['device_name']} <br>";
        $mailToManager .= "<b>Manufacture date:</b> {$options['manufacture_date']} <br>";
        $mailToManager .= "<b>Purchase date:</b> {$options['purchase_date']} <br>";
        $mailToManager .= "<b>Defect description:</b> {$options['defect_description']} <br>";
        $mailToManager .= "<b>Device condition:</b> {$options['device_condition']} <br>";
        $mailToManager .= "<b>Complectation:</b> {$options['complectation']} <br>";
        $mailToManager .= "<b>Note:</b> {$options['note']} <br>";
        $mailToManager .= "<b>Declaration number:</b> {$options['declaration_number']} <br>";


        mail('gsteam@generalse.com', 'Новая регистрация в ПСР', $mailToManager, $headers);
        //mail('do@generalse.com', 'Новая регистрация в ПСР', $mailToManager, $headers);
        return true;
    }
}