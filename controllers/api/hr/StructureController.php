<?php
namespace Umbrella\controllers\api\hr;

use Umbrella\app\Api\Middleware\VerifyToken;
use Umbrella\app\Api\Response;
use Umbrella\app\Api\Services\StructureService;
use Umbrella\models\api\hr\Structure;


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
        new VerifyToken();
    }


    public function actionStructure()
    {
        $structure = new StructureService();
        $listStructure = $structure->buildStructure();

        Response::responseJson($listStructure, 200, 'OK');
        return true;
    }


    /**
     * Add new structure
     * @return bool
     */
    public function actionAddStructure()
    {

        if(isset($_GET['structure']) && $_GET['structure'] == 'company'){
            $name = $_GET['name'];
            Structure::addStructure($name, 0, 'is_company');
        }

        if(isset($_GET['structure']) && $_GET['structure'] == 'department'){
            $name = $_GET['name'];
            $id = (int)$_GET['company_id'];
            Structure::addStructure($name, $id, 'is_department');
        }

        if(isset($_GET['structure']) && $_GET['structure'] == 'branch'){
            $name = $_GET['name'];
            $id = (int)$_GET['department_id'];
            Structure::addStructure($name, $id, 'is_branch');
        }

        Response::responseJson($listStructure = '', 200, 'OK');
        return true;
    }


    /**
     * Edit structure
     */
    public function actionEditStructure()
    {
        $id = (isset($_GET['id']) && !empty($_GET['id'])) ? (int)$_GET['id'] : false;
        if($id !== false){
            $name = $_GET['name'];
            $p_id = $_GET['p_id'];
            Structure::updateStructure($id, $name, $p_id);
        }
        Response::responseJson(null, 200, 'OK');
        return true;
    }


    /**
     * Delete structure by ID
     */
    public function actionDeleteStructure()
    {
        $id = (isset($_GET['id']) && !empty($_GET['id'])) ? (int)$_GET['id'] : false;
        if ($id !== false) {
            Structure::recursiveDeleteStructure($id);
            Response::responseJson(null, 200, 'OK');
        } else {
            Response::responseJson(null, 400, 'Bad Request');
        }
    }

}