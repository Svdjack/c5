<?php

namespace wMVC\Controllers;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Propel;
use PropelModel\Firm;
use PropelModel\FirmQuery;
use PropelModel\FirmUpQuery;

final class AdvCron extends AdvCore
{

    const SPAM_EVERY_DAY = 1;
    const SPAM_EVERY_WEEK = 7;
    const SPAM_EVERY_MONTH = 31;
    const SPAM_LIMIT = 10;
    static $limit;

    public $tarifs = [
        'beta'    => 'Пробный период (7 дней)',
        'premium' => 'Премиальное размещение',
        'all-in'  => 'Всё включено'];

    public function run($secret)
    {
        $this->checkSecret($secret);
        $this->checkStatusFirms();
        if (date('H') >= 8 && date('H') <= 18)
            $this->sendSpam();
        $this->updateFakeStat();
        print (date('Y.m.d H:i'));
    }

    private function checkStatusFirms()
    {
        print ("Update status\n");
        $firmsUp = FirmUpQuery::create()
            ->filterByStatus(1)
            ->filterByTimeEnd(time(), Criteria::LESS_THAN)
            ->find();
        foreach ($firmsUp AS $firmUp) {
            $this->checkLimit();
            (new AdvNotificator())->FirmUpStop($firmUp, $firmUp->getFirm(), $this->tarifs[$firmUp->getType()]);
            $firmUp->setStatus(0);
            $firmUp->save();
        }
    }

    private function sendSpam()
    {
        $this->spamDay();
        $this->spamWeek();
        $this->spamMonth();
        $this->spamLastTenDays();
        $this->spamLastThreeDays();
    }

    private function spamLastThreeDays()
    {
        $firmsUp = FirmUpQuery::create()
            ->joinWith('Firm')
            ->filterByStatus(1)
            ->filterByType(['all-in', 'premium'])
            ->filterByTimeEnd(strtotime('+3 day'), Criteria::LESS_THAN)
            ->_and()
            ->filterByTimeEnd(strtotime('+2 day'), Criteria::GREATER_THAN)
            ->filterByLastDays(10)
            ->find();
        foreach ($firmsUp AS $firmUp) {
            $this->checkLimit();
            $firmUp->setLastDays(3);
            $firmUp->save();
            (new AdvNotificator())->FirmUpLastDays($firmUp, $firmUp->getFirm(), $this->tarifs[$firmUp->getType()], '3 дня');
        }
    }

    private function spamLastTenDays()
    {
        $firmsUp = FirmUpQuery::create()
            ->joinWith('Firm')
            ->filterByStatus(1)
            ->filterByType(['all-in', 'premium'])
            ->filterByTimeEnd(strtotime('+10 day'), Criteria::LESS_THAN)
            ->_and()
            ->filterByTimeEnd(strtotime('+9 day'), Criteria::GREATER_THAN)
            ->filterByLastDays(0)
            ->find();
        foreach ($firmsUp AS $firmUp) {
            $this->checkLimit();
            $firmUp->setLastDays(10);
            $firmUp->save();
            (new AdvNotificator())->FirmUpLastDays($firmUp, $firmUp->getFirm(), $this->tarifs[$firmUp->getType()], '10 дней');
        }
    }


    private function getFirmsUp($type, $time)
    {
        return FirmUpQuery::create()
            ->joinWith('Firm')
            ->filterByStatus(1)
            ->filterBySpamType($type)
            ->filterByLastMailSend($time, Criteria::LESS_THAN)
            ->find();
    }

    private function spamDay()
    {
        print ("Spam day\n");
        $time = strtotime('-1 day');
        $firmsUp = $this->getFirmsUp(self::SPAM_EVERY_DAY, $time);
        $this->spamStat($firmsUp);
    }

    private function spamWeek()
    {
        print ("Spam week\n");
        $time = strtotime('-7 day');
        $firmsUp = $this->getFirmsUp(self::SPAM_EVERY_WEEK, $time);
        $this->spamStat($firmsUp);
    }

    private function spamMonth()
    {
        print ("Spam month\n");
        $time = strtotime('-1 month');
        $firmsUp = $this->getFirmsUp(self::SPAM_EVERY_MONTH, $time);
        $this->spamStat($firmsUp);
    }

    private function spamStat(ObjectCollection $firmsUp)
    {
        foreach ($firmsUp as $firmUp) {
            $this->checkLimit();
            $firm = $firmUp->getFirm();
            $stat = $this->getStat($firm);
            (new AdvNotificator())->statFirmUp($stat, $firmUp, $firm);
            $firmUp->setLastMailSend(time());
            $firmUp->save();
        }
    }

    private function getStat(Firm $firm)
    {
        $stat = new Stat();
        return ['firmWeek'       => $stat->getFirmWeekStat($firm),
                'categoriesWeek' => $stat->getCategoriesWeekStat($firm),
                'tagsWeek'       => $stat->getTagsWeekStat($firm),
                'sumWeek'        => $stat->getSumWeek($firm),
                'sumYear'        => $stat->getSumYear($firm)];
    }

    private function checkLimit()
    {
        if (static::$limit == self::SPAM_LIMIT)
            exit("SPAM LIMIT EXCEEDED\n");
        static::$limit++;
    }

    private function updateFakeStat()
    {
        $this->createEmptyStat();
        $time = strtotime('-1 days');
        $sqls[] = "UPDATE stat SET fake_count = count + RAND(2) WHERE count <= 2 AND date > $time";
        $sqls[] = "UPDATE stat SET fake_count = count + RAND(5) + 2 WHERE count >= 5 AND count <= 10 AND date > '{$time}'";
        $sqls[] = "UPDATE stat SET fake_count = count + RAND(4) + 3 WHERE count >= 11 AND count <= 19 AND date > '{$time}'";
        $sqls[] = "UPDATE stat SET fake_count = count + RAND(6) + 4 WHERE count >= 19 AND count <= 40 AND date > '{$time}'";
        $sqls[] = "UPDATE stat SET fake_count = count + RAND(10) + 5 WHERE count >= 40 AND count <= 70 AND date > '{$time}'";
        $sqls[] = "UPDATE stat SET fake_count = count + RAND(10) + 10 WHERE count >= 70 AND count <= 100 AND date > '{$time}'";
        $conn = Propel::getConnection();
        foreach ($sqls AS $sql) {
            $st = $conn->prepare($sql);
            $st->execute();
        }
    }

    private function createEmptyStat()
    {
        $firms = FirmQuery::create()
            ->useFirmUpQuery(null, Criteria::INNER_JOIN)
            ->filterByStatus(1)
            ->endUse()
            ->find();
        foreach ($firms AS $firm) {
            Stat::initFirmStat($firm);
        }
    }

}
