<?php

namespace Umbrella\controllers\umbrella\crm;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\Stocks;

/**
 * Class StockController
 */
class StockController extends AdminBase
{

    ##############################################################################
    ##############################      STOCKS       #############################
    ##############################################################################

    /**
     * StockController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('crm.stocks', 'controller');
    }

    /**
     * @return bool
     */
    public function actionStocks()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();

        $user = new User($userId);

        if($user->role == 'partner' || $user->role == 'manager') {

            $user_ids = $user->controlUsers($user->id_user);
            $partnerList = Admin::getPartnerControlUsers($user_ids);
            if(count($partnerList) > 3){
                $new_partner = array_chunk($partnerList, (int)count($partnerList) / 3);
            } else {
                $new_partner[] = $partnerList;
            }
            $list_stock = $user->renderSelectStocks($user->id_user, 'stocks');
            if(count($list_stock) > 3){
                $new_stock = array_chunk($list_stock, (int)count($list_stock) / 3);
            } else {
                $new_stock[] = $list_stock;
            }

            $stocks =  isset($_POST['stock']) ? $_POST['stock'] : [];
            $id_partners = isset($_POST['id_partner']) ? $_POST['id_partner'] : [];
            $allGoodsByPartner = Stocks::getGoodsInStocksPartners($id_partners, $stocks);

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin'){

            $partnerList = Admin::getAllPartner();
            $new_partner = array_chunk($partnerList, (int)count($partnerList) / 3);
            $list_stock = $user->renderSelectStocks($user->id_user, 'stocks');
            $new_stock = array_chunk($list_stock, (int)count($list_stock) / 3);

            $stocks =  isset($_POST['stock']) ? $_POST['stock'] : [];
            $id_partners = isset($_POST['id_partner']) ? $_POST['id_partner'] : [];
            $allGoodsByPartner = Stocks::getGoodsInStocksPartners($id_partners, $stocks);
        }

        $this->render('admin/crm/stocks', compact('user','new_partner', 'new_stock', 'allGoodsByPartner'));
        return true;

    }

}