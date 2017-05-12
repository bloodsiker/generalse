<?php

/**
 * Class PsrController
 */
class PsrController extends AdminBase
{

    ##############################################################################
    ##############################      PSR          #############################
    ##############################################################################

    /**
     * PsrController constructor.
     */
    public function __construct()
    {
        self::checkDenied('crm.psr', 'controller');
    }

    /**
     * @return bool
     */
    public function actionPsr()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);


        require_once(ROOT . '/views/admin/crm/psr.php');
        return true;
    }

}