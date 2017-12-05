<?php

namespace Umbrella\controllers\umbrella\psr;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\GroupModel;
use Umbrella\models\Log;

class PsrActivityController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * PsrController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('adm.psr.activity', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }


    /**
     * @return bool
     */
    public function actionIndex()
    {
        $user = $this->user;

        $usersInGroup = GroupModel::getUsersByGroup(6); //6

        $logList = [];

        if(isset($_REQUEST['apply_filter']) && $_REQUEST['apply_filter'] == 'true'){
            $userId = $_REQUEST['user_id'];
            $fromDate = $_REQUEST['start'] . ' 00:00';
            $toDate = $_REQUEST['end'] . ' 23:59';

            $logList = Log::getLogByUserId($userId, $fromDate, $toDate);
        }

        $this->render('admin/psr/activity/index', compact('user', 'usersInGroup', 'logList'));
        return true;
    }
}