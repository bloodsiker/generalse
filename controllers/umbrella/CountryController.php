<?php

namespace Umbrella\controllers\umbrella;

use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\Country;

/**
 * Class CountryController
 */
class CountryController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * CountryController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * @return bool
     */
    public function actionAddCountry()
    {
        $user = $this->user;

        $countryList = Country::getAllCountry();

        if (isset($_POST['add_country'])) {

            $options['short_name'] = $_POST['short_name'];
            $options['full_name'] = $_POST['full_name'];

            $id_country = Country::addCountry($options);

            if($id_country){
                Url::redirect('/adm/users');
            }
        }

        $this->render('admin/country/create', compact('user', 'countryList'));
        return true;
    }


    /**
     * @param $id_country
     * @return bool
     *
     */
    public function actionUpdate($id_country)
    {
        $user = $this->user;

        $countryById = Country::getCountryId($id_country);

        if (isset($_POST['update_country'])) {

            $options['short_name'] = $_POST['short_name'];
            $options['full_name'] = $_POST['full_name'];

            $id_country = Country::updateCountry($id_country, $options);

            if($id_country){
                Url::redirect('/adm/users');
            }
        }

        $this->render('admin/country/update', compact('user', 'countryById'));
        return true;
    }


    /**
     * @param $id
     * @return bool
     */
    public function actionDelete($id)
    {
        $user = $this->user;

        if($user->role == 'administrator'){
            Country::deleteCountryById($id);
        } else {
            echo "<script>alert('У вас нету прав на удаление стран')</script>";
        }

        Url::redirect('/adm/users');
        return true;
    }


}