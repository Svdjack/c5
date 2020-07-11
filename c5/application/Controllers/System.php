<?php
namespace wMVC\Controllers;

use PropelModel\FirmGroupQuery;
use PropelModel\FirmQuery;
use PropelModel\RegionQuery;
use wMVC\Config;
use wMVC\Core\abstractController;

Class System extends abstractController
{
    public function cron($key)
    {
        if ($key != Config::$cron_key) {
            die('guru meditation<!--wrong cron key-->');
        }
        $this->updateRegionCounter();
    }

    //implement system to fire those methods from administration menu
    private function updateRegionCounter()
    {
        //обновляем счетчики для главной
        $cities = RegionQuery::create()->find();
        foreach ($cities as $city) {
            if ($city->getArea()) {
                $cityCount = FirmQuery::create()->filterByCityId($city->getId())->count();
                $city->setCount($cityCount);
                $city->save();
            }
        }

        $regions = RegionQuery::create()->filterByArea(null)->find();
        foreach ($regions as $region) {
            $regionCount = RegionQuery::create()->filterByArea($region->getId())
                ->addAsColumn("region_count", "SUM(region.count)")->find();
            $region->setCount($regionCount->getFirst()->getVirtualColumns()['region_count']);
            $region->save();
        }
    }
}