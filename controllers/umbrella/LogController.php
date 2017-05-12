<?php

/**
 * Class LogController
 */
class LogController extends AdminBase
{
    public function __construct()
    {
        self::checkDenied('user.logs', 'controller');
    }

    public function actionLogs()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);


        $allLogs = Log::getAllLog(0);

        require_once(ROOT . '/views/admin/users/log_user/log.php');
        return true;
    }


    /**
     * @return bool
     */
    public function actionAjaxLoad()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

        if($_REQUEST['action'] == 'load_log'){
            $num = $_REQUEST['num'];

            $resultLog = Log::getAllLog($num);
            if(count($resultLog) > 0){
                $html = "";
                foreach($resultLog as $log){
                    $html .= "<tr>";
                    $html .= "<td>" . $log['id_log'] . "</td>";
                    $html .= "<td>" . $log['name_partner'] . "</td>";
                    $html .= "<td>" . $log['log_text'] . "</td>";
					$html .= "<td>" . $log['ip_user'] . "</td>";
                    $html .= "<td>" . $log['user_agent'] . "</td>";
                    $html .= "<td>" . $log['date_log'] . "</td>";
                    $html .= "</tr>";
                }
                sleep(2); //Сделана задержка в 1 секунду чтобы можно проследить выполнение запроса
                echo $html;
            } else {
                echo 0; //Если записи закончились
            }
        }
        return true;
    }

}