<?php

namespace Umbrella\controllers\umbrella\ccc;

use Carbon\Carbon;
use Josantonius\Request\Request;
use Umbrella\app\AdminBase;
use Umbrella\app\Mail\CCCDebtorsMail;
use Umbrella\app\User;
use Umbrella\components\ImportExcel;
use Umbrella\models\Admin;
use Umbrella\models\ccc\Debtors;
use Umbrella\models\ccc\DebtorsComment;

class DebtorsController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * DebtorsController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('ccc.debtors', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }


    /**
     * KPI
     * @return bool
     */
    public function actionIndex()
    {
        $user = $this->user;

        $partnerList = Debtors::getAllPartners();
        $deferments  = Debtors::getDeferment();

        $filter = '';
        if(!empty(Request::get('user_id'))){
            $namePartner = Request::get('user_id');
            $filter .= " AND name_partner = '{$namePartner}'";
        }

        if(!empty(Request::get('bill_status'))){
            $status = Request::get('bill_status');
            $filter .= " AND bill_status = '{$status}'";
        }

        if(!empty(Request::get('deferment'))){
            $deferment = Request::get('deferment');
            $filter .= " AND deferment = '{$deferment}'";
        }

        $allDebtors = Debtors::getAll($filter);

        $this->render('admin/ccc/debtors/index',
            compact('user', 'allDebtors', 'partnerList', 'deferments'));
        return true;
    }


    public function actionShowComments()
    {
        $user = $this->user;

        if(Request::post('action') == 'show_comments'){
            $partnerId = Request::post('user_id');
            $date = new \DateTime( date('Y-m-d'));

            $interval = [
                [
                    'week' => $date->modify('this week')->format('W'),
                    'year' => $date->modify('this week')->format('Y'),
                    'interval' => $date->modify('this week')->format('d.m.Y') . ' - ' .
                        $date->modify('next week -1 day')->format('d.m.Y'),
                    'add_comment' => 'true',
                    'delete_comment' => 'true',

                ],
            ];
            for ($i = 0; $i <= 2; $i++) {
                $interval[] =
                    [
                        'week' => $date->modify('this week - 1 week')->format('W'),
                        'year' => $date->modify('this week')->format('Y'),
                        'interval' => $date->modify('this week')->format('d.m.Y') . ' - ' .
                            $date->modify('next week -1 day')->format('d.m.Y'),
                        'add_comment' => 'false',
                        'delete_comment' => 'false',

                    ];
            }
            $interval = array_reverse($interval);

            $interval = array_map(function ($value) use ($partnerId){
                $value['comments'] = DebtorsComment::getCommentsInterval($partnerId, $value['week'], $value['year']);
                return $value;
            }, $interval);

            $this->render('admin/ccc/debtors/_part/comments_container', compact('interval', 'user'));
        }
        return true;
    }


    /**
     * @return bool
     */
    public function actionAddComment()
    {
        $user = $this->user;

        if(Request::post('action') == 'send_comment'){
            $options['partner_id'] = Request::post('partner_id');
            $options['comment'] = Request::post('comment');
            $options['week'] = Request::post('week');
            $options['year'] = Request::post('year');
            $options['user_id'] = $user->getId();
            $ok = DebtorsComment::addComment($options);
            if($ok){
                echo '200';
            }
        }
        return true;
    }


    /**
     * @return bool
     */
    public function actionDeleteComment()
    {
        if(Request::post('action') == 'delete_comment'){
            $id = Request::post('id');
            $ok = DebtorsComment::deleteComment($id);
            if($ok){
                echo '200';
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function actionCallIsOver()
    {
        $date = Carbon::now();
        $result['text'] = "Еженедельный обзвон {$date} окончен";
        $result['code'] = 200;
        CCCDebtorsMail::getInstance()->sendCallIsOver($result['text']);
        print_r(json_encode($result));
        return true;
    }
}