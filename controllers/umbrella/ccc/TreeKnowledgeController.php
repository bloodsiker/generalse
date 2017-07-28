<?php

/**
 * Древо знаний для Customer Care Center
 * Class TreeKnowledgeController
 */
class TreeKnowledgeController extends AdminBase
{

    public function __construct()
    {
        //
    }

    /**
     * @return bool
     */
    public function actionIndex()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        $category = KnowledgeCatalog::getAllCategories();


        require_once(ROOT . '/views/admin/ccc/knowledge/index.php');
        return true;
    }


    /**
     * @param $id_category
     * @return bool
     */
    public function actionArticlesByCategory($id_category)
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        require_once(ROOT . '/views/admin/ccc/knowledge/articles.php');
        return true;
    }


    public function actionPopularCategory()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        require_once(ROOT . '/views/admin/ccc/knowledge/popular_category.php');
        return true;
    }


    /**
     * @return bool
     */
    public function actionCategory()
    {
        self::checkAdmin();
        $userId = Admin::CheckLogged();
        $user = new User($userId);

        require_once(ROOT . '/views/admin/ccc/knowledge/index.php');
        return true;
    }
}