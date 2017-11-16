<?php
namespace Umbrella\controllers\api\hr;

use Umbrella\app\Api\Middleware\VerifyToken;
use Umbrella\app\Api\Response;
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
        $filter = '';
        $listStructure = [];
        if(isset($_GET['structure']) && $_GET['structure'] == 'company'){
            $filter .= ' AND is_company = 1';
            $listStructure = Structure::getStructureList($filter);
        }

        if(isset($_GET['structure']) && $_GET['structure'] == 'department'){
            $companyId = (int)$_GET['company_id'];
            $filter .= " AND is_department = 1 AND p_id = {$companyId}";
            $listStructure = Structure::getStructureList($filter);
        }

        if(isset($_GET['structure']) && $_GET['structure'] == 'branch'){
            $departmentId = (int)$_GET['department_id'];
            $filter .= " AND is_branch = 1 AND p_id = {$departmentId}";
            $listStructure = Structure::getStructureList($filter);
            $listStructure = array_map(function ($value){

                $value['company_id'] = Structure::getCompanyBranch($value['id']);
                return $value;
            }, $listStructure);
        }

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