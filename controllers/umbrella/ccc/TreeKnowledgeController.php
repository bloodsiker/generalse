<?php

namespace Umbrella\controllers\umbrella\ccc;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\models\Admin;
use Umbrella\models\ccc\KnowledgeArticle;
use Umbrella\models\ccc\KnowledgeCatalog;

/**
 * Древо знаний для Customer Care Center
 * Class TreeKnowledgeController
 */
class TreeKnowledgeController extends AdminBase
{

    /**
     * @var User
     */
    private $user;

    /**
     * TreeKnowledgeController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('ccc.tree_knowledge', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * @param $customer
     * @return bool
     */
    public function actionIndex($customer)
    {
        $user = $this->user;

        $this->render('admin/ccc/knowledge/index', compact('user', 'customer'));
        return true;
    }


    /**
     * Список статей в категории
     * @param $customer
     * @param $id_category
     * @return bool
     */
    public function actionArticlesByCategory($customer, $id_category)
    {
        $user = $this->user;

        $categoryName = KnowledgeCatalog::getInfoCategoryById($id_category);
        $listCategory = KnowledgeArticle::getArticlesByCategory($id_category);

        $this->render('admin/ccc/knowledge/articles', compact('user', 'id_category', 'listCategory', 'customer', 'categoryName'));
        return true;
    }


    /**
     * Просмотр статьи
     * @param $customer
     * @param $id_category
     * @param $id
     * @return bool
     */
    public function actionViewArticle($customer, $id_category, $id)
    {
        $user = $this->user;

        $article = KnowledgeArticle::getArticlesById($id);

        $lastViewArticle = KnowledgeCatalog::getLastVisitArticle($user->id_user, 1);

        if (is_array($lastViewArticle)){
            if($lastViewArticle[0]['id_article'] != $id){
                KnowledgeCatalog::userViewArticle($user->id_user, $id);
            }
        }

        $this->render('admin/ccc/knowledge/view', compact('user', 'article', 'customer'));
        return true;
    }


    public function actionSearch($customer, $search)
    {
        $user = $this->user;

        $search = $_GET['search'];
        $listSearch = KnowledgeArticle::getSearchContent($customer, $search);

        $this->render('admin/ccc/knowledge/search', compact('user', 'customer', 'listSearch'));
        return true;
    }


    /**
     * Популярные статьи
     * @param $customer
     * @return bool
     */
    public function actionPopularCategory($customer)
    {
        $user = $this->user;

        $popularArticles = KnowledgeArticle::getPopularArticles();

        $this->render('admin/ccc/knowledge/popular_category', compact('user', 'customer', 'popularArticles'));
        return true;
    }
}