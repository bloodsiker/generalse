<?php
namespace Umbrella\controllers\umbrella;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\Log;

/**
 * Class LogController
 */
class LogController extends AdminBase
{
    /**
     * LogController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('user.logs', 'controller');
    }


    /**
     * @return bool
     */
    public function actionLogs()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $allLogs = Log::getAllLog(0);

        $this->render('admin/users/log_user/log', compact('user', 'allLogs'));
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