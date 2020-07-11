<?php

namespace wMVC\Controllers\Search;

use Illuminate\Support\Str;
use Propel\Runtime\ActiveQuery\Criteria;
use PropelModel\FirmQuery;
use PropelModel\RegionQuery;
use wMVC\Entity\Breadcrumb;
use wMVC\Entity\Lang;

class Search extends Core
{
    const  SEARCH_RESULT = 30;
    const  LIVE_SEARCH_LIMIT = 5;

    public function front($city, $query)
    {
        $query = Str::ucfirst($query);
        $city = RegionQuery::create()->findOneByUrl($city);
        $region = RegionQuery::create()->findPk($city->getArea());

        $data = $this->getFirms($query, $city, $this->options['page']);
        $h1 = $data['speller'] ?: $query . ' ' . $city->getPrefix() . ' ' . $city->getCase(5);

        $breadcrumbs = new Breadcrumb();
        $breadcrumbs = $breadcrumbs->addCrumb('Россия', '/')
            ->addCrumb($region->getName(), '/' . $region->getUrl(), $region->getName())
            ->addCrumb($city->getName(), '/' . $city->getUrl(), $city->getName())
            ->addCrumb($query, '/' . $city->getUrl() . '/найти/' . $query, $query);

        $data = [
            'query'       => $query,
            'firms'       => $data['items'],
            'count'       => $data['total'],
            'speller'     => $data['speller'],
            'city'        => $city,
            'pagination'  => $this->getPager($data['total'], $this->options['page']),
            'h1'          => $h1,
            'url'         => "/{$city->getUrl()}/поиск/{$query}",
            'streets'     => $data['streets'],
            'districts'   => $data['districts'],
            'filters'     => $data['filters'],
            'options'     => $this->options,
            'cities'      => $this->getCities($data['cities']),
            'breadcrumbs' => $breadcrumbs->getArray(),
            'head'        => [
                'title'       => "{$h1} — сайты компаний, схемы проездов, графики работ и телефоны",
                'description' => "{$h1} — адреса, телефоны, график работы и отзывы для {$data['total']} компан" . Lang::suffix($data['total'], 'ий', 'ия', 'ии'),
                'placeholder' => $query,
                'og'          => [
                    'image' => $this->getOgImage($data['items'])
                ]
            ],
            'popular_groups' => $city->getPopularGroupsInCity($this->cache),
        ];
        return print $this->view->render('@search/front/front.twig', $data);
    }

    public function frontAjax($query)
    {
        $query = mb_convert_case($query, MB_CASE_TITLE, "UTF-8");
        $city = RegionQuery::create()->findOneByUrl($this->options['city']);
        $data = $this->getFirms($query, $city);

        $h1 = $query . ' ' . $city->getPrefix() . ' ' . $city->getCase(5);

        $data = [
            'query'      => $query,
            'firms'      => $data['items'],
            'count'      => $data['total'],
            'city'       => $city,
            'pagination' => $this->getPager($data['total'], $this->options['page']),
            'h1'         => $h1,
            'url'        => "/{$city->getUrl()}/поиск/{$query}",
            'options'    => $this->options,
            'head'       => [
                'title'       => "{$h1} — сайты компаний, схемы проездов, графики работ и телефоны",
                'description' => "{$h1} — адреса, телефоны, график работы и отзывы для {$data['total']} компан" . Lang::suffix($data['total'], 'ий', 'ия', 'ии'),
            ]
        ];

        print $this->view->render('@search/front/firm-list.twig', $data);
    }

