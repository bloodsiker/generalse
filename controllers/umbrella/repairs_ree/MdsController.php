<?php

namespace Umbrella\controllers\umbrella\repairs_ree;

use Carbon\Carbon;
use Josantonius\Request\Request;
use Josantonius\Session\Session;
use Josantonius\Url\Url;
use Umbrella\app\AdminBase;
use Umbrella\app\Services\repairs_ree\BatchMDS;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\components\Functions;
use Umbrella\components\ImportExcel;
use Umbrella\models\Admin;
use Umbrella\models\repair_ree\Mds;
use upload as FileUpload;

class MdsController extends AdminBase
{

    /**
     *  Path to the upload file for the psr
     */
    const UPLOAD_PATH_MDS = '/upload/upload_mds/';

    /**
     * @var User
     */
    private $user;

    /**
     * PsrController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        //self::checkDenied('adm.psr.activity', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }


    /**
     * @return bool
     * @throws \Exception
     */
    public function actionIndex()
    {
        $user = $this->user;

        $message = Session::pull('message');

        if (Request::files('upload_mds')) {
            $file = null;
            $handle = new FileUpload(Request::files('upload_mds'));
            if ($handle->uploaded) {
                $handle->file_new_name_body = Functions::strUrl($user->getName());
                $handle->file_name_body_add = '-' . substr_replace(sha1(microtime(true)), '', 15);
                $file_name = $handle->file_new_name_body . $handle->file_name_body_add . '.' . $handle->file_src_name_ext;
                $file = self::UPLOAD_PATH_MDS . $file_name;
                $handle->process(ROOT . self::UPLOAD_PATH_MDS);
                $handle->processed;
                $handle->clean();
            }

            $dataExcelArray = ImportExcel::importBatch($file);
            $data = new BatchMDS($dataExcelArray, $user);
            $arrayMds = $data->excelToArray();

            $count = count($arrayMds);
            for ($i = 0; $i < $count; $i++){
                Mds::insertMds($arrayMds[$i]);
            }
            Session::set('message', 'The file was imported successfully');
            Url::previous();
        }

        if($user->isPartner()){

            $allMds = Decoder::arrayToUtf(Mds::getAllByPartner($user->controlUsers($user->getId())));
            $allMds = array_map(function ($value){
                $value['created_on'] = Carbon::parse($value['created_on'])->format('Y-m-d');
                return $value;
            }, $allMds);

        } elseif ($user->isAdmin() || $user->isManager()){
            $allMds = Decoder::arrayToUtf(Mds::getAll());
            $allMds = array_map(function ($value){
                $value['created_on'] = Carbon::parse($value['created_on'])->format('Y-m-d');
                return $value;
            }, $allMds);
        }

        $this->render('admin/repairs_ree/mds/mds', compact('user', 'allMds', 'message'));
        return true;
    }


    public function actionSearch()
    {
        $user = $this->user;

        $this->render('admin/repairs_ree/mds/mds', compact('user'));
        return true;
    }
}