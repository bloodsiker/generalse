<?php
namespace Umbrella\controllers\site;

use Josantonius\Url\Url;
use Umbrella\app\Services\site\SeoMetaService;
use Umbrella\controllers\BaseSiteController;

class MainController extends BaseSiteController
{

    private $curr_lang;

    private $seo;

    /**
     * MainController constructor.
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
        $seo_page = $this->seo->getSeoForPage('main');

//        if($this->curr_lang == 'en'){
//            Url::redirect('/ru/new');
//        }

        $this->render("new_site/{$this->curr_lang}/index", compact('seo_page'));
        return true;
    }
}
