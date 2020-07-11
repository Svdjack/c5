<?php

namespace PropelModel\Base;

use \Exception;
use \PDO;
use PropelModel\Region as ChildRegion;
use PropelModel\RegionQuery as ChildRegionQuery;
use PropelModel\Map\RegionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'region' table.
 *
 *
 *
 * @method     ChildRegionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildRegionQuery orderByArea($order = Criteria::ASC) Order by the area column
 * @method     ChildRegionQuery orderByTelcode($order = Criteria::ASC) Order by the telcode column
 * @method     ChildRegionQuery orderByTimezone($order = Criteria::ASC) Order by the timezone column
 * @method     ChildRegionQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildRegionQuery orderByUrl($order = Criteria::ASC) Order by the url column
 * @method     ChildRegionQuery orderByCount($order = Criteria::ASC) Order by the count column
 * @method     ChildRegionQuery orderByData($order = Criteria::ASC) Order by the data column
 * @method     ChildRegionQuery orderByLon($order = Criteria::ASC) Order by the lon column
 * @method     ChildRegionQuery orderByLat($order = Criteria::ASC) Order by the lat column
 *
 * @method     ChildRegionQuery groupById() Group by the id column
 * @method     ChildRegionQuery groupByArea() Group by the area column
 * @method     ChildRegionQuery groupByTelcode() Group by the telcode column
 * @method     ChildRegionQuery groupByTimezone() Group by the timezone column
 * @method     ChildRegionQuery groupByName() Group by the name column
 * @method     ChildRegionQuery groupByUrl() Group by the url column
 * @method     ChildRegionQuery groupByCount() Group by the count column
 * @method     ChildRegionQuery groupByData() Group by the data column
 * @method     ChildRegionQuery groupByLon() Group by the lon column
 * @method     ChildRegionQuery groupByLat() Group by the lat column
 *
 * @method     ChildRegionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildRegionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildRegionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildRegionQuery leftJoinStat($relationAlias = null) Adds a LEFT JOIN clause to the query using the Stat relation
 * @method     ChildRegionQuery rightJoinStat($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Stat relation
 * @method     ChildRegionQuery innerJoinStat($relationAlias = null) Adds a INNER JOIN clause to the query using the Stat relation
 *
 * @method     ChildRegionQuery leftJoinAdvServerPrices($relationAlias = null) Adds a LEFT JOIN clause to the query using the AdvServerPrices relation
 * @method     ChildRegionQuery rightJoinAdvServerPrices($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AdvServerPrices relation
 * @method     ChildRegionQuery innerJoinAdvServerPrices($relationAlias = null) Adds a INNER JOIN clause to the query using the AdvServerPrices relation
 *
 * @method     ChildRegionQuery leftJoinFirm($relationAlias = null) Adds a LEFT JOIN clause to the query using the Firm relation
 * @method     ChildRegionQuery rightJoinFirm($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Firm relation
 * @method     ChildRegionQuery innerJoinFirm($relationAlias = null) Adds a INNER JOIN clause to the query using the Firm relation
 *
 * @method     \PropelModel\StatQuery|\PropelModel\AdvServerPricesQuery|\PropelModel\FirmQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildRegion findOne(ConnectionInterface $con = null) Return the first ChildRegion matching the query
 * @method     ChildRegion findOneOrCreate(ConnectionInterface $con = null) Return the first ChildRegion matching the query, or a new ChildRegion object populated from the query conditions when no match is found
 *
 * @method     ChildRegion findOneById(int $id) Return the first ChildRegion filtered by the id column
 * @method     ChildRegion findOneByArea(int $area) Return the first ChildRegion filtered by the area column
 * @method     ChildRegion findOneByTelcode(int $telcode) Return the first ChildRegion filtered by the telcode column
 * @method     ChildRegion findOneByTimezone(string $timezone) Return the first ChildRegion filtered by the timezone column
 * @method     ChildRegion findOneByName(string $name) Return the first ChildRegion filtered by the name column
 * @method     ChildRegion findOneByUrl(string $url) Return the first ChildRegion filtered by the url column
 * @method     ChildRegion findOneByCount(int $count) Return the first ChildRegion filtered by the count column
 * @method     ChildRegion findOneByData(resource $data) Return the first ChildRegion filtered by the data column
 * @method     ChildRegion findOneByLon(double $lon) Return the first ChildRegion filtered by the lon column
 * @method     ChildRegion findOneByLat(double $lat) Return the first ChildRegion filtered by the lat column *

 * @method     ChildRegion requirePk($key, ConnectionInterface $con = null) Return the ChildRegion by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRegion requireOne(ConnectionInterface $con = null) Return the first ChildRegion matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRegion requireOneById(int $id) Return the first ChildRegion filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRegion requireOneByArea(int $area) Return the first ChildRegion filtered by the area column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRegion requireOneByTelcode(int $telcode) Return the first ChildRegion filtered by the telcode column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRegion requireOneByTimezone(string $timezone) Return the first ChildRegion filtered by the timezone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRegion requireOneByName(string $name) Return the first ChildRegion filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRegion requireOneByUrl(string $url) Return the first ChildRegion filtered by the url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRegion requireOneByCount(int $count) Return the first ChildRegion filtered by the count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRegion requireOneByData(resource $data) Return the first ChildRegion filtered by the data column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRegion requireOneByLon(double $lon) Return the first ChildRegion filtered by the lon column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRegion requireOneByLat(double $lat) Return the first ChildRegion filtered by the lat column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRegion[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildRegion objects based on current ModelCriteria
 * @method     ChildRegion[]|ObjectCollection findById(int $id) Return ChildRegion objects filtered by the id column
 * @method     ChildRegion[]|ObjectCollection findByArea(int $area) Return ChildRegion objects filtered by the area column
 * @method     ChildRegion[]|ObjectCollection findByTelcode(int $telcode) Return ChildRegion objects filtered by the telcode column
 * @method     ChildRegion[]|ObjectCollection findByTimezone(string $timezone) Return ChildRegion objects filtered by the timezone column
 * @method     ChildRegion[]|ObjectCollection findByName(string $name) Return ChildRegion objects filtered by the name column
 * @method     ChildRegion[]|ObjectCollection findByUrl(string $url) Return ChildRegion objects filtered by the url column
 * @method     ChildRegion[]|ObjectCollection findByCount(int $count) Return ChildRegion objects filtered by the count column
 * @method     ChildRegion[]|ObjectCollection findByData(resource $data) Return ChildRegion objects filtered by the data column
 * @method     ChildRegion[]|ObjectCollection findByLon(double $lon) Return ChildRegion objects filtered by the lon column
 * @method     ChildRegion[]|ObjectCollection findByLat(double $lat) Return ChildRegion objects filtered by the lat column
 * @method     ChildRegion[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class RegionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \PropelModel\Base\RegionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\PropelModel\\Region', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildRegionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildRegionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildRegionQuery) {
            return $criteria;
        }
        $query = new ChildRegionQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildRegion|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = RegionTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(RegionTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildRegion A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, area, telcode, timezone, name, url, count, data, lon, lat FROM region WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildRegion $obj */
            $obj = new ChildRegion();
            $obj->hydrate($row);
            RegionTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildRegion|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(RegionTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(RegionTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(RegionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(RegionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RegionTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the area column
     *
     * Example usage:
     * <code>
     * $query->filterByArea(1234); // WHERE area = 1234
     * $query->filterByArea(array(12, 34)); // WHERE area IN (12, 34)
     * $query->filterByArea(array('min' => 12)); // WHERE area > 12
     * </code>
     *
     * @param     mixed $area The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function filterByArea($area = null, $comparison = null)
    {
        if (is_array($area)) {
            $useMinMax = false;
            if (isset($area['min'])) {
                $this->addUsingAlias(RegionTableMap::COL_AREA, $area['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($area['max'])) {
                $this->addUsingAlias(RegionTableMap::COL_AREA, $area['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RegionTableMap::COL_AREA, $area, $comparison);
    }

    /**
     * Filter the query on the telcode column
     *
     * Example usage:
     * <code>
     * $query->filterByTelcode(1234); // WHERE telcode = 1234
     * $query->filterByTelcode(array(12, 34)); // WHERE telcode IN (12, 34)
     * $query->filterByTelcode(array('min' => 12)); // WHERE telcode > 12
     * </code>
     *
     * @param     mixed $telcode The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function filterByTelcode($telcode = null, $comparison = null)
    {
        if (is_array($telcode)) {
            $useMinMax = false;
            if (isset($telcode['min'])) {
                $this->addUsingAlias(RegionTableMap::COL_TELCODE, $telcode['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($telcode['max'])) {
                $this->addUsingAlias(RegionTableMap::COL_TELCODE, $telcode['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RegionTableMap::COL_TELCODE, $telcode, $comparison);
    }

    /**
     * Filter the query on the timezone column
     *
     * Example usage:
     * <code>
     * $query->filterByTimezone('fooValue');   // WHERE timezone = 'fooValue'
     * $query->filterByTimezone('%fooValue%'); // WHERE timezone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timezone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function filterByTimezone($timezone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timezone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $timezone)) {
                $timezone = str_replace('*', '%', $timezone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(RegionTableMap::COL_TIMEZONE, $timezone, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(RegionTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE url = 'fooValue'
     * $query->filterByUrl('%fooValue%'); // WHERE url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $url)) {
                $url = str_replace('*', '%', $url);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(RegionTableMap::COL_URL, $url, $comparison);
    }

    /**
     * Filter the query on the count column
     *
     * Example usage:
     * <code>
     * $query->filterByCount(1234); // WHERE count = 1234
     * $query->filterByCount(array(12, 34)); // WHERE count IN (12, 34)
     * $query->filterByCount(array('min' => 12)); // WHERE count > 12
     * </code>
     *
     * @param     mixed $count The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function filterByCount($count = null, $comparison = null)
    {
        if (is_array($count)) {
            $useMinMax = false;
            if (isset($count['min'])) {
                $this->addUsingAlias(RegionTableMap::COL_COUNT, $count['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($count['max'])) {
                $this->addUsingAlias(RegionTableMap::COL_COUNT, $count['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RegionTableMap::COL_COUNT, $count, $comparison);
    }

    /**
     * Filter the query on the data column
     *
     * @param     mixed $data The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function filterByData($data = null, $comparison = null)
    {

        return $this->addUsingAlias(RegionTableMap::COL_DATA, $data, $comparison);
    }

    /**
     * Filter the query on the lon column
     *
     * Example usage:
     * <code>
     * $query->filterByLon(1234); // WHERE lon = 1234
     * $query->filterByLon(array(12, 34)); // WHERE lon IN (12, 34)
     * $query->filterByLon(array('min' => 12)); // WHERE lon > 12
     * </code>
     *
     * @param     mixed $lon The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function filterByLon($lon = null, $comparison = null)
    {
        if (is_array($lon)) {
            $useMinMax = false;
            if (isset($lon['min'])) {
                $this->addUsingAlias(RegionTableMap::COL_LON, $lon['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lon['max'])) {
                $this->addUsingAlias(RegionTableMap::COL_LON, $lon['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RegionTableMap::COL_LON, $lon, $comparison);
    }

    /**
     * Filter the query on the lat column
     *
     * Example usage:
     * <code>
     * $query->filterByLat(1234); // WHERE lat = 1234
     * $query->filterByLat(array(12, 34)); // WHERE lat IN (12, 34)
     * $query->filterByLat(array('min' => 12)); // WHERE lat > 12
     * </code>
     *
     * @param     mixed $lat The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function filterByLat($lat = null, $comparison = null)
    {
        if (is_array($lat)) {
            $useMinMax = false;
            if (isset($lat['min'])) {
                $this->addUsingAlias(RegionTableMap::COL_LAT, $lat['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lat['max'])) {
                $this->addUsingAlias(RegionTableMap::COL_LAT, $lat['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RegionTableMap::COL_LAT, $lat, $comparison);
    }

    /**
     * Filter the query by a related \PropelModel\Stat object
     *
     * @param \PropelModel\Stat|ObjectCollection $stat the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRegionQuery The current query, for fluid interface
     */
    public function filterByStat($stat, $comparison = null)
    {
        if ($stat instanceof \PropelModel\Stat) {
            return $this
                ->addUsingAlias(RegionTableMap::COL_ID, $stat->getCityId(), $comparison);
        } elseif ($stat instanceof ObjectCollection) {
            return $this
                ->useStatQuery()
                ->filterByPrimaryKeys($stat->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStat() only accepts arguments of type \PropelModel\Stat or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Stat relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function joinStat($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Stat');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Stat');
        }

        return $this;
    }

    /**
     * Use the Stat relation Stat object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\StatQuery A secondary query class using the current class as primary query
     */
    public function useStatQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStat($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Stat', '\PropelModel\StatQuery');
    }

    /**
     * Filter the query by a related \PropelModel\AdvServerPrices object
     *
     * @param \PropelModel\AdvServerPrices|ObjectCollection $advServerPrices the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRegionQuery The current query, for fluid interface
     */
    public function filterByAdvServerPrices($advServerPrices, $comparison = null)
    {
        if ($advServerPrices instanceof \PropelModel\AdvServerPrices) {
            return $this
                ->addUsingAlias(RegionTableMap::COL_ID, $advServerPrices->getCityId(), $comparison);
        } elseif ($advServerPrices instanceof ObjectCollection) {
            return $this
                ->useAdvServerPricesQuery()
                ->filterByPrimaryKeys($advServerPrices->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAdvServerPrices() only accepts arguments of type \PropelModel\AdvServerPrices or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AdvServerPrices relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function joinAdvServerPrices($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AdvServerPrices');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'AdvServerPrices');
        }

        return $this;
    }

    /**
     * Use the AdvServerPrices relation AdvServerPrices object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\AdvServerPricesQuery A secondary query class using the current class as primary query
     */
    public function useAdvServerPricesQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAdvServerPrices($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AdvServerPrices', '\PropelModel\AdvServerPricesQuery');
    }

    /**
     * Filter the query by a related \PropelModel\Firm object
     *
     * @param \PropelModel\Firm|ObjectCollection $firm the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRegionQuery The current query, for fluid interface
     */
    public function filterByFirm($firm, $comparison = null)
    {
        if ($firm instanceof \PropelModel\Firm) {
            return $this
                ->addUsingAlias(RegionTableMap::COL_ID, $firm->getCityId(), $comparison);
        } elseif ($firm instanceof ObjectCollection) {
            return $this
                ->useFirmQuery()
                ->filterByPrimaryKeys($firm->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFirm() only accepts arguments of type \PropelModel\Firm or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Firm relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function joinFirm($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Firm');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Firm');
        }

        return $this;
    }

    /**
     * Use the Firm relation Firm object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\FirmQuery A secondary query class using the current class as primary query
     */
    public function useFirmQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFirm($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Firm', '\PropelModel\FirmQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildRegion $region Object to remove from the list of results
     *
     * @return $this|ChildRegionQuery The current query, for fluid interface
     */
    public function prune($region = null)
    {
        if ($region) {
            $this->addUsingAlias(RegionTableMap::COL_ID, $region->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the region table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RegionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            RegionTableMap::clearInstancePool();
            RegionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RegionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(RegionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            RegionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            RegionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // RegionQuery
