<?php
namespace Umbrella\controllers\umbrella\engineers;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;


/**
 * Class ReturnsController
 */
class ReturnsController extends  AdminBase
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
     */
    public function actionIndex()
    {
        $user = $this->user;

        $this->render('admin/engineers/returns', compact('user'));
        return true;
    }

}