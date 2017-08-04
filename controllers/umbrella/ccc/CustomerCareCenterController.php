<?php

namespace Umbrella\controllers\umbrella\ccc;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;

/**
 * Class CustomerCarCenterController
 */
class CustomerCareCenterController extends AdminBase
{

    public function __construct()
    {
        parent::__construct();
    }

    public function actionIndex()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $this->render('admin/ccc/index', compact('user'));
        return true;
    }
}