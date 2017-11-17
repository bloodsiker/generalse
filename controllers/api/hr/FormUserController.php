<?php
namespace Umbrella\controllers\api\hr;

use Umbrella\app\Api\Middleware\VerifyToken;
use Umbrella\app\Api\Response;
use Umbrella\components\Functions;
use Umbrella\models\api\hr\FormUser;
use Umbrella\models\api\hr\FormUserComment;
use Umbrella\models\api\hr\Structure;
use upload as FileUpload;


/**
 * Class FormUserController
 * @package Umbrella\controllers\api\hr
 */
class FormUserController
{

    /**
     *  Path to the upload file for the psr
     */
    const UPLOAD_PATH_PHOTO = '/upload/hr/user_form/';

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
            $infoStructure['company_id'] = Structure::getCompanyBranch($id);
        }

        $data['head'] = $infoStructure;
        $data['users'] = $usersInStructure;

        Response::responseJson($data, 200, 'Ok');
        return true;
    }


    /**
     * get form user by id
     * @return bool
     */
    public function actionGetUser()
    {
        $userId = isset($_GET['id']) ? (int)$_GET['id'] : false;
        if($userId !== false){
            $userInfo = FormUser::getFormUserById($userId);
            if($userInfo){
                $comments = FormUserComment::getCommentsByFormUser($userId);
                $data['user'] =  $userInfo;
                $data['comments'] =  $comments;
                Response::responseJson($data, 200, 'OK');
            } else {
                Response::responseJson(null, 404, 'Form user not found');
            }
        }
        Response::responseJson(null, 400, 'Bad Request');
        return true;
    }


    /**
     * Add user
     * @return bool
     */
    public function actionAddFormUser()
    {
        $data = file_get_contents('php://input');
        $dataDecode = json_decode($data);
        $user = $dataDecode->data;

        $options['name'] = $user->name;
        $options['surname'] = $user->surname;
        $options['email'] = $user->email;
        $options['photo'] = $user->photo;
        $options['company_id'] = $user->company_id;
        $options['legal_entity'] = $user->legal_entity;
        $options['department_id'] = $user->department_id;
        $options['branch_id'] = $user->branch_id == 'false' ? 0 : $user->branch_id;
        $options['position'] = $user->position;
        $options['band_id'] = $user->band_id;
        $options['func_group'] = $user->func_group;

        $userId = FormUser::addFormUser($options);
        if($userId){
            Response::responseJson($userId, 200, 'OK');
        } else {
            Response::responseJson(null, 400, 'Bad Request');
        }
        return true;
    }


    /**
     * Edit form user info
     * @return bool
     */
    public function actionEditFormUser()
    {
        $data = file_get_contents('php://input');
        $dataDecode = json_decode($data);
        $user = $dataDecode->data;

        $options['id'] = $user->id;
        $options['name'] = $user->name;
        $options['surname'] = $user->surname;
        $options['email'] = $user->email;
        $options['photo'] = $user->photo;
        $options['company_id'] = $user->company_id;
        $options['legal_entity'] = $user->legal_entity;
        $options['department_id'] = $user->department_id;
        $options['branch_id'] = $user->branch_id == 'false' ? 0 : $user->branch_id;
        $options['position'] = $user->position;
        $options['band_id'] = $user->band_id;
        $options['func_group'] = $user->func_group;

        $ok = FormUser::updateFormUser($options);
        if($ok){
            //$history = json_encode($options);
            Response::responseJson($options, 200, 'OK');
        } else {
            Response::responseJson(null, 304, 'Not Modified');
        }
        return true;
    }


    /**
     * Delete form user
     * @return bool
     */
    public function actionDeleteFormUser()
    {
        $userId = isset($_GET['id']) ? (int)$_GET['id'] : false;
        if($userId !== false){
            $userInfo = FormUser::getFormUserById($userId);
            if($userInfo){
                FormUser::deleteFormUserById($userId);
                Response::responseJson($userInfo, 200, 'OK');
            } else {
                Response::responseJson(null, 404, 'Form user not found');
            }
        }
        Response::responseJson(null, 400, 'Bad Request');
        return true;
    }


    /**
     * Upload file and return path and new name file
     */
    public function actionUploadPhoto()
    {
        if (!empty($_FILES['file'])) {
            $handle = new FileUpload($_FILES['file']);
            if ($handle->uploaded) {
                $handle->file_new_name_body = substr_replace(sha1(microtime(true)), '', 15);
                $file_name = $handle->file_new_name_body . '.' . $handle->file_src_name_ext;
                $handle->process(ROOT . self::UPLOAD_PATH_PHOTO);
                if ($handle->processed) {
                    $fileAndPath = self::UPLOAD_PATH_PHOTO . $file_name;
                    $handle->clean();
                    Response::responseJson($fileAndPath, 200, 'OK');
                } else {
                    Response::responseJson(null, 400, 'Error upload file: ' . $handle->error);
                }
            }
        }
        Response::responseJson(null, 400, 'Bad Request');
    }
}