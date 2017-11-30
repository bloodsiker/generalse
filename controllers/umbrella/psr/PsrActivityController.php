<?php

namespace Umbrella\controllers\umbrella\psr;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\Products;
use Umbrella\models\psr\Psr;

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
        self::checkDenied('adm.psr', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }


    /**
     * @return bool
     */
    public function actionIndex()
    {
        $user = $this->user;

        $this->render('admin/psr/activity/index', compact('user', 'listPsr'));
        return true;
    }


    /**
     * @return bool
     */
    public function actionPsrAjax()
    {

        return true;
    }

}