<?php
namespace wMVC\Entity;

use wMVC\Core\ModelAccess;
use \ArrayAccess;

Class Company extends ModelAccess implements ArrayAccess
{
    private $companyData;
    private $name;

    public function __construct($id = 0)
    {
        if (is_numeric($id)) {
            $this->companyData = $this->loadModel('firm')->getFirm($id);
        }
    }

    public function attachCity(City $city)
    {
        $this->companyData['city'] = $city;
    }

    public function attachCategory(Category $category)
    {
        $this->companyData['groups'][] = $category;
    }

    public function getID()
    {
        return $this->companyData['id'];
    }

    //ArrayAccess
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->companyData[] = $value;
        } else {
            $this->companyData[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->companyData[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->companyData[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->companyData[$offset]) ? $this->companyData[$offset] : null;
    }
}