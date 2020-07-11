<?php

namespace wMVC\Controllers;


use Propel\Runtime\ActiveQuery\Criteria;
use PropelModel\AdvServerFirmUp;
use PropelModel\AdvServerOrders;
use PropelModel\AdvServerOrdersQuery;
use PropelModel\AdvServerPricesQuery;
use PropelModel\Base\CityQuery;
use PropelModel\Base\FirmUpQuery;
use PropelModel\FirmQuery;
use PropelModel\RegionQuery;
use wMVC\Core\Exceptions\SystemExit;
use wMVC\Core\Router;

final class AdvServer extends AdvCore
{

    const BETA = 'beta';

    public function showFront()
    {
        $this->setTitle('Твоя Фирма — Дополнительные возможности');

        print $this->view->render(
            '@adv/server/front.twig',
            []
        );
    }

    public function showCheckout($firmId)
    {
        

        $beta = false;

        $firmzz = FirmQuery::create()
            ->joinWith('Contact', Criteria::LEFT_JOIN)
            ->findById($firmId);

        /** @var \PropelModel\Firm $firm */
        $firm = $firmzz->getFirst();
        if (!$firm)
            throw new SystemExit("Firm not found " . __METHOD__, SystemExit::NOT_FOUND);
        
        if (!$firm->isApproved()) {
            print $this->view->render(
                    '@adv/server/firm_not_approved.twig',
                    [
                        'firm' => $firm,
                    ]
            );
            return;
        }

        $city = $firm->getRegion();

        $this->setTitle("Поднять компанию «{$firm->getName()}» {$city->getPrefix()} {$city->getCase(5)} — Твоя Фирма");

        if ($firm->getCreated() >= strtotime("-2 day") && $firm->getUser()) {
            $beta = true;
        }
        if ($firm->getFirmUps()->count()) {
            $beta = false;
        }

        print $this->view->render(
            '@adv/server/checkout.twig',
            [
                'city'     => $city,
                'firm'     => $firm,
                'contacts' => $firmzz->toArray()[0]['Contacts'],
                'beta'     => $beta,
                'prices'   => json_encode($this->getPriceList($city->getId()))
            ]
        );
    }

    public function submitCheckout($firmId)
    {
        $form = $_POST;
        $month = $form['date_type'];
        $firm = FirmQuery::create()->findPk($firmId);
        $priceList = $this->getPriceList($firm->getCityId());
        
        if (!$firm->isApproved()) {
            print $this->view->render(
                    '@adv/server/firm_not_approved.twig',
                    [
                        'firm' => $firm,
                    ]
            );
            return;
        }

        if (!empty($priceList[$form['type']][$month])) {

            $price = $priceList[$form['type']][$month];
            if (DEBUG) {
                $price = 1;
            }
            $desc = "Поднятие компании «{$firm->getName()}» на {$month} мес.";

            $order = new AdvServerOrders();
            $order->setCash($price);
            $order->setMonths($month);
            $order->setEmail($form['email']);
            $order->setType($form['type']);
            $order->setFirmId($firmId);
            $order->setStatus(0);
            $order->save();

            $merchant = new AdvMerchant($order);

            return $merchant->showMerchant($desc);

        } elseif ($this->isBeta($form['type'])) {
            $order = new AdvServerOrders();
            $order->setEmail($form['email']);
            $order->setType($form['type']);
            $order->setFirmId($firmId);
            $order->setStatus(1);
            $order->save();


            //Поднимаем на клиенте без оплаты на 7 дней
            $result = (new AdvClient())->request($order, $this->getSecret());
            $result = json_decode($result);

            $firm = FirmQuery::create()->findPk($order->getFirmId());
            $this->firmUp($firm, $order, $result->status);

            (new Router())->redirect($firm->getAlias());
        }

        throw new SystemExit("Price not found " . __METHOD__, SystemExit::NOT_FOUND);
    }

    private function getPriceList($cityId)
    {
        $prices = AdvServerPricesQuery::create()->findOneByCityUrl($cityId);
        if (!$prices) {
            $prices = AdvServerPricesQuery::create()
                ->filterByCityId(null)
                ->filterByCityUrl(null)
                ->findOne();
        }
        return unserialize(stream_get_contents($prices->getData(), -1, 0));
    }


