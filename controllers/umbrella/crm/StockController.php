<?php

namespace Umbrella\controllers\umbrella\crm;

use Umbrella\app\AdminBase;
use Umbrella\app\Services\crm\StockService;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\models\Admin;
use Umbrella\models\Currency;
use Umbrella\models\GroupModel;
use Umbrella\models\Products;
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
     * @var StockService
     */
    private $stockService;

    /**
     * StockController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.stocks', 'controller');
        $this->user = new User(Admin::CheckLogged());
        $this->stockService = new StockService();
    }

    /**
     * @return bool
     */
    public function actionStocks()
    {
        $user = $this->user;

        $listSubType = Decoder::arrayToUtf(Stocks::getListSubType());

        if($user->isPartner() || $user->isManager()) {

            $user_ids = $user->controlUsers($user->getId());
            $partnerList = Admin::getPartnerControlUsers($user_ids);
            $list_stock = $user->renderSelectStocks($user->getId(), 'stocks');

        } else if($user->isAdmin()){

            $list_stock = $user->renderSelectStocks($user->getId(), 'stocks');

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
        }

        $allGoodsByPartner = $this->stockService->allGoodsByClient($_REQUEST);

        $this->render('admin/crm/stocks/stocks', compact('user','partnerList',
            'allGoodsByPartner', 'userInGroup', 'list_stock', 'listSubType'));
        return true;
    }


    /**
     * Search product in stocks
     * @return bool
     */
    public function actionSearch()
    {
        $user = $this->user;

        if($user->isPartner() || $user->isManager()) {

            $search = iconv('UTF-8', 'WINDOWS-1251', trim($_REQUEST['search']));

            $user_ids = $user->controlUsers($user->getId());
            $partnerList = Admin::getPartnerControlUsers($user_ids);
            $list_stock = $user->renderSelectStocks($user->getId(), 'stocks');

            $list_stock = $this->stockService->replaceNameStockInFilter($list_stock, 'back_replace', $user->getRole());

            $stocks_name = implode('\' , \'', $list_stock);
            $stock_iconv = Decoder::strToWindows($stocks_name);
            $filter = " AND stock_name IN ('$stock_iconv')";

            $idS = implode(',', $user_ids);
            $filter .= " AND sgu.site_account_id IN ($idS)";
            $allGoodsByPartner = Decoder::arrayToUtf(Stocks::getSearchInStocks($search, $filter));
            $allGoodsByPartner = $this->stockService->replaceInfoProduct($allGoodsByPartner);

        } else if($user->isAdmin()){

            $search = iconv('UTF-8', 'WINDOWS-1251', trim($_REQUEST['search']));

            $list_stock = $user->renderSelectStocks($user->getId(), 'stocks');

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

            $allGoodsByPartner = Decoder::arrayToUtf(Stocks::getSearchInStocks($search));
            $allGoodsByPartner = $this->stockService->replaceInfoProduct($allGoodsByPartner);
        }

        $this->render('admin/crm/stocks/stocks_search', compact('user','partnerList',
            'allGoodsByPartner', 'userInGroup', 'list_stock', 'search'));
        return true;
    }


    /**
     * show prices in modal
     */
    public function actionStockAjax()
    {
        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_prices'){
            $goodID = (int)$_REQUEST['good_id'];
            $part_number = $_REQUEST['part_number'];
            $goods_name = $_REQUEST['goods_name'];
            $user_id = (int)$_REQUEST['user_id'];
            $prices = Currency::getPartnersCurrencyByGoodsID($goodID);
            $currencyUsd = Currency::getRatesCurrency('usd');
            $stockDecompile = Stocks::checkGoodsInDecompileStocksPartners($user_id, $part_number);
            $this->render('admin/crm/stocks/_part/show_prices_modal', compact('prices', 'currencyUsd',
                'part_number', 'goods_name', 'stockDecompile'));
            return true;
        }
    }
}