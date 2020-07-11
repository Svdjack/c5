<?php

namespace PropelModel;

use PropelModel\Base\Group as BaseGroup;

/**
 * Skeleton subclass for representing a row from the 'groups' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Group extends BaseGroup
{
    const BAD_GROUP_FOR_SEARCH = ['Банкоматы'];

    protected $parent_object = null;
    protected $children = null;
    protected $children_ids = [];

//    public function getLevel()
//    {
//        $level = 1;
//        $object = $this;
//        while ($object->getParent() != 0) {
//            $object = GroupQuery::create()->findPk($object->getParent());
//            $level++;
//        }
//        return $level;
//    }

    public function getRoot()
    {
        $object = $this;
        while ($object->getParent() != 0) {
            $object = GroupQuery::create()->findPk($object->getParent());
        }
        return $object;
    }

    public function getParentObject()
    {
        return $this->parent_object ?: $this->parent_object = GroupQuery::create()->findPk($this->getParent());
    }

    public function getChildren()
    {
        return $this->children ?: $this->children = GroupQuery::create()->findByParent($this->getId());
    }
    
    /**
     * Группа не для поиска?
     * @access public
     * @return bool
     */
    public function isBadGroupForSearch(): bool {
        return \in_array($this->name, static::BAD_GROUP_FOR_SEARCH);
    }

    public function getTreeIds()
    {
        if (!count($this->children_ids)) {
            $this->children_ids = [$this->getId()];
            foreach ($this->getChildren() as $child) {
                $this->children_ids[] = $child->getId();
            }
            if ($this->getLevel() == 1) {
                $additional_ids = [];
                $ids = [];
                foreach ($this->children_ids as $key => $id) {
                    if ($key == 0) {
                        continue;
                    }
                    $ids[] = $id;
//                    foreach (GroupQuery::create()->findPk($id)->getChildren() as $child) {
//                        $additional_ids[] = $child->getId();
//                    }
                }
                $additional_ids = array_keys(GroupQuery::create()->findByParent($ids)->toArray('Id'));
                $this->children_ids = array_merge($this->children_ids, $additional_ids);
            }
        }
        return $this->children_ids;
    }
}
