<?php

/**
 * Class DashboardController
 */
class DashboardController extends AdminBase
{

    /**
     * @return bool
     */
    public function actionIndex()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        $coefficient = new CoefficientKPI(new KPI($user->name_partner, date('Y-m') . '-01', date('Y-m-d')));

        $partnerList = Admin::getAllPartner();

//        $ids = array_column($partnerList, 'id_user');
//        $json = json_encode($ids);
//
        //$stock = ['OK', 'BAD', 'Local Source', 'Not Used', 'Restored', 'Dismantling', 'Swap', 'Restored Bad'];
//        $json = json_encode($stock);
//        echo "<pre>";
//        print_r($json);

        $task = new Task();
        //$listTask = $task->taskFactory();


        // Получаем список месяцей и годов из таблицы балансов.
        $listInterval = Balance::getDistinctMonthAndYear();

        // Текущий месяц и год
        $currentMonthYear = Balance::getCurrentMonthAndYear();
        $month = Balance::formatMonth($currentMonthYear['current_month']);
        $year = $currentMonthYear['year'];

        $userInfo = Admin::getAdminById($user->id_user);

        // Получаем месяц и год с формы
        if(isset($_GET['interval']) && !empty($_GET['interval'])){
            $interval = $_GET['interval'];
            $balanceMonth = Balance::getBalanceMonthByPartner($user->id_user, $interval);
        } else {
            // Иначе показываем информацию на текущий месяц
            $interval = $year . '-' . $month;
            $balanceMonth = Balance::getBalanceMonthByPartner($user->id_user, $interval);
        }

        if($user->role == 'partner') {

            $userDetailsBalance = Balance::getDetailsBalanceByPartner($user->id_user, $interval);

        } elseif ($user->role == 'branch-fin'){

            $listUsersInBranch = Branch::getPartnerByBranchNotInFin($user->id_branch);

        } elseif($user->role == 'administrator-fin'){

            //Заявки на выплату от партнеров
            $requestUsersPaid = Balance::getAllRequestPaid();
            //Действия по балансам
            $userDetailsBalance = Balance::getDetailsBalanceByPartner($user->id_user, $interval);
        }

