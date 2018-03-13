<?php
namespace Umbrella\controllers\site;

use Umbrella\app\Services\site\SeoMetaService;
use Umbrella\controllers\BaseSiteController;

/**
 * Class ContactsController
 * @package Umbrella\controllers\site
 */
class ContactsController extends BaseSiteController
{
    private $curr_lang;

    private $seo;

    /**
     * ContactsController constructor.
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
    public function actionIndex()
    {
        $seo_page = $this->seo->getSeoForPage('contact');
        $this->render("new_site/{$this->curr_lang}/contact", compact('seo_page'));
        return true;
    }
}
