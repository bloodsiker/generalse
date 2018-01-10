<?php

namespace Umbrella\app;

use Josantonius\Url\Url;
use Umbrella\app\Middleware\IsActiveUserMiddleware;
use Umbrella\app\Middleware\PartnerRisksMiddleware;
use Umbrella\models\Admin;
use Umbrella\vendor\controller\Controller;

/**
 * Абстрактный класс AdminBase содержит общую логику для контроллеров, которые
 * используются в панели администратора
 */
abstract class AdminBase extends Controller
{
    /**
     * Метод, который проверяет пользователя на то, является ли он администратором
     * @return boolean
     */
    public static function checkAdmin()
    {
        // Проверяем авторизирован ли пользователь. Если нет, он будет переадресован
        $adminId = Admin::checkLogged();

        // Получаем информацию о текущем пользователе
        $admin = Admin::getAdminById($adminId);

        // Иначе завершаем работу с сообщением об закрытом доступе
        //die('Доступ закрыт');
    }


    /**
     * проверяем, и запрещаем посещать пользователю закрытые разделы
     * @param $section
     * @param string $param
     *
     * @return bool
     * @throws \Exception
     */
    public static function checkDenied($section, $param = 'view')
    {
        $user = new User(Admin::checkLogged());

        if(config('app')['server'] == 'down'){
            $user->logout();
        }

        // Проверка на оплаченные счета
        new PartnerRisksMiddleware($user);

        //Проверка на доступ к кабинету
        new IsActiveUserMiddleware($user);

        // Проверка на доступы к разделам и действиям
        $denied = new UserDenied($user);
        if($param == 'view'){
            return $denied->checkUserInDeniedList($section, 'slug');
        } elseif ($param == 'controller'){
            $check = $denied->checkUserInDeniedList($section, 'slug');
            if($check === false){
                Url::redirect('/adm/access_denied');
            }
            return true;
        }
    }

}
