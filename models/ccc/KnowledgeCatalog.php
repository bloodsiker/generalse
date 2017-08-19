<?php
namespace Umbrella\models\ccc;

use PDO;
use Umbrella\components\Db\MySQL;
use Umbrella\components\Functions;

class KnowledgeCatalog
{

    /**
     * @param $customer
     * @return array
     */
    public static function getAllCategories($customer)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                gckc.id,
                gckc.p_id,
                gckc.customer,
                gckc.name,
                gckc.slug,
                gckc.enabled,
                gckc.child,
                (SELECT 
                  count(gcka.id) 
                  FROM gs_ccc_knowledge_articles gcka
                  WHERE gckc.id = gcka.id_category
                  AND gcka.published = 1
                  AND gcka.delete_article = 0) as count,
                (SELECT 
                    GROUP_CONCAT(gcka.updated_at) 
                  FROM gs_ccc_knowledge_articles gcka
                  WHERE gckc.id = gcka.id_category
                  AND gcka.published = 1
                  AND gcka.delete_article = 0
                  GROUP BY gckc.id) as updated_at
                FROM gs_ccc_knowledge_category gckc
                WHERE gckc.customer = :customer
                AND gckc.enabled = 0
                ORDER BY gckc.sort";

        $result = $db->prepare($sql);
        $result->bindParam(':customer', $customer, PDO::PARAM_STR);
        $result->execute();

