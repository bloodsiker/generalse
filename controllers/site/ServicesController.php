<?php
namespace Umbrella\controllers\site;

use Umbrella\app\Services\site\SeoMetaService;
use Umbrella\controllers\BaseSiteController;

class ServicesController extends BaseSiteController
{

    private $curr_lang;

    private $seo;

    /**
     * ServicesController constructor.
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
    public function actionManufacturers()
    {
        $seo_page = $this->seo->getSeoForPage('manufacturer');

        $this->render("new_site/{$this->curr_lang}/services/manufacturers", compact('seo_page'));
        return true;
    }

    public function actionRetailers()
    {
        $seo_page = $this->seo->getSeoForPage('retailers');

        $this->render("new_site/{$this->curr_lang}/services/retailers", compact('seo_page'));
        return true;
    }

    public function actionRepairCenters()
    {
        $seo_page = $this->seo->getSeoForPage('repair_center');

        $this->render("new_site/{$this->curr_lang}/services/repair_centers", compact('seo_page'));
        return true;
    }

    public function actionEnterprises()
    {
        $seo_page = $this->seo->getSeoForPage('enterprises');

        $this->render("new_site/{$this->curr_lang}/services/enterprises", compact('seo_page'));
        return true;
    }
}
