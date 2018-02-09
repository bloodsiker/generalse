<?php

namespace Umbrella\controllers\umbrella\site;

use Josantonius\Request\Request;
use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\Functions;
use Umbrella\models\Admin;
use Umbrella\models\site\News;
use upload as FileUpload;

class NewsController extends AdminBase
{

    /**
     *  Path to the upload file for the psr
     */
    const UPLOAD_PATH_NEWS = '/upload/site/upload_news';

    /**
     * @var User
     */
    private $user;

    /**
     * VacancyController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->user = new User(Admin::CheckLogged());
    }


    /**
     * @return bool
     */
    public function actionAllNews()
    {
        $user = $this->user;

        $all_news = News::getAllNews();

        $message = Session::pull('message');

        $this->render('admin/site/news/index', compact('user', 'all_news', 'message'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionAdd()
    {
        $user = $this->user;

        if(Request::post('add_new') && Request::post('add_new') == 'true'){

            if (Request::files('new_image')) {
                $image = null;
                $handle = new FileUpload(Request::files('new_image'));
                if ($handle->uploaded) {
                    $handle->file_new_name_body = substr_replace(sha1(microtime(true)), '', 15);
                    $image = self::UPLOAD_PATH_NEWS . '/' . $handle->file_new_name_body . '.' . $handle->file_src_name_ext;
                    $handle->process(ROOT . self::UPLOAD_PATH_NEWS);
                    $handle->processed;
                    $handle->clean();
                }
            }

            $options['image'] = $image;
            $options['slug'] = Functions::strUrl(Request::post('ru_title')) . '-' . strtotime('now');
            $options['en_title'] = Request::post('en_title');
            $options['en_description'] = Request::post('en_description');
            $options['en_text'] = Request::post('en_text');
            $options['ru_title'] = Request::post('ru_title');
            $options['ru_description'] = Request::post('ru_description');
            $options['ru_text'] = Request::post('ru_text');
            $options['published'] = Request::post('published');

            $ok = News::add($options);
            if($ok){
                Session::set('message', 'New added!');
                Url::redirect('/adm/site/news');
            }
        }
        $this->render('admin/site/news/create', compact('user'));
        return true;
    }


    /**
     * @param $slug
     *
     * @return bool
     */
    public function actionEdit($slug)
    {
        $user = $this->user;

        $new = News::getNewBySlug($slug);

        if($new){
            if(Request::post('edit_new') && Request::post('edit_new') == 'true'){
                $image = $new['image'];
                if (Request::files('new_image')) {
                    $handle = new FileUpload(Request::files('new_image'));
                    if ($handle->uploaded) {
                        $handle->file_new_name_body = substr_replace(sha1(microtime(true)), '', 15);
                        $image = self::UPLOAD_PATH_NEWS . '/' .$handle->file_new_name_body . '.' . $handle->file_src_name_ext;
                        $handle->process(ROOT . self::UPLOAD_PATH_NEWS);
                        $handle->processed;
                        $handle->clean();
                    }
                }

                $options['image'] = $image;
                $options['en_title'] = Request::post('en_title');
                $options['en_description'] = Request::post('en_description');
                $options['en_text'] = Request::post('en_text');
                $options['ru_title'] = Request::post('ru_title');
                $options['ru_description'] = Request::post('ru_description');
                $options['ru_text'] = Request::post('ru_text');
                $options['published'] = Request::post('published');

                $ok = News::update($slug, $options);
                if($ok){
                    Session::set('message', 'New saved!');
                    Url::redirect('/adm/site/news');
                }
            }
        } else {
            Session::set('message', 'New not found!');
            Url::redirect('/adm/site/news');
        }

        $this->render('admin/site/news/edit', compact('user', 'new'));
        return true;
    }


    /**
     * @param $slug
     *
     * @return bool
     */
    public function actionDelete($slug)
    {
        $user = $this->user;

        $new = News::getNewBySlug($slug);
        if($new){
            $ok = News::delete($slug);
            if($ok) {
                Session::set('message', 'New deleted!');
                Url::redirect('/adm/site/news');
            }
        } else {
            Session::set('message', 'New not found!');
            Url::redirect('/adm/site/news');
        }
        return true;
    }
}