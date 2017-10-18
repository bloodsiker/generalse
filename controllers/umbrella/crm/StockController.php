<?php

namespace Umbrella\controllers\umbrella\crm;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\GroupModel;
use Umbrella\models\Stocks;

/**
 * Class StockController
 */
class StockController extends AdminBase
{
    /**
     * @var User
     */
    private $user;

    /**
     * StockController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.stocks', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * @return bool
     */
    public function actionStocks()
    {
        $user = $this->user;

        if($user->role == 'partner' || $user->role == 'manager') {

            $user_ids = $user->controlUsers($user->id_user);
            $partnerList = Admin::getPartnerControlUsers($user_ids);
            $list_stock = $user->renderSelectStocks($user->id_user, 'stocks');

            $stocks =  isset($_REQUEST['stock']) ? $_REQUEST['stock'] : [];
            $id_partners = isset($_REQUEST['id_partner']) ? $_REQUEST['id_partner'] : [];
            $allGoodsByPartner = Stocks::getGoodsInStocksPartners($id_partners, $stocks);

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin'){

            $list_stock = $user->renderSelectStocks($user->id_user, 'stocks');

            // Параметры для формирование фильтров
            $groupList = GroupModel::getGroupList();
            $userInGroup = [];
            $i = 0;
            foreach ($groupList as $group) {
                $userInGroup[$i]['group_name'] = $group['group_name'];
                $userInGroup[$i]['group_id'] = $group['id'];
                $userInGroup[$i]['users'] = GroupModel::getUsersByGroup($group['id']);
                $i++;
            }
            // Добавляем в массив пользователей без групп
            $userNotGroup[0]['group_name'] = 'Without group';
            $userNotGroup[0]['group_id'] = 'without_group';
            $userNotGroup[0]['users'] = GroupModel::getUsersWithoutGroup();
            $userInGroup = array_merge($userInGroup, $userNotGroup);

            $stocks =  isset($_REQUEST['stock']) ? $_REQUEST['stock'] : [];
            $id_partners = isset($_REQUEST['id_partner']) ? $_REQUEST['id_partner'] : [];

            $allGoodsByPartner = Stocks::getGoodsInStocksPartners($id_partners, $stocks);
        }

        $this->render('admin/crm/stocks', compact('user','partnerList',
            'allGoodsByPartner', 'userInGroup', 'list_stock'));
        return true;

    }

}