<?php
namespace PropelModel;

use PropelModel\Base\Child as BaseChild;

/**
 * Skeleton subclass for representing a row from the 'firm_childs' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Child extends BaseChild
{

    public function __toString(): string
    {
        return $this->getValue();
    }
}
