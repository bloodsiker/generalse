<?php

namespace Umbrella\controllers\umbrella\site;

use Josantonius\Request\Request;
use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\site\ServiceCenter;

class ServiceCenterController extends AdminBase
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
        $this->user = new User(Admin::CheckLogged());
    }


    /**
     * @return bool
     */
    public function actionAll()
    {
        $user = $this->user;

        $allServiceCenter = ServiceCenter::getAllServiceCenter('id', 'DESC');

        $message = Session::pull('message');

        $this->render('admin/site/service_center/index', compact('user', 'allServiceCenter', 'message'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionAdd()
    {
        $user = $this->user;

        if(Request::post('add_sc') && Request::post('add_sc') == 'true'){

            $options['country_code'] = Request::post('country_code');
            $country = ServiceCenter::swapCountry($options['country_code']);
            $options['country_ru'] = $country['ru'];
            $options['company_name'] = Request::post('company_name');
            $options['city_ru'] = Request::post('city_ru');
            $options['address_ru'] = Request::post('address_ru');
            $options['phone'] = Request::post('phone');
            $options['specialization_ru'] = Request::post('specialization_ru');

            $options['country_en'] = $country['en'];
            $options['company_name_en'] = Request::post('company_name_en');
            $options['city_en'] = Request::post('city_en');
            $options['address_en'] = Request::post('address_en');
            $options['specialization_en'] = Request::post('specialization_en');

            $ok = ServiceCenter::addServiceCenter($options);
            if($ok){
                Session::set('message', 'Service center added!');
                Url::redirect('/adm/site/service-center');
            }
        }
        $this->render('admin/site/service_center/create', compact('user'));
        return true;
    }


    /**
     * @param $slug
     *
     * @return bool
     */
    public function actionEdit($id)
    {
        $user = $this->user;

        $sc = ServiceCenter::find($id);

        if($sc){
            if(Request::post('edit_sc') && Request::post('edit_sc') == 'true'){

                $options['country_code'] = Request::post('country_code');
                $country = ServiceCenter::swapCountry($options['country_code']);
                $options['country_ru'] = $country['ru'];
                $options['company_name'] = Request::post('company_name');
                $options['city_ru'] = Request::post('city_ru');
                $options['address_ru'] = Request::post('address_ru');
                $options['phone'] = Request::post('phone');
                $options['specialization_ru'] = Request::post('specialization_ru');

                $options['country_en'] = $country['en'];
                $options['company_name_en'] = Request::post('company_name_en');
                $options['city_en'] = Request::post('city_en');
                $options['address_en'] = Request::post('address_en');
                $options['specialization_en'] = Request::post('specialization_en');

                $ok = ServiceCenter::update($id, $options);
                if($ok){
                    Session::set('message', 'Service Center saved!');
                    Url::redirect('/adm/site/service-center');
                }
            }
        } else {
            Session::set('message', 'Service Center not found!');
            Url::redirect('/adm/site/service-center');
        }

        $this->render('admin/site/service_center/edit', compact('user', 'sc'));
        return true;
    }


    /**
     * @param $id
     *
     * @return bool
     */
    public function actionDelete($id)
    {
        $sc = ServiceCenter::find($id);
        if($sc){
            $ok = ServiceCenter::delete($id);
            if($ok) {
                Session::set('message', 'Service Center deleted!');
                Url::redirect('/adm/site/service-center');
            }
        } else {
            Session::set('message', 'Service Center not found!');
            Url::redirect('/adm/site/service-center');
        }
        return true;
    }
}