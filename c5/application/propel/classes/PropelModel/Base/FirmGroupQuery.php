<?php

namespace PropelModel\Base;

use \Exception;
use \PDO;
use PropelModel\FirmGroup as ChildFirmGroup;
use PropelModel\FirmGroupQuery as ChildFirmGroupQuery;
use PropelModel\Map\FirmGroupTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'firm_group' table.
 *
 *
 *
 * @method     ChildFirmGroupQuery orderByFirmId($order = Criteria::ASC) Order by the firm_id column
 * @method     ChildFirmGroupQuery orderByGroupId($order = Criteria::ASC) Order by the group_id column
 * @method     ChildFirmGroupQuery orderByCity($order = Criteria::ASC) Order by the city_id column
 *
 * @method     ChildFirmGroupQuery groupByFirmId() Group by the firm_id column
 * @method     ChildFirmGroupQuery groupByGroupId() Group by the group_id column
 * @method     ChildFirmGroupQuery groupByCity() Group by the city_id column
 *
 * @method     ChildFirmGroupQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFirmGroupQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFirmGroupQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFirmGroupQuery leftJoinFirm($relationAlias = null) Adds a LEFT JOIN clause to the query using the Firm relation
 * @method     ChildFirmGroupQuery rightJoinFirm($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Firm relation
 * @method     ChildFirmGroupQuery innerJoinFirm($relationAlias = null) Adds a INNER JOIN clause to the query using the Firm relation
 *
 * @method     ChildFirmGroupQuery leftJoinGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the Group relation
 * @method     ChildFirmGroupQuery rightJoinGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Group relation
 * @method     ChildFirmGroupQuery innerJoinGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the Group relation
 *
 * @method     \PropelModel\FirmQuery|\PropelModel\GroupQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildFirmGroup findOne(ConnectionInterface $con = null) Return the first ChildFirmGroup matching the query
 * @method     ChildFirmGroup findOneOrCreate(ConnectionInterface $con = null) Return the first ChildFirmGroup matching the query, or a new ChildFirmGroup object populated from the query conditions when no match is found
 *
 * @method     ChildFirmGroup findOneByFirmId(int $firm_id) Return the first ChildFirmGroup filtered by the firm_id column
 * @method     ChildFirmGroup findOneByGroupId(int $group_id) Return the first ChildFirmGroup filtered by the group_id column
 * @method     ChildFirmGroup findOneByCity(int $city_id) Return the first ChildFirmGroup filtered by the city_id column *

 * @method     ChildFirmGroup requirePk($key, ConnectionInterface $con = null) Return the ChildFirmGroup by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmGroup requireOne(ConnectionInterface $con = null) Return the first ChildFirmGroup matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFirmGroup requireOneByFirmId(int $firm_id) Return the first ChildFirmGroup filtered by the firm_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmGroup requireOneByGroupId(int $group_id) Return the first ChildFirmGroup filtered by the group_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirmGroup requireOneByCity(int $city_id) Return the first ChildFirmGroup filtered by the city_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFirmGroup[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildFirmGroup objects based on current ModelCriteria
 * @method     ChildFirmGroup[]|ObjectCollection findByFirmId(int $firm_id) Return ChildFirmGroup objects filtered by the firm_id column
 * @method     ChildFirmGroup[]|ObjectCollection findByGroupId(int $group_id) Return ChildFirmGroup objects filtered by the group_id column
 * @method     ChildFirmGroup[]|ObjectCollection findByCity(int $city_id) Return ChildFirmGroup objects filtered by the city_id column
 * @method     ChildFirmGroup[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class FirmGroupQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \PropelModel\Base\FirmGroupQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\PropelModel\\FirmGroup', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFirmGroupQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFirmGroupQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildFirmGroupQuery) {
            return $criteria;
        }
        $query = new ChildFirmGroupQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$firm_id, $group_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildFirmGroup|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FirmGroupTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FirmGroupTableMap::DATABASE_NAME);
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
     * @return ChildFirmGroup A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT firm_id, group_id, city_id FROM firm_group WHERE firm_id = :p0 AND group_id = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildFirmGroup $obj */
            $obj = new ChildFirmGroup();
            $obj->hydrate($row);
            FirmGroupTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildFirmGroup|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildFirmGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(FirmGroupTableMap::COL_FIRM_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(FirmGroupTableMap::COL_GROUP_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildFirmGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(FirmGroupTableMap::COL_FIRM_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(FirmGroupTableMap::COL_GROUP_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildFirmGroupQuery The current query, for fluid interface
     */
    public function filterByFirmId($firmId = null, $comparison = null)
    {
        if (is_array($firmId)) {
            $useMinMax = false;
            if (isset($firmId['min'])) {
                $this->addUsingAlias(FirmGroupTableMap::COL_FIRM_ID, $firmId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($firmId['max'])) {
                $this->addUsingAlias(FirmGroupTableMap::COL_FIRM_ID, $firmId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmGroupTableMap::COL_FIRM_ID, $firmId, $comparison);
    }

    /**
     * Filter the query on the group_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGroupId(1234); // WHERE group_id = 1234
     * $query->filterByGroupId(array(12, 34)); // WHERE group_id IN (12, 34)
     * $query->filterByGroupId(array('min' => 12)); // WHERE group_id > 12
     * </code>
     *
     * @see       filterByGroup()
     *
     * @param     mixed $groupId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmGroupQuery The current query, for fluid interface
     */
    public function filterByGroupId($groupId = null, $comparison = null)
    {
        if (is_array($groupId)) {
            $useMinMax = false;
            if (isset($groupId['min'])) {
                $this->addUsingAlias(FirmGroupTableMap::COL_GROUP_ID, $groupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupId['max'])) {
                $this->addUsingAlias(FirmGroupTableMap::COL_GROUP_ID, $groupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmGroupTableMap::COL_GROUP_ID, $groupId, $comparison);
    }

    /**
     * Filter the query on the city_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCity(1234); // WHERE city_id = 1234
     * $query->filterByCity(array(12, 34)); // WHERE city_id IN (12, 34)
     * $query->filterByCity(array('min' => 12)); // WHERE city_id > 12
     * </code>
     *
     * @param     mixed $city The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmGroupQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (is_array($city)) {
            $useMinMax = false;
            if (isset($city['min'])) {
                $this->addUsingAlias(FirmGroupTableMap::COL_CITY_ID, $city['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($city['max'])) {
                $this->addUsingAlias(FirmGroupTableMap::COL_CITY_ID, $city['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmGroupTableMap::COL_CITY_ID, $city, $comparison);
    }

    /**
     * Filter the query by a related \PropelModel\Firm object
     *
     * @param \PropelModel\Firm|ObjectCollection $firm The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFirmGroupQuery The current query, for fluid interface
     */
    public function filterByFirm($firm, $comparison = null)
    {
        if ($firm instanceof \PropelModel\Firm) {
            return $this
                ->addUsingAlias(FirmGroupTableMap::COL_FIRM_ID, $firm->getId(), $comparison);
        } elseif ($firm instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FirmGroupTableMap::COL_FIRM_ID, $firm->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildFirmGroupQuery The current query, for fluid interface
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
     * Filter the query by a related \PropelModel\Group object
     *
     * @param \PropelModel\Group|ObjectCollection $group The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFirmGroupQuery The current query, for fluid interface
     */
    public function filterByGroup($group, $comparison = null)
    {
        if ($group instanceof \PropelModel\Group) {
            return $this
                ->addUsingAlias(FirmGroupTableMap::COL_GROUP_ID, $group->getId(), $comparison);
        } elseif ($group instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FirmGroupTableMap::COL_GROUP_ID, $group->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGroup() only accepts arguments of type \PropelModel\Group or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Group relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFirmGroupQuery The current query, for fluid interface
     */
    public function joinGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Group');

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
            $this->addJoinObject($join, 'Group');
        }

        return $this;
    }

    /**
     * Use the Group relation Group object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\GroupQuery A secondary query class using the current class as primary query
     */
    public function useGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Group', '\PropelModel\GroupQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildFirmGroup $firmGroup Object to remove from the list of results
     *
     * @return $this|ChildFirmGroupQuery The current query, for fluid interface
     */
    public function prune($firmGroup = null)
    {
        if ($firmGroup) {
            $this->addCond('pruneCond0', $this->getAliasedColName(FirmGroupTableMap::COL_FIRM_ID), $firmGroup->getFirmId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(FirmGroupTableMap::COL_GROUP_ID), $firmGroup->getGroupId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the firm_group table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FirmGroupTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FirmGroupTableMap::clearInstancePool();
            FirmGroupTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(FirmGroupTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FirmGroupTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FirmGroupTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FirmGroupTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // FirmGroupQuery
