<?php
namespace Umbrella\controllers\umbrella\engineers;

use Josantonius\Request\Request;
use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\models\Admin;
use Umbrella\models\engineer\Dashboard;

/**
 * Class DashboardController
 */
class DashboardController extends  AdminBase
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
        //self::checkDenied('crm.crm', 'controller');
        $user = $this->user;
        if(Request::post('filter') && Request::post('filter') == 'true'){
            $year = Request::post('year');
            $month = Request::post('month');
        } else {
            $date = new \DateTime(date('Y-m-d'));
            $month = (int)$date->modify('this week')->format('m');
            $year = $date->modify('this week')->format('Y');
        }

        $movementDevicesProducer = Decoder::arrayToUtf(Dashboard::getMovementDevicesProducer($month, $year),
            ['quantity_in', 'quantity_out', 'quantity_stock']);
        $totalDeviceProducer['quantity_in'] = array_sum(array_column($movementDevicesProducer, 'quantity_in'));
        $totalDeviceProducer['quantity_out'] = array_sum(array_column($movementDevicesProducer, 'quantity_out'));
        $totalDeviceProducer['quantity_stock'] = array_sum(array_column($movementDevicesProducer, 'quantity_stock'));
        $movementDevicesClassifier = Decoder::arrayToUtf(Dashboard::getMovementDevicesClassifier($month, $year),
            ['quantity_in', 'quantity_out', 'quantity_stock']);
        $totalDeviceClassifier['quantity_in'] = array_sum(array_column($movementDevicesClassifier, 'quantity_in'));
        $totalDeviceClassifier['quantity_out'] = array_sum(array_column($movementDevicesClassifier, 'quantity_out'));
        $totalDeviceClassifier['quantity_stock'] = array_sum(array_column($movementDevicesClassifier, 'quantity_stock'));

        $intervalYears = Dashboard::getYears();
        $intervalMonths = Dashboard::getMonths();
        $intervalMonths = array_map(function ($value) {
            $value['name'] = Dashboard::nameMonth($value['month']);
            return $value;
        }, $intervalMonths);

        $this->render('admin/engineers/dashboard/dashboard',
            compact('user', 'movementDevicesProducer', 'movementDevicesClassifier', 'intervalYears',
                'intervalMonths', 'year', 'month', 'totalDeviceClassifier', 'totalDeviceProducer'));
        return true;
    }

}