<?php

namespace Umbrella\controllers\umbrella;

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
     * @return bool
     */
    public function actionAddCountry()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();

        $user = new User($userId);

        $countryList = Country::getAllCountry();

        // Обработка формы
        if (isset($_POST['add_country'])) {

            $options['short_name'] = $_POST['short_name'];
            $options['full_name'] = $_POST['full_name'];

            // Сохраняем изменения
            $id_country = Country::addCountry($options);

            if($id_country){
                // Перенаправляем пользователя на страницу юзеров
                header("Location: /adm/users");
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
        self::checkAdmin();
        $userId = Admin::CheckLogged();

        $user = new User($userId);

        $countryById = Country::getCountryId($id_country);

        // Обработка формы
        if (isset($_POST['update_country'])) {

            $options['short_name'] = $_POST['short_name'];
            $options['full_name'] = $_POST['full_name'];

            // Сохраняем изменения
            $id_country = Country::updateCountry($id_country, $options);

            if($id_country){
                // Перенаправляем пользователя на страницу юзеров
                header("Location: /adm/users");
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
        self::checkAdmin();
        $userId = Admin::CheckLogged();

        $user = new User($userId);

        if($user->role == 'administrator'){
            Country::deleteCountryById($id);
        } else {
            echo "<script>alert('У вас нету прав на удаление стран')</script>";
        }

        // Перенаправляем пользователя на страницу управлениями товарами
        header("Location: /adm/users");
        return true;
    }


}