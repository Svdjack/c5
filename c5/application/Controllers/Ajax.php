<?php

namespace wMVC\Controllers;

use Propel\Runtime\ActiveQuery\Criteria;
use PropelModel\GroupQuery;
use PropelModel\RegionQuery;
use PropelModel\TagsQuery;
use wMVC\Core\abstractController;

Class Ajax extends abstractController
{
    public function user_company_region($id)
    {
        $result = array();
        $top_gun = RegionQuery::create()->orderByCount(Criteria::DESC)->findOneByArea($id);
        
        if (!$top_gun) {
            return self::response([]);
        }

        $result[] = ['id'   => $top_gun->getId(),
                     'name' => $top_gun->getName()];
        foreach (RegionQuery::create()->orderByName()->findByArea($id) as $region) {
            if ($region->getId() !== $top_gun->getId()) {
                $result[] = ['id'   => $region->getId(),
                             'name' => $region->getName()];
            }
        }
        self::response($result);
    }

    public function user_company_category($id)
    {
        $result = array();
        foreach (GroupQuery::create()->orderByName()->findByParent($id) as $group) {
            $result[] = array('id'   => $group->getId(),
                              'name' => $group->getName());
        }
        self::response($result);
    }

    public function user_company_keywords($word)
    {
        $result = array();
        foreach (TagsQuery::create()->filterByTag("%{$word}%")->limit(6)->find() as $tag) {
            $result[] = array('name' => $tag->getTag());
        }
        self::response($result);
    }
    
    public function getCity($id)
    {
        $c = RegionQuery::create()->findPk($id);
        if (!$c) {
            return $this->render([]);
        }
        $this->render([
            'id' => $c->getId(),
            'name' => $c->getName(),
            'area' => $c->getArea(),
        ]);
    }

    private function response($response, $code = 200)
    {
        static $sent = 0; // we want to send only one response per one request... but don't die in process
        if (!$sent) {
            $sent = 1;
            //header(':', true, $code);
            self::render($response);
        }
    }

    private function render($array)
    {
        header("Content-Type: application/json");
        print json_encode($array);
    }
}