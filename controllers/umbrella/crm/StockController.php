<?php

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
        self::checkDenied('crm.stocks', 'controller');
    }

    /**
     * @return bool
     */
    public function actionStocks()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
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

        require_once(ROOT . '/views/admin/crm/stocks.php');
        return true;

    }

}