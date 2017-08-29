<?php

namespace Umbrella\controllers\umbrella\ccc;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\Functions;
use Umbrella\models\Admin;
use Umbrella\models\ccc\KnowledgeCatalog;

class CategoryController extends AdminBase
{

    /**
     * @var User
     */
    private $user;

    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::checkDenied('ccc.tree_knowledge.category', 'controller');
        $this->user = new User(Admin::CheckLogged());
    }

    /**
     * Список категорий в древе знаний
     * @param $id_category
     * @return bool
     */
    public function actionCategory($id_category = false)
    {
        $user = $this->user;

        $listCategory = KnowledgeCatalog::getAllCategoriesAdmin(0);
        if(isset($id_category) && $id_category !== false){
            $listSubCategory = KnowledgeCatalog::getAllCategoriesAdmin($id_category);
        }

        $listCustomer = KnowledgeCatalog::getCustomerInCategory();

        // Добавляем категорию
        if((isset($_REQUEST['add-category']) && $_REQUEST['add-category'] == 'true')){
            $options['p_id'] = $_REQUEST['p_id'];
            $options['customer'] = $_REQUEST['customer'];
            $options['name'] = $_REQUEST['name'];
            $options['child'] = isset($_REQUEST['child']) ? 1 : 0;
            $options['slug'] = 'cat-' . Functions::strUrl($_REQUEST['name']);

            $id = KnowledgeCatalog::addCategory($options);
            if($id){
                KnowledgeCatalog::updateCategorySlugById($id, 'cat-' . $id);
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }

        // Добавляем подкатегорию
        if((isset($_REQUEST['add-sub-category']) && $_REQUEST['add-sub-category'] == 'true')){
            $options['p_id'] = $_REQUEST['p_id'];
            $customer = KnowledgeCatalog::getInfoCategoryById($_REQUEST['p_id']);
            $options['customer'] = $customer['customer'];
            $options['name'] = $_REQUEST['name'];
            $options['child'] = isset($_REQUEST['child']) ? 1 : 0;
            $options['slug'] = 'cat-' . Functions::strUrl($_REQUEST['name']);

            $id = KnowledgeCatalog::addCategory($options);
            if($id){
                KnowledgeCatalog::updateCategorySlugById($id, 'cat-' . $id);
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }

        $this->render('admin/ccc/category/index', compact('user', 'listCategory', 'listSubCategory', 'id_category', 'listCustomer'));
        return true;
    }


    /**
     * Редактирование категории
     * @param $id_category
     * @return bool
     */
    public function actionCategoryEdit($id_category)
    {
        $user = $this->user;

        $categoryInfo = KnowledgeCatalog::getInfoCategoryById($id_category);

        $listCustomer = KnowledgeCatalog::getCustomerInCategory();

        if(isset($_REQUEST['edit_category']) && $_REQUEST['edit_category'] == 'true'){
            $options['p_id'] = $_REQUEST['p_id'];
            $options['customer'] = $_REQUEST['customer'];
            $options['name'] = $_REQUEST['name'];
            $options['enabled'] = $_REQUEST['enabled'];
            $options['child'] = isset($_REQUEST['child']) ? 1 : 0;

            $ok = KnowledgeCatalog::updateCategoryById($id_category, $options);
            if($ok){
                header("Location: /adm/ccc/tree_knowledge/category");
            }
        }

        $this->render('admin/ccc/category/edit', compact('user', 'categoryInfo', 'listCustomer', 'id_category'));
        return true;
    }
}