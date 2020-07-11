<?php
namespace wMVC\Core;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use wMVC\Core\Exceptions\SystemExit;
use wMVC\Core\Router;
use wMVC\Core\Debug\Debugger;

Class Application
{
    private $controller = null;
    private $action = null;
    private $params = array();

    public function __construct()
    {
        if (ENVIRONMENT == DEVELOPMENT) {
            $whoops = new Run();
            $whoops->pushHandler(new PrettyPageHandler());
            $whoops->register();
        }
        $this->run();
    }

    public function run()
    {
        try {
            list($this->controller, $this->action, $this->params) = (new Router)->dispatch();
            $controllerName = $this->controller;
            $this->loadController($this->controller);
            $this->isCallable($controllerName);
            
            DEBUG && Debugger::set('appRoute', array(\get_class($this->controller), $this->action, $this->params));

            call_user_func_array(array($this->controller, $this->action), $this->params);
        } catch (SystemExit $e) {
            print '<!--' . $e->getCode() . '-->';
            $this->error($e->getCode(), $e->getMessage());
        } catch (\PDOException $e) {
            print_r($e->getMessage());
        }
    }


    public function error($level, $message = null)
    {
        //implement error_log() with message here
        if (!empty($message)) {
            error_log('PHP SystemExit: ' . $message);
        }
        if (ENVIRONMENT == DEVELOPMENT) {
            print $message;
        }
        $this->controller = 'ErrorHandler';
        $this->action = 'code' . $level;
        $this->loadController($this->controller);
        $this->controller->{$this->action}();
    }

    private function isCallable($controllerName)
    {
        if (!method_exists($this->controller, $this->action)) {
            throw new SystemExit("Method {$this->action} in {$controllerName} not found", SystemExit::NOT_FOUND);
        }

        if (!(new \ReflectionMethod($this->controller, $this->action))->isPublic()) {
            throw new SystemExit("private/protected method {$this->action} called!", SystemExit::CALL_TO_PROTECTED_METHOD);
        }
    }

    private function loadController($controller)
    {
        $controller = '\\wMVC\\Controllers\\' . $controller;
        if (class_exists($controller)) {
            $this->controller = new $controller();
            return;
        }
        throw new SystemExit("controller {$controller} not found", SystemExit::NO_CONTROLLER);
    }
}