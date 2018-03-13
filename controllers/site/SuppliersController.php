<?php
namespace Umbrella\controllers\site;

use Josantonius\Request\Request;
use Josantonius\Url\Url;
use Umbrella\app\Mail\Site\SendSupplier;
use Umbrella\app\Services\site\SeoMetaService;
use Umbrella\components\Functions;
use Umbrella\controllers\BaseSiteController;
use upload as FileUpload;

class SuppliersController extends BaseSiteController
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
        $options['fio'] = Request::post('fio');
        $options['email'] = Request::post('email');
        $options['company'] = Request::post('company');
        $options['message'] = Request::post('message');
        $options['upload_file'] = false;

        if (!empty(Request::files('file-price'))) {
            $handle = new FileUpload(Request::files('file-price'));
            if ($handle->uploaded) {
                $handle->file_overwrite = true;
                $handle->file_new_name_body = Functions::strUrl($options['company']) . '-' . strtotime(date('Y-m-d H:i:s'));
                $file_name = $handle->file_new_name_body . '.' . $handle->file_src_name_ext;
                $path = '/upload/site/attach_suppliers/';
                $handle->process(ROOT . $path);
                if ($handle->processed) {
                    $handle->clean();
                    $options['file'] = Url::getDomain() . $path . $file_name;
                    $options['upload_file'] = 200;
                } else {
                    $options['upload_file'] = 400;
                    $options['upload_error'] = $handle->error;
                }
            }
        }

        SendSupplier::getInstance()->sendEmailSuppliers($options);

        return true;
    }
}
