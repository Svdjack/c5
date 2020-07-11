<?php

namespace PropelModel\Base;

use \Exception;
use \PDO;
use PropelModel\AdvServerOrders as ChildAdvServerOrders;
use PropelModel\AdvServerOrdersQuery as ChildAdvServerOrdersQuery;
use PropelModel\Map\AdvServerOrdersTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'adv_server_orders' table.
 *
 *
 *
 * @method     ChildAdvServerOrdersQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAdvServerOrdersQuery orderByMonths($order = Criteria::ASC) Order by the months column
 * @method     ChildAdvServerOrdersQuery orderByCash($order = Criteria::ASC) Order by the cash column
 * @method     ChildAdvServerOrdersQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildAdvServerOrdersQuery orderByCityUrl($order = Criteria::ASC) Order by the city_url column
 * @method     ChildAdvServerOrdersQuery orderByFirmId($order = Criteria::ASC) Order by the firm_id column
 * @method     ChildAdvServerOrdersQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildAdvServerOrdersQuery orderByStatus($order = Criteria::ASC) Order by the status column
 *
 * @method     ChildAdvServerOrdersQuery groupById() Group by the id column
 * @method     ChildAdvServerOrdersQuery groupByMonths() Group by the months column
 * @method     ChildAdvServerOrdersQuery groupByCash() Group by the cash column
 * @method     ChildAdvServerOrdersQuery groupByType() Group by the type column
 * @method     ChildAdvServerOrdersQuery groupByCityUrl() Group by the city_url column
 * @method     ChildAdvServerOrdersQuery groupByFirmId() Group by the firm_id column
 * @method     ChildAdvServerOrdersQuery groupByEmail() Group by the email column
 * @method     ChildAdvServerOrdersQuery groupByStatus() Group by the status column
 *
 * @method     ChildAdvServerOrdersQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAdvServerOrdersQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAdvServerOrdersQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAdvServerOrdersQuery leftJoinFirm($relationAlias = null) Adds a LEFT JOIN clause to the query using the Firm relation
 * @method     ChildAdvServerOrdersQuery rightJoinFirm($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Firm relation
 * @method     ChildAdvServerOrdersQuery innerJoinFirm($relationAlias = null) Adds a INNER JOIN clause to the query using the Firm relation
 *
 * @method     ChildAdvServerOrdersQuery leftJoinAdvServerFirmUp($relationAlias = null) Adds a LEFT JOIN clause to the query using the AdvServerFirmUp relation
 * @method     ChildAdvServerOrdersQuery rightJoinAdvServerFirmUp($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AdvServerFirmUp relation
 * @method     ChildAdvServerOrdersQuery innerJoinAdvServerFirmUp($relationAlias = null) Adds a INNER JOIN clause to the query using the AdvServerFirmUp relation
 *
 * @method     \PropelModel\FirmQuery|\PropelModel\AdvServerFirmUpQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAdvServerOrders findOne(ConnectionInterface $con = null) Return the first ChildAdvServerOrders matching the query
 * @method     ChildAdvServerOrders findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAdvServerOrders matching the query, or a new ChildAdvServerOrders object populated from the query conditions when no match is found
 *
 * @method     ChildAdvServerOrders findOneById(int $id) Return the first ChildAdvServerOrders filtered by the id column
 * @method     ChildAdvServerOrders findOneByMonths(int $months) Return the first ChildAdvServerOrders filtered by the months column
 * @method     ChildAdvServerOrders findOneByCash(int $cash) Return the first ChildAdvServerOrders filtered by the cash column
 * @method     ChildAdvServerOrders findOneByType(string $type) Return the first ChildAdvServerOrders filtered by the type column
 * @method     ChildAdvServerOrders findOneByCityUrl(string $city_url) Return the first ChildAdvServerOrders filtered by the city_url column
 * @method     ChildAdvServerOrders findOneByFirmId(int $firm_id) Return the first ChildAdvServerOrders filtered by the firm_id column
 * @method     ChildAdvServerOrders findOneByEmail(string $email) Return the first ChildAdvServerOrders filtered by the email column
 * @method     ChildAdvServerOrders findOneByStatus(int $status) Return the first ChildAdvServerOrders filtered by the status column *

 * @method     ChildAdvServerOrders requirePk($key, ConnectionInterface $con = null) Return the ChildAdvServerOrders by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdvServerOrders requireOne(ConnectionInterface $con = null) Return the first ChildAdvServerOrders matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAdvServerOrders requireOneById(int $id) Return the first ChildAdvServerOrders filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdvServerOrders requireOneByMonths(int $months) Return the first ChildAdvServerOrders filtered by the months column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdvServerOrders requireOneByCash(int $cash) Return the first ChildAdvServerOrders filtered by the cash column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdvServerOrders requireOneByType(string $type) Return the first ChildAdvServerOrders filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdvServerOrders requireOneByCityUrl(string $city_url) Return the first ChildAdvServerOrders filtered by the city_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdvServerOrders requireOneByFirmId(int $firm_id) Return the first ChildAdvServerOrders filtered by the firm_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdvServerOrders requireOneByEmail(string $email) Return the first ChildAdvServerOrders filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdvServerOrders requireOneByStatus(int $status) Return the first ChildAdvServerOrders filtered by the status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAdvServerOrders[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAdvServerOrders objects based on current ModelCriteria
 * @method     ChildAdvServerOrders[]|ObjectCollection findById(int $id) Return ChildAdvServerOrders objects filtered by the id column
 * @method     ChildAdvServerOrders[]|ObjectCollection findByMonths(int $months) Return ChildAdvServerOrders objects filtered by the months column
 * @method     ChildAdvServerOrders[]|ObjectCollection findByCash(int $cash) Return ChildAdvServerOrders objects filtered by the cash column
 * @method     ChildAdvServerOrders[]|ObjectCollection findByType(string $type) Return ChildAdvServerOrders objects filtered by the type column
 * @method     ChildAdvServerOrders[]|ObjectCollection findByCityUrl(string $city_url) Return ChildAdvServerOrders objects filtered by the city_url column
 * @method     ChildAdvServerOrders[]|ObjectCollection findByFirmId(int $firm_id) Return ChildAdvServerOrders objects filtered by the firm_id column
 * @method     ChildAdvServerOrders[]|ObjectCollection findByEmail(string $email) Return ChildAdvServerOrders objects filtered by the email column
 * @method     ChildAdvServerOrders[]|ObjectCollection findByStatus(int $status) Return ChildAdvServerOrders objects filtered by the status column
 * @method     ChildAdvServerOrders[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AdvServerOrdersQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \PropelModel\Base\AdvServerOrdersQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\PropelModel\\AdvServerOrders', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAdvServerOrdersQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAdvServerOrdersQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAdvServerOrdersQuery) {
            return $criteria;
        }
        $query = new ChildAdvServerOrdersQuery();
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
     * @return ChildAdvServerOrders|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AdvServerOrdersTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AdvServerOrdersTableMap::DATABASE_NAME);
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
     * @return ChildAdvServerOrders A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, months, cash, type, city_url, firm_id, email, status FROM adv_server_orders WHERE id = :p0';
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
            /** @var ChildAdvServerOrders $obj */
            $obj = new ChildAdvServerOrders();
            $obj->hydrate($row);
            AdvServerOrdersTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildAdvServerOrders|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AdvServerOrdersTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AdvServerOrdersTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AdvServerOrdersTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AdvServerOrdersTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdvServerOrdersTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the months column
     *
     * Example usage:
     * <code>
     * $query->filterByMonths(1234); // WHERE months = 1234
     * $query->filterByMonths(array(12, 34)); // WHERE months IN (12, 34)
     * $query->filterByMonths(array('min' => 12)); // WHERE months > 12
     * </code>
     *
     * @param     mixed $months The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function filterByMonths($months = null, $comparison = null)
    {
        if (is_array($months)) {
            $useMinMax = false;
            if (isset($months['min'])) {
                $this->addUsingAlias(AdvServerOrdersTableMap::COL_MONTHS, $months['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($months['max'])) {
                $this->addUsingAlias(AdvServerOrdersTableMap::COL_MONTHS, $months['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdvServerOrdersTableMap::COL_MONTHS, $months, $comparison);
    }

    /**
     * Filter the query on the cash column
     *
     * Example usage:
     * <code>
     * $query->filterByCash(1234); // WHERE cash = 1234
     * $query->filterByCash(array(12, 34)); // WHERE cash IN (12, 34)
     * $query->filterByCash(array('min' => 12)); // WHERE cash > 12
     * </code>
     *
     * @param     mixed $cash The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function filterByCash($cash = null, $comparison = null)
    {
        if (is_array($cash)) {
            $useMinMax = false;
            if (isset($cash['min'])) {
                $this->addUsingAlias(AdvServerOrdersTableMap::COL_CASH, $cash['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cash['max'])) {
                $this->addUsingAlias(AdvServerOrdersTableMap::COL_CASH, $cash['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdvServerOrdersTableMap::COL_CASH, $cash, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%'); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $type)) {
                $type = str_replace('*', '%', $type);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdvServerOrdersTableMap::COL_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the city_url column
     *
     * Example usage:
     * <code>
     * $query->filterByCityUrl('fooValue');   // WHERE city_url = 'fooValue'
     * $query->filterByCityUrl('%fooValue%'); // WHERE city_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cityUrl The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function filterByCityUrl($cityUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cityUrl)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $cityUrl)) {
                $cityUrl = str_replace('*', '%', $cityUrl);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdvServerOrdersTableMap::COL_CITY_URL, $cityUrl, $comparison);
    }

    /**
     * Filter the query on the firm_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFirmId(1234); // WHERE firm_id = 1234
     * $query->filterByFirmId(array(12, 34)); // WHERE firm_id IN (12, 34)
     * $query->filterByFirmId(array('min' => 12)); // WHERE firm_id > 12
     * </code>
     *
     * @see       filterByFirm()
     *
     * @param     mixed $firmId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function filterByFirmId($firmId = null, $comparison = null)
    {
        if (is_array($firmId)) {
            $useMinMax = false;
            if (isset($firmId['min'])) {
                $this->addUsingAlias(AdvServerOrdersTableMap::COL_FIRM_ID, $firmId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($firmId['max'])) {
                $this->addUsingAlias(AdvServerOrdersTableMap::COL_FIRM_ID, $firmId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdvServerOrdersTableMap::COL_FIRM_ID, $firmId, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdvServerOrdersTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(1234); // WHERE status = 1234
     * $query->filterByStatus(array(12, 34)); // WHERE status IN (12, 34)
     * $query->filterByStatus(array('min' => 12)); // WHERE status > 12
     * </code>
     *
     * @param     mixed $status The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(AdvServerOrdersTableMap::COL_STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(AdvServerOrdersTableMap::COL_STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdvServerOrdersTableMap::COL_STATUS, $status, $comparison);
    }

    /**
     * Filter the query by a related \PropelModel\Firm object
     *
     * @param \PropelModel\Firm|ObjectCollection $firm The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function filterByFirm($firm, $comparison = null)
    {
        if ($firm instanceof \PropelModel\Firm) {
            return $this
                ->addUsingAlias(AdvServerOrdersTableMap::COL_FIRM_ID, $firm->getId(), $comparison);
        } elseif ($firm instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AdvServerOrdersTableMap::COL_FIRM_ID, $firm->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildAdvServerOrdersQuery The current query, for fluid interface
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
     * Filter the query by a related \PropelModel\AdvServerFirmUp object
     *
     * @param \PropelModel\AdvServerFirmUp|ObjectCollection $advServerFirmUp the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function filterByAdvServerFirmUp($advServerFirmUp, $comparison = null)
    {
        if ($advServerFirmUp instanceof \PropelModel\AdvServerFirmUp) {
            return $this
                ->addUsingAlias(AdvServerOrdersTableMap::COL_ID, $advServerFirmUp->getFirmId(), $comparison);
        } elseif ($advServerFirmUp instanceof ObjectCollection) {
            return $this
                ->useAdvServerFirmUpQuery()
                ->filterByPrimaryKeys($advServerFirmUp->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAdvServerFirmUp() only accepts arguments of type \PropelModel\AdvServerFirmUp or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AdvServerFirmUp relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function joinAdvServerFirmUp($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AdvServerFirmUp');

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
            $this->addJoinObject($join, 'AdvServerFirmUp');
        }

        return $this;
    }

    /**
     * Use the AdvServerFirmUp relation AdvServerFirmUp object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\AdvServerFirmUpQuery A secondary query class using the current class as primary query
     */
    public function useAdvServerFirmUpQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAdvServerFirmUp($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AdvServerFirmUp', '\PropelModel\AdvServerFirmUpQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAdvServerOrders $advServerOrders Object to remove from the list of results
     *
     * @return $this|ChildAdvServerOrdersQuery The current query, for fluid interface
     */
    public function prune($advServerOrders = null)
    {
        if ($advServerOrders) {
            $this->addUsingAlias(AdvServerOrdersTableMap::COL_ID, $advServerOrders->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the adv_server_orders table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AdvServerOrdersTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AdvServerOrdersTableMap::clearInstancePool();
            AdvServerOrdersTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AdvServerOrdersTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AdvServerOrdersTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AdvServerOrdersTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AdvServerOrdersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // AdvServerOrdersQuery
