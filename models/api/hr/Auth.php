<?php

namespace Umbrella\models\api\hr;

use PDO;
use Umbrella\app\User;
use Umbrella\app\Api\Token;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Logger;

class Auth
{
    /**
     * @param $login
     * @param $password
     * @return bool
     */
    public static function checkUserData($login, $password)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT * FROM gs_user WHERE login = :login AND password = :password';

        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_INT);
        $result->execute();
        $admin = $result->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            return $admin['id_user'];
        }
        return false;
    }


    /**
     * Если данные правильные, генерируем пользователю токен
     * @param User $user
     */
    public static function auth(User $user)
    {
        if($user instanceof User){
            $token = new Token();
            $user->setToken($token->generateToken());
            Logger::getInstance()->log($user->getId(), 'вошел(а) в HR кабинет');
        }
    }



    /**
     * Если существует сессия пользователя, возращаем ссесию
     * @param $token
     * @return mixed
     */
    public static function checkLogged($token)
    {
        $db = MySQL::getConnection();

        $sql = 'SELECT id_user FROM gs_user WHERE token = :token LIMIT 1';

        $result = $db->prepare($sql);
        $result->bindParam(':token', $token, PDO::PARAM_STR);
        $result->execute();
        $admin = $result->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            return $admin['id_user'];
        }
        return false;
    }


    /**
     * @param $id_user
     * @param $token
     * @return bool
     */
    public static function updateToken($id_user, $token)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_user
            SET
                token = :token
            WHERE id_user = :id_user";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':token', $token, PDO::PARAM_STR);
        return $result->execute();
    }
}