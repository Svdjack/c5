<?php

namespace wMVC\Controllers\Search;

use MCurl\Client;
use wMVC\Core\abstractController;
use wMVC\Core\Debug\Debugger;

class Core extends abstractController
{
    const PROJECT_ID = 3;
    const KOTEL_USER = 'kotel';
    const KOTEL_PASS = 'rjnkj,fpf';
    const API_KEY = 'searchkotel';
    const API_URL = 'http://kotel.spravka.today/api/search/';

    /**
     * @var Client
     * */
    protected $client;

    protected $options = [];

    public function __construct()
    {
        parent::__construct();
        $this->setOptions();
        $this->setClient();
    }


    /**
     * @return Client
     */
    private function setClient()
    {
        $this->client = new Client();
        $this->client->setCurlOption([CURLOPT_USERPWD => self::KOTEL_USER . ':' . self::KOTEL_PASS]);
        return $this->client;
    }


    protected function get($api, $params)
    {
        $params['key'] = self::API_KEY;
        $params['project_id'] = self::PROJECT_ID;

        $url = self::API_URL . "{$api}?" . http_build_query($params);
        
        DEBUG && Debugger::set('kotel_url_' . \time(), $url);

        $result = $this->client->get($url);
        
        DEBUG && Debugger::set('kotel_data_' . \time(), \print_r($result->getJson(\JSON_UNESCAPED_UNICODE), true));

        if (!$result->hasError()) {
            return $result->getJson(JSON_OBJECT_AS_ARRAY);
        }
        return ['items' => [], 'total' => 0];
    }


    protected function post($api, $data)
    {
        $params['key'] = self::API_KEY;
        $params['project_id'] = self::PROJECT_ID;

        $url = self::API_URL . "{$api}?" . http_build_query($params);

        $result = $this->client->post($url, ['data' => json_encode($data)]);
        return $result->getJson(true);
    }

    protected function setOptions()
    {
        $options = [
            'page'       => 1,
            'city'       => '',
            'worktime'   => null,
            'time'       => null,
            'workdays'   => null,
            'is24x7'     => null,
            'near'       => null,
            'distance'   => 2000,
            'site'       => null,
            'street'     => null,
            'district'   => null,
            'attributes' => null,
            'review'     => null,
            'by_rating'  => null,
            'by_title'   => null,
        ];
        foreach ($options as $key => $option) {
            $this->options[$key] = empty($_GET[$key]) ? $option : $_GET[$key];
        }
        return $this->options;
    }

}