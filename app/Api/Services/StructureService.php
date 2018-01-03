<?php

namespace Umbrella\app\Api\Services;

use Umbrella\models\api\hr\Structure;

class StructureService
{

    /**
     * @var array|bool
     */
    private $structureArray = [];

    /**
     * StructureService constructor.
     */
    public function __construct()
    {
        $list = Structure::getStructureList();
        $this->structureArray = $this->getStructure($list);
    }

    /**
     * @param $array
     *
     * @return array|bool
     */
    public function getStructure($array)
    {
        if (!is_array($array)) {
            return false;
        }
        $tree = [];
        foreach ($array as $value) {
            $tree[$value['p_id']][] = $value;
        }
        return $tree;
    }

    /**
     * @param $parent_id
     *
     * @return array|bool
     */
    public function buildStructure($parent_id = 0)
    {
        $newArray = [];
        if (is_array($this->structureArray) && isset($this->structureArray[$parent_id])) {
            foreach ($this->structureArray[$parent_id] as $cat) {
                if ($cat['count'] > 0) {
                    $cat['child'] = self::buildStructure($cat['id']);
                }
                $newArray[] = $cat;
            }
        } else {
            return false;
        }
        return $newArray;
    }
}