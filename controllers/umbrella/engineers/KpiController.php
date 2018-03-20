<?php
namespace Umbrella\controllers\umbrella\engineers;

use Josantonius\Request\Request;
use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\models\Admin;
use Umbrella\models\engineer\Dashboard;
use Umbrella\models\engineer\Kpi;

/**
 * Class KpiController
 */
class KpiController extends  AdminBase
{
    /**
     * @var User
     */
    private $user;

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
        $user = $this->user;
        if(Request::post('filter') && Request::post('filter') == 'true'){
            $year = Request::post('year');
            $month = Request::post('month');
        } else {
            $date = new \DateTime(date('Y-m-d'));
            $month = (int)$date->modify('this week')->format('m');
            $year = $date->modify('this week')->format('Y');
        }


        $intervalYears = Dashboard::getYears();
        $intervalMonths = Dashboard::getMonths();
        $intervalMonths = array_map(function ($value) {
            $value['name'] = Dashboard::nameMonth($value['month']);
            return $value;
        }, $intervalMonths);

        $kpi = Decoder::arrayToUtf(Kpi::getKPI($month, $year));

        $nameEngineers = Decoder::arrayToUtf(Kpi::getNameEngineer($month, $year));

        $newKpi = [];

        foreach ($nameEngineers as $engineer){
            $i = 0;
            foreach ($kpi as $value){
                $value['result'] = round($value['result'], 2);
                $value['coef'] = round($value['coef'], 2);
                $value['rate'] = round($value['rate'], 2);
                if($engineer['worker_id'] == $value['worker_id']){
                    $newKpi[$engineer['worker_id']][$i] = $value;
                    $i++;
                }
            }
        }

        $this->render('admin/engineers/kpi/index',
            compact('user', 'intervalYears',
                'intervalMonths', 'year', 'month', 'newKpi', 'nameEngineers'));
        return true;
    }

}