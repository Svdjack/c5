<?php

namespace wMVC\Controllers;

use Propel\Runtime\ActiveQuery\Criteria;
use PropelModel\Base\GroupQuery;
use PropelModel\CommentQuery;
use PropelModel\FirmGroupQuery;
use PropelModel\FirmQuery;
use PropelModel\Map\CommentTableMap;
use PropelModel\Map\FirmGroupTableMap;
use PropelModel\RegionQuery;
use wMVC\Core\abstractController;
use wMVC\Core\Exceptions\SystemExit;
use wMVC\Entity\Breadcrumb;
use PropelModel\Region as RegionModel;

Class Region extends abstractController
{
    public function dispatch($region)
    {
        $reg = RegionQuery::create()->findOneByUrl($region);

        if (!$reg) {
            throw new SystemExit('', 404);
        }
        if ($reg->getArea()) {
            $this->city($reg);
            return;
        }
        $this->region($reg);
        return;
    }

    private function city(RegionModel $city)
    {
        $redisKey = '@region/city/' . $city->getId();
        if (!DEBUG && $redisCache = $this->cache->myget($redisKey)) {
            print $redisCache . '<!-- Redis city -->';
            return;
        }

        $region = RegionQuery::create()->findPk($city->getArea());
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs = $breadcrumbs->addCrumb('Россия', '/')
            ->addCrumb($region->getName(), '/' . $region->getUrl(), $region->getName())
            ->addCrumb($city->getName())
            ->getArray();


        $categories = [];
        //генерим список рутовых

        $root_categories = GroupQuery::create()->findByParent(0);

        if (0) {
            
        } else {
            $subs = FirmGroupQuery::create()
                ->filterByCity($city->getId())
                ->groupByGroupId()
                ->addAsColumn("count", "COUNT(firm_id)")
                ->find();

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
                        if (isset($review_counters[$sub_group->getRoot()->getId()][$sub_group->getParent()])) {
                            $review_counters[$sub_group->getRoot()->getId()][$sub_group->getParent()] += 1;
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

        if (0) {
            $categories = unserialize($categories_cached);
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
                            $children_review_count = isset($review_counters[$item->getId()][$group->getId()])
                                ? $review_counters[$item->getId()][$group->getId()] : 0;
                            $sub_categories[] = ['company_count' => $children_company_count,
                                                 'review_count'  => $children_review_count,
                                                 'name'          => $group->getName(),
                                                 'url'           => '/' . $city->getUrl() . '/' . $group->getUrl()];
                            $child_sort_company_count[] = $children_company_count;
                        }
                    }
                    array_multisort($child_sort_company_count, SORT_DESC, $sub_categories);

                    $root_company_count = array_sum($counters[$item->getId()]);
                    $categories[] = ['name'          => $item->getName(),
                                     'company_count' => $root_company_count,
                                     'review_count'  => isset($review_counters[$item->getId()]) ? array_sum(
                                         $review_counters[$item->getId()]
                                     ) : 0,
                                     'children'      => $sub_categories];

                    $root_sort_company_count[] = $root_company_count;
                }
            }
            array_multisort($root_sort_company_count, SORT_DESC, $categories);

        }

        if (0) {
            
        } else {
            $latest_companies = [];
            $company_query = FirmQuery::create()->filterByCityId($city->getId())->orderById(Criteria::DESC)
                ->limit(4)->find();

            foreach ($company_query as $cmp) {
                $latest_companies[] = ['title' => $cmp->getName(),
                                       'url'   => $cmp->getAlias()];
            }
        }


