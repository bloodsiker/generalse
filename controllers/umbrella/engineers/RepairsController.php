<?php
namespace Umbrella\controllers\umbrella\engineers;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;


/**
 * Class RepairsController
 */
class RepairsController extends  AdminBase
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

        $this->render('admin/engineers/repairs/index', compact('user'));
        return true;
    }

    /**
     *
     */
    public function actionAjax()
    {
        if(isset($_REQUEST['action'])){

            if( $_REQUEST['action'] == 'get_data_for_diagram'){
                $array = [
                    ['Type', 'Count'],
                    ['Диагностика', 11],
                    ['Согласование', 2],
                    ['Ожидает запчасти', 2],
                    ['Отказ', 2],
                    ['Ремонт', 7],
                ];
                print_r(json_encode($array));
            }

            if($_REQUEST['action'] == 'show_repairs'){
                $diagram_value = $_REQUEST['diagram_value'];
                $this->render('admin/engineers/repairs/_part/show_repairs', compact('diagram_value'));
            }
        }
        return true;
    }

}