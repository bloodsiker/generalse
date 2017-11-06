<?php

namespace Umbrella\controllers\umbrella;

use Josantonius\Session\Session;
use Josantonius\Url\Url;
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

            $server = 'up'; // down || up
            if($server == 'up'){
                //Проверяем существует ли пользователь
                $userId = Admin::checkAdminData($login, $password);

                if($userId == false){
                    $errors['log'] = 'Incorrect data entry';
                    $errors['code'] = 1;
                    echo json_encode($errors);
                } else {
                    //Если данные правильные, запоминаем пользователя в сессию
                    $user = new User($userId);
                    //Проверка на проплату в GM
                    if($user->getUserBlockedGM() == true){
                        if($user->isActive() == 1){
                            Admin::auth($userId);

                            Logger::getInstance()->log($userId, 'вошел(а) в кабинет');

                            //Перенаправляем пользователя в закрытую часть – кабинет
                            $succusse['log'] = $user->getUrlAfterLogin();
                            $succusse['code'] = 2;
                            echo json_encode($succusse);
                        } elseif ($user->isActive() == 0) {
                            Session::destroy('info_user');
                            $errors['log'] = 'Доступ к данному аккаунту закрыт!';
                            $errors['code'] = 3;
                            echo json_encode($errors);
                        }
                    } else {
                        Session::destroy('info_user');
                        $errors['log'] = 'К сожалению, у Вас есть просроченные платежи.
                                            Доступ к личному кабинету будет заблокирован до полного погашения просроченных счетов.
                                            Для дополнительной информации просьба связаться с нами по адресу gsteam@generalse.com
                                            ';
                        $errors['code'] = 3;
                        echo json_encode($errors);
                    }
                }
            } else {
                $errors['log'] = 'Извините, Umbrella на техническом облуживании!<br> Сервис будет доступен 27.10 в 10:00 по Киеву';
                $errors['code'] = 3;
                echo json_encode($errors);
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


    /**
     *
     */
    public function actionLogout()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();

        Session::destroy('user');
        Session::destroy('_token');
        Session::destroy('info_user');

        Logger::getInstance()->log($userId, 'вышел(а) с кабинета');
        Url::redirect('/');
    }

}