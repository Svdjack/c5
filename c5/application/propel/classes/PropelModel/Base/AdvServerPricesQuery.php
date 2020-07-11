<?php

namespace PropelModel\Base;

use \Exception;
use \PDO;
use PropelModel\AdvServerPrices as ChildAdvServerPrices;
use PropelModel\AdvServerPricesQuery as ChildAdvServerPricesQuery;
use PropelModel\Map\AdvServerPricesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'adv_server_prices' table.
 *
 *
 *
 * @method     ChildAdvServerPricesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAdvServerPricesQuery orderByCityId($order = Criteria::ASC) Order by the city_id column
 * @method     ChildAdvServerPricesQuery orderByCityUrl($order = Criteria::ASC) Order by the city_url column
 * @method     ChildAdvServerPricesQuery orderByData($order = Criteria::ASC) Order by the data column
 *
 * @method     ChildAdvServerPricesQuery groupById() Group by the id column
 * @method     ChildAdvServerPricesQuery groupByCityId() Group by the city_id column
 * @method     ChildAdvServerPricesQuery groupByCityUrl() Group by the city_url column
 * @method     ChildAdvServerPricesQuery groupByData() Group by the data column
 *
 * @method     ChildAdvServerPricesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAdvServerPricesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAdvServerPricesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAdvServerPricesQuery leftJoinRegion($relationAlias = null) Adds a LEFT JOIN clause to the query using the Region relation
 * @method     ChildAdvServerPricesQuery rightJoinRegion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Region relation
 * @method     ChildAdvServerPricesQuery innerJoinRegion($relationAlias = null) Adds a INNER JOIN clause to the query using the Region relation
 *
 * @method     \PropelModel\RegionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAdvServerPrices findOne(ConnectionInterface $con = null) Return the first ChildAdvServerPrices matching the query
 * @method     ChildAdvServerPrices findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAdvServerPrices matching the query, or a new ChildAdvServerPrices object populated from the query conditions when no match is found
 *
 * @method     ChildAdvServerPrices findOneById(int $id) Return the first ChildAdvServerPrices filtered by the id column
 * @method     ChildAdvServerPrices findOneByCityId(int $city_id) Return the first ChildAdvServerPrices filtered by the city_id column
 * @method     ChildAdvServerPrices findOneByCityUrl(string $city_url) Return the first ChildAdvServerPrices filtered by the city_url column
 * @method     ChildAdvServerPrices findOneByData(resource $data) Return the first ChildAdvServerPrices filtered by the data column *

 * @method     ChildAdvServerPrices requirePk($key, ConnectionInterface $con = null) Return the ChildAdvServerPrices by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdvServerPrices requireOne(ConnectionInterface $con = null) Return the first ChildAdvServerPrices matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAdvServerPrices requireOneById(int $id) Return the first ChildAdvServerPrices filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdvServerPrices requireOneByCityId(int $city_id) Return the first ChildAdvServerPrices filtered by the city_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdvServerPrices requireOneByCityUrl(string $city_url) Return the first ChildAdvServerPrices filtered by the city_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAdvServerPrices requireOneByData(resource $data) Return the first ChildAdvServerPrices filtered by the data column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAdvServerPrices[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAdvServerPrices objects based on current ModelCriteria
 * @method     ChildAdvServerPrices[]|ObjectCollection findById(int $id) Return ChildAdvServerPrices objects filtered by the id column
 * @method     ChildAdvServerPrices[]|ObjectCollection findByCityId(int $city_id) Return ChildAdvServerPrices objects filtered by the city_id column
 * @method     ChildAdvServerPrices[]|ObjectCollection findByCityUrl(string $city_url) Return ChildAdvServerPrices objects filtered by the city_url column
 * @method     ChildAdvServerPrices[]|ObjectCollection findByData(resource $data) Return ChildAdvServerPrices objects filtered by the data column
 * @method     ChildAdvServerPrices[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AdvServerPricesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \PropelModel\Base\AdvServerPricesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\PropelModel\\AdvServerPrices', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAdvServerPricesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAdvServerPricesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAdvServerPricesQuery) {
            return $criteria;
        }
        $query = new ChildAdvServerPricesQuery();
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
     * @return ChildAdvServerPrices|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AdvServerPricesTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AdvServerPricesTableMap::DATABASE_NAME);
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
     * @return ChildAdvServerPrices A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, city_id, city_url, data FROM adv_server_prices WHERE id = :p0';
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
            /** @var ChildAdvServerPrices $obj */
            $obj = new ChildAdvServerPrices();
            $obj->hydrate($row);
            AdvServerPricesTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildAdvServerPrices|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAdvServerPricesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AdvServerPricesTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAdvServerPricesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AdvServerPricesTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildAdvServerPricesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AdvServerPricesTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AdvServerPricesTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdvServerPricesTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the city_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCityId(1234); // WHERE city_id = 1234
     * $query->filterByCityId(array(12, 34)); // WHERE city_id IN (12, 34)
     * $query->filterByCityId(array('min' => 12)); // WHERE city_id > 12
     * </code>
     *
     * @see       filterByRegion()
     *
     * @param     mixed $cityId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdvServerPricesQuery The current query, for fluid interface
     */
    public function filterByCityId($cityId = null, $comparison = null)
    {
        if (is_array($cityId)) {
            $useMinMax = false;
            if (isset($cityId['min'])) {
                $this->addUsingAlias(AdvServerPricesTableMap::COL_CITY_ID, $cityId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cityId['max'])) {
                $this->addUsingAlias(AdvServerPricesTableMap::COL_CITY_ID, $cityId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdvServerPricesTableMap::COL_CITY_ID, $cityId, $comparison);
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
     * @return $this|ChildAdvServerPricesQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AdvServerPricesTableMap::COL_CITY_URL, $cityUrl, $comparison);
    }

    /**
     * Filter the query on the data column
     *
     * @param     mixed $data The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAdvServerPricesQuery The current query, for fluid interface
     */
    public function filterByData($data = null, $comparison = null)
    {

        return $this->addUsingAlias(AdvServerPricesTableMap::COL_DATA, $data, $comparison);
    }

    /**
     * Filter the query by a related \PropelModel\Region object
     *
     * @param \PropelModel\Region|ObjectCollection $region The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAdvServerPricesQuery The current query, for fluid interface
     */
    public function filterByRegion($region, $comparison = null)
    {
        if ($region instanceof \PropelModel\Region) {
            return $this
                ->addUsingAlias(AdvServerPricesTableMap::COL_CITY_ID, $region->getId(), $comparison);
        } elseif ($region instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AdvServerPricesTableMap::COL_CITY_ID, $region->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByRegion() only accepts arguments of type \PropelModel\Region or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Region relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAdvServerPricesQuery The current query, for fluid interface
     */
    public function joinRegion($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Region');

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
            $this->addJoinObject($join, 'Region');
        }

        return $this;
    }

    /**
     * Use the Region relation Region object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\RegionQuery A secondary query class using the current class as primary query
     */
    public function useRegionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRegion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Region', '\PropelModel\RegionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAdvServerPrices $advServerPrices Object to remove from the list of results
     *
     * @return $this|ChildAdvServerPricesQuery The current query, for fluid interface
     */
    public function prune($advServerPrices = null)
    {
        if ($advServerPrices) {
            $this->addUsingAlias(AdvServerPricesTableMap::COL_ID, $advServerPrices->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the adv_server_prices table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AdvServerPricesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AdvServerPricesTableMap::clearInstancePool();
            AdvServerPricesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AdvServerPricesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AdvServerPricesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AdvServerPricesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AdvServerPricesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // AdvServerPricesQuery
