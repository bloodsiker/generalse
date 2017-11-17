<?php
namespace Umbrella\controllers\api\hr;

use Umbrella\app\Api\Middleware\VerifyToken;
use Umbrella\app\Api\Response;
use Umbrella\models\api\hr\FormUserComment;



/**
 * Class FormUserCommentController
 * @package Umbrella\controllers\api\hr
 */
class FormUserCommentController
{

    /**
     *  Path to the upload file for the psr
     */

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
    public function actionAddComment()
    {
        $data = file_get_contents('php://input');
        $dataDecode = json_decode($data);
        $user = $dataDecode->data;

        $options['form_user_id'] = $user->form_user_id;
        $options['user_id'] = $user->user_id;
        $options['comment'] = $user->comment;

        $userId = FormUserComment::addCommentFormUser($options);
        if($userId){
            $listComments = FormUserComment::getCommentsByFormUser($options['form_user_id']);
            Response::responseJson($listComments, 200, 'OK');
        } else {
            Response::responseJson(null, 400, 'Bad Request');
        }
    }

}