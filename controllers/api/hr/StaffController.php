<?php
namespace Umbrella\controllers\api\hr;

use Umbrella\app\Api\Middleware\VerifyToken;
use Umbrella\app\Api\Response;
use Umbrella\models\api\hr\Band;
use Umbrella\models\api\hr\Staff;


/**
 * список доступов по должностям
 * Class StaffController
 * @package Umbrella\controllers\api\hr
 */
class StaffController
{

    /**
     * StaffController constructor.
     */
    public function __construct()
    {
        new VerifyToken();
    }

    /**
     * @return bool
     */
    public function actionAllStaff()
    {
        $allStaff = Staff::getAll();
        Response::responseJson($allStaff, 200, 'OK');
        return true;
    }
}