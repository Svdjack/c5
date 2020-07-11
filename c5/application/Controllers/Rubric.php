<?php
namespace wMVC\Controllers;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
use PropelModel\CommentQuery;
use PropelModel\ContactQuery;
use PropelModel\District;
use PropelModel\DistrictQuery;
use PropelModel\Firm;
use PropelModel\FirmGroup;
use PropelModel\FirmGroupQuery;
use PropelModel\FirmQuery;
use PropelModel\FirmTagsQuery;
use PropelModel\GroupQuery;
use PropelModel\Map\CommentTableMap;
use PropelModel\Map\FirmGroupTableMap;
use PropelModel\Map\FirmTableMap;
use PropelModel\Map\GroupTableMap;
use PropelModel\RegionQuery;
use PropelModel\TagsQuery;
use PropelModel\UrlAliasesQuery;
use wMVC\Core\abstractController;
use wMVC\Core\Exceptions\SystemExit;
use wMVC\Entity\Breadcrumb;
use wMVC\Entity\Lang;

Class Rubric extends abstractController
{
    private static $SORT_TYPE = ['по-алфавиту', 'по-рейтингу'];

    public function withSort($region_url, $group_url, $sort_type, $page = null)
    {
        if (!in_array($sort_type, self::$SORT_TYPE)) {
            throw new SystemExit('wrong sort param', 404);
        }

        if ($page == 1) {
            header('Location: /' . $region_url . '/' . $group_url . '/' . $sort_type);
            die();
        }
        $this->main($region_url, $group_url, $page, $sort_type);
    }

    public function main($region_url, $group_url, $page = null, $sort_type = null)
    {
        $get = $_GET;
        $redisKey = '@catalog/rubric/' . $region_url . '/' . $group_url . '/' . $page . '/' . $sort_type . '?' . \md5(\print_r($get, true));
        
        if (!DEBUG && $redisCache = $this->cache->myget($redisKey)) {
            print $redisCache . '<!-- Redis rubric main -->';
            return;
        }
        
        if ($page == 1) {
            header('Location: /' . $region_url . '/' . $group_url);
            die();
        }
        if ($page == null) {
            $page = 1;
        }
        if (!$city = RegionQuery::create()->findOneByUrl($region_url)) {
            throw new SystemExit('', 404);
        }
        if (!$group = GroupQuery::create()->findOneByUrl($group_url)) {
            throw new SystemExit('', 404);
        }
        $is_root = 0;
        if ($group->getLevel() == 1) {
            $is_root = 1;
        }
        $region = RegionQuery::create()->findPk($city->getArea());
        if ($group->getLevel() == 3) {
            $parentGroup = GroupQuery::create()->findPk($group->getParent());
            header('Location: /' . $region_url . '/' . $parentGroup->getUrl());
            die();
        }

        GroupQuery::create()->find(); // кэшируем в памяти коллекцию групп

        $street = $district_name = $district = '';

        $filter_type = false;

        if(!empty($get['район'])){
            $district_name = $get['район'];
            $filter_type = 'district';

        }
        if(!empty($get['улица'])){
            $street = $get['улица'];
            $filter_type = 'street';

        }

        if($filter_type == 'district'){
            $district = DistrictQuery::create()->findOneByName($district_name);
            if(!$district){
                throw new SystemExit('there no crap like this', 404);
            }
        }


        $group_ids = $group->getTreeIds();

        $firms = FirmQuery::create()
            ->filterByActive(1)
            ->filterByStatus(1)
            ->joinFirmUp()
            ->orderBy('FirmUp.Status', Criteria::DESC)
            ->orderBy('FirmUp.TimeStart', Criteria::DESC)
            ->orderBy('FirmUp.FirmId', Criteria::DESC)
            ->addAsColumn('Up', 'FirmUp.Status')
            ->useFirmGroupQuery()
            ->filterByCity($city->getId())
            ->filterByGroupId($group_ids)
            ->endUse();

        $districts = DistrictQuery::create()
            ->useFirmQuery()
            ->filterByActive(1)
            ->useFirmGroupQuery()
            ->filterByCity($city->getId())
            ->filterByGroupId($group_ids)
            ->endUse()
            ->endUse()
            ->distinct()
            ->orderByName()
            ->find();

        $streets = FirmQuery::create()
            ->useFirmGroupQuery()
            ->filterByCity($city->getId())
            ->filterByGroupId($group_ids)
            ->endUse()
            ->select('street')
            ->distinct()
            ->orderByStreet()
            ->find()
            ->toArray();

        switch ($sort_type) {
            case self::$SORT_TYPE[0]:
                $firms = $firms->addAscendingOrderByColumn('name');
                break;

            case self::$SORT_TYPE[1]:
                $firms = $firms->addJoin(FirmTableMap::COL_ID, CommentTableMap::COL_FIRM_ID, 'LEFT JOIN')
                    ->groupById()
                    ->addAsColumn('count', 'AVG(comment.score)')
                    ->addDescendingOrderByColumn('count');
                break;

            default:
                break;
        }

        switch ($filter_type){
            case 'district':
                $firms = $firms->filterByDistrict($district);
                break;

            case 'street':
                $firms = $firms->filterByStreet($street);
                break;

            default:
                break;
        }

        $firms = $firms->addDescendingOrderByColumn('id')->paginate($page, 30);

        if ($firms->getLastPage() < $page && $firms->haveToPaginate()) {
            throw new SystemExit('too much', 404);
        }

        $root_cat = $group->getRoot();

        $breadcrumbs = new Breadcrumb();
        $breadcrumbs = $breadcrumbs->addCrumb('Россия', '/')
            ->addCrumb($region->getName(), '/' . $region->getUrl(), $region->getName())
            ->addCrumb($city->getName(), '/' . $city->getUrl(), $city->getName())
            ->addCrumb($root_cat->getName(), '/' . $city->getUrl() . '/' . $root_cat->getUrl(), $root_cat->getName());

        if($street){
            $breadcrumbs = $breadcrumbs->addCrumb(
                $group->getName() . ' на улице '.trim(str_replace('ул.', '', $street)),
                '/' . $city->getUrl() . '/' . $group->getUrl() . '?улица='.$street,
                $group->getName() . ' на улице '.trim(str_replace('ул.', '', $street))
            );
        }elseif($district){
            $breadcrumbs = $breadcrumbs->addCrumb(
                $group->getName() . ' ' . $district->getCase(2) . ' района',
                '/' . $city->getUrl() . '/' . $group->getUrl() . '?район='.$district->getName(),
                $group->getName() . ' ' . $district->getCase(2) . ' района'
            );
        }else{
            $breadcrumbs = $breadcrumbs->addCrumb(
                $group->getName() . ' ' . $city->getPrefix() . ' ' . $city->getCase(5),
                '/' . $city->getUrl() . '/' . $group->getUrl(),
                $group->getName() . ' ' . $city->getPrefix() . ' ' . $city->getCase(5)
            );
        }

        if ($page != 1) {
            $breadcrumbs->addCrumb('Страница ' . $page);
        }

        $breadcrumbs = $breadcrumbs->getArray();


        if ($is_root) {
            unset($breadcrumbs[3]);
        }

        if ($is_root && $page != 1) {
            throw new SystemExit('there should be no pagination on root category', '404');
        }

        $firm_ids = $firm_sources = [];


        $firms_array = [];
        $firms_ids_array = [];
        $firms_objects_array = [];
        foreach ($firms as $firm) {
            $firms_objects_array[] = $firm;
            $firms_ids_array[] = $firm->getId();
            if ($is_root && count($firms_objects_array) == 7) {
                break;
            }
        }

//        CommentQuery::create()
//            ->filterByFirm($this)
//            ->findOne();
        $comments = CommentQuery::create()
            ->filterByFirmId($firms_ids_array)
            ->addAsColumn('average_score', 'AVG(score)')
            ->addAsColumn('score_count', 'COUNT(id)')
            ->groupByFirmId()
            ->find()
            ->toArray('firmId');

        $phones = ContactQuery::create()
            ->filterByFirmId($firms_ids_array)
            ->filterByType('phone')
            ->groupByFirmId()
            ->find()
            ->toArray('firmId');

        $websites = ContactQuery::create()
            ->filterByFirmId($firms_ids_array)
            ->filterByType('website')
            ->groupByFirmId()
            ->find()
            ->toArray('firmId');

        $aliases = UrlAliasesQuery::create()
            ->where('source IN ("/firm/show/' . implode('", "/firm/show/', $firms_ids_array) . '")')
            ->find()
            ->toArray('source');

        Propel::disableInstancePooling();
        $groups = GroupQuery::create()
            ->addJoin(GroupTableMap::COL_ID, FirmGroupTableMap::COL_GROUP_ID)
            ->where('firm_group.firm_id IN (' . implode(',', $firms_ids_array) . ')')
            ->filterByLevel(3)
            ->addAsColumn('FirmId', 'firm_group.firm_id')
            ->find();
        Propel::enableInstancePooling();

        $occupation_array = [];
        foreach ($groups as $levelGroup) {
////            print "{$levelGroup['firmid']}<br/>";
            if (isset($occupation_array[$levelGroup->getFirmId()])) {
                $occupation_array[$levelGroup->getFirmId()] .= ', ' . $levelGroup->getName();
                continue;
            }
            $occupation_array[$levelGroup->getFirmId()] = $levelGroup->getName();
        }


        /** @var Firm $firm */
        foreach ($firms_objects_array as $firm) {
//            $firm_groups = $firm->getGroups();
            $occupation = '';
            if (isset($occupation_array[$firm->getId()])) {
                $occupation = mb_strtolower(trim($occupation_array[$firm->getId()], " ,"));
            }
            $phone = '';
            $website = '';
            if (isset($phones[$firm->getId()])) {
                $phone = $phones[$firm->getId()]['Value'];
            }
            if (isset($websites[$firm->getId()])) {
                $website = ['id' => $websites[$firm->getId()]['id'],
                            'name' => $websites[$firm->getId()]['Value']];
                $website['name'] = str_replace(['http://', 'https://'], '', $website['name']);
                $website['name'] = 'http://' . idn_to_utf8($website['name'], 0, INTL_IDNA_VARIANT_UTS46);
            }

            $review = [
            ];

            if (isset($comments[$firm->getId()])) {
                $review = [
                    'count'   => $comments[$firm->getId()]['score_count'],
                    'average' => $comments[$firm->getId()]['average_score']
                ];
            }

            $url = "/firm/show/" . $firm->getId();

            if (isset($aliases["/firm/show/" . $firm->getId()])) {
                $url = $aliases["/firm/show/" . $firm->getId()]['Alias'];
            } else {
                $url = $firm->getAlias();
            }

            $firms_array[] = [
                'array'        => $firm->toArray(),
                'name'         => $firm->getName(),
                'url'          => $url,
                'full_address' => 'г. ' . $city->getName() . ', ' . $firm->getStreet() . ', '
                    . $firm->getHome(),
                'phone'        => $phone,
                'website'      => $website,
                'occupation'   => $occupation,
                'cords'        =>
                    [
                        'lat' => $firm->getLat(),
                        'lon' => $firm->getLon()
                    ],
                'region'       => $region->getName(),
                'city'         => $city->getName(),
                'address'      => $firm->getStreet() . ', ' . $firm->getHome(),
                'zip'          => $firm->getPostal(),
                'review'       => $review
            ];

            if ($is_root && count($firms_array) == 7) {
                break;
            }
        }

        $subs = [];
        $counters_order = [];

        $totes_counter = 0;
        if ($is_root) {
            $review_counters = [];
            $subs_reviews = FirmGroupQuery::create()
                ->addJoin(FirmGroupTableMap::COL_FIRM_ID, CommentTableMap::COL_FIRM_ID)
                ->filterByCity($city->getId())
                ->addGroupByColumn('comment.id')
                ->find();
            foreach ($subs_reviews as $sub) {
                if ($sub_group = $sub->getGroup()) {
                    if ($sub_group->getLevel() == 3) {
                        if (isset($review_counters[$sub_group->getRoot()->getId()][$sub_group->getParent(
                            )])) {
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

            $rubs = GroupQuery::create()->findByParent($group->getId());


            $firm_counters = FirmGroupQuery::create()->filterByCity($city->getId())
                ->withColumn('COUNT(*)', 'Count')
                ->select(['GroupId', 'Count'])
                ->filterByGroupId($group->getTreeIds())
                ->groupByGroupId()
                ->find()
                ->toArray('GroupId');

            foreach ($rubs as $rub) {
                $subrubrics = $rub->getTreeIds();
                $this_counter = 0;
                foreach ($subrubrics as $seb) {
                    if (!empty($firm_counters[$seb]['Count'])) {
                        $this_counter += $firm_counters[$seb]['Count'];
                    }
                }
                if ($this_counter) {
                    $totes_counter += $this_counter;
                    $subs[] = [
                        'link'         => '/' . $city->getUrl() . '/' . $rub->getUrl(),
                        'name'         => $rub->getName(),
                        'namev'        => $rub->getName() . ' '
                            . $city->getPrefix() . ' '
                            . $city->getCase(5),
                        'count_firm'   => $this_counter,
                        'count_review' => !empty($review_counters[$group->getId()][$rub->getId()])
                            ? $review_counters[$group->getId()][$rub->getId()] : 0
                    ];

                    $counters_order[] = $this_counter;
                }
            }
            array_multisort($counters_order, SORT_DESC, $subs);
        }

        $pagination = [];
        if ($firms->haveToPaginate()) {
            $pagination = $firms->getLinks(5);
            if ($page != 1) {
                if (!in_array(1, $pagination)) {
                    array_unshift($pagination, 'ellipsis');
                }
                array_unshift($pagination, 'first', 'prev');
            }
            if ($page != $firms->getLastPage()) {
                if (!in_array($firms->getLastPage(), $pagination)) {
                    array_push($pagination, 'ellipsis');
                }
                array_push($pagination, 'next', 'last');
            }

            $pagination = [
                'pages'        => $pagination,
                'last_page'    => $firms->getLastPage(),
                'prev_page'    => $firms->getPreviousPage(),
                'next_page'    => $firms->getNextPage(),
                'current_page' => $page
            ];
        }

        $nearby_groups = $city->getNearbyGroup($group, $this->cache);

        $h1 = $group->getName() . ' ' . $city->getPrefix() . ' ' . $city->getCase(5);
        if($street){
            $h1 = $group->getName() . ' на улице ' . trim(str_replace('ул.','',$street)) . ' ' . $city->getPrefix() . ' ' . $city->getCase(5);
        }elseif($district){
            $h1 = $group->getName() . ' в ' . $district->getCase(6) . ' районе ' . $city->getCase(1);
        }

        switch ($sort_type) {
            case self::$SORT_TYPE[0]:
                $title = $h1 . ' (отсортированы по алфавиту)  — адреса, телефоны, ИНН, ОГРН';
                break;

            case self::$SORT_TYPE[1]:
                $title = $h1 . ' (отсортированы по рейтингу)  — адреса, телефоны, ИНН, ОГРН';
                break;

            default:
                $title = $h1 . '  — адреса, телефоны, ИНН, ОГРН';
                break;
        }


        if ($page != 1) {
            $title .= " (страница {$page})";
        }

        $rubric_url_pagination = $rubric_url = '/' . $city->getUrl() . '/' . $group->getUrl();

        if ($sort_type) {
            $rubric_url_pagination = $rubric_url . '/' . $sort_type;
        }

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

        $keywords
            = 'карта, рубрика, фирмы, телефоны, ИНН, ОГРН, сайты предприятий, каталог предприятий, 
        ' . $city->getName() . ', ' . $group->getName() . ',
         ' . $group->getName() . ' ' . $city->getPrefix() . ' ' . $city->getCase(5) . ', адреса, список организаций 
         ' . $city->getCase(1);

        $description = $h1 . ' ― в данной 
        рубрике вы найдете: график работы, схема проезда, ИНН, ОГРН, адреса, телефоны';

        if (!$is_root) {
            $keywords = $group->getName() . ',
             ' . $group->getName() . ' ' . $city->getPrefix() . ' ' . $city->getCase(5) . ',
              адреса, ИНН, ОГРН, список организаций ' . $city->getCase(1) . ', карта, рубрика,
                 фирмы, телефоны, сайты предприятий, каталог предприятий, ' . $city->getName();
        }

        $category_description = '';
        if (!$is_root) {
            $category_description = $group->getLive();
            if ($category_description) {
                $category_description = str_replace("%sitename%", "ТвояФирма.рф", $category_description);
                $category_description = str_replace("%count%", $firms->getNbResults(), $category_description);
                $category_description = str_replace("%city[6]%", $city->getCase(5), $category_description);
                $category_description = str_replace("%city[2]%", $city->getCase(1), $category_description);
                $category_description = str_replace("%city[3]%", $city->getCase(2), $category_description);
                $category_description = str_replace("%city[2]", $city->getCase(1), $category_description);
                $category_description = str_replace("%city[1]%", $city->getName(), $category_description);
                $category_description = str_replace("%category%", $group->getName(), $category_description);
                $category_description = str_replace("%category-link%", $group->getName(), $category_description);

            }
        }
        
        $this->view->addGlobal('googleads', Lang::adsense_check_text($description) == 0);

        $tpl = $this->view->render(
            '@catalog/rubric.twig', [
                'breadcrumbs'           => $breadcrumbs,
                'h1'                    => $h1,
                'group_count'           => $firms->getNbResults(),
                'header'                =>
                    [
                        'placeholder' => 'Искать в ' . $city->getCase(5),
                        'region'      => $regionArray,
                        'cityUrl'     => $city->getUrl()
                    ],
                'head'                  =>
                    [
                        'title'       => $title,
                        'keywords'    => $keywords,
                        'description' => $description
                    ],
                'city_url'              => $city->getUrl(),
                'rubric_url'            => $rubric_url,
                'rubric_url_pagination' => $rubric_url_pagination,
                'firms'                 => $firms_array,
                'is_root'               => $is_root,
                'region_case1'          => $region->getCase(1),
                'city_prefix'           => $city->getPrefix(),
                'city_case5'            => $city->getCase(5),
                'group_name'            => $group->getName(),
                'group'                 => $group,
                'pagination'            => $pagination,
                'nearby_groups'         => $nearby_groups,
                'city_case1'            => $city->getCase(1),
                'city'                  => $city,
                'sort'                  => $sort_type,
                'subrubrics'            => $subs,
                'totes_counter'         => $totes_counter,
                'level_description'     => $category_description,
                'districts'             => $districts,
                'streets'               => $streets,
                'current_street'        => $street,
                'current_district'      => $district
            ]
        );
        
        if(!DEBUG) {
            $this->cache->myset($redisKey, $tpl);
        }

        print $tpl;
    }

    public function keyWithSort($region_url, $group_url, $sort_type, $page = null)
    {
        if (!in_array($sort_type, self::$SORT_TYPE)) {
            throw new SystemExit('wrong sort param', 404);
        }

        if ($page == 1) {
            header('Location: /' . $region_url . '/' . $group_url . '/' . $sort_type);
            die();
        }
        $this->mainKey($region_url, $group_url, $page, $sort_type);
    }

    public function mainKey($region_url, $group_url, $page = null, $sort_type = null)
    {
        $redisKey = '@catalog/key/' . $region_url . '/' . $group_url . '/' . $page . '/' . $sort_type;
        if (!DEBUG && $redisCache = $this->cache->myget($redisKey)) {
            print $redisCache . '<!-- Redis rubric mainKey -->';
            return;
        }
        
        if ($page == 1) {
            header('Location: /' . $region_url . '/' . $group_url);
            die();
        }
        if ($page == null) {
            $page = 1;
        }
        if (!$city = RegionQuery::create()->findOneByUrl($region_url)) {
            throw new SystemExit('', 404);
        }

        if (!$group = TagsQuery::create()->findOneByUrl($group_url)) {
            throw new SystemExit('', 404);
        }

        $get = $_GET;

        $street = $district_name = $district = '';

        $filter_type = false;

        if(!empty($get['район'])){
            $district_name = $get['район'];
            $filter_type = 'district';

        }
        if(!empty($get['улица'])){
            $street = $get['улица'];
            $filter_type = 'street';

        }

        if($filter_type == 'district'){
            $district = DistrictQuery::create()->findOneByName($district_name);
            if(!$district){
                throw new SystemExit('there no crap like this', 404);
            }
        }



        $firms = FirmQuery::create()
            ->filterByActive(1)
            ->filterByStatus(1)
            ->joinFirmUp()
            ->orderBy('FirmUp.Status', Criteria::DESC)
            ->orderBy('FirmUp.TimeStart', Criteria::DESC)
            ->orderBy('FirmUp.FirmId', Criteria::DESC)
            ->addAsColumn('Up', 'FirmUp.Status')
            ->useFirmTagsQuery()
            ->filterByTags($group)
            ->endUse()
            ->filterByCityId($city->getId());

        $districts = DistrictQuery::create()
            ->useFirmQuery()
            ->filterByActive(1)
            ->useFirmTagsQuery()
            ->filterByTags($group)
            ->endUse()
            ->filterByCityId($city->getId())
            ->endUse()
            ->distinct()
            ->find();

        $streets = FirmQuery::create()
            ->useFirmTagsQuery()
            ->filterByTags($group)
            ->endUse()
            ->filterByCityId($city->getId())
            ->select('street')
            ->distinct()
            ->find()
            ->toArray();

        switch ($sort_type) {
            case self::$SORT_TYPE[0]:
                $firms = $firms->addAscendingOrderByColumn('name');
                break;

            case self::$SORT_TYPE[1]:
                $firms = $firms->addJoin(FirmTableMap::COL_ID, CommentTableMap::COL_FIRM_ID, 'LEFT JOIN')
                    ->groupById()
                    ->addAsColumn('count', 'AVG(comment.score)')
                    ->addDescendingOrderByColumn('count');
                break;

            default:
                break;
        }

        switch ($filter_type){
            case 'district':
                $firms = $firms->filterByDistrict($district);
                break;

            case 'street':
                $firms = $firms->filterByStreet($street);
                break;

            default:
                break;
        }

        $firms = $firms->addDescendingOrderByColumn('id')->paginate($page, 30);

        if ($firms->getLastPage() < $page && $firms->haveToPaginate()) {
            throw new SystemExit('too much', 404);
        }

        $region = RegionQuery::create()->findPk($city->getArea());

        $breadcrumbs = new Breadcrumb();
        $breadcrumbs = $breadcrumbs->addCrumb('Россия', '/')
            ->addCrumb($region->getName(), '/' . $region->getUrl(), $region->getName())
            ->addCrumb($city->getName(), '/' . $city->getUrl(), $city->getName());

        if($street){
            $breadcrumbs = $breadcrumbs->addCrumb(
                $group->getTag() . ' на улице '.trim(str_replace('ул.', '', $street)),
                '/' . $city->getUrl() . '/ключевое-слово/' . $group->getUrl() . '?улица='.$street,
                $group->getTag() . ' ' . $city->getPrefix() . ' ' . $city->getCase(5)
            );
        }elseif($district){
            $breadcrumbs = $breadcrumbs->addCrumb(
                $group->getTag() . ' ' . $district->getCase(2) . ' района',
                '/' . $city->getUrl() . '/ключевое-слово/' . $group->getUrl() . '?район='.$district->getName(),
                $group->getTag() . ' ' . $city->getPrefix() . ' ' . $city->getCase(5)
            );
        }else{
            $breadcrumbs = $breadcrumbs->addCrumb(
                $group->getTag() . ' ' . $city->getPrefix() . ' ' . $city->getCase(5),
                '/' . $city->getUrl() . '/ключевое-слово/' . $group->getUrl(),
                $group->getTag() . ' ' . $city->getPrefix() . ' ' . $city->getCase(5)
            );
        }


        if ($page != 1) {
            $breadcrumbs->addCrumb('Страница ' . $page);
        }

        $breadcrumbs = $breadcrumbs->getArray();


//        if ($is_root && $page != 1) {
//            throw new SystemExit('there should be no pagination on root category', '404');
//        }

        $firm_ids = $firm_sources = [];


        $firms_array = [];
        $firms_ids_array = [];
        $firms_objects_array = [];
        foreach ($firms as $firm) {
            $firms_objects_array[] = $firm;
            $firms_ids_array[] = $firm->getId();
        }

//        CommentQuery::create()
//            ->filterByFirm($this)
//            ->findOne();
        $comments = CommentQuery::create()
            ->filterByFirmId($firms_ids_array)
            ->addAsColumn('average_score', 'AVG(score)')
            ->addAsColumn('score_count', 'COUNT(id)')
            ->groupByFirmId()
            ->find()
            ->toArray('firmId');

        $phones = ContactQuery::create()
            ->filterByFirmId($firms_ids_array)
            ->filterByType('phone')
            ->groupByFirmId()
            ->find()
            ->toArray('firmId');

        $websites = ContactQuery::create()
            ->filterByFirmId($firms_ids_array)
            ->filterByType('website')
            ->groupByFirmId()
            ->find()
            ->toArray('firmId');

        $aliases = UrlAliasesQuery::create()
            ->where('source IN ("/firm/show/' . implode('", "/firm/show/', $firms_ids_array) . '")')
            ->find()
            ->toArray('source');

        Propel::disableInstancePooling();
        $groups = GroupQuery::create()
            ->addJoin(GroupTableMap::COL_ID, FirmGroupTableMap::COL_GROUP_ID)
            ->where('firm_group.firm_id IN (' . implode(',', $firms_ids_array) . ')')
            ->filterByLevel(3)
            ->addAsColumn('FirmId', 'firm_group.firm_id')
            ->find();
        Propel::enableInstancePooling();

        $occupation_array = [];
        foreach ($groups as $levelGroup) {
////            print "{$levelGroup['firmid']}<br/>";
            if (isset($occupation_array[$levelGroup->getFirmId()])) {
                $occupation_array[$levelGroup->getFirmId()] .= ', ' . $levelGroup->getName();
                continue;
            }
            $occupation_array[$levelGroup->getFirmId()] = $levelGroup->getName();
        }


        /** @var Firm $firm */
        foreach ($firms_objects_array as $firm) {
//            $firm_groups = $firm->getGroups();
            $occupation = '';
            if (isset($occupation_array[$firm->getId()])) {
                $occupation = mb_strtolower(trim($occupation_array[$firm->getId()], " ,"));
            }
            $phone = '';
            $website = '';
            if (isset($phones[$firm->getId()])) {
                $phone = $phones[$firm->getId()]['Value'];
            }
            if (isset($websites[$firm->getId()])) {
                $website = ['id' => $websites[$firm->getId()]['id'],
                            'name' => $websites[$firm->getId()]['Value']];
            }

            $review = [
            ];

            if (isset($comments[$firm->getId()])) {
                $review = [
                    'count'   => $comments[$firm->getId()]['score_count'],
                    'average' => $comments[$firm->getId()]['average_score']
                ];
            }

            $url = "/firm/show/" . $firm->getId();

            if (isset($aliases["/firm/show/" . $firm->getId()])) {
                $url = $aliases["/firm/show/" . $firm->getId()]['Alias'];
            }

            $firms_array[] = [
                'name'         => $firm->getName(),
                'url'          => $url,
                'full_address' => 'г. ' . $city->getName() . ', ' . $firm->getStreet() . ', '
                    . $firm->getHome(),
                'phone'        => $phone,
                'website'      => $website,
                'occupation'   => $occupation,
                'cords'        =>
                    [
                        'lat' => $firm->getLat(),
                        'lon' => $firm->getLon()
                    ],
                'region'       => $region->getName(),
                'city'         => $city->getName(),
                'address'      => $firm->getStreet() . ', ' . $firm->getHome(),
                'zip'          => $firm->getPostal(),
                'review'       => $review,
                'array'        => $firm->toArray()
            ];

        }

        $subs = [];
        $counters_order = [];

        $totes_counter = 0;


        $pagination = [];
        if ($firms->haveToPaginate()) {
            $pagination = $firms->getLinks(5);
            if ($page != 1) {
                if (!in_array(1, $pagination)) {
                    array_unshift($pagination, 'ellipsis');
                }
                array_unshift($pagination, 'first', 'prev');
            }
            if ($page != $firms->getLastPage()) {
                if (!in_array($firms->getLastPage(), $pagination)) {
                    array_push($pagination, 'ellipsis');
                }
                array_push($pagination, 'next', 'last');
            }

            $pagination = [
                'pages'        => $pagination,
                'last_page'    => $firms->getLastPage(),
                'prev_page'    => $firms->getPreviousPage(),
                'next_page'    => $firms->getNextPage(),
                'current_page' => $page
            ];
        }

        $h1 = $group->getTag() . ' ' . $city->getPrefix() . ' ' . $city->getCase(5);

        $description = 'По слову «'.$group->getTag().'» ' . $city->getPrefix() . ' ' . $city->getCase(5) . '
         ('.$region->getCase(1).') найдено '.$firms->getNbResults().' компани'
            .Lang::suffix($firms->getNbResults(), 'й', 'я', 'и').' ― график работы, 
         схема проезда, ИНН, ОГРН, адреса, телефоны';

        if($street){

            $description = 'По слову «'.$group->getTag().'» на улице ' . trim(str_replace('ул.','',$street)) . ' ' . $city->getPrefix() . ' ' . $city->getCase(5) . '
         ('.$region->getCase(1).') найдено '.$firms->getNbResults().' компани'
                .Lang::suffix($firms->getNbResults(), 'й', 'я', 'и').' ― график работы, 
         схема проезда, ИНН, ОГРН, адреса, телефоны';

            $h1 = $group->getTag() . ' на улице ' . trim(str_replace('ул.','',$street)) . ' '. $city->getPrefix() . ' ' . $city->getCase(5);

        } elseif ($district){

            $description = 'По слову «'.$group->getTag().'» в ' . $district->getCase(6) . ' районе города ' . $city->getCase(1) . '
         ('.$region->getName().') найдено '.$firms->getNbResults().' компани'
                .Lang::suffix($firms->getNbResults(), 'й', 'я', 'и').' ― график работы, 
         схема проезда, ИНН, ОГРН, адреса, телефоны';

            $h1 = $group->getTag() . ' в ' . $district->getCase(6) . ' районе города ' . $city->getCase(1);
        }

        switch ($sort_type) {
            case self::$SORT_TYPE[0]:
                $title = $h1 . ' (отсортированы по алфавиту)  — компании по ключевому слову, адреса, телефоны, ИНН, ОГРН';
                break;

            case self::$SORT_TYPE[1]:
                $title = $h1 . ' (отсортированы по рейтингу)  — компании по ключевому слову, адреса, телефоны, ИНН, ОГРН';
                break;

            default:
                $title = $h1 . '  — компании по ключевому слову, адреса, телефоны, ИНН, ОГРН';
                break;
        }


        if ($page != 1) {
            $title .= " (страница {$page})";
        }

        $rubric_url_pagination = $rubric_url = '/' . $city->getUrl() . '/ключевое-слово/' . $group->getUrl();

        if ($sort_type) {
            $rubric_url_pagination = $rubric_url . '/' . $sort_type;
        }

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

        $keywords = '';



        if ($page != 1) {
            $description .= " (страница {$page})";
        }

        $category_description = '';
        
        $this->view->addGlobal('googleads', Lang::adsense_check_text($title) == 0);        

        $tpl = $this->view->render(
            '@catalog/key.twig', [
                'breadcrumbs'           => $breadcrumbs,
                'h1'                    => $h1,
                'group_count'           => $firms->getNbResults(),
                'header'                =>
                    [
                        'placeholder' => 'Искать в ' . $city->getCase(5),
                        'region'      => $regionArray,
                        'cityUrl'     => $city->getUrl()
                    ],
                'head'                  =>
                    [
                        'title'       => $title,
                        'keywords'    => $keywords,
                        'description' => $description
                    ],
                'rubric_url'            => $rubric_url,
                'rubric_url_pagination' => $rubric_url_pagination,
                'firms'                 => $firms_array,
                'is_root'               => 0,
                'region_case1'          => $region->getCase(1),
                'city_prefix'           => $city->getPrefix(),
                'city'                  => $city,
                'tag'                   => $group,
                'city_case5'            => $city->getCase(5),
                'group_name'            => $group->getTag(),
                'pagination'            => $pagination,
                'nearby_groups'         => $city->getNearbyTags($group),
                'city_case1'            => $city->getCase(1),
                'sort'                  => $sort_type,
                'subrubrics'            => $subs,
                'totes_counter'         => $totes_counter,
                'level_description'     => $category_description,
                'districts'             => $districts,
                'streets'               => $streets,
                'current_street'        => $street,
                'current_district'      => $district,
                'city_url'              => $city->getUrl(),
            ]
        );
        
        if (!DEBUG) {
            $this->cache->myset($redisKey, $tpl);
        }

        print $tpl;
    }


}