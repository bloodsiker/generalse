<?php
namespace Umbrella\controllers\site;

use Josantonius\Request\Request;
use Umbrella\app\Mail\Site\SendCareer;
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

    /**
     * @return bool
     */
    public function actionSendForm()
    {
        $data = Request::post('json');
        $data_json = json_decode($data, true);
        print_r($data_json);

        SendCareer::getInstance()->sendEmailSuppliers($data_json);

        return true;
    }
}
