<?php
namespace Umbrella\controllers\site;

use Umbrella\app\Mail\Site\HelpNotFound;
use Umbrella\app\Services\site\SeoMetaService;
use Umbrella\controllers\BaseSiteController;

/**
 * Class NotFoundController
 * @package Umbrella\controllers\site
 */
class NotFoundController extends BaseSiteController
{
    private $curr_lang;

    private $seo;

    /**
     * NotFoundController constructor.
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
        $seo_page = $this->seo->getSeoForPage('404');
        $this->render("new_site/{$this->curr_lang}/404", compact('seo_page'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionSend()
    {
        if(isset($_REQUEST['json'])){
            $data_json = json_decode($_REQUEST['json'], true);

            $options['fio'] = $data_json['fio'];
            $options['email'] = $data_json['email'];
            $options['message'] = $data_json['message'];

            HelpNotFound::getInstance()->sendEmailPageNotFound($options);
            $result['code'] = 200;
            $result['message'] = '<h5>Спасибо! Сообщение отправленно.</h5>';
            print_r(json_encode($result));
        }
        return true;
    }
}
