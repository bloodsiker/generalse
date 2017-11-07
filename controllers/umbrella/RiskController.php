<?php

namespace Umbrella\controllers\umbrella;

use Josantonius\Session\Session;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\models\Admin;
use Umbrella\models\Risk;
use Umbrella\vendor\controller\Controller;

/**
 * Просроченные счета по оплате
 * Class RiskController
 * @package Umbrella\controllers\umbrella
 */
class RiskController extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * CountryController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * Просроченные платежы
     * @return bool
     */
    public function actionRisks()
    {
        $user = $this->user;
        Session::destroy('info_user');

        $listRisks = Decoder::arrayToUtf(Risk::getUserRisks(15));

        $this->render('admin/risks', compact('user', 'listRisks'));
        return true;
    }
}