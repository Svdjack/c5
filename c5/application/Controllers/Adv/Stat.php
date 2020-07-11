<?php

namespace wMVC\Controllers;

use Propel\Runtime\ActiveQuery\Criteria;
use PropelModel\FirmQuery;
use PropelModel\FirmUpQuery;
use PropelModel\Group;
use PropelModel\StatQuery;
use wMVC\Core\abstractController;
use wMVC\Core\Controller;
use wMVC\Core\Exceptions\SystemExit;

define('REAL_STAT', 'count');
define('FAKE_STAT', 'fake_count');

final class Stat extends abstractController
{

    private $typeStat;

    public function __construct($type = FAKE_STAT)
    {
        parent::__construct();
        $this->typeStat = $type;
    }

    public function inc($cityId, $entityType, $entityId, $count = 1)
    {
        $stat = StatQuery::create()
            ->filterByCityId($cityId)
            ->filterByEntityId($entityId)
            ->filterByType($entityType)
            ->filterByDate(date('Y-m-d', time()))
            ->findOneOrCreate();
        $stat->setCount($stat->getCount() + $count);
        $stat->setFakeCount($stat->getFakeCount() + $count);
        $stat->save();

    }

    public function show($firmId)
    {
        $this->checkAccess(self::USER_ROLE);
        $user = $this->user;
        $firm = FirmQuery::create()
            ->filterByUser($user)
            ->useFirmUpQuery(null, Criteria::INNER_JOIN)
            ->filterByStatus(1)
            ->filterByTimeEnd(date('Y-m-d', time()), Criteria::GREATER_EQUAL)
            ->addAsColumn('UpId', 'FirmUp.Id')
            ->endUse()
            ->findOneById($firmId);

        if (!$firm) throw new SystemExit("Firm not found " . __METHOD__, SystemExit::ACCESS_DENIED);

        $this->setTitle("Статистика просмотров компании «{$firm->getName()}»");

        self::initFirmStat($firm);

        print $this->view->render(
            '@stat/show.twig',
            [
                'content' =>
                    [
                        'firm'           => $firm,
                        'upData'         => FirmUpQuery::create()->findOneById($firm->getUpId()),
                        'firmWeek'       => $this->getFirmWeekStat($firm),
                        'categoriesWeek' => $this->getCategoriesWeekStat($firm),
                        'tagsWeek'       => $this->getTagsWeekStat($firm),
                        'sumWeek'        => $this->getSumWeek($firm),
                        'sumYear'        => $this->getSumYear($firm),
                        'graph'          => $this->getGraph($firm)
                    ],
            ]
        );
    }

    public function getFirmWeekStat(\PropelModel\Firm $firm)
    {
        $stat = StatQuery::create()
            ->filterByType('FIRM')
            ->filterByEntityId($firm->getId())
            ->filterByCityId($firm->getCityId())
            ->filterByDate(date('Y-m-d', strtotime('-7 days')), Criteria::GREATER_THAN)
            ->addAsColumn('Cnt', "{$this->typeStat}")
            ->find()
            ->toArray();

        $stat = $this->prepareStat($stat, 'FIRM');

        return [
            'head'  => $this->getHeadTable($stat),
            'body'  => [
                ['stat' => $stat, 'link' => '', 'title' => $firm->getName()]
            ],
            'title' => 'Страница компании',
        ];
    }

    public function getCategoriesWeekStat(\PropelModel\Firm $firm)
    {
        /** @var Group $group */
        foreach ($firm->getLevel2Groups() as $group) {
            $stat = StatQuery::create()
                ->filterByType('GROUP')
                ->filterByEntityId($group->getId())
                ->filterByCityId($firm->getCityId())
                ->filterByDate(date('Y-m-d', strtotime('-7 days')), Criteria::GREATER_THAN)
                ->addAsColumn('Cnt', "{$this->typeStat}")
                ->find()
                ->toArray();

            $stat = $this->prepareStat($stat, 'GROUP');
            $body[] = ['stat' => $stat, 'link' => '', 'title' => $group->getName()];
        }
        return [
            'head'  => $this->getHeadTable($stat),
            'body'  => $body,
            'title' => 'Рубрики',
        ];
    }

