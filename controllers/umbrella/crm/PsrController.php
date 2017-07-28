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

    ##############################################################################
    ##############################      PSR          #############################
    ##############################################################################

    /**
     * PsrController constructor.
     */
    public function __construct()
    {
        self::checkDenied('crm.psr', 'controller');
    }

    /**
     * @return bool
     */
    public function actionPsr()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        require_once(ROOT . '/views/admin/crm/psr.php');
        return true;
    }

}