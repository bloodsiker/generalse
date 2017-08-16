<?php

namespace Umbrella\controllers\umbrella;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\Functions;
use Umbrella\components\Logger;
use Umbrella\models\Admin;

/**
 * Class AdminController
 */
class AdminController extends AdminBase
{

    /**
     * @return bool
     */
    public function actionAuth(){

        if($_REQUEST['action'] == 'login'){
            $login = $_REQUEST['login'];
            $password = Functions::hashPass($_REQUEST['password']);

            //Проверяем существует ли пользователь
            $userId = Admin::checkAdminData($login, $password);

            if($userId == false){
                $errors['log'] = 'Incorrect data entry';
                $errors['code'] = 1;
                echo json_encode($errors);
            } else {
                //Если данные правильные, запоминаем пользователя в сессию
                Admin::auth($userId);
                $user = new User($userId);

                Logger::getInstance()->log($userId, 'вошел(а) в кабинет');

                //Перенаправляем пользователя в закрытую часть – кабинет
                //header("Location: /adm/kpi");
                $succusse['log'] = $user->login_url;
                $succusse['code'] = 2;
                echo json_encode($succusse);
            }
        }
        return true;
    }


    /**
     * Доступ закрыт
     * @return bool
     */
    public function actionAccess()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $this->render('admin/access_denied', compact('user'));
        return true;
    }


    public function actionLogout()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();

        unset($_SESSION['user']);
        unset($_SESSION['_token']);

        Logger::getInstance()->log($userId, 'вышел(а) с кабинета');
        header("Location: /");
    }

}