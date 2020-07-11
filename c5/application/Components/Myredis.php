<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace wMVC\Components;

use Predis\Client;

/**
 * Description of Myredis
 *
 * @author alan_aleks <troshenkov@saytum.ru>
 */
class Myredis extends Client {

    public function myget($key) {
        $get = $this->get(\md5($key));

        if (!$get) {
            $this->incr('cache_miss');
            $this->expireat('cache_miss', strtotime('+999 days'));
            return $get;
        }

        $this->incr('cache_ok');
        $this->expireat('cache_ok', strtotime('+999 days'));

        return \gzdecode($get);
    }

    public function myset($key, $val, int $days = 7) {
        $newkey = \md5($key);
        $this->set($newkey, \gzencode($val, 4));
        $this->expireat($newkey, strtotime('+' . $days . ' days'));
    }

}
