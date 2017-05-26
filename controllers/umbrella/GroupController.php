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
                // Добавляем юзеру все запрещенные страницы группы
                $user->addDeniedForGroupUser($id_group, $id_user);
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
            $ok = GroupModel::deleteUserFromGroup($id_group, $id_user);
            if($ok){
                // Удаляем запрещенные страницы, которые запрещены для группы в которой находился пользователь
                $user->deleteDeniedForGroupUser($id_user, $id_group);
            }
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


    /**
     *
     * @param $id_group
     * @param null $p_id
     * @param null $sub_id
     * @return bool
     */
    public function actionGroupDenied($id_group, $p_id = null, $sub_id = null)
    {
        // Проверка доступа
        self::checkAdmin();
        //self::checkDenied('user.denied', 'controller');
        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

        $group = new Group();
        // Список страниц
        $list_page = Denied::getListPageInSystem();

        if($p_id !== null){
            $sub_menu = Denied::getListPageInSystem(intval($p_id), 0);
        }

        if($sub_id === null){
            //Кнопки которые присутсвуют в категорях главного меню
            $sub_menu_button = Denied::getListPageInSystem(intval($p_id), 0, 1);
        } else {
            //Кнопки которые присутсвуют в подкатегориях
            $sub_menu_button = Denied::getListPageInSystem(intval($sub_id), 0, 1);
        }

        $list_denied = Denied::getDeniedByGroup($id_group);
        $new_array = array_column($list_denied, 'slug');

        if(isset($_POST['action']) && $_POST['action'] == 'denied'){
            $name = $_POST['name'];
            $slug = $_POST['slug'];
            $ok = Denied::addDeniedSlugInGroup($id_group, $name, $slug);
            if($ok){
                //При добавлении нового запрета для группы, добавляем этот запрет и каждому пользователю
                $list_user_group = $group->usersFromGroup($id_group);
                $group->addDeniedUserFromGroup($list_user_group, $name, $slug, $id_group);
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        } elseif(isset($_POST['action']) && $_POST['action'] == 'success'){
            $name = $_POST['name'];
            $slug = $_POST['slug'];
            $ok = Denied::deleteSlugInGroup($id_group, $name, $slug);
            if($ok){
                //При удалении запретной страницы из группы, она удаляеться у всех членов группы
                $group->deleteDeniedForGroupUser($id_group, $slug);
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }

        require_once(ROOT . '/views/admin/group/denied.php');
        return true;
    }
}