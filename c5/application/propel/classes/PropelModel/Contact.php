<?php

namespace PropelModel;

use PropelModel\Base\Contact as BaseContact;

/**
 * Skeleton subclass for representing a row from the 'firm_contacts' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Contact extends BaseContact
{
    public function getValue()
    {
        $value = parent::getValue();
        if($this->getType() == 'phone'){
            $p = trim($value);
            $p = preg_replace('/\+\s{1,}7/', '+7', $p);        // убираем пробел между плюсом и семеркой
            $p = str_replace('-', '', $p);
            $p = trim($p);
            $p = preg_replace('/^(\+7|8)/', '', $p);        // убираем впереди +7 и 8
            $p = trim($p);
            if (preg_match('/^(?:\((\d{3,5})\))?\s*(\d{5,10})$/', $p, $match)) {
                $c = $match[1];
                $p = $match[2];
                switch (mb_strlen($p)) {
                    case 5:    // (xxx) x-xx-xx
                        $p = ($c ? '(' . $c . ') ' : '') . $p[0] . '-' . $p[1] . $p[2] . '-' . $p[3] . $p[4];
                        break;
                    case 6:    // (xxx) xx-xx-xx
                        $p = ($c ? '(' . $c . ') ' : '') . $p[0] . $p[1] . '-' . $p[2] . $p[3] . '-' . $p[4] . $p[5];
                        break;
                    case 7:    // (xxx) xxx-xx-xx
                        $p = ($c ? '(' . $c . ') ' : '') . $p[0] . $p[1] . $p[2] . '-' . $p[3] . $p[4] . '-' . $p[5] . $p[6];
                        break;
                    case 10: // xxx-xx-xx-xxx
                        $p = $p[0] . $p[1] . $p[2] . '-' . $p[3] . $p[4] . '-' . $p[5] . $p[6] . '-' . $p[7] . $p[8] . $p[9];
                        break;
                    default:
                        $p = ($c ? '(' . $c . ') ' : '') . $p;
                }
                // Если надо, то добавляем спереди +7 или 8
                $p = '+7 '.$p;

                $value = $p;
            }
        }
        return $value;
    }
}
