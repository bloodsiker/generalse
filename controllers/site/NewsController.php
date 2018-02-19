<?php
namespace Umbrella\controllers\site;

use Umbrella\app\Services\site\NewsService;
use Umbrella\app\Services\site\SeoMetaService;
use Umbrella\vendor\controller\Controller;

class NewsController extends Controller
{

    private $curr_lang;

    private $seo;

    /**
     * NewsController constructor.
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
        $seo_page = $this->seo->getSeoForPage('news');

        $news = new NewsService($this->curr_lang);
        $all_news = $news->getAllNews();

        $this->render("new_site/{$this->curr_lang}/news/index", compact('seo_page', 'all_news'));
        return true;
    }

    /**
     * @param $slug
     *
     * @return bool
     */
    public function actionNew($slug)
    {
        $seo_page = $this->seo->getSeoForPage('show_new');

        $news = new NewsService($this->curr_lang);
        $info_news = $news->findBySlug($slug);
        $seo_page['title'] .= ' – ' . $info_news['title'];

        $this->render("new_site/{$this->curr_lang}/news/show_new", compact('seo_page', 'info_news'));
        return true;
    }
}
