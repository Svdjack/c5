<?php

namespace wMVC\Controllers;

use Propel\Runtime\ActiveQuery\Criteria;
use PropelModel\AdvServerOrders;
use PropelModel\CommentQuery;
use PropelModel\FirmQuery;
use PropelModel\FirmUpQuery;
use PropelModel\FirmUser;
use PropelModel\FirmUserQuery;
use PropelModel\UserQuery;
use wMVC\Core\Exceptions\SystemExit;
use wMVC\Core\Router;

final class AdvClient extends AdvCore
{

    public static function getTarif($tarif)
    {
        $tarifs = [
            'beta'    => ['stat', 'background'],
            'premium' => ['stat', 'background', 'label', 'spam'],
            'all-in'  => ['stat', 'background', 'label', 'spam', 'reviews', 'hide-ads'],
        ];
        return !empty($tarifs[$tarif]) ? $tarifs[$tarif] : null;
    }

    public static function getTarifName($tarif)
    {
        $tarifs = [
            'beta'    => 'Пробный',
            'premium' => 'Премиальное размещение',
            'all-in'  => 'Все включено',
        ];
        return !empty($tarifs[$tarif]) ? $tarifs[$tarif] : null;
    }

    public function request(AdvServerOrders $order, $secret)
    {
        $this->checkSecret($secret);
        $this->firmUp($order->getFirmId(), $order->getCash(), $order->getMonths(), $order->getEmail(), $order->getType());
        return json_encode(['status' => 1]);
    }

    public function firmUp($firmId, $cash, $month, $email, $type)
    {
        $time = $month ? "+ {$month} month" : "+ 7 days";
        $firmUp = FirmUpQuery::create()
            ->filterByFirmId($firmId)
            ->findOneOrCreate();

        if (!$firmUp->isNew() && empty($month))
            return $firmUp;

        $firmUp->setCash($cash + $firmUp->getCash());
        $firmUp->setType($type);
        $firmUp->setStatus(1);
        $firmUp->setEmail($email);
        $firmUp->setSpamType(1);
        $firmUp->isNew() && $firmUp->setTimeStart(time());
        $end = strtotime($time, ($firmUp->getTimeEnd() && $firmUp->getTimeEnd() > time()) ? $firmUp->getTimeEnd() : time());
        $firmUp->setTimeEnd($end);

        if (!$month) {
            $user = $firmUp->getFirm()->getUser();
        } else {
            $user = $this->attachUser($email, $firmId);
        }

        (new AdvNotificator())->firmUp($user, $firmUp, $end);
        Stat::initFirmStat($firmUp->getFirm());

        $firmUp->save();


        return $firmUp;
    }

    public function isUp($firmId)
    {
        $firmUp = FirmUpQuery::create()
            ->filterByFirmId($firmId)
            ->filterByStatus(1)
            ->filterByTimeEnd(time(), Criteria::GREATER_THAN)
            ->findOne();
        return $firmUp ? true : false;
    }


    public function showReviews($firmId)
    {
        $this->checkAccess(self::USER_ROLE);
        if (!$this->isUp($firmId))
            throw new SystemExit("Firm not up " . __METHOD__, SystemExit::ACCESS_DENIED);

        $firmUp = FirmUpQuery::create()->findOneByFirmId($firmId);
        $tarif = self::getTarif($firmUp->getType());

        if (!in_array('reviews', $tarif))
            throw new SystemExit("It pleb tarif " . __METHOD__, SystemExit::ACCESS_DENIED);

        $user = $this->user;
        $firm = FirmQuery::create()
            ->filterById($firmId)
            ->filterByUser($user)
            ->findOne();

        if (!$firm)
            throw new SystemExit("Firm not found " . __METHOD__, SystemExit::ACCESS_DENIED);

        print $this->view->render(
            '@adv/client/reviews.twig',
            [
                'content' =>
                    [
                        'firm' => $firm,
                    ],
            ]
        );
    }

    public function deleteReview($reviewId)
    {

        $this->checkAccess(self::USER_ROLE);

        $review = CommentQuery::create()->findOneById($reviewId);

        if (!$review)
            throw new SystemExit("Comment not found " . __METHOD__, SystemExit::ACCESS_DENIED);

        if (!$this->isUp($review->getFirmId()))
            throw new SystemExit("Firm not up " . __METHOD__, SystemExit::ACCESS_DENIED);


        if ($this->user->getId() != $review->getFirm()->getUser()->getId())
            throw new SystemExit("Attacked!!! " . __METHOD__, SystemExit::ACCESS_DENIED);

        $review->setActive($review->getActive() ? 0 : 1);
        $review->save();
        (new Router())->redirect('/профиль/отзывы/' . $review->getFirm()->getId());

    }


    public function getFirmInfo($firmId, $secret)
    {
        $this->checkSecret($secret);
        $firm = FirmQuery::create()
            ->findOneById($firmId);

        $this->showJson($firm->toArray());
    }

    public function getCityInfo($secret)
    {
        $this->checkSecret($secret);
        $city = $this->city;
        unset($city['Data'], $city['About']);
        $this->showJson($city);
    }

    private function attachUser($email, $firmId)
    {
        $firm = FirmQuery::create()
            ->findOneById($firmId);

        $user = UserQuery::create()->findOneByEmail($email);
        if (!$user) {
            $user = \wMVC\Entity\User::add($email);
        }
        $firm->save();
        FirmUserQuery::create()->filterByFirmId($firm->getId())->delete();
        $firm_user = new FirmUser();
        $firm_user->setUserId($user->getId());
        $firm_user->setFirmId($firm->getId());
        $firm_user->save();
        return $user;
    }

    public function spamSettings()
    {
        $this->checkAccess(self::USER_ROLE);
        $firmUp = FirmQuery::create()
            ->joinWith('FirmUp', Criteria::INNER_JOIN)
            ->filterByUser(UserQuery::create()->findPk($this->user->getId()))
            ->find()
            ->getFirst();
        $firmUp && $firmUp = $firmUp->getFirmUps()->getFirst();

        $this->setTitle('Настройка оповещения');

        print $this->view->render(
            '@adv/client/spam_settings.twig',
            [
                'firmUp' => $firmUp
            ]
        );
    }

    public function spamSettingsSubmit()
    {
        $form = $_POST;
        $firmsUp = FirmUpQuery::create()
            ->useFirmQuery()
            ->filterByUser(UserQuery::create()->findPk($this->user->getId()))
            ->endUse()
            ->find();
        foreach ($firmsUp as $firmUp) {
            $firmUp->setSpamType($form['spam']);
            $firmUp->save();
        }
        $this->spamSettings();
    }

    public function hideFirm($firmId)
    {
        $firm = FirmQuery::create()
            ->filterById($firmId)
            ->filterByUser($this->user)
            ->innerJoinFirmUp()
            ->findOne();
        if ($firm) {
            $firm->setActive($firm->getActive() ? 0 : 1);
            $firm->save();
        }
        (new Router())->redirect('/профиль/компании');
    }

}