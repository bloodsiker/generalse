<?php
namespace Umbrella\controllers\api\umbrella;

use Umbrella\app\Api\Middleware\VerifyToken;
use Umbrella\app\Api\Response;
use Umbrella\models\api\hr\Band;
use Umbrella\models\crm\Currency;


/**
 * Class CurrencyController
 * @package Umbrella\controllers\api\umbrella
 */
class CurrencyController
{

    /**
     * @param string $currency
     *
     * @return bool
     */
    public function actionRate($currency = 'usd')
    {
        $rate = Currency::getRatesCurrency($currency);
        if($rate){
            Response::responseJson($rate, 200, 'OK');
        } else {
            Response::responseJson(null, 404, 'Currency not found');
        }
        return true;
    }
}