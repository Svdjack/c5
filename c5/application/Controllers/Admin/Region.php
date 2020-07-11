<?php

namespace wMVC\Controllers\Admin;

use PropelModel\RegionQuery;
use wMVC\Controllers\abstractAdmin;

Class Region extends abstractAdmin{
    public function getRegions()
    {
        self::requireAdmin();
        $result = array();
        foreach (RegionQuery::create()->filterByArea(null)->orderByName()->find() as $region) {
            $result[] = [
                'id'   => $region->getId(),
                'name' => $region->getName()
            ];
        }

        self::response($result);
    }

    public function getCitiesByRegion($region)
    {
        self::requireAdmin();
        $result = array();
        foreach (RegionQuery::create()->filterByArea($region)->orderByName()->find() as $city) {
            $result[] = [
                'id'   => $city->getId(),
                'name' => $city->getName()
            ];
        }

        self::response($result);
    }
}