//        print_r($counters);

        $regionArray = ['prefix' => $city->getPrefix(),
                        'case5'  => $city->getCase(5),
                        'name'   => $city->getName(),
                        'list'   => []];
        foreach (
            RegionQuery::create()->addAscendingOrderByColumn('url')->filterByArea($region->getId())->find() as
            $item
        ) {
            $regionArray['list'][] = ['url' => $item->getUrl(), 'name' => $item->getName(),
                                      'alt' => $item->getCase(3)];
        }        

        $keywords = $city->getName() . ', справочник ' . $city->getCase(1) . ', ИНН, ОГРН,
         список организаций ' . $city->getCase(1) . ', города ' . $region->getCase(1) . ',
          карта, предприятия ' . $city->getCase(1) . ', фирмы, адреса, телефоны, сайты предприятий';
        $description = 'Справочник компаний ' . $city->getCase(1)
            . ' ― справочник организаций, предприятий, фирм с реквизитами: 
        адрес, телефон, ИНН, ОГРН, сайт компании.';


        $tpl = $this->view->render(
            "@region/city.twig", ['head'             =>
                                      [
                                          'title'       => 'Справочник компаний ' . $city->getCase(1) .
                                              ' — организации, предприятия, фирмы',
                                          'keywords'    => $keywords,
                                          'description' => $description
                                      ],
                                  'city'             =>
                                      [
                                          'prefix' => $city->getPrefix(),
                                          'case5'  => $city->getCase(5),
                                          'case1'  => $city->getCase(1),
                                          'lat'    => $city->getLat(),
                                          'lon'    => $city->getLon(),
                                          'url'    => $city->getUrl()
                                      ],
                                  'region'           =>
                                      [
                                          'case1' => $region->getCase(1)
                                      ],
                                  'header'           =>
                                      [
                                          'region'      => $regionArray,
                                          'placeholder' => 'Искать в ' . $city->getCase(5),
                                          'cityUrl'     => $city->getUrl(),
                                          'cityPage'  => true,
                                      ],
                                  'breadcrumbs'      => $breadcrumbs,
                                  'title_case'       => $city->getCase(1),
                                  'company_count'    => $city->getCount(),
                                  'root_rubrics'     => $categories,
                                  'latest_companies' => $latest_companies,
            ]
        );
        
        if (!DEBUG) {
            $this->cache->myset($redisKey, $tpl);
        }

        print $tpl;
    }

    private function region(RegionModel $region)
    {
        $regionInfo = $region->toArray();
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs = $breadcrumbs->addCrumb('Россия', '/')
            ->addCrumb($region->getName())
            ->getArray();

        foreach ([1, 2, 3, 4, 5] as $num) {
            $regionInfo['cases'][$num] = $region->getCase($num);
        }

        $cities = RegionQuery::create()->addDescendingOrderByColumn('count')->findByArea($region->getId());
        $citiesList = [];
        $cty = [];
        foreach ($cities as $n => $city) {
            $n == 0 && $cty = $city;
            $citiesList[] = ['name'      => $city->getName(),
                             'name_case' => $city->getCase(1),
                             'count'     => $city->getCount(),
                             'coords'    => $city->getLat() . ', ' . $city->getLon(),
                             'url'       => $city->getUrl()
            ];
        }

        if (count($citiesList) === 0) {
            throw new SystemExit('', 404);
        }

        $regionArray = ['prefix' => $region->getPrefix(),
                        'case5'  => $region->getCase(5),
                        'name'   => $region->getName(),
                        'list'   => []];
        foreach (RegionQuery::create()->addAscendingOrderByColumn('url')->filterByArea()->find() as $item) {
            $regionArray['list'][] = ['url' => $item->getUrl(), 'name' => $item->getName(),
                                      'alt' => $item->getCase(3)];
        }

        $vars = ['head'        =>
                     [
                         'title'       => 'Твоя Фирма ― Справочник компаний ' . $region->getCase(1)
                             . ' — адреса, телефоны, ИНН, ОГРН',
                         'description' => 'Твоя Фирма ― Справочник компаний ' . $region->getCase(1)
                             . ', в котором вы сможете найти информацию о предприятии или фирме: адрес, телефон, сайт организации, ИНН, ОГРН.',
                         'keywords'    => 'фирмы, адреса, телефоны, сайты предприятий, ИНН, ОГРН, '
                             . $region->getName()
                             . ', справочник, список организаций ' . $region->getCase(1) . ', города ' .
                             $region->getCase(1) . ', предприятия'
                     ],
                 'header'      =>
                     [
                         'region'      => $regionArray,
                         'placeholder' => 'Искать в ' . $regionArray['case5'],
                         'regionPage'  => true,
                     ],
                 'breadcrumbs' => $breadcrumbs,
                 'main_region' => $regionInfo,
                 'citiesList'  => $citiesList,
                 'city'        => $cty
        ];

        print $this->view->render("@region/region.twig", $vars);
    }
}