    public function getTagsWeekStat(\PropelModel\Firm $firm)
    {
        $has_tags = 0;
        foreach ($firm->getFirmTagss() as $firmTag) {
            $has_tags = 1;
            $stat = StatQuery::create()
                ->filterByType('KEYWORD')
                ->filterByEntityId($firmTag->getTagId())
                ->filterByCityId($firm->getCityId())
                ->filterByDate(date('Y-m-d', strtotime('-7 days')), Criteria::GREATER_THAN)
                ->addAsColumn('Cnt', "{$this->typeStat}")
                ->find()
                ->toArray();

            $stat = $this->prepareStat($stat, 'KEYWORD');
            $body[] = ['stat' => $stat, 'link' => '', 'title' => $firmTag->getTags()->getTag()];
        }

        if($has_tags === 0){
            return [
//                'head'  => $this->getHeadTable($stat),
//                'body'  => $body,
                'title' => 'Ключевые слова ',
            ];
        }

        return [
            'head'  => $this->getHeadTable($stat),
            'body'  => $body,
            'title' => 'Ключевые слова ',
        ];
    }

    public function getSumWeek(\PropelModel\Firm $firm)
    {
        $tags = array_keys($firm->getFirmTagss()->toArray('TagId'));
        $groups = array_keys($firm->getFirmGroups()->toArray('GroupId'));
        $ids = array_merge($tags, $groups, [$firm->getId()]);
        $stat = StatQuery::create()
            ->filterByEntityId($ids)
            ->filterByCityId($firm->getCityId())
            ->filterByDate(date('Y-m-d', strtotime('-7 days')), Criteria::GREATER_THAN)
            ->groupByDate()
            ->addAsColumn('Cnt', "SUM({$this->typeStat})")
            ->find()
            ->toArray();

        $stat = $this->prepareStat($stat, 'WEEK');
        return [
            'head'  => $this->getHeadTable($stat, 'за месяц'),
            'body'  => [
                ['stat' => $stat, 'link' => '', 'title' => $firm->getName()]
            ],
            'title' => 'Суммарная статистика за текущую неделю',
        ];
    }

    public function getSumYear(\PropelModel\Firm $firm)
    {
        $tags = array_keys($firm->getFirmTagss()->toArray('TagId'));
        $groups = array_keys($firm->getFirmGroups()->toArray('GroupId'));
        $ids = array_merge($tags, $groups, [$firm->getId()]);
        $stat = StatQuery::create()
            ->filterByEntityId($ids)
            ->filterByCityId($firm->getCityId())
            ->filterByDate(date('Y-m-d', strtotime('-' . date('n') . ' month')), Criteria::GREATER_THAN)
            ->addGroupByColumn('MONTHNAME(date)')
            ->addAsColumn('Cnt', "SUM({$this->typeStat})")
            ->find()
            ->toArray();

        $stat = $this->prepareYearStat($stat, 'YEAR');

        return [
            'head'  => $this->getHeadTable($stat, 'за год', 'YEAR'),
            'body'  => [
                ['stat' => $stat, 'link' => '', 'title' => $firm->getName()]
            ],
            'title' => 'Суммарная статистика за текущий год',
        ];
    }

