<?php
namespace wMVC\Core;

use \PDO, \PDOException, \PDOStatement;
use wMVC\Core\Exceptions\SystemExit;

abstract class Model
{
    public static $DBInstance = null;
    protected $db = null;
    public static $sql = array();
    public static $shutdownSet = 0;

    public function __construct()
    {
        $this->db = $this->getDBInstance();
        if (self::$shutdownSet == 0 && ENVIRONMENT == DEVELOPMENT) {
            register_shutdown_function(array($this, 'sqlTimers'));
            self::$shutdownSet = 1;
        }
    }

    public static function sqlTimers()
    {
        $totalTimer = (float)0;
        $string = '';
        foreach (self::$sql as $query) {
            $totalTimer += $query['timer'];
            $string .= sprintf("[%.5fs] %s\n", $query['timer'], $query['query']);
//            $string .= "{$query['query']} [{$query['timer']}]\n";
        }

        printf("<!--\n%s Total: %fs\n-->", $string, $totalTimer);
    }

    protected function getDBInstance()
    {
        if (!self::$DBInstance) {
            $dsn = sprintf('%s:dbname=%s;host=%s', DB_TYPE, DB_NAME, DB_HOST);
            try {
                self::$DBInstance = new PDO(
                    $dsn, DB_USER, DB_PASS, array(
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exception when error occurred
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC)       // set default fetch style
                );
            } catch (PDOException $e) {
                print $e->getMessage();
                throw new SystemExit('No database connection', SystemExit::DB_HAS_GONE_AWAY);
            }
            unset($dsn);
        }
        return self::$DBInstance;
    }

    /**
     * @param string $sql
     * @param array  $params
     *
     * @return PDOStatement
     */
    protected function query($sql, $params = array())
    {
        $timer = microtime(true);
        $query = $this->db->prepare($sql);
        $query->execute($params);
        self::$sql[] = array('query' => $sql, 'timer' => microtime(true) - $timer);
        return $query;
    }

    protected function getAll($sql, $params = array())
    {
        $query = $this->query($sql, $params);
        $result = $query->fetchAll();
        $query->closeCursor();
        return $result;
    }

    protected function getFirst($sql, $params = array())
    {
        $query = $this->query($sql, $params);
        $result = $query->fetch();
        $query->closeCursor();
        return $result;
    }

    protected function getAllCol($sql, $column = 0, $params = array())
    {
        $query = $this->query($sql, $params);
        $result = array();
        while ($col = $query->fetchColumn($column)) {
            $result[] = $col;
        }
        $query->closeCursor();
        return $result;
    }
}
