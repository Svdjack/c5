<?php
namespace wMVC\Entity;

use wMVC\Core\ModelAccess;

Class Category extends ModelAccess
{
    private $tree = array();
    private $id;
    private $categoryLevel;
    private $name;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getLevel()
    {
        $this->makeTree();
        $this->categoryLevel = key(array_slice($this->tree, -1, 1, true));

        return $this->categoryLevel;
    }

    private function makeTree()
    {
        if (count($this->tree) === 0) {
            $id = $this->id;
            array_unshift($this->tree, $id);
            while (($cat_id = $this->loadModel('category')->getParentByID($id)) != false) {
                array_unshift($this->tree, $id = $cat_id);
            }
        }
    }

    public function getParent()
    {
        $this->makeTree();
        if (empty($this->categoryLevel)) {
            $this->getLevel();
        }
        return $this->tree[$this->categoryLevel - 1];
    }

    public function getTree()
    {
        $this->makeTree();
        return $this->tree;
    }

    public function getAncestor($level)
    {
        $this->makeTree();
        if (in_array($level, array_keys($this->tree))) {
            return $this->tree[$level];
        }
        return false;
    }

    public function getChildrenFirmsByCity(City $city)
    {
        return $this->loadModel('category')->getCategoriesByParent($this->id, $city->getID());
    }

    public function getName()
    {
        if (empty($this->name)) {
            $this->name = $this->loadModel('category')->getCategoryNameByID($this->id);
        }
        return $this->name;
    }
}