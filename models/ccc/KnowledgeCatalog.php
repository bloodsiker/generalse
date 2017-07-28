<?php

class KnowledgeCatalog
{

    public static function getAllCategories()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                *
                FROM gs_ccc_knowledge_category
                WHERE enabled = 0
                ORDER BY sort";

        $result = $db->prepare($sql);
        $result->execute();

        $user = $result->fetchAll(PDO::FETCH_ASSOC);
        return $user;
    }

    /**
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
     * Формируем меню
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
                if($cat['child'] == 1){

                    $tree .= '<li class="has-children">';
                    $tree .= '<input type="checkbox" name="group-' . $cat['id'] . '" id="group-' . $cat['id'] . '" >';
                    $tree .= '<label for="group-' . $cat['id'] . '">' . $cat['name'] . '</label>';
                } else {
                    $tree .= '<li><a href="/adm/ccc/tree_knowledge/category/cat-' . $cat['id'] . '">' . $cat['name'] . '</a>';
                }

                $tree .= self::build_tree($cats, $cat['id'], $category_id);
                $tree .= '</li>';
            }
            $tree .= '<li><a href="/adm/ccc/tree_knowledge/popular/category">Популярные</a>';
            $tree .= '</ul>';
        } else {
            return false;
        }
        return $tree;
    }

}