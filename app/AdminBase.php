<?php

namespace Umbrella\app;

use Umbrella\models\Admin;
use Umbrella\app\UserDenied;
use Umbrella\app\User;
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

//        if($admin['name_partner'] != 'Dima Ovsijchuk'){
//            unset($_SESSION['user']);
//            header('Location: /');
//        }

        // Если роль текущего пользователя "admin", пускаем его в админпанель
//        if ($admin['role'] == 'admin' || $admin['role'] == 'moderator') {
//            return true;
//        }

        // Иначе завершаем работу с сообщением об закрытом доступе
        //die('Доступ закрыт');
    }


    /**
     * проверяем, и запрещаем посещать пользователю закрытые разделы
     * @param $section
     * @param string $param
     * @return bool
     */
    public static function checkDenied($section, $param = 'view')
    {
        $adminId = Admin::checkLogged();
        $user = new User($adminId);

        if($user->is_active == 0) {
            unset($_SESSION['user']);
            header('Location: /');
        }

        $denied = new UserDenied($user);
        if($param == 'view'){
            return $denied->checkUserInDeniedList($section, 'slug');
        } elseif ($param == 'controller'){
            $check = $denied->checkUserInDeniedList($section, 'slug');
            if($check === false){
                header('Location: /adm/access_denied');
            }
            return true;
        }
    }

}
