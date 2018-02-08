<?php
namespace Umbrella\controllers\site;

use Umbrella\app\Services\site\SeoMetaService;
use Umbrella\vendor\controller\Controller;

class SuppliersController extends Controller
{

    private $curr_lang;

    private $seo;

    /**
     * SuppliersController constructor.
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
        $seo_page = $this->seo->getSeoForPage('suppliers');

        $this->render("new_site/{$this->curr_lang}/suppliers", compact('seo_page'));
        return true;
    }
}
