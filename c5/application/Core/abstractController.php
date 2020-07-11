<?php

namespace wMVC\Core;

use Plasticbrain\FlashMessages\FlashMessages;
use wMVC\Components\Myredis;
use PropelModel\UserQuery;
use wMVC\Core\Exceptions\SystemExit;
use wMVC\Entity\Lang;
use wMVC\Entity\User;
use wMVC\Core\Debug\{
    TwigEnvironment,
    Debugger
};
use wMVC\Components\Mobile;

abstract class abstractController
{
    /**
     *
     * @var \Twig_Environment
     */
    static protected $twig = null;
    static protected $redis = null;
    /* @var $view \Twig_Environment */
    protected $view = null;
    
    /**
     * Redis cache
     * @access protected
     * @var Myredis
     */
    protected $cache = null;
    protected $models = array();
    protected $flash;
    protected $test = '';
    /* @var $view \PropelModel\User */
    protected $user = null;


    const ADMIN_ROLE = '1';
    const USER_ROLE = '0';

    public function __construct()
    {
        if (isset($_COOKIE['is_user'])) {
            $this->initSession();
        }

        User::init();
        if (!self::$twig) {
            $loader = new \Twig_Loader_Filesystem(TEMPLATES_PATH);
            // @ here to prevent notice, we only need return result of splice
            foreach (@array_splice(scandir(TEMPLATES_PATH), 2) as $path) {
                $loader->addPath(TEMPLATES_PATH . $path, $path);
            }
            
            if (DEBUG) {
                self::$twig = new TwigEnvironment(
                        $loader, array(
                    'cache' => APP_PATH . 'twig/cache/',
                    'auto_reload' => true
                        )
                );
                
                $function = new \Twig\TwigFunction('dbg', [Debugger::class, 'print']);
                self::$twig->addFunction($function);
            } else {
                self::$twig = new \Twig_Environment(
                        $loader, array(
                    'cache' => APP_PATH . 'twig/cache/',
                    'auto_reload' => ENVIRONMENT == DEVELOPMENT OR DEBUG ? true : false
                        )
                );
                
                $function = new \Twig\TwigFunction('dbg', function() {
                    //
                });
                self::$twig->addFunction($function);
            }
        }

        if (!self::$redis) {
            $single_server = self::getRedisConf();
            self::$redis = new Myredis($single_server, array('prefix' => 'c5:'));
            self::$redis->select($single_server['database']);
        }

        $this->cache = self::$redis;

        if (isset($_SESSION['user_id'])) {
            $this->user = UserQuery::create()->findPk($_SESSION['user_id']);
        }
        $this->view = self::$twig;

        if (User::authorized()) {
            $this->view->addGlobal('header_username', User::$data['login']);
        }

        /* Нужно переопределять в контроллерах */
        $this->view->addGlobal('googleads', 1);
        $this->view->addGlobal('main_domain', MAIN_DOMAIN);
        $canonical = "https://" . MAIN_DOMAIN . "{$_SERVER['REQUEST_URI']}";
        $this->view->addGlobal('canonical', $canonical);
        $this->view->addGlobal('isMobile', Mobile::isMobile());
        $this->view->addGlobal('isDebug', DEBUG);

        //        $this->initFlash();
    }
    
    public static function getRedisConf()
    {
        return array(
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 1,
        );
    }

    protected function sanitize_array($data = array())
    {
        if (!is_array($data) || !count($data)) {
            return array();
        }
        foreach ($data as $kkey => $value) {
            if (!is_array($value) && !is_object($value)) {
                $data[$kkey] = htmlspecialchars(trim(Lang::rip_tags($value)));
            }
            if (is_array($value)) {
                $data[$kkey] = self::sanitize_array($value);
            }
        }
        return $data;
    }

    protected function initSession()
    {
        if (!session_id()) {
            @session_start();
        }
    }

    protected function className()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    protected function destroySession()
    {
        session_unset();
        session_destroy();
    }

    protected function requireAuth()
    {
        if (!User::authorized()) {
            header('Location: /профиль/вход', true, 307);
            die();
        }
    }

    protected function requireAdmin()
    {
        $this->requireAuth();
        $user = UserQuery::create()->findPk(User::getId());
        if ($user->getRole() !== 1) {
            throw new SystemExit('', 404);
            die();
        }
    }

    protected function checkAccess($role)
    {
        if (!$this->user)
            throw new SystemExit("User not found. Access denied", SystemExit::ACCESS_DENIED);

        if ($this->user->getRole() == $role)
            return true;

        throw new SystemExit("Access denied", SystemExit::ACCESS_DENIED);
    }

    protected function initFlash()
    {
        $this->flash = new FlashMessages();
        $this->view->addGlobal('', $this->flash->display(null, false));
    }

    protected function setTitle($title)
    {
        $data['title'] = $title;
        $this->view->addGlobal('head', $data);
    }
}