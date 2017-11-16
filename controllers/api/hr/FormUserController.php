<?php
namespace Umbrella\controllers\api\hr;

use Umbrella\components\Api\Response;
use Umbrella\models\hr\Structure;


/**
 * Class StructureController
 * @package Umbrella\controllers\api\hr
 */
class StructureController
{

    /**
     * StructureController constructor.
     */
    public function __construct()
    {

    }


    public function actionStructure()
    {
        $listStructure = Structure::getStructureList('is_company', 1);

        Response::responseJson($listStructure, 200, 'Ok');
        return true;
    }

}