        $user = $result->fetchAll(PDO::FETCH_ASSOC);
        return $user;
    }



    /**
     * Формируем массив для построение дерева категорий
     * @param $mess
     * @return array|bool
     */
    public static function form_tree($mess)
    {
        if(!is_array($mess)){
            return false;
        }
        $tree = array();
        foreach ($mess as $value) {
            $tree[$value['p_id']][] = $value;
        }
        return $tree;
    }

    /**
     * Формируем меню в sidebar
     * @param $cats
     * @param $p_id
     * @param $category_id
     * @return bool|string
     */
    public static function build_tree($cats, $p_id, $category_id = false)
    {
        if (is_array($cats) && isset($cats[$p_id])) {
            if($p_id == 0){
                $tree = '<ul class="cd-accordion-menu animated">';
            } else {
                $tree = '<ul>';
            }

            foreach ($cats[$p_id] as $cat) {
                $active = \Umbrella\components\Url::IsActive("/adm/ccc/tree_knowledge/customer-{$cat['customer']}/{$cat['slug']}", "active-req");

                if($cat['child'] == 1){

                    $tree .= '<li class="has-children">';
                    $tree .= '<input type="checkbox" checked name="group-' . $cat['id'] . '" id="group-' . $cat['id'] . '" >';
                    $tree .= '<label for="group-' . $cat['id'] . '">' . $cat['name'] . '</label>';
                } else {
                    // Проверяем, если в этом разделе статьи обновленный не более двух дней назад
                    $listUpdateArticle = explode(',', $cat['updated_at']);
                    $update = null;
                    foreach ($listUpdateArticle as $updateArticle) {
                        if(Functions::calcDiffSec($updateArticle) < 172800){
                            $update = " <span style='color: orange; font-size: 14px'><b>!</b></span>";
                        }
                    }

                    $tree .= '<li><a class="' . $active . '" href="/adm/ccc/tree_knowledge/customer-' . $cat['customer'] . '/' . $cat['slug'] . '">' . $cat['name'] . ' (' . $cat['count'] .')' . $update . '</a>';
                }

                $tree .= self::build_tree($cats, $cat['id'], $category_id);
                $tree .= '</li>';
            }
            $tree .= '</ul>';
        } else {
            return false;
        }
        return $tree;
    }


    /**
     * Формируем выпадающий список категорий
     * @param $p_id
     * @param string $default
     * @return string
     */
    public static function subCategoryList($p_id, $default = '0')
    {
        static $offset = 0;
        $option = '';

        $listCategory = self::getCategoriesForOption(intval($p_id));
        if ($offset == 0) {
            $option = '<option value="0" disabled selected></option>';
        }
        $offset++;
        foreach ($listCategory as $category){
            $selected = $default == $category['id'] ? " selected" : null;
            $disabled = $category['child'] == 1 ? 'disabled' : null;

            if($category['p_id'] == 0 && $category['child'] == 1) {
                $bg = '#000';
                $color = '#fff';
            } else {
                $bg = '#fff';
                $color = '#000';
            }

            $option .= "<option style='background: {$bg}; color: {$color}' value='{$category['id']}' $selected $disabled>" . ucfirst($category['customer']) . str_repeat(' -- ', $offset) . $category['name'] . "</option>";
            $option .= self::subCategoryList($category['id'], $default);
        }
        $offset--;
        return $option;
    }



    /**
     * Информация о категории
     * @param $id_category
     * @return mixed
     */
    public static function getInfoCategoryById($id_category)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  *
                FROM gs_ccc_knowledge_category
                WHERE id = :id_category";

        $result = $db->prepare($sql);
        $result->bindParam(':id_category', $id_category, PDO::PARAM_INT);
        $result->execute();

        $all = $result->fetch(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Список заказчиков в категорях
     * @return mixed
     */
    public static function getCustomerInCategory()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  customer
                FROM gs_ccc_knowledge_category
                GROUP BY customer";

        $result = $db->prepare($sql);
        $result->execute();

        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }



    /**
     * @param $id_user
     * @param int $limit
     * @return array
     */
    public static function getLastVisitArticle($id_user, $limit = 4)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                    gcva.id_user,
                    gcva.id_article,
                    gckc.name,
                    gcka.title,
                    gckc.customer
                FROM gs_ccc_view_articles gcva
                    INNER JOIN gs_ccc_knowledge_articles gcka
                        ON gcva.id_article = gcka.id
                    INNER JOIN gs_ccc_knowledge_category gckc
                        ON gcka.id_category = gckc.id
                WHERE gcva.id_user = :id_user
                ORDER BY gcva.id DESC
                LIMIT :limit";

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->execute();

        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }


    /**
     * Добавляем просмотренные статьи для пользователя
     * @param $user_id
     * @param $id_article
     * @return bool
     */
    public static function userViewArticle($user_id, $id_article)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_ccc_view_articles '
            . '(id_user, id_article) '
            . 'VALUES '
            . '(:id_user, :id_article)';

        $result = $db->prepare($sql);
        $result->bindParam(':id_user', $user_id, PDO::PARAM_INT);
        $result->bindParam(':id_article', $id_article, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Получаем список всех категорий
     * @param int $p_id
     * @return array
     */
    public static function getAllCategoriesAdmin($p_id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                *
                FROM gs_ccc_knowledge_category
                WHERE p_id = :p_id
                ORDER BY customer, sort";

        $result = $db->prepare($sql);
        $result->bindParam(':p_id', $p_id, PDO::PARAM_INT);
        $result->execute();
        $category = $result->fetchAll(PDO::FETCH_ASSOC);
        return $category;
    }


    /**
     * Выборка для выпаающего списка категорий <select></select>
     * @param $p_id
     * @return array
     */
    public static function getCategoriesForOption($p_id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                *
                FROM gs_ccc_knowledge_category
                WHERE p_id = :p_id
                AND to_site = 1
                ORDER BY customer, sort";

        $result = $db->prepare($sql);
        $result->bindParam(':p_id', $p_id, PDO::PARAM_INT);
        $result->execute();
        $category = $result->fetchAll(PDO::FETCH_ASSOC);
        return $category;
    }


    /**
     * Список основных категорий
     * @param int $p_id
     * @param $customer
     * @return array
     */
    public static function getAllCategoriesCustomerAdmin($p_id = 0, $customer)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                *
                FROM gs_ccc_knowledge_category
                WHERE p_id = :p_id
                AND customer = :customer
                AND to_site = 1
                ORDER BY customer, sort";

        $result = $db->prepare($sql);
        $result->bindParam(':p_id', $p_id, PDO::PARAM_INT);
        $result->bindParam(':customer', $customer, PDO::PARAM_INT);
        $result->execute();
        $category = $result->fetchAll(PDO::FETCH_ASSOC);
        return $category;
    }


    /**
     * @param $id
     * @param $options
     * @return bool
     */
    public static function updateCategoryById($id, $options)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_ccc_knowledge_category
            SET
                p_id = :p_id,
                customer = :customer,
                name = :name,
                enabled = :enabled,
                child = :child
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':p_id', $options['p_id'], PDO::PARAM_INT);
        $result->bindParam(':customer', $options['customer'], PDO::PARAM_STR);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':enabled', $options['enabled'], PDO::PARAM_INT);
        $result->bindParam(':child', $options['child'], PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Добавляем новую категорию\подкатегорию
     * @param $options
     * @return bool
     */
    public static function addCategory($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_ccc_knowledge_category '
            . '(p_id, customer, name, slug, child) '
            . 'VALUES '
            . '(:p_id, :customer, :name, :slug, :child)';

        $result = $db->prepare($sql);
        $result->bindParam(':p_id', $options['p_id'], PDO::PARAM_INT);
        $result->bindParam(':customer', $options['customer'], PDO::PARAM_STR);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':slug', $options['slug'], PDO::PARAM_STR);
        $result->bindParam(':child', $options['child'], PDO::PARAM_INT);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }


    /**
     * Обновляем slug для категории
     * @param $id
     * @param $slug
     * @return bool
     */
    public static function updateCategorySlugById($id, $slug)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_ccc_knowledge_category
            SET
                slug = :slug
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':slug', $slug, PDO::PARAM_STR);
        return $result->execute();
    }

}