<?php
namespace Umbrella\controllers\site;

use Umbrella\app\Services\site\SeoMetaService;
use Umbrella\controllers\BaseSiteController;
use Umbrella\models\site\ServiceCenter;

class AuthController extends BaseSiteController
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
    public function actionLogin()
    {
        $seo_page = $this->seo->getSeoForPage('login');

        $this->render("new_site/{$this->curr_lang}/login", compact('seo_page'));
        return true;
    }
}
