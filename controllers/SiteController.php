<?php
namespace Umbrella\controllers;

use Umbrella\app\AdminBase;

class SiteController extends AdminBase
{

    public function actionIndex()
    {



        require_once(ROOT . '/views/site/index.php');
        return true;
    }

    public function actionForBusiness()
    {




        require_once(ROOT . '/views/site/for_business.php');
        return true;
    }

    public function actionDirections()
    {




        require_once(ROOT . '/views/site/directions.php');
        return true;
    }

    public function actionCareer()
    {




        require_once(ROOT . '/views/site/career.php');
        return true;
    }

    public function actionContact()
    {




        require_once(ROOT . '/views/site/contact.php');
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
