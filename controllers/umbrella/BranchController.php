<?php

/**
 * Class BranchController
 */
class BranchController extends AdminBase
{

    /**
     * @param $id_branch
     * @return bool
     */
    public function actionView($id_branch)
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

        $userListBranch = Branch::getPartnerByBranch($id_branch);
        //Все пользователи которые входят в бранчи
        $allUserBranch = Branch::getAllUserBranch();
        $listUsers = Admin::getAllUsers();

        if(isset($_POST['add_user_branch']) && $_POST['add_user_branch'] == 'true'){
            $user_idS = $_POST['id_user'];
            foreach ($user_idS as $user_id){
                if(Branch::checkUserInBranch($allUserBranch, $user_id)){
                    //Branch::deleteUserInBranch($user_id, $id_branch);
                } else {
                    Branch::addUserInBranch($user_id, $id_branch);
                }
            }
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
        require_once(ROOT . '/views/admin/branch/index.php');
        return true;
    }

    /**
     * @return bool
     */
    public function actionAddBranch()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

        // Обработка формы
        if (isset($_POST['add_branch']) && $_POST['add_branch'] == 'true') {

            $options['branch_name'] = $_POST['branch_name'];

            // Сохраняем изменения
            $id_country = Branch::addBranch($options);

            if($id_country){
                // Перенаправляем пользователя на страницу юзеров
                header("Location: /adm/users");
            }
        }

        require_once(ROOT . '/views/admin/branch/create.php');
        return true;
    }

    /**
     * Удаляем связь пользователя и бранча
     * @param $id_branch
     * @param $id_user
     * @return bool
     */
    public function actionDelete($id_branch, $id_user)
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        //Получаем информацию о пользователе из БД
        $user = new User($userId);

        if($user->role == 'administrator'){
            Branch::deleteUserInBranch($id_user, $id_branch);
        } else {
            echo "<script>alert('У вас нету прав на удаление стран')</script>";
        }

        // Перенаправляем пользователя на страницу управлениями товарами
        header("Location: " . $_SERVER['HTTP_REFERER']);

        return true;
    }

}