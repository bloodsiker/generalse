<?php
namespace Umbrella\controllers\umbrella\crm;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;

/**
 * Class PsrController
 */
class PsrController extends AdminBase
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
        self::checkDenied('crm.psr', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * @return bool
     */
    public function actionPsr()
    {
        $user = $this->user;

        $this->render('admin/crm/psr', compact('user'));
        return true;
    }

}