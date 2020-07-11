<?php
namespace wMVC\Entity;

use wMVC\Core\ModelAccess;

class City extends ModelAccess
{
    private $id;
    private $cases;

    public function __construct($id)
    {
        $this->id = $id;
        $this->cases = $this->loadModel('region')->getNameByIDWithCases($id);
    }

    public function __toString()
    {
        return (string)$this->cases[0];
    }

    public function getCase($case)
    {
        if (in_array($case, array_keys($this->cases))) {
            return $this->cases[$case];
        }
        return '';
    }

    public function withPrefix($case = 0, $capitalize = false)
    {
        $excludes = array('фр', 'вл', 'вн', 'вс', 'вз', 'вв', 'вр', 'вт', 'вд');
        $start = mb_substr($this->getCase($case), 0, 2); // taking first two chars from name
        $prefix = '';
        if (in_array(mb_strtolower($start), $excludes)) {
            if ($capitalize) {
                $prefix = 'Во ';
            }
            $prefix = 'во ';
        } else {
            if ($capitalize) {
                $prefix = 'В ';
            }
            $prefix = 'в ';
        }
        return $prefix . $this->getCase($case);
    }

    public function getID()
    {
        return $this->id;
    }

}