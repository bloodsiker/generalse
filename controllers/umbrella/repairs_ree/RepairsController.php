<?php

namespace Umbrella\controllers\umbrella\repairs_ree;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;

class RepairsController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * PsrController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('adm.repairs_ree', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }


    /**
     * @return bool
     */
    public function actionIndex()
    {
        $user = $this->user;

        $this->render('admin/repairs_ree/index', compact('user'));
        return true;
    }
}