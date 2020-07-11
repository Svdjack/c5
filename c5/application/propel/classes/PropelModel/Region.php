<?php

namespace PropelModel;

use Predis\Client;
use PropelModel\Base\Region as BaseRegion;
use PropelModel\Map\FirmGroupTableMap;
use PropelModel\Map\RegionTableMap;
use wMVC\Entity\Lang;

/**
 * Skeleton subclass for representing a row from the 'region' table.
 *
 *2
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Region extends BaseRegion
{
    protected $cases = array();

    public function getCase($case)
    {
        if (count($this->cases) === 0) {
            $cases = $this->getData();
            $cases = stream_get_contents($cases, -1, 0);
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

    public function getPrefix()
    {
        $excludes = array('фр', 'вл', 'вн', 'вс', 'вз', 'вв', 'вр', 'вт', 'вд');
        $start = mb_substr($this->getName(), 0, 2); // taking first two chars from cityname
        if (in_array(mb_strtolower($start), $excludes)) {
            return 'во';
        } else {
            return 'в';
        }
    }

    public function distanceTo(Region $region)
    {
        $first = [$this->getLon(), $this->getLat()];
        $second = [$region->getLon(), $region->getLat()];

        $lon1 = $first[0];
        $lon2 = $second[0];
        $lat1 = $first[1];
        $lat2 = $second[1];

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return $miles * 1.609344;
    }

    /**
     * @param Group
     *
     * @return array|mixed|\Propel\Runtime\Collection\ObjectCollection
     */

    public function getNearbyTags(Tags $tag)
    {
        $nearbyCities = RegionQuery::create()
            ->addAsColumn(
                'distance', 'SQRT(POW(111 * (region.lat - ' . $this->getLat() . '), 2) +
                                  POW(111 * (' . $this->getLon() . ' - region.lon) * COS(region.lat / 57.3), 2))'
            )
            ->where('Region.Area IS NOT NULL')
            ->having('distance < 250 and distance > 1')
            ->orderBy('distance')
            ->find();


        $cities = [];
        $counters = [];

        foreach ($nearbyCities as $city) {
            $query = FirmTagsQuery::create()->filterByCityId($city->getId())
                ->filterByTagId($tag->getId());
            if ($query->findOne()) {
                $counters[$city->getPrimaryKey()] = $query->count();
                $cities[] = $city;
            }
            if (count($cities) == 4) {
                break;
            }
        }

        $cities_return = [];

        /** @var \PropelModel\Region $city */
        foreach ($cities as $city) {
            $count = "{$counters[$city->getPrimaryKey()]} компани" . Lang::suffix($counters[$city->getPrimaryKey()], 'й', 'я', 'и');
            $cities_return[] = [
                'path'      => '/' . $city->getUrl() . '/ключевое-слово/' . $tag->getUrl(),
                'count'     => $count,
                'name'      => $tag->getTag() . ' ' . $city->getPrefix() . ' ' . $city->getCase(5),
                'distance'  => $city->toArray()['distance'],
                'city_name' => $city->getName()
            ];
        }

        return $cities_return;
    }
    
    /**
     * Популярные группы в городе
     * @access public
     * @param Client $cache predis
     * @return array
     */
    public function getPopularGroupsInCity(Client $cache): array {

        $cache_key = 'popular_group_in_region_' . $this->getId();

        if($cached_result = $cache->get($cache_key)){
            return unserialize($cached_result);
        }
        
        $fgq = FirmGroupQuery::create()
                ->groupByGroupId()
                ->filterByCity($this->id)
                ->withColumn('COUNT(group_id)', 'count')
                ->limit(9)
                ->orderBy('count', 'desc');      
        
        if(!$find = $fgq->find()) {
            return [];
        }
        
        $return = [];
        
        foreach ($find as $_fg) {
            /* @var $_fg \PropelModel\FirmGroup */
            $grp = $_fg->getGroup();
            
            if (!$grp || $grp->isBadGroupForSearch()) {
                continue;
            }

            $return[] = [
                'path' => '/' . $this->getUrl() . '/' . $grp->getUrl(),
                'name' => $grp->getName()
            ];
        }
        
        $cache->set($cache_key, serialize($return));
        $cache->expireat($cache_key, strtotime('+2 days'));
        
        return $return;
    }

    public function getNearbyGroup(Group $group, Client $cache)
    {

        $treeIds = $group->getTreeIds();
        $nearbyCities = RegionQuery::create()
            ->addAsColumn(
                'distance', 'SQRT(POW(111 * (region.lat - ' . $this->getLat() . '), 2) +
                                  POW(111 * (' . $this->getLon() . ' - region.lon) * COS(region.lat / 57.3), 2))'
            )
            ->where('Region.Area IS NOT NULL')
            ->having('distance < 250 and distance > 1')
            ->orderBy('distance')
            ->find();


        $cities = [];
        $counters = [];

        foreach ($nearbyCities as $city) {
            $query = FirmGroupQuery::create()->filterByCity($city->getId())->where('group_id IN (' . implode(',', $treeIds) . ')');
            if ($query->findOne()) {
                $counters[$city->getPrimaryKey()] = $query->count();
                $cities[] = $city;
            }
            if (count($cities) == 4) {
                break;
            }
        }

        $cities_return = [];

        /** @var \PropelModel\Region $city */
        foreach ($cities as $city) {
            $count = "{$counters[$city->getPrimaryKey()]} компани" . Lang::suffix($counters[$city->getPrimaryKey()], 'й', 'я', 'и');
            $cities_return[] = [
                'path'      => '/' . $city->getUrl() . '/' . $group->getUrl(),
                'count'     => $count,
                'name'      => $group->getName() . ' ' . $city->getPrefix() . ' ' . $city->getCase(5),
                'distance'  => $city->toArray()['distance'],
                'city_name' => $city->getName()
            ];
        }

        return $cities_return;
    }
}
