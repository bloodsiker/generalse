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

    /**
     * @var User
     */
    private $user;

    /**
     * CustomerCareCenterController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('adm.ccc', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * @return bool
     */
    public function actionIndex()
    {
        $user = $this->user;

        $this->render('admin/ccc/index', compact('user'));
        return true;
    }
}