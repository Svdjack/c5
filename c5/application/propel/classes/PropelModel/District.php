<?php

namespace PropelModel;

use PropelModel\Base\District as BaseDistrict;

/**
 * Skeleton subclass for representing a row from the 'district' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class District extends BaseDistrict
{
    protected $cases = array();

    public function getCase($case)
    {
        if (count($this->cases) === 0) {
            $cases = $this->getData();
            $cases = unserialize($cases);
            array_unshift($this->cases, $this->getName());
            foreach ($cases as $one) {
                array_unshift($this->cases, $one);
            }
            $this->cases = array_reverse($this->cases);
        }

        if (in_array($case, array_keys($this->cases))) {
            return $this->cases[$case];
        }
        return $this->getName();
    }
}
