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
    public function actionAuth()
    {

        if(Request::post('action') === 'login'){
            $lang = 'ru';
            $login = Request::post('login');
            $password = Functions::hashPass(Request::post('password'));
            //$lang = Request::post('lang');
            //$lang = ($current_lang == 'ru' || $current_lang == 'en') ? $current_lang : $default_lang;


            //$server = 'down'; // down || up
            if(config('app')['server'] === 'up'){
                //Проверяем существует ли пользователь
                $userId = Admin::checkAdminData($login, $password);

                if($userId == false){
                    $errors['log'] = config('app')['notification'][$lang]['login_false'];
                    $errors['code'] = 1;
                    echo json_encode($errors);
                } else {
                    //Если данные правильные, запоминаем пользователя в сессию
                    $user = new User($userId);
                    // Доступ к проекту
                    if ($user->getAuthProject('umbrella')){
                        //Проверка на проплату в GM
                        if($user->getUserBlockedGM() === 'active'){
                            if($user->isActive() == 1){
                                Admin::auth($user);

                                //Перенаправляем пользователя в закрытую часть – кабинет
                                $succusse['log'] = $user->getUrlAfterLogin();
                                $succusse['code'] = 2;
                                echo json_encode($succusse);
                            } elseif ($user->isActive() == 0) {
                                Session::destroy('info_user');
                                $errors['log'] = config('app')['notification'][$lang]['user_is_active'];
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
                        $errors['log'] = config('app')['notification'][$lang]['project_denied'];
                        $errors['code'] = 3;
                        echo json_encode($errors);
                    }
                }
            } else {
                $errors['log'] = config('app')['notification'][$lang]['server_down'];
                $errors['code'] = 3;
                echo json_encode($errors);
            }
        }
        return true;
    }


    /**
     * Доступ закрыт
     * @return bool
     * @throws \Exception
     */
    public function actionAccess()
    {
        self::checkAdmin();
        $userId = Admin::checkLogged();
        $user = new User($userId);

        $this->render('admin/access_denied', compact('user'));
        return true;
    }

    /**
     * Авторизируем админа\менеджера в кабинет партнера без запроса пароля
     * @return bool
     * @throws \Exception
     */
    public function actionReLogin()
    {
        $user = null;
        if(Request::get('auth_url') == 'true'){
            $listPartner = Admin::getAllUsers(' gr.id_role IN (1,3)');
            if(Request::get('user')){
                $user = new User(Request::get('user'));
                Admin::auth($user);
            }
        } else {
            $userId = Admin::checkLogged();
            $user = new User($userId);
            $listPartner = Admin::getAllPartner();
        }

        $error = false;

        if($user instanceof User){
            if($user->isAdmin() || $user->isManager() || $user->getReLogin()['access'] == 1){
                if(Request::post('re-login') === 'true'){
                    $idPartner = Request::post('id_partner');
                    Session::destroy('user');
                    Session::destroy('info_user');
                    $userPartner = new User($idPartner);
                    Session::set('user', $userPartner->getId());

                    $reLogin = Session::get('re_login');
                    $reLogin['my_account'] = 0;
                    Session::set('re_login', $reLogin);
                    Logger::getInstance()->log($user->getReLogin()['id'], 'зашел в кабинет партнера ' . $userPartner->getName());
                    Url::redirect('/' . $userPartner->getUrlAfterLogin());
                }
            } else {
                $error = true;
            }
        }


        $this->render('admin/re_login', compact('user', 'listPartner', 'error'));
        return true;
    }

    /**
     * Возвращаем админа\менеджера в свой кабинет
     * @return bool
     * @throws \Exception
     */
    public function actionReturnMyAccount()
    {
        Admin::checkLogged();

        if(Session::get('re_login')['my_account'] == 0){

            $reLogin = Session::get('re_login');
            Session::destroy('user');
            Session::destroy('info_user');
            $user = new User($reLogin['id']);
            Session::set('user', $user->getId());
            $reLogin['my_account'] = 1;
            Session::set('re_login', $reLogin);
            Url::redirect('/' . $user->getUrlAfterLogin());
        } else {
            Url::previous();
        }
        return true;
    }


    /**
     *
     */
    public function actionLogout()
    {
        $userId = Admin::checkLogged();

        Session::destroy('user');
        Session::destroy('_token');
        Session::destroy('info_user');
        Session::destroy('re_login');
        Auth::updateToken($userId, null);

        Logger::getInstance()->log($userId, 'вышел(а) с кабинета');
        Url::redirect('/');
    }

}