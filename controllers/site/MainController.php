<?php
namespace Umbrella\controllers;

use Umbrella\app\Mail\Site\RegisterClient;
use Umbrella\app\Mail\Site\SendCareer;
use Umbrella\models\site\Client;
use Umbrella\vendor\controller\Controller;

/**
 * Class SiteController
 * @package Umbrella\controllers
 */
class SiteController extends Controller
{

    /**
     * @return bool
     */
    public function actionIndex()
    {

        $this->render('site/index');
        return true;
    }

    /**
     * @return bool
     */
    public function actionForBusiness()
    {

        $this->render('site/for_business');
        return true;
    }


    /**
     * @return bool
     */
    public function actionDirections()
    {

        $this->render('site/directions');
        return true;
    }


    /**
     * @return bool
     */
    public function actionCareer()
    {
        if(isset($_REQUEST['json'])){
            $data_json = json_decode($_REQUEST['json'], true);

            $options['fio'] = $data_json['fio'];
            $options['email'] = $data_json['email'];
            $options['company'] = $data_json['company'];
            $options['phone'] = $data_json['phone'];
            $options['page'] = $data_json['page'];
            $options['vacancy'] = $data_json['vacancy'];
            $options['message'] = $data_json['message'];
            SendCareer::getInstance()->sendEmailCareer($options);
        }

        $this->render('site/career');
        return true;
    }


    /**
     * @return bool
     */
    public function actionSignUp()
    {
        $data_json = json_decode($_REQUEST['json'], true);

        $options['country'] = $data_json['country'];
        $options['fio'] = $data_json['fio'];
        $options['company'] = $data_json['company'];
        $options['email'] = $data_json['email'];
        $options['login'] = $data_json['login'];
        $options['phone'] = $data_json['phone'];
        $options['address'] = $data_json['address'];
        $options['group_products'] = $data_json['group_products'];
        $options['message'] = $data_json['message'];
        $options['page'] = 'sign_up';

        $ok = Client::registrationClient($options);
        if($ok){
            RegisterClient::getInstance()->sendEmailWithNewClient($options);
            print_r(1);
        } else {
            print_r(0);
        }
        return true;
    }


    /**
     * Page Contact
     */
    public function actionContact()
    {

        $this->render('site/contact');
        return true;
    }

    /**
     * @return bool
     */
    public function actionContactForm()
    {

        $_REQUEST['email'];
        $_REQUEST['subject'];
        $_REQUEST['text'];

        $to = "gsteam@generalse.com";

        $subject = $_REQUEST['subject'];

        $message = "От : {$_REQUEST['email']} <br>
                    Сообщение: {$_REQUEST['text']}";

        $headers = "From: gs@generalse.com\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        mail($to, $subject, $message, $headers);
        return true;
    }
}
