<?php

namespace Umbrella\models\hr;

use PDO;
use Umbrella\components\Db\MySQL;

/**
 * Class Structure
 * @package Umbrella\models\hr
 */
class Structure
{

    /**
     * List structure in company
     * @param $filter
     * @return array
     * @internal param string $structure
     * @internal param $value
     */
    public static function getStructureList($filter)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  id,
                  name,
                  (SELECT 
                  count(id) 
                  FROM gs_hr_structure ghs
                  WHERE ghs.p_id = gs_hr_structure.id) as count
                FROM gs_hr_structure
                WHERE 1 = 1 {$filter}";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * get info structure by id
     * @param $id
     * @return mixed
     */
    public static function getStructureById($id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  *
                FROM gs_hr_structure
                WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * List child structures
     * @param $id
     * @return mixed
     */
    public static function getChildStructureById($id)
    {
        $db = MySQL::getConnection();

        $sql = "SELECT 
                  id,
                  name,
                  p_id
                FROM gs_hr_structure
                WHERE p_id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $name
     * @param $p_id
     * @param $attr
     * @return int|string
     */
    public static function addStructure($name, $p_id, $attr)
    {
        $db = MySQL::getConnection();

        $sql = "INSERT INTO gs_hr_structure "
            . "(name, p_id, {$attr})"
            . "VALUES "
            . "(:name, :p_id, 1)";

        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':p_id', $p_id, PDO::PARAM_INT);

        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }


    /**
     * @param $id
     * @param $name
     * @param $p_id
     * @return bool
     */
    public static function updateStructure($id, $name, $p_id)
    {
        $db = MySQL::getConnection();

        $sql = "UPDATE gs_hr_structure
            SET
                name = :name,
                p_id = :p_id
            WHERE id = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':p_id', $p_id, PDO::PARAM_STR);
        return $result->execute();
    }


    /**
     * delete structure by id
     * @param $id
     * @return bool
     */
    public static function deleteStructure($id)
    {
        $db = MySQL::getConnection();

        $sql = 'DELETE FROM gs_hr_structure WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }


    /**
     * Recursive delete structure and
     * @param $id
     */
    public static function recursiveDeleteStructure($id)
    {
        $childStructure = Structure::getChildStructureById($id);

        self::deleteStructure($id);

        if(is_array($childStructure)){
            foreach ($childStructure as $structure){
                self::deleteStructure($structure['id']);
                self::recursiveDeleteStructure($structure['id']);
            }
        }
    }


    /**
     * Получаем ID компании в которой находится отдел
     * @param $id
     * @return mixed
     */
    public static function getCompanyBranch($id)
    {
        $infoStructure = self::getStructureById($id);
        //return $infoStructure['p_id'];
        return $infoStructure['is_company'] == 1 ? $infoStructure['id'] : self::getCompanyBranch($infoStructure['p_id']);
//        if($infoStructure['is_company'] == 1){
//            var_dump($infoStructure['id']);
//            return (int)$infoStructure['p_id'];
//            die();
//        } else {
//            self::getCompanyBranch($infoStructure['p_id']);
//
//        }
    }
}