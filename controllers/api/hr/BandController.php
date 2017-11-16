<?php
namespace Umbrella\controllers\api\hr;

use Umbrella\app\Api\Middleware\VerifyToken;
use Umbrella\app\Api\Response;
use Umbrella\models\api\hr\Band;


/**
 * Class BandController
 * @package Umbrella\controllers\api\hr
 */
class BandController
{

    /**
     * BandController constructor.
     */
    public function __construct()
    {
        new VerifyToken();
    }

    /**
     * @return bool
     */
    public function actionAllBand()
    {
        $allBand = Band::getAllBand();
        Response::responseJson($allBand, 200, 'OK');
        return true;
    }


    /**
     * @return bool
     */
    public function actionBand()
    {
        $infoBand = null;

        if(isset($_GET['id'])){
            $id = (int)$_GET['id'];
            $infoBand = Band::getBandById($id);
        }

        Response::responseJson($infoBand, 200, 'OK');
        return true;
    }
}