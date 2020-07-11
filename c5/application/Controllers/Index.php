<?php

namespace wMVC\Controllers;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Propel;
use PropelModel\Base\Region;
use PropelModel\FirmTagsQuery;
use PropelModel\Map\CommentTableMap;
use PropelModel\Map\FirmTableMap;
use PropelModel\TagsQuery;
use PropelModel\FirmGroupQuery;
use PropelModel\FirmQuery;
use PropelModel\DistrictQuery;
use PropelModel\GroupQuery;
use PropelModel\Map\FirmGroupTableMap;
use PropelModel\RegionQuery;
use PropelModel\UserQuery;
use wMVC\Config;
use wMVC\Core\abstractController;
use wMVC\Core\Router;
use wMVC\Entity\SxGeo;

Class Index extends abstractController
{
    public function main()
    {

        $regions = RegionQuery::create()->findByArea(null);

        $regionsList = [];
        foreach ($regions as $region) {
            $name = $region->getName();
            $id = $region->getId();
            if ($region->getCount()) {
                $regionsList[$id] = ['name'       => $name,
                                     'children'   => [],
                                     'firm_count' => $region->getCount(),
                                     'url'        => $region->getUrl()];
                $regionsSort[$id] = $name;
            }
        }

        $cities = RegionQuery::create()->find();
        foreach ($cities as $city) {
            if ($city->getArea()) {
                $regionsList[$city->getArea()]['children'][] = $city->getId();
            }
        }

        array_multisort($regionsSort, SORT_ASC, $regionsList);

//        foreach ($regionsList as $id => $region) {
//            $regionsList[$id]['firm_count'] =;
//            print '<br>';
//        }


//        print_r($regionsList);

        print $this->view->render(
            "@index/front.twig", [
                'head'        =>
                    [
                        'title'       => 'Твоя Фирма ― Справочник компаний России с полными реквизитами',
                        'description' => 'Твоя Фирма ― Справочник компаний России с полными реквизитами: телефоны, адреса, ИНН, ОГРН, сайты организаций и время работы.',
                        'keywords'    => 'фирма, адреса, телефоны, сайты предприятий, ИНН, ОГРН, Россия, справочник, список организаций России, города России, предприятия, график работы'
                    ],
                'header'      =>
                    [
                        'placeholder' => 'Введите название города',
                        'frontPage'   => true,
                    ],
                'regionList'  => $regionsList,
                'redirect'    => $this->ipRedirect()
            ]
        );
    }

    public function region($customparam)
    {
        print_r(func_get_args());

//        print 'success' . $customparam;
//        $_SESSION['userId'] = 1;
    }

    public function city($params, $catss)
    {
        print 'city: ' . $params . $catss;
    }

    private function ipRedirect()
    {

        $sx = new SxGeo(SX_GEO_PATH);
        if (!empty($_SERVER['HTTP_REFERER'])
            && mb_strpos($_SERVER['HTTP_REFERER'], Config::$site_url_puny) !== false
        ) {
            return false;
        }
        $user_city = $sx->getCity($_SERVER['REMOTE_ADDR']);
        if (!$user_city) {
            return false;
        }
        if (empty($user_city['city']['name_ru'])) {
            return false;
        }
        $user_city = RegionQuery::create()->findOneByName($user_city['city']['name_ru']);
        if ($user_city) {
            return 'https://' . MAIN_DOMAIN . '/' . $user_city->getUrl();
        }

        return false;
    }

    public function regionsList()
    {
        static $REDIS_KEY_TEMPLATE = __METHOD__ . '_template';
        
        $redisTmp = $this->cache->myget($REDIS_KEY_TEMPLATE);
        if ($redisTmp) {
            print $redisTmp;
            return;
        }

        $regions = RegionQuery::create()->addAscendingOrderByColumn('name')->find();

        $cityArray = [];
        $regionsArray = [];
        $cityArrayNew = [];
        $json_regions_array = [];
        foreach ($regions as $region) {
            if ($region->getArea()) {
                $cityArray[$region->getArea()][mb_substr($region->getName(), 0, 1)][] = [
                    'name' => $region->getName(),
                    'url' => $region->getUrl(),
                    'count' => $region->getCount()
                ];
            } else {
                $regionsArray[$region->getId()] = $region->getName();
            }
            $json_regions_array[] = ['name' => $region->getName(), 'url' => $region->getUrl()];
        }

        foreach ($cityArray as $key => &$item) {
            ksort($item);
            $cityArrayNew[$regionsArray[$key]] = $item;
        }

        ksort($cityArrayNew);
//        array_multisort($cityArray, $regionsArray, SORT_ASC);

        $redisTmp = $this->view->render(
            '@ajax/regions_list.twig',
            ['regions' => $cityArrayNew, 'json' => json_encode($json_regions_array)]
        );

        $this->cache->myset($REDIS_KEY_TEMPLATE, $redisTmp);

        print $redisTmp;
    }

    public function adblock()
    {
        print $this->view->render('@ajax/adblock.twig');
    }

    public function adblock_mobile()
    {
        print $this->view->render('@ajax/adblock_mobile.twig');
    }

    public function add_homescreen()
    {
        print $this->view->render('@ajax/add_homescreen.twig');
    }

    public function add_homescreen_mobile()
    {
        print $this->view->render('@ajax/add_homescreen_mobile.twig');
    }

    public function add_homescreen_ipad()
    {
        print $this->view->render('@ajax/add_homescreen_ipad.twig');
    }

    public function add_homescreen_iphone()
    {
        print $this->view->render('@ajax/add_homescreen_iphone.twig');
    }

    public function search()
    {
        print $this->view->render(
            '@index/gsearch.twig', [
                'google_search' => Config::$google_search
            ]
        );
    }

    public function automaticTest()
    {
        if(!DEBUG) {
            if ($_POST['key'] !== 'A15agU07ZyJQUrBjltzA3TyUqImC8De1') {
                exit(json_encode(['err' => 'INVALID KEY']));
            }
        }

        $con = Propel::getWriteConnection(FirmTableMap::DATABASE_NAME);
        $con->useDebug(true);

        $time = microtime(true);
        header('Content-type: application/json');

        $url_templates = [
            'regions' => 'https://%s/%s',                      // siteurl, region_url
            'cities'  => 'https://%s/%s',                      // siteurl, city_url
            'groups'  => 'https://%s%s',                       // siteurl, city_url, group_url
            'filterS' => 'https://%s%s?улица=%s',              // siteurl, city_url, group_url, street_url
            'filterD' => 'https://%s%s?район=%s',              // siteurl, city_url, group_url, district_url
            'keys'    => 'https://%s/%s/ключевое-слово/%s',    // siteurl, city_url, $key_url
            'firms'   => 'https://%s%s',                       // siteurl, firm->getAlias
            'prints'  => 'https://%s/формат/для-печати/%s',    // siteurl, firm->getId
        ];

        $limit = 5;
        if (isset($_POST['limit']) && is_numeric($_POST['limit'])) {
            $limit = intval($_POST['limit']);
        }
        $answer = [
            'regions' => [],
            'cities'  => [],
            'keys'    => [],
            'groups'  => [],
            'filters' => [],
            'firms'   => [],
            'prints'  => []
        ];


        // Главная
        $answer['main'] = 'https://' . Config::$site_url_puny;

        // Области
        $regions = RegionQuery::create()
            ->filterByArea(null, Criteria::ISNULL)
            ->addAscendingOrderByColumn('rand()')
            ->limit($limit)
            ->find();
        foreach ($regions as $region) {
            $answer['regions'][] = sprintf(
                $url_templates['regions'], Config::$site_url_puny, $region->getUrl()
            );
        }

        // Города
        $cities = RegionQuery::create()
            ->filterByArea(null, Criteria::NOT_EQUAL)
            ->addAscendingOrderByColumn('rand()')
            ->limit($limit)->find();

        foreach ($cities as $city) {
            $answer['cities'][] = sprintf($url_templates['cities'], Config::$site_url_puny, $city->getUrl());
        }

        // Ключи
        foreach ($cities as $city) {
            if (count($answer['keys']) == $limit) {
                break;
            }
            $tags = TagsQuery::create()
                ->useFirmTagsQuery()
                ->filterByCityId($city->getId())
                ->endUse()
                ->groupById()
                ->addAsColumn('count', 'COUNT(firm_tags.firm_id)')
                ->find();

//            $tags = FirmTagsQuery::create()
//                ->filterByCityId($city->getId())
//                ->groupByTagId()
//                ->addAsColumn('count', 'COUNT(firm_tags.firm_id)')
//                ->find();


            foreach ($tags AS $tag) {
                $page_count = ceil($tag->getVirtualColumn('count') / 30);
                if (count($answer['keys']) < $limit) {
                    if ($page_count > 1) {
                        $answer['keys'][] = sprintf(
                            $url_templates['keys'], Config::$site_url_puny, $city->getUrl(),
                            $tag->getUrl() . '/стр/2'
                        );
                    } else {
                        $answer['keys'][] = sprintf(
                            $url_templates['keys'], Config::$site_url_puny, $city->getUrl(), $tag->getUrl()
                        );
                    }
                } else {
                    break;
                }
            }
        }

        // Рубрики
        $root_categories = GroupQuery::create()->findByParent(0);
        $answer['roots'] = [];
        $big_cities = ['Екатеринбург', 'Москва', 'Новосибирск', 'Красноярск', 'Санкт-Петербург'];
        shuffle($big_cities);
        foreach ($root_categories as $root) {
            $answer['roots'][] = sprintf($url_templates['groups'], Config::$site_url_puny, '/' . array_shift($big_cities) . '/' . $root->getUrl());
            if (count($answer['roots']) >= $limit)
                break;
        }

        foreach ($cities as $city) {
            if (count($answer['filters']) == $limit) {
                break;
            }

            $categories = [];

            if (0) {
                
            } else {
                $subs = FirmGroupQuery::create()
                    ->filterByCity($city->getId())
                    ->groupByGroupId()
                    ->addAsColumn("count", "COUNT(firm_id)")
                    ->find();

                //        $this->cache->set('abit', array('1','2','3'));

                $counters = [];


                GroupQuery::create()->find(); // кэшируем в памяти коллекцию групп

                //считаем рога и копыта
                foreach ($subs as $sub) {
                    if ($sub_group = $sub->getGroup()) {
                        if ($sub_group->getLevel() == 3) {
                            if (isset($counters[$sub_group->getRoot()->getId()][$sub_group->getParent()])) {
                                $counters[$sub_group->getRoot()->getId()][$sub_group->getParent()]
                                    += $sub->getVirtualColumns()['count'];
                                continue;
                            }
                            $counters[$sub_group->getRoot()->getId()][$sub_group->getParent()]
                                = $sub->getVirtualColumns()['count'];
                        }
                        if ($sub->getGroup()->getLevel() == 2) {
                            if (isset($counters[$sub_group->getRoot()->getId()][$sub->getGroupId()])) {
                                $counters[$sub_group->getRoot()->getId()][$sub->getGroupId()]
                                    += $sub->getVirtualColumns()['count'];
                                continue;
                            }
                            $counters[$sub_group->getRoot()->getId()][$sub->getGroupId()]
                                = $sub->getVirtualColumns()['count'];
                        }
                    }
                }
            }

            if (0) {

            } else {
                //считаем отзывы
                $subs_reviews = FirmGroupQuery::create()
                    ->addJoin(FirmGroupTableMap::COL_FIRM_ID, CommentTableMap::COL_FIRM_ID)
                    ->filterByCity($city->getId())
                    ->addGroupByColumn('comment.id')
                    ->find();

                $review_counters = [];

                foreach ($subs_reviews as $sub) {
                    if ($sub_group = $sub->getGroup()) {
                        if ($sub_group->getLevel() == 3) {
                            if (isset(
                                $review_counters[$sub_group->getRoot()->getId()][$sub_group->getParent()]
                            )) {
                                $review_counters[$sub_group->getRoot()->getId()][$sub_group->getParent()]
                                    += 1;
                                continue;
                            }
                            $review_counters[$sub_group->getRoot()->getId()][$sub_group->getParent()] = 1;
                        }
                        if ($sub->getGroup()->getLevel() == 2) {
                            if (isset($review_counters[$sub_group->getRoot()->getId()][$sub->getGroupId()])) {
                                $review_counters[$sub_group->getRoot()->getId()][$sub->getGroupId()] += 1;
                                continue;
                            }
                            $review_counters[$sub_group->getRoot()->getId()][$sub->getGroupId()] = 1;
                        }
                    }
                }
            }

            $categories_cache_key = 'categories_for_city_' . $city->getId();
            if (0) {
                
            } else {
                $root_sort_company_count = [];
                foreach ($root_categories as $item) {
                    if (array_key_exists($item->getId(), $counters)) {
                        $sub_categories = [];

                        $children = GroupQuery::create()->findByParent($item->getId());

                        $child_sort_company_count = [];
                        foreach ($children as $group) {
                            if (array_key_exists($group->getId(), $counters[$item->getId()])) {
                                $children_company_count = $counters[$item->getId()][$group->getId()];
                                $children_review_count = isset(
                                    $review_counters[$item->getId()][$group->getId()]
                                )
                                    ? $review_counters[$item->getId()][$group->getId()] : 0;
                                $sub_categories[] = ['company_count' => $children_company_count,
                                                     'review_count'  => $children_review_count,
                                                     'name'          => $group->getName(),
                                                     'id'            => $group->getId(),
                                                     'url'           => '/' . $city->getUrl() . '/' . $group->getUrl()];
                                $child_sort_company_count[] = $children_company_count;
                            }
                        }
                        array_multisort($child_sort_company_count, SORT_DESC, $sub_categories);

                        $root_company_count = array_sum($counters[$item->getId()]);
                        $categories[] = ['name'          => $item->getName(),
                                         'company_count' => $root_company_count,
                                         'review_count'  => isset($review_counters[$item->getId()])
                                             ? array_sum(
                                                 $review_counters[$item->getId()]
                                             ) : 0,
                                         'children'      => $sub_categories];

                        $root_sort_company_count[] = $root_company_count;
                    }
                }
                array_multisort($root_sort_company_count, SORT_DESC, $categories);

            }

            if (isset($categories[0]) && isset($categories[0]['children'][0])) {
                $grp = $categories[0]['children'][0];
                $grp_url = $grp['url'];
                if ($grp['company_count'] > 41) {
                    $grp_url = $grp['url'] . (mt_rand(0, 1) ? '/стр/2' : '');
                }

                $streets = FirmQuery::create()
                    ->useFirmGroupQuery()
                    ->filterByCity($city->getId())
                    ->filterByGroupId($grp['id'])
                    ->endUse()
                    ->select('street')
                    ->distinct()
                    ->orderByStreet()
                    ->find()
                    ->toArray();

                foreach ($streets as $value) {
                    $street = $value;
                    if (!empty($value)) {
                        $answer['filters'][] = sprintf($url_templates['filterS'], Config::$site_url_puny, $grp['url'], $street);
                        break;
                    }
                }

                $districts = DistrictQuery::create()
                    ->useFirmQuery()
                    ->filterByActive(1)
                    ->useFirmGroupQuery()
                    ->filterByCity($city->getId())
                    ->filterByGroupId($grp['id'])
                    ->endUse()
                    ->endUse()
                    ->distinct()
                    ->orderByName()
                    ->find()
                    ->toArray();
                foreach ($districts as $d) {
                    if (!empty($d['name'])) {
                        $answer['filters'][] = sprintf($url_templates['filterD'], Config::$site_url_puny, $grp['url'], $d['name']);
                    }
                }

                $answer['groups'][] = sprintf($url_templates['groups'], Config::$site_url_puny, $grp_url);
            }

        }


        $randomize = mt_rand(100, 9999);


        // Фирмы
        $firms = FirmQuery::create()
            ->filterById('%' . $randomize, ModelCriteria::LIKE)
//            ->addAscendingOrderByColumn('rand()')
            ->limit($limit)
            ->find();
        foreach ($firms as $firm) {
            $answer['firms'][] = sprintf($url_templates['firms'], Config::$site_url_puny, $firm->getAlias());
        }

        // Печать
        $prints = FirmQuery::create()
            ->filterById('%' . $randomize, ModelCriteria::LIKE)
//            ->addAscendingOrderByColumn('rand()')
            ->limit($limit)
            ->find();
        foreach ($prints as $print) {
            $answer['prints'][] = sprintf($url_templates['prints'], Config::$site_url_puny, $print->getId());
        }

        // Одиночные страницы
        $answer['add'] = 'https://' . Config::$site_url_puny
            . '/%D0%B4%D0%BE%D0%B1%D0%B0%D0%B2%D0%B8%D1%82%D1%8C-%D0%BA%D0%BE%D0%BC%D0%BF%D0%B0%D0%BD%D0%B8%D1%8E';
        $answer['login'] = 'https://' . Config::$site_url_puny
            . '/%D0%BF%D1%80%D0%BE%D1%84%D0%B8%D0%BB%D1%8C/%D0%B2%D1%85%D0%BE%D0%B4';
        $answer['feedback'] = 'https://' . Config::$site_url_puny
            . '/%D0%BD%D0%B0%D0%BF%D0%B8%D1%81%D0%B0%D1%82%D1%8C-%D0%BD%D0%B0%D0%BC';
        $answer['agree'] = 'https://' . Config::$site_url_puny
            . '/%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D1%81%D0%BA%D0%BE%D0%B5-%D1%81%D0%BE%D0%B3%D0%BB%D0%B0%D1%88%D0%B5%D0%BD%D0%B8%D0%B5';

        $answer['polit'] = 'https://' . Config::$site_url_puny . '/polit.pdf';
        $answer['confirm'] = 'https://' . Config::$site_url_puny . '/confirm.pdf';


        exit(json_encode($answer));
    }
}
