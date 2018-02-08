<?php

namespace Umbrella\controllers\umbrella\site;

use Josantonius\Request\Request;
use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\Functions;
use Umbrella\models\Admin;
use Umbrella\models\site\Vacancy;

class VacancyController extends AdminBase
{
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
        //self::checkDenied('group.view', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }


    /**
     * @return bool
     */
    public function actionAllVacancy()
    {
        $user = $this->user;

        $all_vacancy = Vacancy::getAllVacancy();

        $message = Session::pull('message');

        $this->render('admin/site/vacancy/index', compact('user', 'all_vacancy', 'message'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionAdd()
    {
        $user = $this->user;

        if(Request::post('add_vacancy') && Request::post('add_vacancy') == 'true'){
            $options['slug'] = Functions::strUrl(Request::post('ru_title')) . '-' . strtotime('now');
            $options['en_title'] = Request::post('en_title');
            $options['en_department'] = Request::post('en_department');
            $options['en_location'] = Request::post('en_location');
            $options['en_employment'] = Request::post('en_employment');
            $options['en_text'] = Request::post('en_text');
            $options['ru_title'] = Request::post('ru_title');
            $options['ru_department'] = Request::post('ru_department');
            $options['ru_location'] = Request::post('ru_location');
            $options['ru_employment'] = Request::post('ru_employment');
            $options['ru_text'] = Request::post('ru_text');
            $options['published'] = Request::post('published');

            $ok = Vacancy::addVacancy($options);
            if($ok){
                Session::set('message', 'Vacancy added!');
                Url::redirect('/adm/site/vacancy');
            }
        }
        $this->render('admin/site/vacancy/create', compact('user'));
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

        $vacancy = Vacancy::getVacancyBySlug($slug);

        if($vacancy){
            if(Request::post('edit_vacancy') && Request::post('edit_vacancy') == 'true'){
                $options['en_title'] = Request::post('en_title');
                $options['en_department'] = Request::post('en_department');
                $options['en_location'] = Request::post('en_location');
                $options['en_employment'] = Request::post('en_employment');
                $options['en_text'] = Request::post('en_text');
                $options['ru_title'] = Request::post('ru_title');
                $options['ru_department'] = Request::post('ru_department');
                $options['ru_location'] = Request::post('ru_location');
                $options['ru_employment'] = Request::post('ru_employment');
                $options['ru_text'] = Request::post('ru_text');
                $options['published'] = Request::post('published');

                $ok = Vacancy::updateVacancy($slug, $options);
                if($ok){
                    Session::set('message', 'Vacancy saved!');
                    Url::redirect('/adm/site/vacancy');
                }
            }
        } else {
            Session::set('message', 'Vacancy not found!');
            Url::redirect('/adm/site/vacancy');
        }

        $this->render('admin/site/vacancy/edit', compact('user', 'vacancy'));
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

        $vacancy = Vacancy::getVacancyBySlug($slug);
        if($vacancy){
            $ok = Vacancy::deleteVacancy($slug);
            if($ok) {
                Session::set('message', 'Vacancy deleted!');
                Url::redirect('/adm/site/vacancy');
            }
        } else {
            Session::set('message', 'Vacancy not found!');
            Url::redirect('/adm/site/vacancy');
        }

        $this->render('admin/site/vacancy/index', compact('user', 'all_vacancy'));
        return true;
    }
}