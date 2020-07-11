<?php

namespace wMVC\Controllers\Admin;

use Propel\Runtime\ActiveQuery\Criteria;
use PropelModel\FirmQuery;
use PropelModel\Map\RegionTableMap;
use PropelModel\Region;
use PropelModel\RegionQuery;
use wMVC\Controllers\abstractAdmin;
use wMVC\Core\Exceptions\SystemExit;

Class City extends abstractAdmin
{
    public function getList($type, $page = 0)
    {
        $cities = RegionQuery::create('city')
            ->addAlias('city', RegionTableMap::TABLE_NAME)
            ->addJoin('city.Area', RegionTableMap::COL_ID)
            ->where('city.Area is not null')
            ->select(array('Name', 'Url', 'Id'))
            ->addAsColumn('region', 'region.name');
        switch ($type) {
            case 'cities-name':
                $cities = $cities->orderByName();
                break;

            case 'cities-url':
                $cities = $cities->orderByUrl();
                break;

            case 'cities-region':
                $cities = $cities->orderBy('region.name')->orderByName();
                break;

            default:
                Page::show404();
                break;
        }

        $cities = $cities->paginate($page, 100);
        $totalPages = $cities->getLastPage();
        $data = [];
        foreach ($cities as $city) {
            $data[] = array_change_key_case($city, CASE_LOWER);
        }

        $currentPage = $page;
        $result = array('data' => $data, 'currentPage' => $currentPage, 'totalPages' => $totalPages);
        self::response($result);
    }

    public function getCity($id)
    {
        $city = RegionQuery::create()->findPk($id);
        $result = [];
        $result['id'] = $city->getId();
        $result['name'] = $city->getName();
        $result['url'] = $city->getUrl();
        $result['area'] = $city->getArea();
        $result['name_p2'] = $city->getCase(1);
        $result['name_p3'] = $city->getCase(2);
        $result['name_p4'] = $city->getCase(3);
        $result['name_p5'] = $city->getCase(4);
        $result['name_p6'] = $city->getCase(5);

        self::response($result);
    }

    public function updateCity($id)
    {
        $city_data = (array)json_decode(self::getInputContent());
        $data = serialize(
            array(
                'p2' => $city_data['name_p2'],
                'p3' => $city_data['name_p3'],
                'p4' => $city_data['name_p4'],
                'p5' => $city_data['name_p5'],
                'p6' => $city_data['name_p6'])
        );

        if (RegionQuery::create()
            ->filterById($id, Criteria::NOT_EQUAL)
            ->filterByUrl($city_data['url'])
            ->count()
        ) {
            self::response(array('error' => 'Город с таким url уже существует'), 500);
            return;
        }

        $city = RegionQuery::create()->findPk($id);

        $city->setArea($city_data['area']);
        $city->setName($city_data['name']);
        $city->setUrl($city_data['url']);
        $city->setData($data);
        $city->save();
        
        self::response(array('ok' => 1));
    }

    public function addCity()
    {
        $name = $_POST['name'];
        if (!$name) {
            self::response(array('error' => 'Введите название города'), 500);
            return;
        }
        $region = $_POST['region'];

        $check_city = RegionQuery::create()->filterByName($name)->filterByArea($region)->count();

        if ($check_city) {
            self::response(array('error' => 'Город с таким названием уже существует в этом регионе'), 500);
            return;
        }

        $city = new Region();
        $data = serialize([
            'p2' => null,
            'p3' => null,
            'p4' => null,
            'p5' => null,
            'p6' => null,
        ]);

        $city->setData($data);
        $city->setName($name);
        $city->setArea($region);

        $city->save();

        self::response(
            array('message' => 'Город добавлен. Заполните также остальные поля.', 'city_id' => $city->getId())
        );
    }

    public function deleteCity($id)
    {
        FirmQuery::create()->filterByCityId($id)->update(array('Active' => 0));
        RegionQuery::create()->filterById($id)->delete();

        self::response(array('ok' => 1));
    }
}