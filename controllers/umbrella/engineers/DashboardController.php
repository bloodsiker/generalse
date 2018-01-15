<?php
namespace Umbrella\controllers\umbrella\engineers;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;

/**
 * Class DashboardController
 */
class DashboardController extends  AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * BatchController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        //self::checkDenied('crm.crm', 'controller');
        $this->user = new User(Admin::CheckLogged());

    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function actionIndex()
    {
        //self::checkDenied('crm.crm', 'controller');
        $user = $this->user;

        $this->render('admin/engineers/dashboard', compact('user'));
        return true;
    }

}