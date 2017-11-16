<?php
namespace Umbrella\controllers\api\hr;

use Umbrella\app\Api\Middleware\VerifyToken;
use Umbrella\app\Api\Response;
use Umbrella\models\hr\FormUser;
use Umbrella\models\hr\Structure;


/**
 * Class FormUserController
 * @package Umbrella\controllers\api\hr
 */
class FormUserController
{

    /**
     * FormUserController constructor.
     */
    public function __construct()
    {
        new VerifyToken();
    }


    public function actionUsersInStructure()
    {
        $filter = '';
        $usersInStructure = [];
        $infoStructure = [];

        if(isset($_GET['structure']) && $_GET['structure'] == 'company'){
            $id = (int)$_GET['id'];
            $filter .= " AND company_id = {$id}";
            $usersInStructure = FormUser::getFormsUserByDepartment($filter);
            $infoStructure = Structure::getStructureById($id);
        }

        if(isset($_GET['structure']) && $_GET['structure'] == 'department'){
            $id = (int)$_GET['id'];
            $filter .= " AND department_id = {$id} AND branch_id = 0";
            $usersInStructure = FormUser::getFormsUserByDepartment($filter);
            $infoStructure = Structure::getStructureById($id);
        }

        if(isset($_GET['structure']) && $_GET['structure'] == 'branch'){
            $id = (int)$_GET['id'];
            $filter .= " AND branch_id = {$id}";
            $usersInStructure = FormUser::getFormsUserByDepartment($filter);
            $infoStructure = Structure::getStructureById($id);
        }

        $data['head'] = $infoStructure;
        $data['users'] = $usersInStructure;

        Response::responseJson($data, 200, 'Ok');
        return true;
    }


    public function actionAddFormUser()
    {
        $options['name'] = (isset($_GET['name']) && !empty($_GET['name'])) ? $_GET['name'] : null;
        $options['surname'] = (isset($_GET['surname']) && !empty($_GET['surname'])) ? $_GET['surname'] : null;
        $options['email'] = (isset($_GET['email']) && !empty($_GET['email'])) ? $_GET['email'] : null;
        $options['photo'] = (isset($_GET['photo']) && !empty($_GET['photo'])) ? $_GET['photo'] : null;
        $options['company_id'] = (isset($_GET['company_id']) && !empty($_GET['company_id'])) ? (int)$_GET['company_id'] : null;
        $options['legal_entity'] = (isset($_GET['legal_entity']) && !empty($_GET['legal_entity'])) ? $_GET['legal_entity'] : null;
        $options['department_id'] = (isset($_GET['department_id']) && !empty($_GET['department_id'])) ? (int)$_GET['department_id'] : null;
        $options['branch_id'] = (isset($_GET['branch_id']) && !empty($_GET['branch_id'])) ? (int)$_GET['branch_id'] : null;
        $options['position'] = (isset($_GET['position']) && !empty($_GET['position'])) ? $_GET['position'] : null;
        $options['band_id'] = (isset($_GET['band_id']) && !empty($_GET['band_id'])) ? (int)$_GET['band_id'] : null;
        $options['func_group_id'] = (isset($_GET['func_group_id']) && !empty($_GET['func_group_id'])) ? (int)$_GET['func_group_id'] : null;

    }
}