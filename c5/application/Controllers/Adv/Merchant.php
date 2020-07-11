<?php

namespace wMVC\Controllers;

use PropelModel\AdvServerOrders;
use wMVC\Core\Router;

class AdvMerchant extends AdvCore
{
    const MERCHANT_URL = 'https://auth.robokassa.ru/Merchant/Index.aspx';

    const MERCHANT_LOGIN = 'xn--80adsqinks2h.xn--p1ai';

    const ROBOKASSA_TEST = 0;
   
    const MERCHANT_PASS_1 = 'NMe10OHqtBEQf7n19FPU';
    const MERCHANT_PASS_2 = 'eKwVj9Rk21at77AvpbUY';

    //const MERCHANT_PASS_1 = 'v2K0Q4YBps9WvTz0QIWF'; //test
    //const MERCHANT_PASS_2 = 'wWc7aL6qmrRU7qZ3A9FL'; //test

    private $signatureValue;
    private $order;
    
    private static $tarifs = [
        'beta'      => 'Пробный',
        'all-in'    => 'Всё включено',
        'premium'   => 'Премиум'
    ];

    public function __construct(AdvServerOrders $order)
    {
        $this->order = $order;
        $this->setSignatureValue($order);
    }

    public function showMerchant($desc)
    {
        $order = $this->order;
        //http://docs.robokassa.ru/#4140
        $params = [
            'MrchLogin'      => self::MERCHANT_LOGIN,
            'OutSum'         => $order->getCash(),
            'InvId'          => $order->getId(),
            'Desc'           => $desc,
            'Receipt'        => urlencode($this->getReceipt($order)),
            'SignatureValue' => $this->signatureValue,
            'Culture'        => 'ru',
            'Email'          => $order->getEmail(),
            'isTest'         => self::ROBOKASSA_TEST
        ];

        $url = self::MERCHANT_URL . '?';
        foreach ($params AS $key => $value) {
            $url .= "{$key}={$value}&";
        }
        $url = trim($url, '&');

        (new Router())->redirect($url);
        Return true;
    }

    private function setSignatureValue(AdvServerOrders $order)
    {
        $this->signatureValue = md5(self::MERCHANT_LOGIN . ":{$order->getCash()}:{$order->getId()}:".$this->getReceipt($order).":" . self::MERCHANT_PASS_1);
    }

    public static function getSignatureValue($data)
    {
        return strtoupper(md5("{$data['OutSum']}:{$data['InvId']}:" . self::MERCHANT_PASS_2));
    }
    
    private function getReceipt(AdvServerOrders $order)
    {
        $result['sno'] = 'usn_income';
        $result['items'][] = [
            'name'      => "Поднятие компании, пакет ".self::$tarifs[$order->getType()],
            'quantity'  => $order->getMonths(),
            'sum'       => $order->getCash(),
            'tax'       => "none"
        ];
        return json_encode($result);
    }
}