    public function index($city, $query)
    {
        $query = Str::ucfirst($query);
        $city = RegionQuery::create()->findOneByUrl($city);

        $data = $this->getFirms($query, $city, $this->options['page']);
        
        if (!isset($data['speller'])) {
            $data['speller'] = '';
        }

        $h1 = $data['speller'] ?: $query . ' ' . $city->getPrefix() . ' ' . $city->getCase(5);

        $data = [
            'query'      => $query,
            'firms'      => $data['items'],
            'count'      => $data['total'],
            'speller'    => $data['speller'],
            'city'       => $city,
            'pagination' => $this->getPager($data['total'], $this->options['page']),
            'h1'         => $h1,
            'url'        => "/{$city->getUrl()}/поиск/{$query}",
            'streets'    => $data['streets'],
            'districts'  => $data['districts'],
            'filters'    => $data['filters'],
            'options'    => $this->options,
            'cities'     => $this->getCities($data['cities']),
            'head'       => [
                'title'       => "{$h1} — сайты компаний, схемы проездов, графики работ и телефоны",
                'description' => "{$h1} — адреса, телефоны, график работы и отзывы для {$data['total']} компан" . Lang::suffix($data['total'], 'ий', 'ия', 'ии'),
                'og'          => [
                    'image' => $this->getOgImage($data['items'])
                ]
            ]
        ];

        print $this->view->render('@search/result.twig', $data);
    }

    public function ajax($query)
    {
        $query = mb_convert_case($query, MB_CASE_TITLE, "UTF-8");
        $city = RegionQuery::create()->findOneByUrl($this->options['city']);
        $data = $this->getFirms($query, $city);

        $h1 = $query . ' ' . $city->getPrefix() . ' ' . $city->getCase(5);

        $data = [
            'query'      => $query,
            'firms'      => $data['items'],
            'count'      => $data['total'],
            'city'       => $city,
            'pagination' => $this->getPager($data['total'], $this->options['page']),
            'h1'         => $h1,
            'url'        => "/{$city->getUrl()}/поиск/{$query}",
            'options'    => $this->options,
            'head'       => [
                'title'       => "{$h1} — сайты компаний, схемы проездов, графики работ и телефоны",
                'description' => "{$h1} — адреса, телефоны, график работы и отзывы для {$data['total']} компан" . Lang::suffix($data['total'], 'ий', 'ия', 'ии'),
            ]
        ];

        print $this->view->render('@search/firm_list.twig', $data);
    }

    public function getMarkers($query)
    {
        $city = RegionQuery::create()->findOneByUrl($this->options['city']);
        $data = [
            'query' => $query,
            'city'  => $city->getName(),
        ];

        $data = $this->setFilters($data);

        $result = $this->get('markers', $data);
        header("Content-Type: application/json");
        print json_encode($result['items']);
    }


    protected function getFirms($query, $city)
    {
        $data = [
            'query' => $query,
            'city'  => $city->getName(),
            'page'  => $this->options['page'],
            'count' => self::SEARCH_RESULT
        ];

        $data = $this->setFilters($data);
        $list = $this->get('list', $data);
        
        foreach ($list['items'] as $_key => &$item) {

            if (!empty($item['url'])) {
                continue;
            }
            
            foreach (($item['sync_status']) as $status) {
                if ($status['response'] == 'OK') {
                    $item['url'] = $status['check_url'];
                    break;
                }
            }
            
            if (!empty($item['url'])) {
                continue;
            }
            
            if (empty($item['name']) || empty($item['street_id'])) {
                unset($list['items'][$_key]);
                continue;
            }

            $firm = FirmQuery::create()
                ->filterByCityId($city->getId())
                ->filterByHome($item['building'])
                ->filterByStreet($list['streets'][$item['street_id']])
                ->filterByName($item['name'])
                //->toString();
                ->findOne();
            
            if (!$firm) {
                unset($list['items'][$_key]);
                continue;
            }

            $item['url'] = $firm->getAlias();
        }

        return $list;
    }

