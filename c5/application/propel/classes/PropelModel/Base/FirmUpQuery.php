<?php

namespace PropelModel\Base;

use \Exception;
use \PDO;
use PropelModel\FirmUp as ChildFirmUp;
use PropelModel\FirmUpQuery as ChildFirmUpQuery;
use PropelModel\Map\FirmUpTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'firm_up' table.
 *
 *
 *
 * @method     ChildFirmUpQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildFirmUpQuery orderByFirmId($order = Criteria::ASC) Order by the firm_id column
 * @method     ChildFirmUpQuery orderByTimeStart($order = Criteria::ASC) Order by the time_start column
 * @method     ChildFirmUpQuery orderByTimeEnd($order = Criteria::ASC) Order by the time_end column
 * @method     ChildFirmUpQuery orderByCash($order = Criteria::ASC) Order by the cash column
 * @method     ChildFirmUpQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildFirmUpQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildFirmUpQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildFirmUpQuery orderBySpamType($order = Criteria::ASC) Order by the spam_type column
 * @method     ChildFirmUpQuery orderByLastMailSend($order = Criteria::ASC) Order by the last_mail_send column
 * @method     ChildFirmUpQuery orderByLastDays($order = Criteria::ASC) Order by the last_days column
 *
 * @method     ChildFirmUpQuery groupById() Group by the id column
 * @method     ChildFirmUpQuery groupByFirmId() Group by the firm_id column
 * @method     ChildFirmUpQuery groupByTimeStart() Group by the time_start column
 * @method     ChildFirmUpQuery groupByTimeEnd() Group by the time_end column
 * @method     ChildFirmUpQuery groupByCash() Group by the cash column
 * @method     ChildFirmUpQuery groupByType() Group by the type column
 * @method     ChildFirmUpQuery groupByEmail() Group by the email column
 * @method     ChildFirmUpQuery groupByStatus() Group by the status column
 * @method     ChildFirmUpQuery groupBySpamType() Group by the spam_type column
 * @method     ChildFirmUpQuery groupByLastMailSend() Group by the last_mail_send column
 * @method     ChildFirmUpQuery groupByLastDays() Group by the last_days column
 *
 * @method     ChildFirmUpQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFirmUpQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFirmUpQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFirmUpQuery leftJoinFirm($relationAlias = null) Adds a LEFT JOIN clause to the query using the Firm relation
 * @method     ChildFirmUpQuery rightJoinFirm($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Firm relation
 * @method     ChildFirmUpQuery innerJoinFirm($relationAlias = null) Adds a INNER JOIN clause to the query using the Firm relation
 *
 * @method     \PropelModel\FirmQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildFirmUp findOne(ConnectionInterface $con = null) Return the first ChildFirmUp matching the query
 * @method     ChildFirmUp findOneOrCreate(ConnectionInterface $con = null) Return the first ChildFirmUp matching the query, or a new ChildFirmUp object populated from the query conditions when no match is found
 *
 * @method     ChildFirmUp findOneById(int $id) Return the first ChildFirmUp filtered by the id column
 * @method     ChildFirmUp findOneByFirmId(int $firm_id) Return the first ChildFirmUp filtered by the firm_id column
 * @method     ChildFirmUp findOneByTimeStart(int $time_start) Return the first ChildFirmUp filtered by the time_start column
 * @method     ChildFirmUp findOneByTimeEnd(int $time_end) Return the first ChildFirmUp filtered by the time_end column
 * @method     ChildFirmUp findOneByCash(int $cash) Return the first ChildFirmUp filtered by the cash column
 * @method     ChildFirmUp findOneByType(string $type) Return the first ChildFirmUp filtered by the type column
 * @method     ChildFirmUp findOneByEmail(string $email) Return the first ChildFirmUp filtered by the email column
 * @method     ChildFirmUp findOneByStatus(int $status) Return the first ChildFirmUp filtered by the status column
 * @method     ChildFirmUp findOneBySpamType(int $spam_type) Return the first ChildFirmUp filtered by the spam_type column
 * @method     ChildFirmUp findOneByLastMailSend(int $last_mail_send) Return the first ChildFirmUp filtered by the last_mail_send column
 * @method     ChildFirmUp findOneByLastDays(int $last_days) Return the first ChildFirmUp filtered by the last_days column *

 * @method     ChildFirmUp requirePk($key, ConnectionInterface $con = null) Return the ChildFirmUp by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmUp requireOne(ConnectionInterface $con = null) Return the first ChildFirmUp matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFirmUp requireOneById(int $id) Return the first ChildFirmUp filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmUp requireOneByFirmId(int $firm_id) Return the first ChildFirmUp filtered by the firm_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmUp requireOneByTimeStart(int $time_start) Return the first ChildFirmUp filtered by the time_start column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmUp requireOneByTimeEnd(int $time_end) Return the first ChildFirmUp filtered by the time_end column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmUp requireOneByCash(int $cash) Return the first ChildFirmUp filtered by the cash column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmUp requireOneByType(string $type) Return the first ChildFirmUp filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmUp requireOneByEmail(string $email) Return the first ChildFirmUp filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmUp requireOneByStatus(int $status) Return the first ChildFirmUp filtered by the status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmUp requireOneBySpamType(int $spam_type) Return the first ChildFirmUp filtered by the spam_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmUp requireOneByLastMailSend(int $last_mail_send) Return the first ChildFirmUp filtered by the last_mail_send column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmUp requireOneByLastDays(int $last_days) Return the first ChildFirmUp filtered by the last_days column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFirmUp[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildFirmUp objects based on current ModelCriteria
 * @method     ChildFirmUp[]|ObjectCollection findById(int $id) Return ChildFirmUp objects filtered by the id column
 * @method     ChildFirmUp[]|ObjectCollection findByFirmId(int $firm_id) Return ChildFirmUp objects filtered by the firm_id column
 * @method     ChildFirmUp[]|ObjectCollection findByTimeStart(int $time_start) Return ChildFirmUp objects filtered by the time_start column
 * @method     ChildFirmUp[]|ObjectCollection findByTimeEnd(int $time_end) Return ChildFirmUp objects filtered by the time_end column
 * @method     ChildFirmUp[]|ObjectCollection findByCash(int $cash) Return ChildFirmUp objects filtered by the cash column
 * @method     ChildFirmUp[]|ObjectCollection findByType(string $type) Return ChildFirmUp objects filtered by the type column
 * @method     ChildFirmUp[]|ObjectCollection findByEmail(string $email) Return ChildFirmUp objects filtered by the email column
 * @method     ChildFirmUp[]|ObjectCollection findByStatus(int $status) Return ChildFirmUp objects filtered by the status column
 * @method     ChildFirmUp[]|ObjectCollection findBySpamType(int $spam_type) Return ChildFirmUp objects filtered by the spam_type column
 * @method     ChildFirmUp[]|ObjectCollection findByLastMailSend(int $last_mail_send) Return ChildFirmUp objects filtered by the last_mail_send column
 * @method     ChildFirmUp[]|ObjectCollection findByLastDays(int $last_days) Return ChildFirmUp objects filtered by the last_days column
 * @method     ChildFirmUp[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class FirmUpQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \PropelModel\Base\FirmUpQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\PropelModel\\FirmUp', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFirmUpQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFirmUpQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildFirmUpQuery) {
            return $criteria;
        }
        $query = new ChildFirmUpQuery();
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
     * @return ChildFirmUp|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FirmUpTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FirmUpTableMap::DATABASE_NAME);
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
     * @return ChildFirmUp A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, firm_id, time_start, time_end, cash, type, email, status, spam_type, last_mail_send, last_days FROM firm_up WHERE id = :p0';
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
            /** @var ChildFirmUp $obj */
            $obj = new ChildFirmUp();
            $obj->hydrate($row);
            FirmUpTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildFirmUp|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FirmUpTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FirmUpTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmUpTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
     */
    public function filterByFirmId($firmId = null, $comparison = null)
    {
        if (is_array($firmId)) {
            $useMinMax = false;
            if (isset($firmId['min'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_FIRM_ID, $firmId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($firmId['max'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_FIRM_ID, $firmId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmUpTableMap::COL_FIRM_ID, $firmId, $comparison);
    }

    /**
     * Filter the query on the time_start column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeStart(1234); // WHERE time_start = 1234
     * $query->filterByTimeStart(array(12, 34)); // WHERE time_start IN (12, 34)
     * $query->filterByTimeStart(array('min' => 12)); // WHERE time_start > 12
     * </code>
     *
     * @param     mixed $timeStart The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
     */
    public function filterByTimeStart($timeStart = null, $comparison = null)
    {
        if (is_array($timeStart)) {
            $useMinMax = false;
            if (isset($timeStart['min'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_TIME_START, $timeStart['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($timeStart['max'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_TIME_START, $timeStart['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmUpTableMap::COL_TIME_START, $timeStart, $comparison);
    }

    /**
     * Filter the query on the time_end column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeEnd(1234); // WHERE time_end = 1234
     * $query->filterByTimeEnd(array(12, 34)); // WHERE time_end IN (12, 34)
     * $query->filterByTimeEnd(array('min' => 12)); // WHERE time_end > 12
     * </code>
     *
     * @param     mixed $timeEnd The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
     */
    public function filterByTimeEnd($timeEnd = null, $comparison = null)
    {
        if (is_array($timeEnd)) {
            $useMinMax = false;
            if (isset($timeEnd['min'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_TIME_END, $timeEnd['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($timeEnd['max'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_TIME_END, $timeEnd['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmUpTableMap::COL_TIME_END, $timeEnd, $comparison);
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
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
     */
    public function filterByCash($cash = null, $comparison = null)
    {
        if (is_array($cash)) {
            $useMinMax = false;
            if (isset($cash['min'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_CASH, $cash['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cash['max'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_CASH, $cash['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmUpTableMap::COL_CASH, $cash, $comparison);
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
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
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

        return $this->addUsingAlias(FirmUpTableMap::COL_TYPE, $type, $comparison);
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
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
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

        return $this->addUsingAlias(FirmUpTableMap::COL_EMAIL, $email, $comparison);
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
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmUpTableMap::COL_STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the spam_type column
     *
     * Example usage:
     * <code>
     * $query->filterBySpamType(1234); // WHERE spam_type = 1234
     * $query->filterBySpamType(array(12, 34)); // WHERE spam_type IN (12, 34)
     * $query->filterBySpamType(array('min' => 12)); // WHERE spam_type > 12
     * </code>
     *
     * @param     mixed $spamType The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
     */
    public function filterBySpamType($spamType = null, $comparison = null)
    {
        if (is_array($spamType)) {
            $useMinMax = false;
            if (isset($spamType['min'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_SPAM_TYPE, $spamType['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($spamType['max'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_SPAM_TYPE, $spamType['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmUpTableMap::COL_SPAM_TYPE, $spamType, $comparison);
    }

    /**
     * Filter the query on the last_mail_send column
     *
     * Example usage:
     * <code>
     * $query->filterByLastMailSend(1234); // WHERE last_mail_send = 1234
     * $query->filterByLastMailSend(array(12, 34)); // WHERE last_mail_send IN (12, 34)
     * $query->filterByLastMailSend(array('min' => 12)); // WHERE last_mail_send > 12
     * </code>
     *
     * @param     mixed $lastMailSend The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
     */
    public function filterByLastMailSend($lastMailSend = null, $comparison = null)
    {
        if (is_array($lastMailSend)) {
            $useMinMax = false;
            if (isset($lastMailSend['min'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_LAST_MAIL_SEND, $lastMailSend['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastMailSend['max'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_LAST_MAIL_SEND, $lastMailSend['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmUpTableMap::COL_LAST_MAIL_SEND, $lastMailSend, $comparison);
    }

    /**
     * Filter the query on the last_days column
     *
     * Example usage:
     * <code>
     * $query->filterByLastDays(1234); // WHERE last_days = 1234
     * $query->filterByLastDays(array(12, 34)); // WHERE last_days IN (12, 34)
     * $query->filterByLastDays(array('min' => 12)); // WHERE last_days > 12
     * </code>
     *
     * @param     mixed $lastDays The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
     */
    public function filterByLastDays($lastDays = null, $comparison = null)
    {
        if (is_array($lastDays)) {
            $useMinMax = false;
            if (isset($lastDays['min'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_LAST_DAYS, $lastDays['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastDays['max'])) {
                $this->addUsingAlias(FirmUpTableMap::COL_LAST_DAYS, $lastDays['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmUpTableMap::COL_LAST_DAYS, $lastDays, $comparison);
    }

    /**
     * Filter the query by a related \PropelModel\Firm object
     *
     * @param \PropelModel\Firm|ObjectCollection $firm The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFirmUpQuery The current query, for fluid interface
     */
    public function filterByFirm($firm, $comparison = null)
    {
        if ($firm instanceof \PropelModel\Firm) {
            return $this
                ->addUsingAlias(FirmUpTableMap::COL_FIRM_ID, $firm->getId(), $comparison);
        } elseif ($firm instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FirmUpTableMap::COL_FIRM_ID, $firm->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
     */
    public function joinFirm($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useFirmQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFirm($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Firm', '\PropelModel\FirmQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildFirmUp $firmUp Object to remove from the list of results
     *
     * @return $this|ChildFirmUpQuery The current query, for fluid interface
     */
    public function prune($firmUp = null)
    {
        if ($firmUp) {
            $this->addUsingAlias(FirmUpTableMap::COL_ID, $firmUp->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the firm_up table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FirmUpTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FirmUpTableMap::clearInstancePool();
            FirmUpTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(FirmUpTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FirmUpTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FirmUpTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FirmUpTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // FirmUpQuery