        require_once(ROOT . '/views/admin/dashboard/index.php');
        return true;
    }

    /**
     * Выводим таблицу юзеров в дашбрде для просмотра балансов
     * @return bool
     */
    public function actionUsers()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if($user->role != 'administrator-fin') {
            header("Location: /adm/access_denied");
        }

        $partnerList = Admin::getAllPartner();

        require_once(ROOT . '/views/admin/dashboard/users.php');
        return true;
    }

    /**
     * Заявки на выплату
     * @return bool
     */
    public function actionRequestPayment()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if($user->role != 'administrator-fin') {
            header("Location: /adm/access_denied");
        }

        if($user->role == 'administrator-fin'){

            //Заявки на выплату от партнеров
            $requestUsersPaid = Balance::getAllRequestPaid();
        }

        require_once(ROOT . '/views/admin/dashboard/request_payment.php');
        return true;
    }

    /**
     * Просмотр балансу партнера
     * @param $id_user
     * @return bool
     */
    public function actionUserBalance($id_user)
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if($user->role != 'administrator-fin') {
            header("Location: /adm/access_denied");
        }

        $partnerList = Admin::getAllPartner();
        // Получаем список месяцей и годов из таблицы балансов.
        $listInterval = Balance::getDistinctMonthAndYear();

        // Текущий месяц и год
        $currentMonthYear = Balance::getCurrentMonthAndYear();
        $month = Balance::formatMonth($currentMonthYear['current_month']);
        $year = $currentMonthYear['year'];

        $userInfo = Admin::getAdminById($id_user);

        // Список заказчиков
        $listCustomer = Dashboard::getAllCustomer();

        // Получаем месяц и год с формы
        if(isset($_GET['interval']) && !empty($_GET['interval'])){
            $interval = $_GET['interval'];
            $balanceMonth = Balance::getBalanceMonthByPartner($id_user, $interval);
        } else {
            // Иначе показываем информацию на текущий месяц
            $interval = $year . '-' . $month;
            $balanceMonth = Balance::getBalanceMonthByPartner($id_user, $interval);
        }

        $userDetailsBalance = Balance::getDetailsBalanceByPartner($id_user, $interval);

        // Начисление штрафных санкций
        if(isset($_POST['send-penalty']) && $_POST['send-penalty'] == 'true'){
            if($_POST['_token'] == $_SESSION['_token']){
                $id_number = Balance::getNumberBalanceByUser($id_user);

                $options['id_user'] = $id_user;
                $options['penalty'] = '-' . (float)str_replace(',','.', $_POST['penalty']);
                $options['id_number'] = $id_number['id'];
                $options['action_balance'] = 'Штрафные санкции';
                $options['id_customer'] = $_POST['id_customer'];
                $options['comment'] = $_POST['comment'];
                if(isset($_GET['interval']) && !empty($_GET['interval'])){
                    $day = new DateTime($_GET['interval']);
                    $last_day = $day->format('t');
                    $options['date_create'] = $_GET['interval'] . '-' . $last_day . ' 23:59:00';
                } else {
                    $options['date_create'] = date('Y-m-d H:i:s');
                }

                $ok = Balance::addPenaltyBalance($options);
                if($ok){
                    // Send manager email

                    header("Location: "  . $_SERVER['HTTP_REFERER']);
                }

            }
        }

        require_once(ROOT . '/views/admin/dashboard/user_balance.php');
        return true;
    }


    public function actionTask()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if($user->role != 'administrator-fin') {
            header("Location: /adm/access_denied");
        }

        $taskList = TaskList::getTaskList();

        require_once(ROOT . '/views/admin/dashboard/task.php');
        return true;
    }


    /**
     * Запросить выплату баланса
     * @return bool
     */
    public  function actionPostPay()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);
        if(isset($_POST['action']) && $_POST['action'] == 'pay'){
            $id_number = Balance::getNumberBalanceByUser($user->id_user);

            $options['id_user'] = $user->id_user;
            $options['receive_funds'] = '-' . (float)$_POST['receive_funds'];
            $options['id_number'] = $id_number['id'];
            $options['action_balance'] = 'Запрос на выплату за ' . Balance::getNameMonth($_POST['interval']);
            $options['id_customer'] = 0;
            $options['status'] = 'Ожидание';
            $day = new DateTime($_POST['interval']);
            $last_day = $day->format('t');
            $options['date_create'] = $_POST['interval'] . '-' . $last_day . ' 23:59:00';
            $options['request_paid'] = 1;
            //print_r($options);
            $ok = Balance::outputBalance($options);
            if($ok){
                // Send manager email

                header("Location: "  . $_SERVER['HTTP_REFERER']);
            }
        }
        return true;
    }

    /**
     * Действие по балансу, подтверждение, отказ
     * @return bool
     */
    public  function actionAjaxBalance()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if($user->role == 'administrator-fin' || $user->role == 'administrator'){
            if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'accept'){
                $paid_id = $_REQUEST['paid_id'];
                $paid = 1; // подтверждено
                $status = 'Подтверждено';
                $ok = Balance::updatePaid($paid_id, $status, $paid);
                $status = [];
                if($ok){
                    $status['ok'] = 1;
                    $status['class'] = 'green';
                    $status['text'] = 'Подтверждено';
                } else {
                    $status['ok'] = 0;
                    $status['error'] = 'Ошибка подтверждения!';
                }
                echo json_encode($status);
            }

            if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'dismiss'){
                $paid_id = $_REQUEST['paid_id'];
                $paid = 2; // Отказано
                $status = 'Отказано';
                $comment = $_REQUEST['comment'];
                $ok = Balance::updatePaid($paid_id, $status, $paid, $comment);
                $status = [];
                if($ok){
                    $status['ok'] = 1;
                    $status['class'] = 'red';
                    $status['text'] = 'Отказано';
                } else {
                    $status['ok'] = 0;
                    $status['error'] = 'Ошибка подтверждения!';
                }
                echo json_encode($status);
            }
        } else {
            die();
        }

        return true;
    }



    /**
     * @return bool
     */
    public function actionAjaxShowInfo()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'show'){
            $section = $_REQUEST['section'];
            $id_row = $_REQUEST['id_row'];
            $id_task = $_REQUEST['id_task'];

            $list_completed_elements = TaskListFunction::getListCompletedElements($section, $id_row, $id_task);

            $html = " ";
            foreach ($list_completed_elements as $element){
                $html .= "<tr>";
                $html .="<td>{$element['part_number']}</td>";
                $html .="<td>{$element['goods_name']}</td>";
                $html .="<td>{$element['quantity']}</td>";
                $html .="<td>{$element['stock_name']}</td>";
                $html .="<td>{$element['classifier']}</td>";
                $html .="<td>{$element['goods_sub_type']}</td>";
                $html .="<td>{$element['price']}</td>";
                $html .="<td>{$element['add_pay']}</td>";
                $html .= "</tr>";
            }
            print_r($html);
        }

        return true;
    }

}