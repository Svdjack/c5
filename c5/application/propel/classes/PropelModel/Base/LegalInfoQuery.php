<?php

namespace PropelModel\Base;

use \Exception;
use \PDO;
use PropelModel\LegalInfo as ChildLegalInfo;
use PropelModel\LegalInfoQuery as ChildLegalInfoQuery;
use PropelModel\Map\LegalInfoTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'jur_data' table.
 *
 *
 *
 * @method     ChildLegalInfoQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLegalInfoQuery orderByRusprofileId($order = Criteria::ASC) Order by the rusprofile_id column
 * @method     ChildLegalInfoQuery orderByFirmId($order = Criteria::ASC) Order by the firm_id column
 * @method     ChildLegalInfoQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildLegalInfoQuery orderByRegion($order = Criteria::ASC) Order by the region column
 * @method     ChildLegalInfoQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method     ChildLegalInfoQuery orderByPostal($order = Criteria::ASC) Order by the postal column
 * @method     ChildLegalInfoQuery orderByAddress($order = Criteria::ASC) Order by the address column
 * @method     ChildLegalInfoQuery orderByDirector($order = Criteria::ASC) Order by the director column
 * @method     ChildLegalInfoQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method     ChildLegalInfoQuery orderByInn($order = Criteria::ASC) Order by the inn column
 * @method     ChildLegalInfoQuery orderByOkato($order = Criteria::ASC) Order by the okato column
 * @method     ChildLegalInfoQuery orderByFsfr($order = Criteria::ASC) Order by the fsfr column
 * @method     ChildLegalInfoQuery orderByOgrn($order = Criteria::ASC) Order by the ogrn column
 * @method     ChildLegalInfoQuery orderByOkpo($order = Criteria::ASC) Order by the okpo column
 * @method     ChildLegalInfoQuery orderByOrgForm($order = Criteria::ASC) Order by the org_form column
 * @method     ChildLegalInfoQuery orderByOkogu($order = Criteria::ASC) Order by the okogu column
 * @method     ChildLegalInfoQuery orderByRegDate($order = Criteria::ASC) Order by the reg_date column
 * @method     ChildLegalInfoQuery orderByIsLiquidated($order = Criteria::ASC) Order by the is_liquidated column
 * @method     ChildLegalInfoQuery orderByCapital($order = Criteria::ASC) Order by the capital column
 * @method     ChildLegalInfoQuery orderByActivities($order = Criteria::ASC) Order by the activities column
 * @method     ChildLegalInfoQuery orderByKpp($order = Criteria::ASC) Order by the kpp column
 *
 * @method     ChildLegalInfoQuery groupById() Group by the id column
 * @method     ChildLegalInfoQuery groupByRusprofileId() Group by the rusprofile_id column
 * @method     ChildLegalInfoQuery groupByFirmId() Group by the firm_id column
 * @method     ChildLegalInfoQuery groupByName() Group by the name column
 * @method     ChildLegalInfoQuery groupByRegion() Group by the region column
 * @method     ChildLegalInfoQuery groupByCity() Group by the city column
 * @method     ChildLegalInfoQuery groupByPostal() Group by the postal column
 * @method     ChildLegalInfoQuery groupByAddress() Group by the address column
 * @method     ChildLegalInfoQuery groupByDirector() Group by the director column
 * @method     ChildLegalInfoQuery groupByPhone() Group by the phone column
 * @method     ChildLegalInfoQuery groupByInn() Group by the inn column
 * @method     ChildLegalInfoQuery groupByOkato() Group by the okato column
 * @method     ChildLegalInfoQuery groupByFsfr() Group by the fsfr column
 * @method     ChildLegalInfoQuery groupByOgrn() Group by the ogrn column
 * @method     ChildLegalInfoQuery groupByOkpo() Group by the okpo column
 * @method     ChildLegalInfoQuery groupByOrgForm() Group by the org_form column
 * @method     ChildLegalInfoQuery groupByOkogu() Group by the okogu column
 * @method     ChildLegalInfoQuery groupByRegDate() Group by the reg_date column
 * @method     ChildLegalInfoQuery groupByIsLiquidated() Group by the is_liquidated column
 * @method     ChildLegalInfoQuery groupByCapital() Group by the capital column
 * @method     ChildLegalInfoQuery groupByActivities() Group by the activities column
 * @method     ChildLegalInfoQuery groupByKpp() Group by the kpp column
 *
 * @method     ChildLegalInfoQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLegalInfoQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLegalInfoQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLegalInfoQuery leftJoinFirmRelatedByFirmId($relationAlias = null) Adds a LEFT JOIN clause to the query using the FirmRelatedByFirmId relation
 * @method     ChildLegalInfoQuery rightJoinFirmRelatedByFirmId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FirmRelatedByFirmId relation
 * @method     ChildLegalInfoQuery innerJoinFirmRelatedByFirmId($relationAlias = null) Adds a INNER JOIN clause to the query using the FirmRelatedByFirmId relation
 *
 * @method     ChildLegalInfoQuery leftJoinFirmRelatedById($relationAlias = null) Adds a LEFT JOIN clause to the query using the FirmRelatedById relation
 * @method     ChildLegalInfoQuery rightJoinFirmRelatedById($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FirmRelatedById relation
 * @method     ChildLegalInfoQuery innerJoinFirmRelatedById($relationAlias = null) Adds a INNER JOIN clause to the query using the FirmRelatedById relation
 *
 * @method     \PropelModel\FirmQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLegalInfo findOne(ConnectionInterface $con = null) Return the first ChildLegalInfo matching the query
 * @method     ChildLegalInfo findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLegalInfo matching the query, or a new ChildLegalInfo object populated from the query conditions when no match is found
 *
 * @method     ChildLegalInfo findOneById(int $id) Return the first ChildLegalInfo filtered by the id column
 * @method     ChildLegalInfo findOneByRusprofileId(int $rusprofile_id) Return the first ChildLegalInfo filtered by the rusprofile_id column
 * @method     ChildLegalInfo findOneByFirmId(int $firm_id) Return the first ChildLegalInfo filtered by the firm_id column
 * @method     ChildLegalInfo findOneByName(string $name) Return the first ChildLegalInfo filtered by the name column
 * @method     ChildLegalInfo findOneByRegion(string $region) Return the first ChildLegalInfo filtered by the region column
 * @method     ChildLegalInfo findOneByCity(string $city) Return the first ChildLegalInfo filtered by the city column
 * @method     ChildLegalInfo findOneByPostal(string $postal) Return the first ChildLegalInfo filtered by the postal column
 * @method     ChildLegalInfo findOneByAddress(string $address) Return the first ChildLegalInfo filtered by the address column
 * @method     ChildLegalInfo findOneByDirector(string $director) Return the first ChildLegalInfo filtered by the director column
 * @method     ChildLegalInfo findOneByPhone(string $phone) Return the first ChildLegalInfo filtered by the phone column
 * @method     ChildLegalInfo findOneByInn(string $inn) Return the first ChildLegalInfo filtered by the inn column
 * @method     ChildLegalInfo findOneByOkato(string $okato) Return the first ChildLegalInfo filtered by the okato column
 * @method     ChildLegalInfo findOneByFsfr(string $fsfr) Return the first ChildLegalInfo filtered by the fsfr column
 * @method     ChildLegalInfo findOneByOgrn(string $ogrn) Return the first ChildLegalInfo filtered by the ogrn column
 * @method     ChildLegalInfo findOneByOkpo(string $okpo) Return the first ChildLegalInfo filtered by the okpo column
 * @method     ChildLegalInfo findOneByOrgForm(string $org_form) Return the first ChildLegalInfo filtered by the org_form column
 * @method     ChildLegalInfo findOneByOkogu(string $okogu) Return the first ChildLegalInfo filtered by the okogu column
 * @method     ChildLegalInfo findOneByRegDate(string $reg_date) Return the first ChildLegalInfo filtered by the reg_date column
 * @method     ChildLegalInfo findOneByIsLiquidated(string $is_liquidated) Return the first ChildLegalInfo filtered by the is_liquidated column
 * @method     ChildLegalInfo findOneByCapital(string $capital) Return the first ChildLegalInfo filtered by the capital column
 * @method     ChildLegalInfo findOneByActivities(string $activities) Return the first ChildLegalInfo filtered by the activities column
 * @method     ChildLegalInfo findOneByKpp(string $kpp) Return the first ChildLegalInfo filtered by the kpp column *

 * @method     ChildLegalInfo requirePk($key, ConnectionInterface $con = null) Return the ChildLegalInfo by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOne(ConnectionInterface $con = null) Return the first ChildLegalInfo matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLegalInfo requireOneById(int $id) Return the first ChildLegalInfo filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByRusprofileId(int $rusprofile_id) Return the first ChildLegalInfo filtered by the rusprofile_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByFirmId(int $firm_id) Return the first ChildLegalInfo filtered by the firm_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByName(string $name) Return the first ChildLegalInfo filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByRegion(string $region) Return the first ChildLegalInfo filtered by the region column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByCity(string $city) Return the first ChildLegalInfo filtered by the city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByPostal(string $postal) Return the first ChildLegalInfo filtered by the postal column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByAddress(string $address) Return the first ChildLegalInfo filtered by the address column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByDirector(string $director) Return the first ChildLegalInfo filtered by the director column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByPhone(string $phone) Return the first ChildLegalInfo filtered by the phone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByInn(string $inn) Return the first ChildLegalInfo filtered by the inn column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByOkato(string $okato) Return the first ChildLegalInfo filtered by the okato column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByFsfr(string $fsfr) Return the first ChildLegalInfo filtered by the fsfr column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByOgrn(string $ogrn) Return the first ChildLegalInfo filtered by the ogrn column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByOkpo(string $okpo) Return the first ChildLegalInfo filtered by the okpo column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByOrgForm(string $org_form) Return the first ChildLegalInfo filtered by the org_form column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByOkogu(string $okogu) Return the first ChildLegalInfo filtered by the okogu column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByRegDate(string $reg_date) Return the first ChildLegalInfo filtered by the reg_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByIsLiquidated(string $is_liquidated) Return the first ChildLegalInfo filtered by the is_liquidated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByCapital(string $capital) Return the first ChildLegalInfo filtered by the capital column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByActivities(string $activities) Return the first ChildLegalInfo filtered by the activities column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLegalInfo requireOneByKpp(string $kpp) Return the first ChildLegalInfo filtered by the kpp column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLegalInfo[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLegalInfo objects based on current ModelCriteria
 * @method     ChildLegalInfo[]|ObjectCollection findById(int $id) Return ChildLegalInfo objects filtered by the id column
 * @method     ChildLegalInfo[]|ObjectCollection findByRusprofileId(int $rusprofile_id) Return ChildLegalInfo objects filtered by the rusprofile_id column
 * @method     ChildLegalInfo[]|ObjectCollection findByFirmId(int $firm_id) Return ChildLegalInfo objects filtered by the firm_id column
 * @method     ChildLegalInfo[]|ObjectCollection findByName(string $name) Return ChildLegalInfo objects filtered by the name column
 * @method     ChildLegalInfo[]|ObjectCollection findByRegion(string $region) Return ChildLegalInfo objects filtered by the region column
 * @method     ChildLegalInfo[]|ObjectCollection findByCity(string $city) Return ChildLegalInfo objects filtered by the city column
 * @method     ChildLegalInfo[]|ObjectCollection findByPostal(string $postal) Return ChildLegalInfo objects filtered by the postal column
 * @method     ChildLegalInfo[]|ObjectCollection findByAddress(string $address) Return ChildLegalInfo objects filtered by the address column
 * @method     ChildLegalInfo[]|ObjectCollection findByDirector(string $director) Return ChildLegalInfo objects filtered by the director column
 * @method     ChildLegalInfo[]|ObjectCollection findByPhone(string $phone) Return ChildLegalInfo objects filtered by the phone column
 * @method     ChildLegalInfo[]|ObjectCollection findByInn(string $inn) Return ChildLegalInfo objects filtered by the inn column
 * @method     ChildLegalInfo[]|ObjectCollection findByOkato(string $okato) Return ChildLegalInfo objects filtered by the okato column
 * @method     ChildLegalInfo[]|ObjectCollection findByFsfr(string $fsfr) Return ChildLegalInfo objects filtered by the fsfr column
 * @method     ChildLegalInfo[]|ObjectCollection findByOgrn(string $ogrn) Return ChildLegalInfo objects filtered by the ogrn column
 * @method     ChildLegalInfo[]|ObjectCollection findByOkpo(string $okpo) Return ChildLegalInfo objects filtered by the okpo column
 * @method     ChildLegalInfo[]|ObjectCollection findByOrgForm(string $org_form) Return ChildLegalInfo objects filtered by the org_form column
 * @method     ChildLegalInfo[]|ObjectCollection findByOkogu(string $okogu) Return ChildLegalInfo objects filtered by the okogu column
 * @method     ChildLegalInfo[]|ObjectCollection findByRegDate(string $reg_date) Return ChildLegalInfo objects filtered by the reg_date column
 * @method     ChildLegalInfo[]|ObjectCollection findByIsLiquidated(string $is_liquidated) Return ChildLegalInfo objects filtered by the is_liquidated column
 * @method     ChildLegalInfo[]|ObjectCollection findByCapital(string $capital) Return ChildLegalInfo objects filtered by the capital column
 * @method     ChildLegalInfo[]|ObjectCollection findByActivities(string $activities) Return ChildLegalInfo objects filtered by the activities column
 * @method     ChildLegalInfo[]|ObjectCollection findByKpp(string $kpp) Return ChildLegalInfo objects filtered by the kpp column
 * @method     ChildLegalInfo[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LegalInfoQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \PropelModel\Base\LegalInfoQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\PropelModel\\LegalInfo', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLegalInfoQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLegalInfoQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLegalInfoQuery) {
            return $criteria;
        }
        $query = new ChildLegalInfoQuery();
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
     * @return ChildLegalInfo|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LegalInfoTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LegalInfoTableMap::DATABASE_NAME);
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
     * @return ChildLegalInfo A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, rusprofile_id, firm_id, name, region, city, postal, address, director, phone, inn, okato, fsfr, ogrn, okpo, org_form, okogu, reg_date, is_liquidated, capital, activities, kpp FROM jur_data WHERE id = :p0';
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
            /** @var ChildLegalInfo $obj */
            $obj = new ChildLegalInfo();
            $obj->hydrate($row);
            LegalInfoTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildLegalInfo|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LegalInfoTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LegalInfoTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LegalInfoTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LegalInfoTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the rusprofile_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRusprofileId(1234); // WHERE rusprofile_id = 1234
     * $query->filterByRusprofileId(array(12, 34)); // WHERE rusprofile_id IN (12, 34)
     * $query->filterByRusprofileId(array('min' => 12)); // WHERE rusprofile_id > 12
     * </code>
     *
     * @param     mixed $rusprofileId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByRusprofileId($rusprofileId = null, $comparison = null)
    {
        if (is_array($rusprofileId)) {
            $useMinMax = false;
            if (isset($rusprofileId['min'])) {
                $this->addUsingAlias(LegalInfoTableMap::COL_RUSPROFILE_ID, $rusprofileId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rusprofileId['max'])) {
                $this->addUsingAlias(LegalInfoTableMap::COL_RUSPROFILE_ID, $rusprofileId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_RUSPROFILE_ID, $rusprofileId, $comparison);
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
     * @see       filterByFirmRelatedByFirmId()
     *
     * @param     mixed $firmId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByFirmId($firmId = null, $comparison = null)
    {
        if (is_array($firmId)) {
            $useMinMax = false;
            if (isset($firmId['min'])) {
                $this->addUsingAlias(LegalInfoTableMap::COL_FIRM_ID, $firmId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($firmId['max'])) {
                $this->addUsingAlias(LegalInfoTableMap::COL_FIRM_ID, $firmId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_FIRM_ID, $firmId, $comparison);
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
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
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

        return $this->addUsingAlias(LegalInfoTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the region column
     *
     * Example usage:
     * <code>
     * $query->filterByRegion('fooValue');   // WHERE region = 'fooValue'
     * $query->filterByRegion('%fooValue%'); // WHERE region LIKE '%fooValue%'
     * </code>
     *
     * @param     string $region The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByRegion($region = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($region)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $region)) {
                $region = str_replace('*', '%', $region);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_REGION, $region, $comparison);
    }

    /**
     * Filter the query on the city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE city = 'fooValue'
     * $query->filterByCity('%fooValue%'); // WHERE city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $city)) {
                $city = str_replace('*', '%', $city);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_CITY, $city, $comparison);
    }

    /**
     * Filter the query on the postal column
     *
     * Example usage:
     * <code>
     * $query->filterByPostal('fooValue');   // WHERE postal = 'fooValue'
     * $query->filterByPostal('%fooValue%'); // WHERE postal LIKE '%fooValue%'
     * </code>
     *
     * @param     string $postal The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByPostal($postal = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postal)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $postal)) {
                $postal = str_replace('*', '%', $postal);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_POSTAL, $postal, $comparison);
    }

    /**
     * Filter the query on the address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE address = 'fooValue'
     * $query->filterByAddress('%fooValue%'); // WHERE address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByAddress($address = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $address)) {
                $address = str_replace('*', '%', $address);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_ADDRESS, $address, $comparison);
    }

    /**
     * Filter the query on the director column
     *
     * Example usage:
     * <code>
     * $query->filterByDirector('fooValue');   // WHERE director = 'fooValue'
     * $query->filterByDirector('%fooValue%'); // WHERE director LIKE '%fooValue%'
     * </code>
     *
     * @param     string $director The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByDirector($director = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($director)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $director)) {
                $director = str_replace('*', '%', $director);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_DIRECTOR, $director, $comparison);
    }

    /**
     * Filter the query on the phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE phone = 'fooValue'
     * $query->filterByPhone('%fooValue%'); // WHERE phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phone)) {
                $phone = str_replace('*', '%', $phone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the inn column
     *
     * Example usage:
     * <code>
     * $query->filterByInn('fooValue');   // WHERE inn = 'fooValue'
     * $query->filterByInn('%fooValue%'); // WHERE inn LIKE '%fooValue%'
     * </code>
     *
     * @param     string $inn The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByInn($inn = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($inn)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $inn)) {
                $inn = str_replace('*', '%', $inn);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_INN, $inn, $comparison);
    }

    /**
     * Filter the query on the okato column
     *
     * Example usage:
     * <code>
     * $query->filterByOkato('fooValue');   // WHERE okato = 'fooValue'
     * $query->filterByOkato('%fooValue%'); // WHERE okato LIKE '%fooValue%'
     * </code>
     *
     * @param     string $okato The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByOkato($okato = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($okato)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $okato)) {
                $okato = str_replace('*', '%', $okato);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_OKATO, $okato, $comparison);
    }

    /**
     * Filter the query on the fsfr column
     *
     * Example usage:
     * <code>
     * $query->filterByFsfr('fooValue');   // WHERE fsfr = 'fooValue'
     * $query->filterByFsfr('%fooValue%'); // WHERE fsfr LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fsfr The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByFsfr($fsfr = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fsfr)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $fsfr)) {
                $fsfr = str_replace('*', '%', $fsfr);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_FSFR, $fsfr, $comparison);
    }

    /**
     * Filter the query on the ogrn column
     *
     * Example usage:
     * <code>
     * $query->filterByOgrn('fooValue');   // WHERE ogrn = 'fooValue'
     * $query->filterByOgrn('%fooValue%'); // WHERE ogrn LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ogrn The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByOgrn($ogrn = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ogrn)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $ogrn)) {
                $ogrn = str_replace('*', '%', $ogrn);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_OGRN, $ogrn, $comparison);
    }

    /**
     * Filter the query on the okpo column
     *
     * Example usage:
     * <code>
     * $query->filterByOkpo('fooValue');   // WHERE okpo = 'fooValue'
     * $query->filterByOkpo('%fooValue%'); // WHERE okpo LIKE '%fooValue%'
     * </code>
     *
     * @param     string $okpo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByOkpo($okpo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($okpo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $okpo)) {
                $okpo = str_replace('*', '%', $okpo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_OKPO, $okpo, $comparison);
    }

    /**
     * Filter the query on the org_form column
     *
     * Example usage:
     * <code>
     * $query->filterByOrgForm('fooValue');   // WHERE org_form = 'fooValue'
     * $query->filterByOrgForm('%fooValue%'); // WHERE org_form LIKE '%fooValue%'
     * </code>
     *
     * @param     string $orgForm The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByOrgForm($orgForm = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($orgForm)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $orgForm)) {
                $orgForm = str_replace('*', '%', $orgForm);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_ORG_FORM, $orgForm, $comparison);
    }

    /**
     * Filter the query on the okogu column
     *
     * Example usage:
     * <code>
     * $query->filterByOkogu('fooValue');   // WHERE okogu = 'fooValue'
     * $query->filterByOkogu('%fooValue%'); // WHERE okogu LIKE '%fooValue%'
     * </code>
     *
     * @param     string $okogu The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByOkogu($okogu = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($okogu)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $okogu)) {
                $okogu = str_replace('*', '%', $okogu);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_OKOGU, $okogu, $comparison);
    }

    /**
     * Filter the query on the reg_date column
     *
     * Example usage:
     * <code>
     * $query->filterByRegDate('fooValue');   // WHERE reg_date = 'fooValue'
     * $query->filterByRegDate('%fooValue%'); // WHERE reg_date LIKE '%fooValue%'
     * </code>
     *
     * @param     string $regDate The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByRegDate($regDate = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($regDate)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $regDate)) {
                $regDate = str_replace('*', '%', $regDate);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_REG_DATE, $regDate, $comparison);
    }

    /**
     * Filter the query on the is_liquidated column
     *
     * Example usage:
     * <code>
     * $query->filterByIsLiquidated('fooValue');   // WHERE is_liquidated = 'fooValue'
     * $query->filterByIsLiquidated('%fooValue%'); // WHERE is_liquidated LIKE '%fooValue%'
     * </code>
     *
     * @param     string $isLiquidated The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByIsLiquidated($isLiquidated = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isLiquidated)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $isLiquidated)) {
                $isLiquidated = str_replace('*', '%', $isLiquidated);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_IS_LIQUIDATED, $isLiquidated, $comparison);
    }

    /**
     * Filter the query on the capital column
     *
     * Example usage:
     * <code>
     * $query->filterByCapital('fooValue');   // WHERE capital = 'fooValue'
     * $query->filterByCapital('%fooValue%'); // WHERE capital LIKE '%fooValue%'
     * </code>
     *
     * @param     string $capital The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByCapital($capital = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($capital)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $capital)) {
                $capital = str_replace('*', '%', $capital);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_CAPITAL, $capital, $comparison);
    }

    /**
     * Filter the query on the activities column
     *
     * Example usage:
     * <code>
     * $query->filterByActivities('fooValue');   // WHERE activities = 'fooValue'
     * $query->filterByActivities('%fooValue%'); // WHERE activities LIKE '%fooValue%'
     * </code>
     *
     * @param     string $activities The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByActivities($activities = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($activities)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $activities)) {
                $activities = str_replace('*', '%', $activities);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_ACTIVITIES, $activities, $comparison);
    }

    /**
     * Filter the query on the kpp column
     *
     * Example usage:
     * <code>
     * $query->filterByKpp('fooValue');   // WHERE kpp = 'fooValue'
     * $query->filterByKpp('%fooValue%'); // WHERE kpp LIKE '%fooValue%'
     * </code>
     *
     * @param     string $kpp The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByKpp($kpp = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($kpp)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $kpp)) {
                $kpp = str_replace('*', '%', $kpp);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LegalInfoTableMap::COL_KPP, $kpp, $comparison);
    }

    /**
     * Filter the query by a related \PropelModel\Firm object
     *
     * @param \PropelModel\Firm|ObjectCollection $firm The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByFirmRelatedByFirmId($firm, $comparison = null)
    {
        if ($firm instanceof \PropelModel\Firm) {
            return $this
                ->addUsingAlias(LegalInfoTableMap::COL_FIRM_ID, $firm->getId(), $comparison);
        } elseif ($firm instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LegalInfoTableMap::COL_FIRM_ID, $firm->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFirmRelatedByFirmId() only accepts arguments of type \PropelModel\Firm or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FirmRelatedByFirmId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function joinFirmRelatedByFirmId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FirmRelatedByFirmId');

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
            $this->addJoinObject($join, 'FirmRelatedByFirmId');
        }

        return $this;
    }

    /**
     * Use the FirmRelatedByFirmId relation Firm object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\FirmQuery A secondary query class using the current class as primary query
     */
    public function useFirmRelatedByFirmIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFirmRelatedByFirmId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FirmRelatedByFirmId', '\PropelModel\FirmQuery');
    }

    /**
     * Filter the query by a related \PropelModel\Firm object
     *
     * @param \PropelModel\Firm|ObjectCollection $firm the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLegalInfoQuery The current query, for fluid interface
     */
    public function filterByFirmRelatedById($firm, $comparison = null)
    {
        if ($firm instanceof \PropelModel\Firm) {
            return $this
                ->addUsingAlias(LegalInfoTableMap::COL_FIRM_ID, $firm->getId(), $comparison);
        } elseif ($firm instanceof ObjectCollection) {
            return $this
                ->useFirmRelatedByIdQuery()
                ->filterByPrimaryKeys($firm->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFirmRelatedById() only accepts arguments of type \PropelModel\Firm or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FirmRelatedById relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function joinFirmRelatedById($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FirmRelatedById');

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
            $this->addJoinObject($join, 'FirmRelatedById');
        }

        return $this;
    }

    /**
     * Use the FirmRelatedById relation Firm object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\FirmQuery A secondary query class using the current class as primary query
     */
    public function useFirmRelatedByIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFirmRelatedById($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FirmRelatedById', '\PropelModel\FirmQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLegalInfo $legalInfo Object to remove from the list of results
     *
     * @return $this|ChildLegalInfoQuery The current query, for fluid interface
     */
    public function prune($legalInfo = null)
    {
        if ($legalInfo) {
            $this->addUsingAlias(LegalInfoTableMap::COL_ID, $legalInfo->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the jur_data table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LegalInfoTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LegalInfoTableMap::clearInstancePool();
            LegalInfoTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LegalInfoTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LegalInfoTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LegalInfoTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LegalInfoTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LegalInfoQuery
