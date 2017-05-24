<?php

class UserGroupController extends AdminBase
{
    public function actionAddGroup()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
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

        require_once(ROOT . '/views/admin/country/create.php');
        return true;
    }
}