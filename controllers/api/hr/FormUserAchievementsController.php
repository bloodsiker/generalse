<?php
namespace Umbrella\controllers\api\hr;

use Umbrella\app\Api\Middleware\VerifyToken;
use Umbrella\app\Api\Response;
use Umbrella\models\api\hr\FormUserAchievements;
use Umbrella\models\api\hr\FormUserComment;



/**
 * Class FormUserAchievementsController
 * @package Umbrella\controllers\api\hr
 */
class FormUserAchievementsController
{

    /**
     * FormUserCommentController constructor.
     */
    public function __construct()
    {
        new VerifyToken();
    }


    /**
     * Add comment by form user
     */
    public function actionAddAchievements()
    {
        $data = file_get_contents('php://input');
        $dataDecode = json_decode($data);
        $user = $dataDecode->data;

        $options['form_user_id'] = $user->form_user_id;
        $options['user_id'] = $user->user_id;
        $options['text'] = $user->text;

        $userId = FormUserAchievements::addAchievementsFormUser($options);
        if($userId){
            $listAchievements = FormUserAchievements::getAchievementsByFormUser($options['form_user_id']);
            Response::responseJson($listAchievements, 200, 'OK');
        } else {
            Response::responseJson(null, 400, 'Bad Request');
        }
        return true;
    }
}