<?php
namespace Umbrella\controllers\site;

use Umbrella\vendor\controller\Controller;

class AboutController extends Controller
{

    /**
     * @return bool
     */
    public function actionCompanyInfo()
    {

        $this->render('new_site/en/about/company_info');
        return true;
    }

    public function actionGeography()
    {

        $this->render('new_site/en/about/geography');
        return true;
    }

    public function actionResponsibility()
    {

        $this->render('new_site/en/about/responsibility');
        return true;
    }

    public function actionCertificates()
    {

        $this->render('new_site/en/about/certificates');
        return true;
    }
}
