<?php
namespace Umbrella\controllers\api\hr;

use Umbrella\app\Api\Middleware\VerifyToken;
use Umbrella\app\Api\Response;
use Umbrella\models\api\hr\User;


/**
 * список пользователей
 * Class UserController
 * @package Umbrella\controllers\api\hr
 */
class UserController
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
    public function actionAllUsers()
    {
        $allUsers = User::getAll('hr');
        $userRelativeForm = User::getUsersRelativeForm();
        $allUsers = array_map(function ($value) use ($userRelativeForm){
            $newValue['id'] = $value['id_user'];
            $newValue['name'] = $value['name_partner'];
            $newValue['disabled'] = '';
            if(in_array($value['id_user'], array_column($userRelativeForm, 'user_id'))){
                $newValue['disabled'] = 'true';
            }
            return $newValue;
        }, $allUsers);
        Response::responseJson($allUsers, 200, 'OK');
        return true;
    }
}