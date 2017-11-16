<?php

namespace Umbrella\app\Api\Middleware;

use Umbrella\app\Api\Response;
use Umbrella\app\User;
use Umbrella\models\api\hr\Auth;

/**
 * Class VerifyToken
 * @package Umbrella\app\Api\Middleware
 */
class VerifyToken
{

    /**
     * VerifyToken constructor.
     */
    public function __construct()
    {
        $this->handle($_REQUEST);
    }

    /**
     * @param $request
     * @return bool
     */
    public function handle($request)
    {
        $data = file_get_contents('php://input');
        $dataToken = json_decode($data);

        if(isset($request['token']) || isset($dataToken->token)){
            $token = isset($request['token']) ? $request['token'] : $dataToken->token;
            $auth = Auth::checkLogged($token);
            if ($auth) {
                $user = new User($auth);
                if($user->getAuthProject(['global', 'hr'])){
                    return true;
                    //Response::responseJson($user, 200, 'OK');
                } else {
                    Response::responseJson(null, 403, 'Forbidden');
                }
            }
            Response::responseJson(null, 401, 'Unauthorized');
        }
        Response::responseJson(null, 401, 'Unauthorized');
    }
}