<?php

/**
 * Class AdminController
 */
class AdminController extends AdminBase
{

    public function actionAuth2(){

        $login = '';
        $password = '';

        if(isset($_POST['submit'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];

            $errors = false;

            if (!Admin::checkLogin($login)) {
                $errors[] = 'Не верный логин';
            }

            //Проверяем существует ли пользователь
            $adminId = Admin::checkAdminData($login, $password);

            if ($adminId == false){
                $errors[] = 'Не верные данные для входа в кабинет';
            } else {
                //Если данные правильные, запоминаем пользователя в сессию
                Admin::auth($adminId);

                //Перенаправляем пользователя в закрытую часть – кабинет
                header("Location: /adm/kpi");
            }
        }

        require_once(ROOT . '/views/site/auth.php');
        return true;
    }



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

                Logger::getInstance()->log($userId, 'вошел(а) в кабинет');

                //Перенаправляем пользователя в закрытую часть – кабинет
                //header("Location: /adm/kpi");
                $succusse['log'] = "/adm/crm/orders";
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
    public function actionAccess(){

        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

        require_once(ROOT . '/views/admin/access_denied.php');
        return true;
    }


    public function actionLogout()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        unset($_SESSION['user']);
        unset($_SESSION['_token']);

        Logger::getInstance()->log($userId, 'вышел(а) с кабинета');
        header("Location: /");
    }

}