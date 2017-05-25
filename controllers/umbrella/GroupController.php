<?php

class GroupController extends AdminBase
{
    public function __construct()
    {
        self::checkDenied('group.view', 'controller');
    }

    public function actionAddGroup()
    {
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('group.add', 'controller');

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        if (isset($_POST['add_group']) && $_POST['add_group'] == 'true') {

            $options['group_name'] = $_POST['group_name'];
            $ok = GroupModel::addGroup($options);

            if($ok){
                header("Location: /adm/users");
            }
        }
        require_once(ROOT . '/views/admin/group/create.php');
        return true;
    }


    /**
     * View group
     * @param $id_group
     * @return bool
     */
    public function actionView($id_group)
    {
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('group.view', 'controller');

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        $user = new User($userId);
        $group = new Group();

        $listUsers = Admin::getAllUsers();
        $listUserByGroup = GroupModel::getUsersByGroup($id_group);

        if(isset($_POST['add_user_group']) && $_POST['add_user_group'] == 'true'){
            self::checkDenied('group.user.add', 'controller');

            $id_user = $_POST['id_user'];
            $ok = GroupModel::addUserGroup($id_group, $id_user);
            if($ok){
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }

        require_once(ROOT . '/views/admin/group/view.php');
        return true;
    }


    /**
     * Страница просмотра складов в группе
     * @param $id_group
     * @param $section
     * @return bool
     */
    public function actionStock($id_group, $section)
    {
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('group.stock.view', 'controller');

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        $user = new User($userId);
        $group = new Group();

        $allStocks = Stocks::getAllStocks();
        $listStocksGroup = GroupModel::getStocksFromGroup($id_group, $section);

        if(isset($_POST['add_stock_group']) && $_POST['add_stock_group'] == 'true'){
            self::checkDenied('group.stock.add', 'controller');

            $id_stock = $_POST['id_stock'];
            $ok = GroupModel::addStockGroup($id_group, $id_stock, $section);
            if($ok){
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }

        require_once(ROOT . '/views/admin/group/stock.php');
        return true;
    }


    /**
     * Delete a user from a group
     * @param $id_group
     * @param $id_user
     * @return bool
     */
    public function actionDeleteUser($id_group, $id_user)
    {
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('group.user.delete', 'controller');

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        //Получаем информацию о пользователе из БД
        $user = new User($userId);

        if($user->role == 'administrator'){
            GroupModel::deleteUserFromGroup($id_group, $id_user);
        } else {
            echo "<script>alert('У вас нету прав на удаление')</script>";
        }

        // Перенаправляем пользователя на страницу управлениями товарами
        header("Location: " . $_SERVER['HTTP_REFERER']);
        return true;
    }


    /**
     * Удаляем склад из группы
     * @param $id
     * @return bool
     */
    public function actionDeleteStock($id)
    {
        // Проверка доступа
        self::checkAdmin();
        self::checkDenied('group.stock.delete', 'controller');

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        //Получаем информацию о пользователе из БД
        $user = new User($userId);

        if($user->role == 'administrator'){
            GroupModel::deleteStockFromGroup($id);
        } else {
            echo "<script>alert('У вас нету прав на удаление')</script>";
        }

        // Перенаправляем пользователя на страницу управлениями товарами
        header("Location: " . $_SERVER['HTTP_REFERER']);
        return true;
    }
}