    private function setFilters($data)
    {
        if (!empty($this->options['worktime'])) {
            $data['work_time'] = 'now';
        }
        if (!empty($this->options['time'])) {
            $day = jddayofweek($this->options['workdays'], CAL_DOW_SHORT);
            $time = "{$this->options['time']}:00";
            $data['work_time'] = "$day $time";
        }
        if (!empty($this->options['is24x7'])) {
            $data['work_time'] = '03:00';
        }
        if (!empty($this->options['site'])) {
            $data['has_site'] = 1;
        }
        if (!empty($this->options['near'])) {
            $data['lat'] = $_COOKIE['lat'];
            $data['lon'] = $_COOKIE['lon'];
            $data['distance'] = $this->options['distance'];
        }
        if (!empty($this->options['street'])) {
            $data['street'] = $this->options['street'];
        }

        if (!empty($this->options['district'])) {
            $data['district'] = $this->options['district'];
        }
        if (!empty($this->options['attributes'])) {
            $data['attributes'] = $this->options['attributes'];
        }
        if (!empty($this->options['review'])) {
            $data['has_review'] = 1;
        }
        if (!empty($this->options['by_rating'])) {
            $data['sort'] = 'rating';
        }
        if (!empty($this->options['by_title'])) {
            $data['sort'] = 'name';
        }
        return $data;
    }

    private function getPager($count, $page)
    {
        $pages = ceil($count / self::SEARCH_RESULT);
        $pagination = [
            'pages'   => $pages,
            'prev'    => $page > 1 ? $page - 1 : $page,
            'next'    => $page < $pages ? $page + 1 : $page,
            'current' => $page,
        ];
        return $pagination;
    }

    public function getModal($modal)
    {
        print $this->view->render("@search/modals/{$modal}.twig");
    }

    public function getFirmById($id, $ret = false) 
    {
        $firm = $this->get('firm', ['id' => $id]);
        if ($firm) {
            $firm = $firm['item'];
            $city = RegionQuery::create()->findOneByName($firm['city']['name']);
            $siteFirm = FirmQuery::create()
                ->filterByCityId($city->getId())
                ->filterByHome($firm['building'])
                ->filterByStreet($firm['street']['name'])
                ->filterByName($firm['name'])
                //->toString();
                ->findOne();            
            $firm['url'] = $siteFirm ? $siteFirm->getAlias() : null;
            $firm['worktime'] = unserialize($firm['worktime']);

            if (!empty($firm['worktime']['is_24x7'])) {
                unset($firm['worktime']['is_24x7']);
            }
            $title = Str::ucfirst($firm['subtitle']) . " {$firm['name']} - {$firm['address']}";
            
            if (isset($firm['contacts'])) {                
                $social = false;
                $countPhones = 0;
                foreach ($firm['contacts'] as $_contact) {
                    if ($_contact['type'] != 'phone') {
                        $social = true;
                        break;
                    }
                    
                    if ($_contact['type'] === 'phone') {
                        ++$countPhones;
                    }
                }
                $firm['contacts_social'] = $social;
                $firm['contacts_phones'] = $countPhones;
            }

            $data = [
                'firm' => $firm,
                'head' => [
                    'title'       => $title,
                    'description' => "{$title} ― схема проезда, телефон, часы работы и отзывы"
                ]
            ];
                    
            if($ret) {
                return $firm;
            }

            print $this->view->render('@search/firm.twig', $data);
        }
        
        if ($ret) {
            return null;
        }

        print '';
    }

    public function liveSearch()
    {
        $this->post('suggest', $_POST);
    }

    protected function getCities($cities)
    {
        if ($cities) {
            $cities = RegionQuery::create()
                ->filterByName($cities)
                ->filterByArea(null, Criteria::NOT_EQUAL)
                ->orderByCount(Criteria::DESC)
                ->find();
        }
        return $cities;
    }

    protected function getOgImage($firms)
    {
        $points = [];
        $center = '';
        $n = 0;
        foreach ($firms as $firm) {
            $n == 0 && $center = "{$firm['lon']},{$firm['lat']}";
            if ($n > 10)
                break;
            $points[] = "{$firm['lon']},{$firm['lat']},pm2vvm";
            $n++;
        }

        return 'https://static-maps.yandex.ru/1.x/?ll=' . $center . '&size=450,450&l=map&z=10&l=map&pt=' . implode('~', $points);
    }
}