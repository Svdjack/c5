<?php

namespace PropelModel;

use Predis\Client;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use PropelModel\Base\Firm as BaseFirm;
use wMVC\Entity\Lang;

/**
 * Skeleton subclass for representing a row from the 'firm' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Firm extends BaseFirm
{
    public function isApproved(): bool
    {
        return $this->active == 1 && $this->status == 1;
    }
    
    public function save(\Propel\Runtime\Connection\ConnectionInterface $con = null)
    {
        $this->changed = $this->changed > 1 ? 0 : $this->changed;
        if (!$this->moderation_time) {
            $this->setModerationTime(0);
        }
        return parent::save($con);
    }

    public function getReviewOverall()
    {
        $comments = CommentQuery::create()
            ->filterByFirm($this)
            ->addAsColumn('average_score', 'AVG(score)')
            ->addAsColumn('score_count', 'COUNT(id)')
            ->findOne();
        if ($comments->getId()) {
            $count = $comments->toArray()['score_count'];
            print $count;
            $count .= " отзыв" . Lang::suffix($count, 'ов', 'а', 'ов');
            $average = round($comments->toArray()['average_score']);
            return ['count'   => $count,
                    'average' => $average];
        }
        return ['count'   => 0,
                'average' => 0];
    }

    public function distanceTo(Firm $firm)
    {
        $first = [$this->getLon(), $this->getLat()];
        $second = [$firm->getLon(), $firm->getLat()];

        $lat1 = $first[1] * M_PI / 180;
        $lat2 = $second[1] * M_PI / 180;
        $long1 = $first[0] * M_PI / 180;
        $long2 = $second[0] * M_PI / 180;

        // косинусы и синусы широт и разницы долгот
        $cl1 = cos($lat1);
        $cl2 = cos($lat2);
        $sl1 = sin($lat1);
        $sl2 = sin($lat2);
        $delta = $long2 - $long1;
        $cdelta = cos($delta);
        $sdelta = sin($delta);

        // вычисления длины большого круга
        $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
        $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

        //
        $ad = atan2($y, $x);
        $dist = $ad * 6372795;

        return $dist;
    }

    /**
     * @param Client $cache
     *
     * @return array|mixed|\Propel\Runtime\Collection\ObjectCollection
     */

    public function getNearby(Client $cache)
    {
        $nearbyFirms = FirmQuery::create()
            ->filterByCityId($this->getCityId())
            ->filterByMainCategory($this->getMainCategory())
            ->filterByActive(1)
            ->setFormatter(ModelCriteria::FORMAT_ON_DEMAND)
            ->limit(250)
            ->find();

        $distances = [];
        foreach ($nearbyFirms as $firm) {
            $distances[$firm->getPrimaryKey()] = $this->distanceTo($firm);
        }
        if (isset($distances[$this->getPrimaryKey()])) {
            unset($distances[$this->getPrimaryKey()]);
        }
        asort($distances);
        $distances = array_slice($distances, 0, 4, true);

        $firms = [];
        $distance = [];

        $suffix = function ($input) {
            $num = $input; // число
            $scriptProperties['item'] = 'метр'; // наименование
            $scriptProperties['end_1'] = 'ах'; // например 45 товаров
            $scriptProperties['end_2'] = 'е'; // например 31 товар
            $scriptProperties['end_3'] = 'ах'; // например // 2 товара

            $str = array($scriptProperties['end_1'], $scriptProperties['end_2'], $scriptProperties['end_3']);
            $index = $num % 100;
            if ($index >= 11 && $index <= 14) {
                $index = 0;
            } else {
                $index = ($index %= 10) < 5 ? ($index > 2 ? 2 : $index) : 0;
            }
            return $scriptProperties['item'] . $str[$index];
        };

        foreach (FirmQuery::create()->findPks(array_keys($distances)) as $firm) {
            $position = 'в этом же здании на ' . $firm->getStreet() . ', ' . $firm->getHome();
            if ($distances[$firm->getId()] > 0) {
                $position = "в " . (int)$distances[$firm->getId()]
                    . " {$suffix((int)$distances[$firm->getId()])} на "
                    . $firm->getStreet() . ', ' . $firm->getHome();
            }
            $firms[] = ['id'       => $firm->getId(),
                        'path'     => $firm->getAlias(),
                        'title'    => $firm->getName(),
                        'distance' => $distances[$firm->getId()],
                        'text'     => $position];
            $distance[] = $distances[$firm->getId()];
        }

        array_multisort($distance, SORT_ASC, $firms);

        return $firms;
    }

    public function setCoordsByAddress($query)
    {
        $api = new \Yandex\Geo\Api();

        $api->setQuery($query);

        $api
            ->setLimit(1)
            ->setLang(\Yandex\Geo\Api::LANG_RU)
            ->load();

        $response = $api->getResponse();
        $collection = $response->getList();

        foreach ($collection as $item) {
            if (empty($lat)){
                $lat = $item->getLatitude();
                $this->setLat($lat);
            }
            if (empty($lon)){
                $lon = $item->getLongitude();
                $this->setLon($lon);
            }
        }
    }

    public function getName()
    {
        return Lang::ucfirst_leave_rest(htmlspecialchars_decode(htmlspecialchars_decode(htmlspecialchars_decode(htmlspecialchars_decode(($this->name))))));
    }

    public function getDescription()
    {
        return htmlspecialchars_decode(htmlspecialchars_decode(htmlspecialchars_decode(htmlspecialchars_decode($this->description))));
    }

    public function getSubtitle()
    {
        //return Lang::ucfirst(htmlspecialchars_decode($this->subtitle));
        return htmlspecialchars_decode($this->subtitle);
    }

    public function getOfficialName()
    {
        return htmlspecialchars_decode($this->official_name);
    }
    
    /**
     * Get the url or alias
     * @access public
     * @return string
     */
    public function getUrl() {
        
        $u = parent::getUrl();
        
        if (!$u) {
            return $this->getAlias();
        }
        
        if ($u == $this->getName()) {
            return $this->getAlias();
        }
        return $u;
    }

    public function getAlias()
    {
        $alias = UrlAliasesQuery::create()->findOneBySource('/firm/show/' . $this->getId());
        if ($alias) {
            return $alias->getAlias();
        }
        
        try {
            $region = RegionQuery::create()->findOneById($this->getCityId());
            
            if (!$region) {
                throw new \Exception('Region not found');
            }

            $city = $region->getUrl();
            
            $cat = GroupQuery::create()->findPk($this->getMainCategory());
            
            if (!$cat) {
                throw new \Exception('Cat/Group not found');
            }

            $main_cat = $cat->getUrl();

            $url = Lang::toUrl($this->getName());

            $alias = "/{$city}/{$main_cat}/{$url}";

            if (UrlAliasesQuery::create()->filterByAlias($alias)->count()) {
                $alias .= '-' . $this->getId();
            }

            $alias_obj = new UrlAliases();
            $alias_obj->setSource('/firm/show/' . $this->getId());
            $alias_obj->setAlias($alias);
            $s = $alias_obj->save();
            return $alias;
        } catch (\Exception $ex) {
            
        }

        return '/firm/show/' . $this->getId();
    }

    public function getFirstContactByType($type)
    {
        return ContactQuery::create()->filterByFirmId($this->getId())->findOneByType($type);
    }

    public function getUser()
    {
        return UserQuery::create()
            ->useFirmUserQuery()
            ->filterByFirmId($this->getId())
            ->endUse()
            ->findOne();
    }

    public function getLevel2Groups()
    {
        $all_groups = $this->getGroups();
        $level_2_groups = [];
        foreach($all_groups as $group){
            if($group->getLevel() === 3){
                $group = $group->getParentObject();
            }
            $level_2_groups[] = $group;
        }

        return $level_2_groups;
    }
}
