<?php

namespace Umbrella\controllers\umbrella;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\Innovation;

class InnovationController extends AdminBase
{

    /**
     * @var User
     */
    private $user;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('adm.users', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }


    public function actionIndex()
    {
        return true;
    }


    /**
     * Ajax action
     */
    public function actionAjaxAction()
    {
        $user = $this->user;

        if($_REQUEST['action'] == 'innovation_view'){
            $innovation_id = $_REQUEST['innovation_id'];
            $user_id = $user->id_user;
            $ok = Innovation::viewInnovation($innovation_id, $user_id);
            if($ok){
                echo '200';
            }
        }
        return true;
    }
}