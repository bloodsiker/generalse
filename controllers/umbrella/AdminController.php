<?php

namespace Umbrella\controllers\umbrella;

use Josantonius\Request\Request;
use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\Functions;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\api\hr\Auth;

/**
 * Class AdminController
 */
class AdminController extends AdminBase
{

    /**
     * @return bool
     * @throws \Exception
     */
    public function actionAuth(){

        if(Request::post('action') == 'login'){
            $login = Request::post('login');
            $password = Functions::hashPass(Request::post('password'));

            //$server = 'down'; // down || up
            if(config('app')['server'] == 'up'){
                //Проверяем существует ли пользователь
                $userId = Admin::checkAdminData($login, $password);

                if($userId == false){
                    $errors['log'] = config('app')['notification']['login_false'];
                    $errors['code'] = 1;
                    echo json_encode($errors);
                } else {
                    //Если данные правильные, запоминаем пользователя в сессию
                    $user = new User($userId);
                    // Доступ к проекту
                    if ($user->getAuthProject('umbrella')){
                        //Проверка на проплату в GM
                        if($user->getUserBlockedGM() == 'active'){
                            if($user->isActive() == 1){
                                Admin::auth($user);

                                //Перенаправляем пользователя в закрытую часть – кабинет
                                $succusse['log'] = $user->getUrlAfterLogin();
                                $succusse['code'] = 2;
                                echo json_encode($succusse);
                            } elseif ($user->isActive() == 0) {
                                Session::destroy('info_user');
                                $errors['log'] = config('app')['notification']['user_is_active'];
                                $errors['code'] = 3;
                                echo json_encode($errors);
                            }
                        } else {
                            Admin::auth($user);
                            $succusse['log'] = config('app')['url_redirect']['user_risk'];
                            $succusse['code'] = 2;
                            echo json_encode($succusse);
                        }
                    } else {
                        $errors['log'] = config('app')['notification']['project_denied'];
                        $errors['code'] = 3;
                        echo json_encode($errors);
                    }
                }
            } else {
                $errors['log'] = config('app')['notification']['server_down'];
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
        Auth::updateToken($userId, null);

        Logger::getInstance()->log($userId, 'вышел(а) с кабинета');
        Url::redirect('/');
    }

}