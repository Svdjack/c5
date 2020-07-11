<?php
namespace wMVC\Core;

use Propel\Runtime\Propel;
use PropelModel\UrlAliases;
use PropelModel\UrlAliasesQuery;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use wMVC\Config;
use wMVC\Core\Debug\Debugger;
use wMVC\Core\Exceptions\SystemExit;

Class Router
{
    private $routes = array();

    public function dispatch()
    {
        $uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        //Redirect to same page but without trailing slash
        if (($uri != $path = rtrim($uri, '/')) && $uri != '/') {
            $this->redirect($path);
        }

        //Check if alias for this url exists
        $uri = $this->checkUrlAlias($uri);

        $dispatcher = \FastRoute\cachedDispatcher(
            function (RouteCollector $r) {
            $ext = require __DIR__.'/../config/routes.php';
                foreach ($ext as $route) {
                    $r->addRoute($route['method'], $route['pattern'], $route['handler']);
                }
                foreach (Config::$routes as $route) {
                    $r->addRoute($route['method'], $route['pattern'], $route['handler']);
                }
                
            },
            ['cacheFile' => APP_PATH . 'route.cache']
        );

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        DEBUG && Debugger::set('routeInfo', $routeInfo);
        if ($routeInfo[0] == Dispatcher::FOUND) {
            return array($routeInfo[1][0], $routeInfo[1][1], $routeInfo[2]);
        }

        //If none of above is right - parse url like controller/action/...args
        throw new SystemExit('there is no route liek this', SystemExit::NOT_FOUND);
    }

    public function redirect($location)
    {
        header('Location: ' . $location);
        die();
    }

    public function getUrl()
    {
        return !empty($_GET['q']) ? $_GET['q'] : null;
    }

    public function checkUrlAlias($path)
    {
        if ($alias_path = UrlAliasesQuery::create()->findOneByAlias($path)) {
            return $alias_path->getSource();
        }
        return $path;
    }
}