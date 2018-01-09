<?php
namespace Umbrella\controllers\api\hr;

use Umbrella\app\Api\Middleware\VerifyToken;
use Umbrella\app\Api\Response;
use Umbrella\models\api\hr\File;
use upload as FileUpload;


/**
 * Class FileController
 * @package Umbrella\controllers\api\hr
 */
class FileController
{

    /**
     *  Path to the upload file for the psr
     */
    const UPLOAD_PATH_PHOTO = '/upload/hr/file/';

    /**
     * FormUserController constructor.
     */
    public function __construct()
    {
        new VerifyToken();
    }


    /**
     * Upload file and return path and new name file
     */
    public function actionUploadFile()
    {
        if (!empty($_FILES['file'])) {
            $handle = new FileUpload($_FILES['file']);
            if ($handle->uploaded) {
                $handle->file_new_name_body = substr_replace(sha1(microtime(true)), '', 15);
                $file_name = $handle->file_new_name_body . '.' . $handle->file_src_name_ext;
                $handle->process(ROOT . self::UPLOAD_PATH_PHOTO);
                if ($handle->processed) {
                    $options['form_user_id'] = $_REQUEST['form_user_id'];
                    $options['file_type'] = $_REQUEST['file_type'];
                    $options['file_name'] = self::UPLOAD_PATH_PHOTO . $file_name;
                    File::addFile($options);
                    $handle->clean();
                    Response::responseJson($options, 200, 'OK');
                } else {
                    Response::responseJson(null, 400, 'Error upload file: ' . $handle->error);
                }
            }
        }

        Response::responseJson(null, 400, 'Bad Request');
    }


    /**
     * Delete file
     */
    public function actionDeleteFile()
    {
        $userId = isset($_GET['form_user_id']) ? (int)$_GET['form_user_id'] : false;
        $fileType = isset($_GET['type']) ? $_GET['type'] : false;
        $nameFile =  isset($_GET['name']) ? $_GET['name'] : false;
        $ok = File::deleteFile($userId, $fileType);
        if($ok){
            $file = ROOT . $nameFile;
            if (file_exists($file)) {
                unlink($file);
                Response::responseJson(true, 200, 'OK');
            }
        }
        Response::responseJson(null, 400, 'Bad Request');
    }
}