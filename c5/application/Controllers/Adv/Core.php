<?php

namespace wMVC\Controllers;

use wMVC\Core\abstractController;
use wMVC\Core\Exceptions\SystemExit;

class AdvCore extends abstractController
{

    const SECRET = 'oihrs0987e56s9i';

    const HOST = 'твояфирма.рф';
    
    protected function get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'ADV SYSTEM');
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output);
    }

    protected function checkSecret($secret)
    {
        if ($secret != $this->getSecret())
            throw new SystemExit("Attacked!!! BAD SECRET {$secret} " . __METHOD__, SystemExit::ACCESS_DENIED);
    }

    protected function getSecret()
    {
        return self::SECRET;
    }

    protected function getHost()
    {
        return self::HOST;
    }

    protected function showJson($value = [])
    {
        header('Content-type: application/json');
        exit(json_encode($value));
    }
}