    public function getGraph(\PropelModel\Firm $firm)
    {
        $yearFirm = StatQuery::create()
            ->filterByEntityId($firm->getId())
            ->filterByCityId($firm->getCityId())
            ->filterByDate(date('Y-m-d', strtotime('-' . date('n') . ' month')), Criteria::GREATER_THAN)
            ->addGroupByColumn('MONTHNAME(date)')
            ->addAsColumn('Cnt', "SUM({$this->typeStat})")
            ->find()
            ->toArray();

        $yearFirm = $this->prepareYearStat($yearFirm, 'YEAR');

        foreach ($firm->getFirmGroups() as $firmGroup) {
            $groupStat = StatQuery::create()
                ->filterByEntityId($firmGroup->getGroupId())
                ->filterByType('GROUP')
                ->filterByCityId($firm->getCityId())
                ->filterByDate(date('Y-m-d', strtotime('-' . date('n') . ' month')), Criteria::GREATER_THAN)
                ->addGroupByColumn('MONTHNAME(date)')
                ->addAsColumn('Cnt', "SUM({$this->typeStat})")
                ->find()->toArray();
            $yearGroup[$firmGroup->getGroup()->getName()] = $this->prepareYearStat($groupStat, 'YEAR');
        }
        $yearTag = [];
        foreach ($firm->getFirmTagss() as $firmTag) {
            $tagStat = StatQuery::create()
                ->filterByEntityId($firmTag->getTagId())
                ->filterByType('KEYWORD')
                ->filterByCityId($firm->getCityId())
                ->filterByDate(date('Y-m-d', strtotime('-1 year')), Criteria::GREATER_THAN)
                ->addGroupByColumn('MONTHNAME(date)')
                ->addAsColumn('Cnt', "SUM({$this->typeStat})")
                ->find()->toArray();
            $yearTag[$firmTag->getTags()->getTag()] = $this->prepareYearStat($tagStat, 'YEAR');
        }

        return [
            'months'   => $this->getMonthName(),
            'yearFirm' => $yearFirm,
            'groups'   => $yearGroup,
            'tags'     => $yearTag
        ];
    }

    public function getHeadTable($stat, $period = 'за неделю', $type = null)
    {
        $result[] = 'Страница';
        foreach ($stat as $n => $day) {
            $month = date('n', strtotime($day['Date']));
            $weekDay = date('N', strtotime($day['Date']));
            $monthDay = date('d', strtotime($day['Date']));
            if ($type != 'YEAR')
                $result[$monthDay . ' ' . $this->getMonthName($month)] = $this->getDayName($weekDay);
            else
                $result[$n] = $this->getMonthName($n);
        }
        $result[$period] = 'Всего';
        return $result;
    }

    public function prepareStat($stat, $type = '')
    {

        $days = [];
        $result = [];

        for ($i = 6; $i >= 0; $i--) {
            $time = time() - 60 * 60 * 24 * $i;
            $days[] = date('Ymd', $time);
        }

        foreach ($stat AS $day) {
            $result[date('Ymd', strtotime($day['Date']))] = $day;
        }

        foreach ($days as $day) {
            if (empty($result[$day])) {
                $result[$day] = [
                    'Cnt'  => 0,
                    'Date' => date('Y-m-d', strtotime($day)),
                    'Type' => $type
                ];
            }
        }
        ksort($result);
        return $result;
    }

    public function prepareYearStat($stat, $type = '')
    {
        $mounths = range(1, 12);
        $result = [];

        foreach ($stat AS $month) {
            $result[date('n', strtotime($month['Date']))] = $month;
        }

        foreach ($mounths as $mounth) {
            if (empty($result[$mounth])) {
                $result[$mounth] = [
                    'Cnt'  => 0,
                    'Date' => date('Y-m-d', strtotime($mounth)),
                    'Type' => $type
                ];
            }
        }
        ksort($result);
        return $result;
    }

    public function getDayName($n)
    {
        $days = ['понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота', 'воскресенье'];
        return $days[$n - 1];
    }

    function getMonthName($n = null, $type = null)
    {
        if ($type) {
            $months = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
        } else {
            $months = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];
        }
        return $n ? $months[$n - 1] : $months;
    }

    public static function initFirmStat(\PropelModel\Firm $firm)
    {
        $data[$firm->getId()] = 'firm';
        foreach ($firm->getFirmGroups() as $firmGroup) {
            $data[$firmGroup->getGroupId()] = 'group';
        }
        foreach ($firm->getFirmTagss() as $firmTags) {
            $data[$firmTags->getTagId()] = 'keyword';
        }
        foreach ($data AS $entityId => $entity) {
            $stat = StatQuery::create()
                ->filterByCityId($firm->getCityId())
                ->filterByEntityId($entityId)
                ->filterByType($entity)
                ->filterByDate(time())
                ->findOneOrCreate();
            $stat->save();
        }
    }

}