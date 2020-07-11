<?php

namespace PropelModel\Base;

use \Exception;
use PropelModel\SitesData as ChildSitesData;
use PropelModel\SitesDataQuery as ChildSitesDataQuery;
use PropelModel\Map\SitesDataTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'sites_data' table.
 *
 *
 *
 * @method     ChildSitesDataQuery orderByUrl($order = Criteria::ASC) Order by the url column
 * @method     ChildSitesDataQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildSitesDataQuery orderByKeywords($order = Criteria::ASC) Order by the keywords column
 * @method     ChildSitesDataQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildSitesDataQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildSitesDataQuery orderByScreen($order = Criteria::ASC) Order by the screen column
 * @method     ChildSitesDataQuery orderByDate($order = Criteria::ASC) Order by the date column
 *
 * @method     ChildSitesDataQuery groupByUrl() Group by the url column
 * @method     ChildSitesDataQuery groupByTitle() Group by the title column
 * @method     ChildSitesDataQuery groupByKeywords() Group by the keywords column
 * @method     ChildSitesDataQuery groupByDescription() Group by the description column
 * @method     ChildSitesDataQuery groupByStatus() Group by the status column
 * @method     ChildSitesDataQuery groupByScreen() Group by the screen column
 * @method     ChildSitesDataQuery groupByDate() Group by the date column
 *
 * @method     ChildSitesDataQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSitesDataQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSitesDataQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSitesData findOne(ConnectionInterface $con = null) Return the first ChildSitesData matching the query
 * @method     ChildSitesData findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSitesData matching the query, or a new ChildSitesData object populated from the query conditions when no match is found
 *
 * @method     ChildSitesData findOneByUrl(string $url) Return the first ChildSitesData filtered by the url column
 * @method     ChildSitesData findOneByTitle(string $title) Return the first ChildSitesData filtered by the title column
 * @method     ChildSitesData findOneByKeywords(string $keywords) Return the first ChildSitesData filtered by the keywords column
 * @method     ChildSitesData findOneByDescription(string $description) Return the first ChildSitesData filtered by the description column
 * @method     ChildSitesData findOneByStatus(int $status) Return the first ChildSitesData filtered by the status column
 * @method     ChildSitesData findOneByScreen(string $screen) Return the first ChildSitesData filtered by the screen column
 * @method     ChildSitesData findOneByDate(int $date) Return the first ChildSitesData filtered by the date column *

 * @method     ChildSitesData requirePk($key, ConnectionInterface $con = null) Return the ChildSitesData by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSitesData requireOne(ConnectionInterface $con = null) Return the first ChildSitesData matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSitesData requireOneByUrl(string $url) Return the first ChildSitesData filtered by the url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSitesData requireOneByTitle(string $title) Return the first ChildSitesData filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSitesData requireOneByKeywords(string $keywords) Return the first ChildSitesData filtered by the keywords column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSitesData requireOneByDescription(string $description) Return the first ChildSitesData filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSitesData requireOneByStatus(int $status) Return the first ChildSitesData filtered by the status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSitesData requireOneByScreen(string $screen) Return the first ChildSitesData filtered by the screen column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSitesData requireOneByDate(int $date) Return the first ChildSitesData filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSitesData[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSitesData objects based on current ModelCriteria
 * @method     ChildSitesData[]|ObjectCollection findByUrl(string $url) Return ChildSitesData objects filtered by the url column
 * @method     ChildSitesData[]|ObjectCollection findByTitle(string $title) Return ChildSitesData objects filtered by the title column
 * @method     ChildSitesData[]|ObjectCollection findByKeywords(string $keywords) Return ChildSitesData objects filtered by the keywords column
 * @method     ChildSitesData[]|ObjectCollection findByDescription(string $description) Return ChildSitesData objects filtered by the description column
 * @method     ChildSitesData[]|ObjectCollection findByStatus(int $status) Return ChildSitesData objects filtered by the status column
 * @method     ChildSitesData[]|ObjectCollection findByScreen(string $screen) Return ChildSitesData objects filtered by the screen column
 * @method     ChildSitesData[]|ObjectCollection findByDate(int $date) Return ChildSitesData objects filtered by the date column
 * @method     ChildSitesData[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SitesDataQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \PropelModel\Base\SitesDataQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\PropelModel\\SitesData', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSitesDataQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSitesDataQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSitesDataQuery) {
            return $criteria;
        }
        $query = new ChildSitesDataQuery();
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
     * @return ChildSitesData|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        throw new LogicException('The SitesData object has no primary key');
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
        throw new LogicException('The SitesData object has no primary key');
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildSitesDataQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        throw new LogicException('The SitesData object has no primary key');
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSitesDataQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        throw new LogicException('The SitesData object has no primary key');
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
     * @return $this|ChildSitesDataQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SitesDataTableMap::COL_URL, $url, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSitesDataQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SitesDataTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the keywords column
     *
     * Example usage:
     * <code>
     * $query->filterByKeywords('fooValue');   // WHERE keywords = 'fooValue'
     * $query->filterByKeywords('%fooValue%'); // WHERE keywords LIKE '%fooValue%'
     * </code>
     *
     * @param     string $keywords The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSitesDataQuery The current query, for fluid interface
     */
    public function filterByKeywords($keywords = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($keywords)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $keywords)) {
                $keywords = str_replace('*', '%', $keywords);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SitesDataTableMap::COL_KEYWORDS, $keywords, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSitesDataQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SitesDataTableMap::COL_DESCRIPTION, $description, $comparison);
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
     * @return $this|ChildSitesDataQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(SitesDataTableMap::COL_STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(SitesDataTableMap::COL_STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SitesDataTableMap::COL_STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the screen column
     *
     * Example usage:
     * <code>
     * $query->filterByScreen('fooValue');   // WHERE screen = 'fooValue'
     * $query->filterByScreen('%fooValue%'); // WHERE screen LIKE '%fooValue%'
     * </code>
     *
     * @param     string $screen The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSitesDataQuery The current query, for fluid interface
     */
    public function filterByScreen($screen = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($screen)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $screen)) {
                $screen = str_replace('*', '%', $screen);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SitesDataTableMap::COL_SCREEN, $screen, $comparison);
    }

    /**
     * Filter the query on the date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate(1234); // WHERE date = 1234
     * $query->filterByDate(array(12, 34)); // WHERE date IN (12, 34)
     * $query->filterByDate(array('min' => 12)); // WHERE date > 12
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSitesDataQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(SitesDataTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(SitesDataTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SitesDataTableMap::COL_DATE, $date, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSitesData $sitesData Object to remove from the list of results
     *
     * @return $this|ChildSitesDataQuery The current query, for fluid interface
     */
    public function prune($sitesData = null)
    {
        if ($sitesData) {
            throw new LogicException('SitesData object has no primary key');

        }

        return $this;
    }

    /**
     * Deletes all rows from the sites_data table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SitesDataTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SitesDataTableMap::clearInstancePool();
            SitesDataTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SitesDataTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SitesDataTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SitesDataTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SitesDataTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SitesDataQuery
