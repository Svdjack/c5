<?php

namespace PropelModel;

use PropelModel\Base\GroupQuery as BaseGroupQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'groups' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class GroupQuery extends BaseGroupQuery
{
    public static function getRandomGroup()
    {
        return GroupQuery::create()->offset(mt_rand(0, GroupQuery::create()->count() - 1))->findOne();
    }

    public static function getRandomGroupFromRoot(Group $root)
    {
        return GroupQuery::create()
            ->filterByParent($root->getId())
            ->offset(
                mt_rand(
                    0, GroupQuery::create()
                        ->filterByParent($root->getId())
                        ->count() - 1
                )
            )
            ->findOne();
    }
}
