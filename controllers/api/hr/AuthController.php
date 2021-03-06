<?php
namespace Umbrella\controllers\api\hr;

use Umbrella\app\User;
use Umbrella\app\Api\Response;
use Umbrella\components\Functions;
use Umbrella\models\api\hr\Auth;


/**
 * Class AuthController
 * @package Umbrella\controllers\api\hr
 */
class AuthController
{

    /**
     * @return bool
     */
    public function actionAuth()
    {
        $data = file_get_contents('php://input');
        $data_decode = json_decode($data);
        $login = isset($data_decode->login) ? $data_decode->login : null;
        $password = isset($data_decode->password) ? Functions::hashPass($data_decode->password) : null;
        $auth = Auth::checkUserData($login, $password);
        if($auth !== false){
            $user = new User($auth);
            if($user->getAuthProject('hr')){
                Auth::auth($user);
                $newUser = \Umbrella\models\api\hr\User::getUserById($user->getId());
                Response::responseJson($newUser, 200, 'OK');
            } else {
                Response::responseJson($data = null, 403, 'Forbidden');
            }

        } else {
            Response::responseJson($data = '', 401, 'Unauthorized');
        }
        return true;
    }


    /**
     * Logout user
     */
    public function actionLogout()
    {
        $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
        if($userId !== null){
            Auth::updateToken($userId, null);
            Response::responseJson(null, 200, 'OK');
        } else {
            Response::responseJson(null, 400, 'Bad Request');
        }
    }

}