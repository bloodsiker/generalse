<?php
namespace Umbrella\controllers;

use Umbrella\vendor\controller\Controller;

class SiteController extends Controller
{

    public function actionIndex()
    {

        $this->render('site/index');
        return true;
    }

    public function actionForBusiness()
    {

        $this->render('site/for_business');
        return true;
    }

    public function actionDirections()
    {

        $this->render('site/directions');
        return true;
    }

    public function actionCareer()
    {

        $this->render('site/career');
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

    public function actionContactForm()
    {

        $_REQUEST['email'];
        $_REQUEST['subject'];
        $_REQUEST['text'];

        $to = "gsteam@generalse.com";

        $subject = $_REQUEST['subject'];

        $message = "
                    От : {$_REQUEST['email']} <br>
                    Сообщение: {$_REQUEST['text']}
                    ";

        $headers = "From: gs@generalse.com\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit";

        mail($to, $subject, $message, $headers);

        return true;
    }
}
