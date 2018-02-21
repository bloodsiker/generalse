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


        $disassemblyProducer = Decoder::arrayToUtf(Dashboard::getDisassemblyProducer($month, $year),
            ['quantity_prev', 'quantity_decompiled', 'quantity_shipped', 'quantity_ok', 'quantity_bad']);
        $totalDisassemblyProducer['quantity_prev'] = array_sum(array_column($disassemblyProducer, 'quantity_prev'));
        $totalDisassemblyProducer['quantity_decompiled'] = array_sum(array_column($disassemblyProducer, 'quantity_decompiled'));
        $totalDisassemblyProducer['quantity_shipped'] = array_sum(array_column($disassemblyProducer, 'quantity_shipped'));
        $totalDisassemblyProducer['quantity_ok'] = array_sum(array_column($disassemblyProducer, 'quantity_ok'));
        $totalDisassemblyProducer['quantity_bad'] = array_sum(array_column($disassemblyProducer, 'quantity_bad'));

        $disassemblyClassifier = Decoder::arrayToUtf(Dashboard::getDisassemblyClassifier($month, $year),
            ['quantity_prev', 'quantity_decompiled', 'quantity_shipped', 'quantity_ok', 'quantity_bad']);
        $totalDisassemblyClassifier['quantity_prev'] = array_sum(array_column($disassemblyClassifier, 'quantity_prev'));
        $totalDisassemblyClassifier['quantity_decompiled'] = array_sum(array_column($disassemblyClassifier, 'quantity_decompiled'));
        $totalDisassemblyClassifier['quantity_shipped'] = array_sum(array_column($disassemblyClassifier, 'quantity_shipped'));
        $totalDisassemblyClassifier['quantity_ok'] = array_sum(array_column($disassemblyClassifier, 'quantity_ok'));
        $totalDisassemblyClassifier['quantity_bad'] = array_sum(array_column($disassemblyClassifier, 'quantity_bad'));

        $intervalYears = Dashboard::getYears();
        $intervalMonths = Dashboard::getMonths();
        $intervalMonths = array_map(function ($value) {
            $value['name'] = Dashboard::nameMonth($value['month']);
            return $value;
        }, $intervalMonths);

        $this->render('admin/engineers/dashboard/dashboard',
            compact('user', 'movementDevicesProducer', 'movementDevicesClassifier', 'intervalYears',
                'intervalMonths', 'year', 'month', 'totalDeviceClassifier', 'totalDeviceProducer', 'disassemblyProducer',
                'disassemblyClassifier', 'totalDisassemblyProducer', 'totalDisassemblyClassifier'));
        return true;
    }

}