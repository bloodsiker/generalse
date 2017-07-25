<?php

/**
 * Class CustomerCarCenterController
 */
class CustomerCareCenterController extends AdminBase
{

    public function __construct()
    {
        //
    }

    public function actionIndex()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        require_once(ROOT . '/views/admin/ccc/index.php');
        return true;
    }
}