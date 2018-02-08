<?php
namespace Umbrella\controllers\site;

use Umbrella\app\Services\site\SeoMetaService;
use Umbrella\vendor\controller\Controller;

class AboutController extends Controller
{

    private $curr_lang;

    private $seo;

    /**
     * AboutController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->curr_lang = $this->lang->getCurrentLang('controller');
        $this->seo = new SeoMetaService($this->curr_lang);
    }

    /**
     * @return bool
     */
    public function actionCompanyInfo()
    {
        $seo_page = $this->seo->getSeoForPage('company_info');

        $this->render("new_site/{$this->curr_lang}/about/company_info", compact('seo_page'));
        return true;
    }

    /**
     * @return bool
     */
    public function actionGeography()
    {
        $seo_page = $this->seo->getSeoForPage('geography');

        $this->render("new_site/{$this->curr_lang}/about/geography", compact('seo_page'));
        return true;
    }

    /**
     * @return bool
     */
    public function actionResponsibility()
    {
        $seo_page = $this->seo->getSeoForPage('responsibility');

        $this->render("new_site/{$this->curr_lang}/about/responsibility", compact('seo_page'));
        return true;
    }

    /**
     * @return bool
     */
    public function actionCertificates()
    {
        $seo_page = $this->seo->getSeoForPage('certificates');

        $this->render("new_site/{$this->curr_lang}/about/certificates", compact('seo_page'));
        return true;
    }
}
