<?php
namespace Umbrella\controllers\api\hr;

use Umbrella\app\Api\Middleware\VerifyToken;
use Umbrella\app\Api\Response;
use Umbrella\components\Functions;
use Umbrella\models\Admin;
use Umbrella\models\api\hr\Band;
use Umbrella\models\api\hr\File;
use Umbrella\models\api\hr\FormUser;
use Umbrella\models\api\hr\FormUserAchievements;
use Umbrella\models\api\hr\FormUserComment;
use Umbrella\models\api\hr\Staff;
use Umbrella\models\api\hr\Structure;
use Umbrella\models\api\hr\User;
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
        $userFire = 0;
        $usersInStructure = [];
        $infoStructure = [];

        if(isset($_GET['structure']) && $_GET['structure'] == 'company'){
            $id = (int)$_GET['id'];
            $userFire = (int)$_GET['user_fire'];
            $filter .= " AND company_id = {$id} AND user_fire = {$userFire}";
            $usersInStructure = FormUser::getFormsUserByDepartment($filter);
            $infoStructure = Structure::getStructureById($id);
        }

        if(isset($_GET['structure']) && $_GET['structure'] == 'department'){
            $id = (int)$_GET['id'];
            $userFire = (int)$_GET['user_fire'];
            $filter .= " AND department_id = {$id} AND branch_id = 0 AND user_fire = {$userFire}";
            $usersInStructure = FormUser::getFormsUserByDepartment($filter);
            $infoStructure = Structure::getStructureById($id);
        }

        if(isset($_GET['structure']) && $_GET['structure'] == 'branch'){
            $id = (int)$_GET['id'];
            $userFire = (int)$_GET['user_fire'];
            $filter .= " AND branch_id = {$id} AND user_fire = {$userFire}";
            $usersInStructure = FormUser::getFormsUserByDepartment($filter);
            $infoStructure = Structure::getStructureById($id);
            $infoStructure['company_id'] = Structure::getCompanyBranch($id);
        }

        // Проверяем, есть в пользователе изменения
        $usersInStructure = array_map(function ($user){
            $newUser = json_decode($user['form']);
            $newUser->id = $user['id'];
            $saved = 0;
            foreach ($newUser as $key => $value){
                $obj = $newUser->{$key};
                if(isset($obj->state) && $obj->state == 'saved'){
                    $saved = 1;
                }
            }
            $newUser->saved = $saved;
            //$user = $newUser;
            return $newUser;
        }, $usersInStructure);

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
                $logs = FormUser::getLogsByFormUserId($userId);
                $achievements = FormUserAchievements::getAchievementsByFormUser($userId);
                //$userInfo['settings'] = $settings;
                $userInfo['trigger_action_file'] = File::getFileByFormUserLabel($userId, 'trigger_action_file')['file_name'];
                $userInfo['language_lvl_file'] = File::getFileByFormUserLabel($userId, 'language_lvl_file')['file_name'];
                $data['user'] =  json_decode($userInfo['form'], true);
                $data['comments'] =  $comments;
                $data['history'] =  $logs;
                $data['achievements'] =  $achievements;
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
        $options['phone'] = $user->phone;
        $options['phone_2'] = $user->phone_2;
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

        $oldForm = FormUser::getFormUserById($user->id);

        $settings = $this->getSettings($user->id);

        $options['id'] = $user->id;
        $options['name'] = $user->name;
        $options['surname'] = $user->surname;
        $options['email'] = $user->email;
        $options['phone'] = $user->phone;
        $options['phone_2'] = $user->phone_2;
        $options['photo'] = $user->photo;
        $settings = $user->settings;

        foreach ($settings as $setting){
            if($setting->key == 'structure'){
                $concat = $oldForm['company_id'] . '_' . $oldForm['department_id'] .'_' . $oldForm['branch_id'];
                if($setting->value != $concat){
                    FormUser::updateAttrFormUser($options['id'],
                        'structure_state', 'saved',
                        'structure_date', date('Y-m-d'));
                }
            } else {
                if($setting->value != $oldForm[$setting->key])
                FormUser::updateAttrFormUser($options['id'],
                    $setting->key .'_state', 'saved',
                    $setting->key .'_date', date('Y-m-d'));
            }
        }

        $options['company_id'] = $user->company_id;
        $options['legal_entity'] = $user->legal_entity;
        $options['department_id'] = $user->department_id;
        $options['branch_id'] = $user->branch_id == 'false' ? 0 : $user->branch_id;
        $options['position'] = $user->position;
        $options['band_id'] = $user->band_id;
        $options['func_group'] = $user->func_group;
        $options['user_fire'] = $user->user_fire;

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
     * Save apply input value and state
     */
    public function actionApplySaveFormUser()
    {
        $data = file_get_contents('php://input');
        $dataDecode = json_decode($data);
        $formUser = $dataDecode->data;

        $formId = $formUser->form_user_id;
        $comment = $formUser->comment;
        $date = $formUser->date;
        $key = $formUser->key;
        $user_id = $formUser->user_id;

        // Подтверждаем измененное поле
        if($key == 'structure'){
            FormUser::updateAttrFormUser($formId,
                'structure_state', 'apply',
                'structure_date', $date);
        } else {
            FormUser::updateAttrFormUser($formId,
                $key .'_state', 'apply',
                $key .'_date', $date
            );
        }

        // Пишем в историю
        $oldForm = FormUser::getFormUserById($formUser->form_user_id);

        if($key == 'structure'){
            $options['value_form'] = $oldForm['company'] . ' -> ' . $oldForm['department'] .' -> ' . $oldForm['branch'];
        } else {
            $options['value_form'] = $oldForm[$key];
        }

        $options['form_user_id'] = $formId;
        $options['user_id'] = $user_id;
        $options['key_form'] = $key;
        $options['comment'] = $comment;
        $options['updated_at'] = $date;

        FormUser::addLog($options);

        Response::responseJson(true, 200, 'OK');
        return true;
    }


    /**
     * get settings for user form
     * @param $userId
     * @return array
     */
    public function getSettings($userId)
    {
        $userInfo = FormUser::getFormUserById($userId);
        $settings = [
            [
                'key' => 'structure',
                'value' => $userInfo['company_id'] . '_' . $userInfo['department_id'] .'_' . $userInfo['branch_id'],
                'state' => $userInfo['structure_state'],
                'date'  => $userInfo['structure_date'],
                'childs' => [
                    [
                        'company',
                        'company_id',
                        'Компания'
                    ],
                    [
                        'department',
                        'department_id',
                        'Департамент'
                    ],
                    [
                        'branch',
                        'branch_id',
                        'Отдел'
                    ],
                ],
            ],
            [
                'key' => 'band_id',
                'value' => $userInfo['band_id'],
                'title' => 'Band',
                'state' => $userInfo['band_id_state'],
                'date'  => $userInfo['band_id_date'],
            ],
            [
                'key'   => 'legal_entity',
                'value' => $userInfo['legal_entity'],
                'title' => 'Юридическое лицо',
                'state' => $userInfo['legal_entity_state'],
                'date'  => $userInfo['legal_entity_date'],
            ],
            [
                'key' => 'position',
                'value' => $userInfo['position'],
                'title' => 'Должность',
                'state' => $userInfo['position_state'],
                'date'  => $userInfo['position_date'],
            ],
            [
                'key' => 'func_group',
                'value' => $userInfo['func_group'],
                'title' => 'Functional Group',
                'state' => $userInfo['func_group_state'],
                'date'  => $userInfo['func_group_date'],
            ],
            [
                'key' => 'user_fire',
                'value' => $userInfo['user_fire'],
                'title' => 'Статус',
                'state' => $userInfo['user_fire_state'],
                'date'  => $userInfo['user_fire_date'],
            ]
        ];

        return $settings;
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


    /*******************************************************/
    /*********************  NEW USER FORM  ************************/
    /*******************************************************/
    public function actionNewForm()
    {
        $columns = [
            'photo' => ['title' => 'Фото', 'type_field' => 'photo', 'required' => 'true'],
            'user_id' => ['title' => 'Пользователь', 'type_field' => 'select', 'required' => 'false'],
            'name' => ['title' => 'Имя', 'type_field' => 'text', 'required' => 'true'],
            'surname' => ['title' => 'Фамилия', 'type_field' => 'text', 'required' => 'true'],
            'staff' => ['title' => 'Доступ', 'type_field' => 'select', 'required' => 'true'],
            'email' => ['title' => 'Email', 'type_field' => 'text', 'required' => 'true'],
            'phone' => ['title' => 'Телефон', 'type_field' => 'phone', 'required' => 'true'],
            'phone_2' => ['title' => 'Телефон 2', 'type_field' => 'phone', 'required' => 'false'],
            'company' => ['title' => 'Компания', 'type_field' => 'select', 'required' => 'true'],
            'department' => ['title' => 'Департамент', 'type_field' => 'select', 'required' => 'true'],
            'branch' => ['title' => 'Отдел', 'type_field' => 'select', 'required' => 'true'],
            'legal_entity' => ['title' => 'Юридическое лицо', 'type_field' => 'text', 'required' => 'false'],
            'position' => ['title' => 'Должность', 'type_field' => 'text', 'required' => 'true'],
            'band' => ['title' => 'Band', 'type_field' => 'select', 'required' => 'true'],
            'func_group' => ['title' => 'Functional Group', 'type_field' => 'text', 'required' => 'true'],
            'user_fire' => ['title' => 'Статус', 'type_field' => 'select', 'required' => 'true'],
            ];
        $user = [];
        $i = 0;
        foreach ($columns as $key => $value){
            $user += [$key => $value +=[
                'value'         => '',
                'new_value'     => '',
                'state'         => 'default',
                'date'          => '',
                'sort'          => $i,
                'new_value_id'  => ''
            ]];
        $i++;
        }
        Response::responseJson($user, 200, 'OK');
    }


    /**
     * add new user
     * @return bool
     */
    public function actionAdd()
    {
        $data = file_get_contents('php://input');
        $dataDecode = json_decode($data);
        $user = $dataDecode->data;

        $user->user_id->value = User::getUserById($user->user_id->value_id)['name_partner'];

        $user->staff->value = Staff::getStaffById($user->staff->value_id)['name'];

        $user->company->value = Structure::getStructureById($user->company->value_id)['name'];

        $user->department->value = Structure::getStructureById($user->department->value_id)['name'];

        $user->branch->value = Structure::getStructureById($user->branch->value_id)['name'];

        $user->band->value = $user->band->value_id;

        $options['user_id'] = !empty($user->user_id->value_id) ? $user->user_id->value_id : null;
        $options['form'] = json_encode($user);
        $options['staff_id'] = $user->staff->value_id;
        $options['company_id'] = $user->company->value_id;
        $options['department_id'] = $user->department->value_id;
        $options['branch_id'] = $user->branch->value_id;
        $options['band_id'] = $user->band->value_id;

        $userId = FormUser::addNewFormUser($options);
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
    public function actionEditNewFormUser()
    {
        $data = file_get_contents('php://input');
        $dataDecode = json_decode($data);
        $newFormUser = $dataDecode->data;

        $oldForm = FormUser::getNewFormUserById($newFormUser->id);
        $oldFormInfo = json_decode($oldForm['form']);

        $options['id'] = $newFormUser->id;
        unset($newFormUser->id);

        foreach ($newFormUser as $key => $value){
            $obj = $newFormUser->{$key};
            if($obj->value != $oldFormInfo->{$key}->value || $obj->value_id != $oldFormInfo->{$key}->value_id){
                $newFormUser->{$key}->new_value = $newFormUser->{$key}->value;
                $newFormUser->{$key}->value = $oldFormInfo->{$key}->value;
                $newFormUser->{$key}->state = 'saved';
                $newFormUser->{$key}->date = date('Y-m-d');

                if($newFormUser->{$key}->type_field == 'select'){
                    $newFormUser->{$key}->new_value_id = $newFormUser->{$key}->value_id;
                    $newFormUser->{$key}->value_id = $oldFormInfo->{$key}->value_id;
                }
            }
        }

        $options['form'] = json_encode($newFormUser);

        $ok = FormUser::savedNewFormUser($options);
        if($ok){
            Response::responseJson($oldFormInfo, 200, 'OK');
        } else {
            Response::responseJson(null, 304, 'Not Modified');
        }
        return true;
    }


    public function actionApplyNewFormUser()
    {
        $data = file_get_contents('php://input');
        $dataDecode = json_decode($data);
        $applyFormUser = $dataDecode->data;

        $formId = $applyFormUser->form_user_id;

        $oldForm = FormUser::getNewFormUserById($formId);
        $oldFormInfo = json_decode($oldForm['form']);

        foreach ($oldFormInfo as $key => $value){
            if($oldFormInfo->{$key}->key == $applyFormUser->key){
                //Apply saved form user info
                if($applyFormUser->state == 'apply'){
                    $newValue = $oldFormInfo->{$key}->new_value;
                    $oldValue = $oldFormInfo->{$key}->value;

                    $oldFormInfo->{$key}->value = $newValue;
                    $oldFormInfo->{$key}->new_value = null;
                    $oldFormInfo->{$key}->state = $applyFormUser->state;
                    $oldFormInfo->{$key}->date = date('Y-m-d');

                    if($oldFormInfo->{$key}->type_field == 'select'){
                        $oldFormInfo->{$key}->value_id = $oldFormInfo->{$key}->new_value_id;
                        $oldFormInfo->{$key}->new_value_id = null;

                        if($oldFormInfo->{$key}->key == 'user_fire'){
                            $db_key = $oldFormInfo->{$key}->key;
                        } elseif ($oldFormInfo->{$key}->key == 'user_id'){
                            $db_key = $oldFormInfo->{$key}->key;
                        } else {
                            $db_key = $oldFormInfo->{$key}->key . '_id';
                        }
                        FormUser::updateSelectAttrFormUser(
                            $applyFormUser->form_user_id,
                            $db_key,
                            $oldFormInfo->{$key}->value_id );
                    }
                    // Log history
                    $options['title'] = $oldFormInfo->{$key}->title;
                    $options['value_form'] = $oldValue . ' -> ' . $newValue;
                    $options['state'] = $applyFormUser->state;
                    //Cancel saved form user info
                } elseif ($applyFormUser->state == 'cancel'){
                    $newValue = $oldFormInfo->{$key}->new_value;
                    $oldValue = $oldFormInfo->{$key}->value;

                    $oldFormInfo->{$key}->new_value = null;
                    $oldFormInfo->{$key}->state = 'default';
                    $oldFormInfo->{$key}->date = null;

                    if($oldFormInfo->{$key}->type_field == 'select') {
                        $oldFormInfo->{$key}->new_value_id = null;
                    }
                    // Log history
                    $options['title'] = $oldFormInfo->{$key}->title;
                    $options['value_form'] = "{$oldValue} -> {$newValue}";
                    $options['state'] = $applyFormUser->state;
                }
            }
        }

        $options['id'] = $applyFormUser->form_user_id;
        $options['form'] = json_encode($oldFormInfo);

        $ok = FormUser::savedNewFormUser($options);
        if($ok){
            $options['form_user_id'] = $formId;
            $options['user_id'] = $applyFormUser->user_id;
            $options['key_form'] = $applyFormUser->key;
            $options['comment'] = $applyFormUser->comment;
            $options['updated_at'] = !empty($applyFormUser->date) ? $applyFormUser->date : date('Y-m-d');
            FormUser::addLog($options);

            Response::responseJson($oldFormInfo, 200, 'OK');
        } else {
            Response::responseJson(null, 304, 'Not Modified');
        }
        return true;
    }


    /**
     * find user by ID
     * @return bool
     */
    public function actionGetFormUser()
    {
        $userId = isset($_GET['id']) ? (int)$_GET['id'] : false;
        if($userId !== false){
            $userInfo = FormUser::getNewFormUserById($userId);
            if($userInfo){
                $comments = FormUserComment::getCommentsByFormUser($userId);
                $logs = FormUser::getLogsByFormUserId($userId);
                $achievements = FormUserAchievements::getAchievementsByFormUser($userId);
                $data['user'] =  json_decode($userInfo['form'], true);
                $data['files'] =  [
                    'trigger_action_file'     => File::getFileByFormUserLabel($userId, 'trigger_action_file')['file_name'],
                    'language_level_file'     => File::getFileByFormUserLabel($userId, 'language_level_file')['file_name']
                ];
                $data['comments'] =  $comments;
                $data['history'] =  $logs;
                $data['achievements'] =  $achievements;
                Response::responseJson($data, 200, 'OK');
            } else {
                Response::responseJson(null, 404, 'Form user not found');
            }
        }
        Response::responseJson(null, 400, 'Bad Request');
        return true;
    }

}