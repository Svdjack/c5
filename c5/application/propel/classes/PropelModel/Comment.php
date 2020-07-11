<?php

namespace PropelModel;

use PropelModel\Base\Comment as BaseComment;
use PropelModel\Map\CommentTableMap;

/**
 * Skeleton subclass for representing a row from the 'comment' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Comment extends BaseComment
{
    public function save(\Propel\Runtime\Connection\ConnectionInterface $con = null)
    {
        if (!$this->moderation_time) {
            //$this->moderation_time = \date)'';
            //$this->modifiedColumns[CommentTableMap::COL_MODERATION_TIME] = true;
            $this->setModerationTime('1970-01-01');
        }
        return parent::save($con);
    }
}
