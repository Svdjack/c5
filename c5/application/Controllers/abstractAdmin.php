<?php
namespace wMVC\Controllers;

use wMVC\Core\abstractController;
use wMVC\Core\Exceptions\SystemExit;
use wMVC\Entity\User;

// тут будет апи для админки
abstract Class abstractAdmin extends abstractController
{

    public function __construct() {
        parent::__construct();
        $this->cache->flushdb();
    }
    
    protected function response($response, $code = 200)
    {
        static $sent = 0; // we want to send only one response per one request... but don't die in process
        if (!$sent) {
            $sent = 1;
            //header(':', true, $code);
            self::render($response);
        }
    }

    private function render($array)
    {
        header("Content-Type: application/json");
        print json_encode($array);
    }

    protected function requireAdmin()
    {
        parent::requireAuth();
        if(!User::$data['is_admin']){
            throw new SystemExit('', 404);
        }
    }

    protected function getInputContent()
    {
        return file_get_contents('php://input');
    }
}