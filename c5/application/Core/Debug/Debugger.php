<?php

namespace wMVC\Core\Debug;

/**
 * Debugger
 */
class Debugger {

    protected static $data = [];

    public static function set($key, $value) {
        static::$data[$key] = $value;
    }

    public static function print() {
        
        if (!DEBUG) {
            return;
        }

        echo '<!-- DEBUGERRR ';
        \print_r(static::$data);
        echo '-->';
    }

}
