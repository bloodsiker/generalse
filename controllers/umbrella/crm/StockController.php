<?php

namespace Umbrella\controllers\umbrella\crm;

use Josantonius\Request\Request;
use Umbrella\app\AdminBase;
use Umbrella\app\Group;
use Umbrella\app\Services\crm\StockService;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\models\Admin;
use Umbrella\models\Classifier;
use Umbrella\models\crm\Currency;
use Umbrella\models\GroupModel;
use Umbrella\models\crm\Stocks;
use Umbrella\models\Producer;

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
     * @throws \Exception
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
     * @throws \Exception
     */
    public function actionStocks()
    {
        $user = $this->user;

        $listSubType = Decoder::arrayToUtf(Stocks::getListSubType());
        $listProducers = Decoder::arrayToUtf(Producer::allProducers());
        $listClassifiers = Decoder::arrayToUtf(Classifier::allClassifiers());

        if($user->isPartner() || $user->isManager()) {

            $user_ids = $user->controlUsers($user->getId());
            $partnerList = Admin::getPartnerControlUsers($user_ids);
            $list_stock = $user->renderSelectStocks($user->getId(), 'stocks');

        } else if($user->isAdmin()){

            $group = new Group();

            $list_stock = $user->renderSelectStocks($user->getId(), 'stocks');

            // Параметры для формирование фильтров
            $userInGroup = $group->groupFormationForFilter();
        }

        $allGoodsByPartner = $this->stockService->allGoodsByClient($_REQUEST);

            $this->render('admin/crm/stocks/stocks', compact('user','partnerList',
            'allGoodsByPartner', 'userInGroup', 'list_stock', 'listSubType', 'listProducers', 'listClassifiers'));
        return true;
    }


    /**
     * Search product in stocks
     * @return bool
     * @throws \Exception
     */
    public function actionSearch()
    {
        $user = $this->user;

        if($user->isPartner() || $user->isManager()) {

            $search = Decoder::strToWindows(trim($_REQUEST['search']));

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

            $group = new Group();

            $search = Decoder::strToWindows(trim($_REQUEST['search']));

            $list_stock = $user->renderSelectStocks($user->getId(), 'stocks');

            // Параметры для формирование фильтров
            $userInGroup = $group->groupFormationForFilter();

            $allGoodsByPartner = Decoder::arrayToUtf(Stocks::getSearchInStocks($search));
            $allGoodsByPartner = $this->stockService->replaceInfoProduct($allGoodsByPartner);
        }

        $this->render('admin/crm/stocks/stocks_search', compact('user','partnerList',
            'allGoodsByPartner', 'userInGroup', 'list_stock', 'search'));
        return true;
    }


    /**
     * @return bool
     * @throws \Exception
     */
    public function actionListProducts()
    {
        self::checkDenied('crm.stocks.list_products', 'controller');
        $user = $this->user;

        $currencyUsd = Currency::getRatesCurrency('usd');

        if($user->isPartner()){
            $listProduct = Decoder::arrayToUtf(Stocks::getListProducts($user->getId()));
        } elseif ($user->isAdmin() || $user->isManager()){
            $listProduct = Decoder::arrayToUtf(Stocks::getListProductsAdmin());
        }

        $this->render('admin/crm/stocks/list_products', compact('user', 'listProduct', 'currencyUsd'));
        return true;
    }


    /**
     * @return bool
     * @throws \Exception
     */
    public function actionSearchListProducts()
    {
        self::checkDenied('crm.stocks.list_products', 'controller');
        $user = $this->user;

        $search = Decoder::strToWindows(Request::get('search'));

        $listProduct = Decoder::arrayToUtf(Stocks::searchListProducts($search));

        $this->render('admin/crm/stocks/search_list_products', compact('user', 'listProduct', 'search'));
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
            $stockDecompile = Stocks::checkGoodsByIdInDecompileStocksPartners($user_id, $part_number);
            $this->render('admin/crm/stocks/_part/show_prices_modal', compact('prices', 'currencyUsd',
                'part_number', 'goods_name', 'stockDecompile'));
            return true;
        }
    }
}