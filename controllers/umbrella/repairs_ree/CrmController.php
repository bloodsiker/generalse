<?php

namespace Umbrella\controllers\umbrella\repairs_ree;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;

class CrmController extends AdminBase
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
        //self::checkDenied('adm.psr.activity', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }


    /**
     * @return bool
     */
    public function actionIndex()
    {
        $user = $this->user;

        $this->render('admin/repairs_ree/crm/crm', compact('user'));
        return true;
    }

    public function actionSearch()
    {
        $user = $this->user;

        $this->render('admin/repairs_ree/crm/crm', compact('user'));
        return true;
    }
}