    private function getFirmInfo($firmId)
    {
        $url = "http://{$this->getHost()}/adv/firm-info/{$firmId}/{$this->getSecret()}";
//        $firm = $this->request($url);
        $firm = FirmQuery::create()
            ->joinWith('Contact', Criteria::LEFT_JOIN)
            ->joinWith('User', Criteria::LEFT_JOIN)
            ->findById($firmId);

        return $firm ? $firm->toArray()[0] : null;
    }

    private function getCityInfo($cityUrl)
    {
//        $city = $this->request($url);
        $city = RegionQuery::create()->findOneByUrl($cityUrl);
        return $city;
    }


    /*
     * Если контрольные суммы совпали, то Ваш скрипт должен ответить ROBOKASSA,
    чтобы мы поняли, что Ваш скрипт работает правильно и повторное уведомление с нашей стороны не требуется.
    Результат должен содержать  текст OK и параметр InvId.
    Например, для номера счёта 5 должен быть возвращён вот такой ответ: OK5.
    */
    public function merchantResult($secret)
    {
        $this->checkSecret($secret);
        $merchant = $_POST;

        $crc = $merchant["SignatureValue"];
        $crc = strtoupper($crc);
        $my_crc = AdvMerchant::getSignatureValue($merchant);
        
        if ($my_crc == $crc) {
            $order = AdvServerOrdersQuery::create()->findOneById($merchant['InvId']);
            if ($order->getStatus() == 0) {
                //Поднимаем на клиенте после ответа из кассы
                $result = (new AdvClient())->request($order, $this->getSecret());
                $result = json_decode($result);
                $firm = FirmQuery::create()->findPk($order->getFirmId());
                $this->firmUp($firm, $order, $result->status);
                $order->setStatus($result->status);
                $order->save();
            }
            exit("OK{$order->getId()}");
        }
        throw new SystemExit("Fail md5 " . __METHOD__, SystemExit::ACCESS_DENIED);
    }


    public function merchantSuccess()
    {
        $merchant = $_POST;
        $order = AdvServerOrdersQuery::create()->findOneById($merchant['InvId']);
        $firm = FirmQuery::create()->findPk($order->getFirmId());
        (new Router())->redirect($firm->getAlias());
    }

    public function merchantCancel()
    {
        $order = AdvServerOrdersQuery::create()->findPk($_POST['InvId']);
        if (!$order) {
            throw new SystemExit('', 404);
        }
        $signature = md5(AdvMerchant::MERCHANT_LOGIN . ":{$order->getCash()}:{$order->getId()}:" . AdvMerchant::MERCHANT_PASS_1);
        $params = [
            'MrchLogin'      => AdvMerchant::MERCHANT_LOGIN,
            'OutSum'         => $order->getCash(),
            'InvId'          => $order->getId(),
            'Desc'           => 'Повторная попытка оплаты',
            'SignatureValue' => $signature,
            'Culture'        => 'ru',
            'Email'          => $order->getEmail(),
            'isTest'         => AdvMerchant::ROBOKASSA_TEST
        ];

        $url = AdvMerchant::MERCHANT_URL . '?';
        foreach ($params AS $key => $value) {
            $url .= "{$key}={$value}&";
        }
        $url = trim($url, '&');
        print $this->view->render(
            '@adv/server/cancel.twig',
            [
                'head' => [
                    'title' => 'Платеж отменен'
                ],
                'url'  => $url
            ]
        );

    }

    private function firmUp(\PropelModel\Firm $firm, AdvServerOrders $order, $status)
    {
        $firmUp = new AdvServerFirmUp();
        $firmUp->setFirmId($firm->getId());
        $firmUp->setAdvServerOrders($order);
        $firmUp->setName($firm->getName());
        $firmUp->setUrl($firm->getAlias());
        $firmUp->setStatus($status);
        $firmUp->save();
        $firm->setChangedTime(time());
        $firm->save();
        return $firmUp;
    }


    private function isBeta($tarif)
    {
        return self::BETA == $tarif;
    }


    public function aboutPage()
    {
        $this->setTitle('О нас');
        print $this->view->render(
            '@adv/server/about.twig',
            []
        );
    }

    public function contactPage()
    {
        $this->setTitle('Контакты');
        print $this->view->render(
            '@adv/server/contact.twig',
            []
        );
    }

}