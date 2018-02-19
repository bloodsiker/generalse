<?php
namespace Umbrella\controllers\site;

use Umbrella\app\Services\site\SeoMetaService;
use Umbrella\app\Services\site\VacancyService;
use Umbrella\vendor\controller\Controller;

class CareerController extends Controller
{

    private $curr_lang;

    private $seo;

    /**
     * CareerController constructor.
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
        $seo_page = $this->seo->getSeoForPage('career');
        $vacancy = new VacancyService($this->curr_lang);
        $all_vacancy = $vacancy->getAllVacancy();

        $this->render("new_site/{$this->curr_lang}/career/index", compact('seo_page', 'all_vacancy'));
        return true;
    }

    /**
     * @param $slug
     *
     * @return bool
     */
    public function actionShow($slug)
    {
        $seo_page = $this->seo->getSeoForPage('show_vacancy');
        $vacancy = new VacancyService($this->curr_lang);
        $info_vacancy = $vacancy->findBySlug($slug);
        $seo_page['title'] .= ' – ' . $info_vacancy['title'];

        $this->render("new_site/{$this->curr_lang}/career/show_vacancy", compact('seo_page', 'info_vacancy'));
        return true;
    }
}
