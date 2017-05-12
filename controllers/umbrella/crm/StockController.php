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

        $partnerList = Admin::getAllPartner();

        if($user->role == 'partner') {
            $allGoodsByPartner = Stocks::getAllGoodsByPartner($user->id_user);
            if (isset($_GET['stock'])) {
                $stock = iconv('UTF-8', 'WINDOWS-1251', $_GET['stock']);
                if ($stock == 'all') {
                    $allGoodsByPartner = Stocks::getAllGoodsByPartner($user->id_user);
                } else {
                    $allGoodsByPartner = Stocks::getGoodsInStockByPartner($user->id_user, $stock);
                }
            }
        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){
            if (isset($_GET['stock']) && isset($_GET['id_partner'])) {
                $stock = iconv('UTF-8', 'WINDOWS-1251', $_GET['stock']);
                $id_partner = $_GET['id_partner'];
                if ($stock == 'all' && $id_partner == 'all') {
                    $allGoodsByPartner = Stocks::getAllGoodsAllPartner();
                } elseif ($stock != 'all' && $id_partner == 'all'){
                    $allGoodsByPartner = Stocks::getGoodsAllPartnerByStock($stock);
                } elseif ($stock == 'all') {
                    $allGoodsByPartner = Stocks::getAllGoodsByPartner($id_partner);
                } else {
                    $allGoodsByPartner = Stocks::getGoodsInStockByPartner($id_partner, $stock);
                }
            }
        }
        require_once(ROOT . '/views/admin/crm/stocks.php');
        return true;

    }

}