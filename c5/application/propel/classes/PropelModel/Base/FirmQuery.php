<?php

namespace PropelModel\Base;

use \Exception;
use \PDO;
use PropelModel\Firm as ChildFirm;
use PropelModel\FirmQuery as ChildFirmQuery;
use PropelModel\Map\FirmTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'firm' table.
 *
 *
 *
 * @method     ChildFirmQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildFirmQuery orderByActive($order = Criteria::ASC) Order by the active column
 * @method     ChildFirmQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildFirmQuery orderByChanged($order = Criteria::ASC) Order by the changed column
 * @method     ChildFirmQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildFirmQuery orderByOfficialName($order = Criteria::ASC) Order by the official_name column
 * @method     ChildFirmQuery orderByUrl($order = Criteria::ASC) Order by the url column
 * @method     ChildFirmQuery orderBySubtitle($order = Criteria::ASC) Order by the subtitle column
 * @method     ChildFirmQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildFirmQuery orderByPostal($order = Criteria::ASC) Order by the postal column
 * @method     ChildFirmQuery orderByDistrictId($order = Criteria::ASC) Order by the district_id column
 * @method     ChildFirmQuery orderByAddress($order = Criteria::ASC) Order by the address column
 * @method     ChildFirmQuery orderByCityId($order = Criteria::ASC) Order by the city_id column
 * @method     ChildFirmQuery orderByStreet($order = Criteria::ASC) Order by the street column
 * @method     ChildFirmQuery orderByHome($order = Criteria::ASC) Order by the home column
 * @method     ChildFirmQuery orderByOffice($order = Criteria::ASC) Order by the office column
 * @method     ChildFirmQuery orderByMainCategory($order = Criteria::ASC) Order by the main_category column
 * @method     ChildFirmQuery orderByWorktime($order = Criteria::ASC) Order by the worktime column
 * @method     ChildFirmQuery orderByViews($order = Criteria::ASC) Order by the views column
 * @method     ChildFirmQuery orderByCreated($order = Criteria::ASC) Order by the created column
 * @method     ChildFirmQuery orderByModerationTime($order = Criteria::ASC) Order by the moderation_time column
 * @method     ChildFirmQuery orderByChangedTime($order = Criteria::ASC) Order by the changed_time column
 * @method     ChildFirmQuery orderByLon($order = Criteria::ASC) Order by the lon column
 * @method     ChildFirmQuery orderByLat($order = Criteria::ASC) Order by the lat column
 * @method     ChildFirmQuery orderByRandom($order = Criteria::ASC) Order by the random column
 * @method     ChildFirmQuery orderByLogo($order = Criteria::ASC) Order by the logo column
 * @method     ChildFirmQuery orderByRedirectID($order = Criteria::ASC) Order by the redirect_id column
 *
 * @method     ChildFirmQuery groupById() Group by the id column
 * @method     ChildFirmQuery groupByActive() Group by the active column
 * @method     ChildFirmQuery groupByStatus() Group by the status column
 * @method     ChildFirmQuery groupByChanged() Group by the changed column
 * @method     ChildFirmQuery groupByName() Group by the name column
 * @method     ChildFirmQuery groupByOfficialName() Group by the official_name column
 * @method     ChildFirmQuery groupByUrl() Group by the url column
 * @method     ChildFirmQuery groupBySubtitle() Group by the subtitle column
 * @method     ChildFirmQuery groupByDescription() Group by the description column
 * @method     ChildFirmQuery groupByPostal() Group by the postal column
 * @method     ChildFirmQuery groupByDistrictId() Group by the district_id column
 * @method     ChildFirmQuery groupByAddress() Group by the address column
 * @method     ChildFirmQuery groupByCityId() Group by the city_id column
 * @method     ChildFirmQuery groupByStreet() Group by the street column
 * @method     ChildFirmQuery groupByHome() Group by the home column
 * @method     ChildFirmQuery groupByOffice() Group by the office column
 * @method     ChildFirmQuery groupByMainCategory() Group by the main_category column
 * @method     ChildFirmQuery groupByWorktime() Group by the worktime column
 * @method     ChildFirmQuery groupByViews() Group by the views column
 * @method     ChildFirmQuery groupByCreated() Group by the created column
 * @method     ChildFirmQuery groupByModerationTime() Group by the moderation_time column
 * @method     ChildFirmQuery groupByChangedTime() Group by the changed_time column
 * @method     ChildFirmQuery groupByLon() Group by the lon column
 * @method     ChildFirmQuery groupByLat() Group by the lat column
 * @method     ChildFirmQuery groupByRandom() Group by the random column
 * @method     ChildFirmQuery groupByLogo() Group by the logo column
 * @method     ChildFirmQuery groupByRedirectID() Group by the redirect_id column
 *
 * @method     ChildFirmQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFirmQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFirmQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFirmQuery leftJoinRegion($relationAlias = null) Adds a LEFT JOIN clause to the query using the Region relation
 * @method     ChildFirmQuery rightJoinRegion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Region relation
 * @method     ChildFirmQuery innerJoinRegion($relationAlias = null) Adds a INNER JOIN clause to the query using the Region relation
 *
 * @method     ChildFirmQuery leftJoinDistrict($relationAlias = null) Adds a LEFT JOIN clause to the query using the District relation
 * @method     ChildFirmQuery rightJoinDistrict($relationAlias = null) Adds a RIGHT JOIN clause to the query using the District relation
 * @method     ChildFirmQuery innerJoinDistrict($relationAlias = null) Adds a INNER JOIN clause to the query using the District relation
 *
 * @method     ChildFirmQuery leftJoinLegalInfoRelatedById($relationAlias = null) Adds a LEFT JOIN clause to the query using the LegalInfoRelatedById relation
 * @method     ChildFirmQuery rightJoinLegalInfoRelatedById($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LegalInfoRelatedById relation
 * @method     ChildFirmQuery innerJoinLegalInfoRelatedById($relationAlias = null) Adds a INNER JOIN clause to the query using the LegalInfoRelatedById relation
 *
 * @method     ChildFirmQuery leftJoinFirmUp($relationAlias = null) Adds a LEFT JOIN clause to the query using the FirmUp relation
 * @method     ChildFirmQuery rightJoinFirmUp($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FirmUp relation
 * @method     ChildFirmQuery innerJoinFirmUp($relationAlias = null) Adds a INNER JOIN clause to the query using the FirmUp relation
 *
 * @method     ChildFirmQuery leftJoinAdvServerOrders($relationAlias = null) Adds a LEFT JOIN clause to the query using the AdvServerOrders relation
 * @method     ChildFirmQuery rightJoinAdvServerOrders($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AdvServerOrders relation
 * @method     ChildFirmQuery innerJoinAdvServerOrders($relationAlias = null) Adds a INNER JOIN clause to the query using the AdvServerOrders relation
 *
 * @method     ChildFirmQuery leftJoinComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Comment relation
 * @method     ChildFirmQuery rightJoinComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Comment relation
 * @method     ChildFirmQuery innerJoinComment($relationAlias = null) Adds a INNER JOIN clause to the query using the Comment relation
 *
 * @method     ChildFirmQuery leftJoinChild($relationAlias = null) Adds a LEFT JOIN clause to the query using the Child relation
 * @method     ChildFirmQuery rightJoinChild($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Child relation
 * @method     ChildFirmQuery innerJoinChild($relationAlias = null) Adds a INNER JOIN clause to the query using the Child relation
 *
 * @method     ChildFirmQuery leftJoinContact($relationAlias = null) Adds a LEFT JOIN clause to the query using the Contact relation
 * @method     ChildFirmQuery rightJoinContact($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Contact relation
 * @method     ChildFirmQuery innerJoinContact($relationAlias = null) Adds a INNER JOIN clause to the query using the Contact relation
 *
 * @method     ChildFirmQuery leftJoinFirmGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the FirmGroup relation
 * @method     ChildFirmQuery rightJoinFirmGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FirmGroup relation
 * @method     ChildFirmQuery innerJoinFirmGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the FirmGroup relation
 *
 * @method     ChildFirmQuery leftJoinFirmPhotos($relationAlias = null) Adds a LEFT JOIN clause to the query using the FirmPhotos relation
 * @method     ChildFirmQuery rightJoinFirmPhotos($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FirmPhotos relation
 * @method     ChildFirmQuery innerJoinFirmPhotos($relationAlias = null) Adds a INNER JOIN clause to the query using the FirmPhotos relation
 *
 * @method     ChildFirmQuery leftJoinFirmTags($relationAlias = null) Adds a LEFT JOIN clause to the query using the FirmTags relation
 * @method     ChildFirmQuery rightJoinFirmTags($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FirmTags relation
 * @method     ChildFirmQuery innerJoinFirmTags($relationAlias = null) Adds a INNER JOIN clause to the query using the FirmTags relation
 *
 * @method     ChildFirmQuery leftJoinFirmUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the FirmUser relation
 * @method     ChildFirmQuery rightJoinFirmUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FirmUser relation
 * @method     ChildFirmQuery innerJoinFirmUser($relationAlias = null) Adds a INNER JOIN clause to the query using the FirmUser relation
 *
 * @method     ChildFirmQuery leftJoinLegalInfoRelatedByFirmId($relationAlias = null) Adds a LEFT JOIN clause to the query using the LegalInfoRelatedByFirmId relation
 * @method     ChildFirmQuery rightJoinLegalInfoRelatedByFirmId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LegalInfoRelatedByFirmId relation
 * @method     ChildFirmQuery innerJoinLegalInfoRelatedByFirmId($relationAlias = null) Adds a INNER JOIN clause to the query using the LegalInfoRelatedByFirmId relation
 *
 * @method     \PropelModel\RegionQuery|\PropelModel\DistrictQuery|\PropelModel\LegalInfoQuery|\PropelModel\FirmUpQuery|\PropelModel\AdvServerOrdersQuery|\PropelModel\CommentQuery|\PropelModel\ChildQuery|\PropelModel\ContactQuery|\PropelModel\FirmGroupQuery|\PropelModel\FirmPhotosQuery|\PropelModel\FirmTagsQuery|\PropelModel\FirmUserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildFirm findOne(ConnectionInterface $con = null) Return the first ChildFirm matching the query
 * @method     ChildFirm findOneOrCreate(ConnectionInterface $con = null) Return the first ChildFirm matching the query, or a new ChildFirm object populated from the query conditions when no match is found
 *
 * @method     ChildFirm findOneById(int $id) Return the first ChildFirm filtered by the id column
 * @method     ChildFirm findOneByActive(int $active) Return the first ChildFirm filtered by the active column
 * @method     ChildFirm findOneByStatus(int $status) Return the first ChildFirm filtered by the status column
 * @method     ChildFirm findOneByChanged(int $changed) Return the first ChildFirm filtered by the changed column
 * @method     ChildFirm findOneByName(string $name) Return the first ChildFirm filtered by the name column
 * @method     ChildFirm findOneByOfficialName(string $official_name) Return the first ChildFirm filtered by the official_name column
 * @method     ChildFirm findOneByUrl(string $url) Return the first ChildFirm filtered by the url column
 * @method     ChildFirm findOneBySubtitle(string $subtitle) Return the first ChildFirm filtered by the subtitle column
 * @method     ChildFirm findOneByDescription(string $description) Return the first ChildFirm filtered by the description column
 * @method     ChildFirm findOneByPostal(string $postal) Return the first ChildFirm filtered by the postal column
 * @method     ChildFirm findOneByDistrictId(int $district_id) Return the first ChildFirm filtered by the district_id column
 * @method     ChildFirm findOneByAddress(string $address) Return the first ChildFirm filtered by the address column
 * @method     ChildFirm findOneByCityId(int $city_id) Return the first ChildFirm filtered by the city_id column
 * @method     ChildFirm findOneByStreet(string $street) Return the first ChildFirm filtered by the street column
 * @method     ChildFirm findOneByHome(string $home) Return the first ChildFirm filtered by the home column
 * @method     ChildFirm findOneByOffice(string $office) Return the first ChildFirm filtered by the office column
 * @method     ChildFirm findOneByMainCategory(int $main_category) Return the first ChildFirm filtered by the main_category column
 * @method     ChildFirm findOneByWorktime(string $worktime) Return the first ChildFirm filtered by the worktime column
 * @method     ChildFirm findOneByViews(int $views) Return the first ChildFirm filtered by the views column
 * @method     ChildFirm findOneByCreated(int $created) Return the first ChildFirm filtered by the created column
 * @method     ChildFirm findOneByModerationTime(int $moderation_time) Return the first ChildFirm filtered by the moderation_time column
 * @method     ChildFirm findOneByChangedTime(int $changed_time) Return the first ChildFirm filtered by the changed_time column
 * @method     ChildFirm findOneByLon(double $lon) Return the first ChildFirm filtered by the lon column
 * @method     ChildFirm findOneByLat(double $lat) Return the first ChildFirm filtered by the lat column
 * @method     ChildFirm findOneByRandom(int $random) Return the first ChildFirm filtered by the random column
 * @method     ChildFirm findOneByLogo(string $logo) Return the first ChildFirm filtered by the logo column
 * @method     ChildFirm findOneByRedirectID(int $redirect_id) Return the first ChildFirm filtered by the redirect_id column *

 * @method     ChildFirm requirePk($key, ConnectionInterface $con = null) Return the ChildFirm by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOne(ConnectionInterface $con = null) Return the first ChildFirm matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFirm requireOneById(int $id) Return the first ChildFirm filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByActive(int $active) Return the first ChildFirm filtered by the active column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByStatus(int $status) Return the first ChildFirm filtered by the status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByChanged(int $changed) Return the first ChildFirm filtered by the changed column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByName(string $name) Return the first ChildFirm filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByOfficialName(string $official_name) Return the first ChildFirm filtered by the official_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByUrl(string $url) Return the first ChildFirm filtered by the url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneBySubtitle(string $subtitle) Return the first ChildFirm filtered by the subtitle column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByDescription(string $description) Return the first ChildFirm filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByPostal(string $postal) Return the first ChildFirm filtered by the postal column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByDistrictId(int $district_id) Return the first ChildFirm filtered by the district_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByAddress(string $address) Return the first ChildFirm filtered by the address column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByCityId(int $city_id) Return the first ChildFirm filtered by the city_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByStreet(string $street) Return the first ChildFirm filtered by the street column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByHome(string $home) Return the first ChildFirm filtered by the home column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByOffice(string $office) Return the first ChildFirm filtered by the office column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByMainCategory(int $main_category) Return the first ChildFirm filtered by the main_category column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByWorktime(string $worktime) Return the first ChildFirm filtered by the worktime column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByViews(int $views) Return the first ChildFirm filtered by the views column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByCreated(int $created) Return the first ChildFirm filtered by the created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByModerationTime(int $moderation_time) Return the first ChildFirm filtered by the moderation_time column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByChangedTime(int $changed_time) Return the first ChildFirm filtered by the changed_time column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByLon(double $lon) Return the first ChildFirm filtered by the lon column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByLat(double $lat) Return the first ChildFirm filtered by the lat column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByRandom(int $random) Return the first ChildFirm filtered by the random column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByLogo(string $logo) Return the first ChildFirm filtered by the logo column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFirm requireOneByRedirectID(int $redirect_id) Return the first ChildFirm filtered by the redirect_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFirm[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildFirm objects based on current ModelCriteria
 * @method     ChildFirm[]|ObjectCollection findById(int $id) Return ChildFirm objects filtered by the id column
 * @method     ChildFirm[]|ObjectCollection findByActive(int $active) Return ChildFirm objects filtered by the active column
 * @method     ChildFirm[]|ObjectCollection findByStatus(int $status) Return ChildFirm objects filtered by the status column
 * @method     ChildFirm[]|ObjectCollection findByChanged(int $changed) Return ChildFirm objects filtered by the changed column
 * @method     ChildFirm[]|ObjectCollection findByName(string $name) Return ChildFirm objects filtered by the name column
 * @method     ChildFirm[]|ObjectCollection findByOfficialName(string $official_name) Return ChildFirm objects filtered by the official_name column
 * @method     ChildFirm[]|ObjectCollection findByUrl(string $url) Return ChildFirm objects filtered by the url column
 * @method     ChildFirm[]|ObjectCollection findBySubtitle(string $subtitle) Return ChildFirm objects filtered by the subtitle column
 * @method     ChildFirm[]|ObjectCollection findByDescription(string $description) Return ChildFirm objects filtered by the description column
 * @method     ChildFirm[]|ObjectCollection findByPostal(string $postal) Return ChildFirm objects filtered by the postal column
 * @method     ChildFirm[]|ObjectCollection findByDistrictId(int $district_id) Return ChildFirm objects filtered by the district_id column
 * @method     ChildFirm[]|ObjectCollection findByAddress(string $address) Return ChildFirm objects filtered by the address column
 * @method     ChildFirm[]|ObjectCollection findByCityId(int $city_id) Return ChildFirm objects filtered by the city_id column
 * @method     ChildFirm[]|ObjectCollection findByStreet(string $street) Return ChildFirm objects filtered by the street column
 * @method     ChildFirm[]|ObjectCollection findByHome(string $home) Return ChildFirm objects filtered by the home column
 * @method     ChildFirm[]|ObjectCollection findByOffice(string $office) Return ChildFirm objects filtered by the office column
 * @method     ChildFirm[]|ObjectCollection findByMainCategory(int $main_category) Return ChildFirm objects filtered by the main_category column
 * @method     ChildFirm[]|ObjectCollection findByWorktime(string $worktime) Return ChildFirm objects filtered by the worktime column
 * @method     ChildFirm[]|ObjectCollection findByViews(int $views) Return ChildFirm objects filtered by the views column
 * @method     ChildFirm[]|ObjectCollection findByCreated(int $created) Return ChildFirm objects filtered by the created column
 * @method     ChildFirm[]|ObjectCollection findByModerationTime(int $moderation_time) Return ChildFirm objects filtered by the moderation_time column
 * @method     ChildFirm[]|ObjectCollection findByChangedTime(int $changed_time) Return ChildFirm objects filtered by the changed_time column
 * @method     ChildFirm[]|ObjectCollection findByLon(double $lon) Return ChildFirm objects filtered by the lon column
 * @method     ChildFirm[]|ObjectCollection findByLat(double $lat) Return ChildFirm objects filtered by the lat column
 * @method     ChildFirm[]|ObjectCollection findByRandom(int $random) Return ChildFirm objects filtered by the random column
 * @method     ChildFirm[]|ObjectCollection findByLogo(string $logo) Return ChildFirm objects filtered by the logo column
 * @method     ChildFirm[]|ObjectCollection findByRedirectID(int $redirect_id) Return ChildFirm objects filtered by the redirect_id column
 * @method     ChildFirm[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class FirmQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \PropelModel\Base\FirmQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\PropelModel\\Firm', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFirmQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFirmQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildFirmQuery) {
            return $criteria;
        }
        $query = new ChildFirmQuery();
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
     * @return ChildFirm|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FirmTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FirmTableMap::DATABASE_NAME);
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
     * @return ChildFirm A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, active, status, changed, name, official_name, url, subtitle, description, postal, district_id, address, city_id, street, home, office, main_category, worktime, views, created, moderation_time, changed_time, lon, lat, random, logo, redirect_id FROM firm WHERE id = :p0';
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
            /** @var ChildFirm $obj */
            $obj = new ChildFirm();
            $obj->hydrate($row);
            FirmTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildFirm|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FirmTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FirmTableMap::COL_ID, $keys, Criteria::IN);
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
     * @see       filterByLegalInfoRelatedById()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the active column
     *
     * Example usage:
     * <code>
     * $query->filterByActive(1234); // WHERE active = 1234
     * $query->filterByActive(array(12, 34)); // WHERE active IN (12, 34)
     * $query->filterByActive(array('min' => 12)); // WHERE active > 12
     * </code>
     *
     * @param     mixed $active The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_array($active)) {
            $useMinMax = false;
            if (isset($active['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_ACTIVE, $active['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($active['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_ACTIVE, $active['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_ACTIVE, $active, $comparison);
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
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the changed column
     *
     * Example usage:
     * <code>
     * $query->filterByChanged(1234); // WHERE changed = 1234
     * $query->filterByChanged(array(12, 34)); // WHERE changed IN (12, 34)
     * $query->filterByChanged(array('min' => 12)); // WHERE changed > 12
     * </code>
     *
     * @param     mixed $changed The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByChanged($changed = null, $comparison = null)
    {
        if (is_array($changed)) {
            $useMinMax = false;
            if (isset($changed['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_CHANGED, $changed['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($changed['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_CHANGED, $changed['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_CHANGED, $changed, $comparison);
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
     * @return $this|ChildFirmQuery The current query, for fluid interface
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

        return $this->addUsingAlias(FirmTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the official_name column
     *
     * Example usage:
     * <code>
     * $query->filterByOfficialName('fooValue');   // WHERE official_name = 'fooValue'
     * $query->filterByOfficialName('%fooValue%'); // WHERE official_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $officialName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByOfficialName($officialName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($officialName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $officialName)) {
                $officialName = str_replace('*', '%', $officialName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_OFFICIAL_NAME, $officialName, $comparison);
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
     * @return $this|ChildFirmQuery The current query, for fluid interface
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

        return $this->addUsingAlias(FirmTableMap::COL_URL, $url, $comparison);
    }

    /**
     * Filter the query on the subtitle column
     *
     * Example usage:
     * <code>
     * $query->filterBySubtitle('fooValue');   // WHERE subtitle = 'fooValue'
     * $query->filterBySubtitle('%fooValue%'); // WHERE subtitle LIKE '%fooValue%'
     * </code>
     *
     * @param     string $subtitle The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterBySubtitle($subtitle = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subtitle)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $subtitle)) {
                $subtitle = str_replace('*', '%', $subtitle);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_SUBTITLE, $subtitle, $comparison);
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
     * @return $this|ChildFirmQuery The current query, for fluid interface
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

        return $this->addUsingAlias(FirmTableMap::COL_DESCRIPTION, $description, $comparison);
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
     * @return $this|ChildFirmQuery The current query, for fluid interface
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

        return $this->addUsingAlias(FirmTableMap::COL_POSTAL, $postal, $comparison);
    }

    /**
     * Filter the query on the district_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDistrictId(1234); // WHERE district_id = 1234
     * $query->filterByDistrictId(array(12, 34)); // WHERE district_id IN (12, 34)
     * $query->filterByDistrictId(array('min' => 12)); // WHERE district_id > 12
     * </code>
     *
     * @see       filterByDistrict()
     *
     * @param     mixed $districtId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByDistrictId($districtId = null, $comparison = null)
    {
        if (is_array($districtId)) {
            $useMinMax = false;
            if (isset($districtId['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_DISTRICT_ID, $districtId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($districtId['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_DISTRICT_ID, $districtId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_DISTRICT_ID, $districtId, $comparison);
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
     * @return $this|ChildFirmQuery The current query, for fluid interface
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

        return $this->addUsingAlias(FirmTableMap::COL_ADDRESS, $address, $comparison);
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
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByCityId($cityId = null, $comparison = null)
    {
        if (is_array($cityId)) {
            $useMinMax = false;
            if (isset($cityId['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_CITY_ID, $cityId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cityId['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_CITY_ID, $cityId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_CITY_ID, $cityId, $comparison);
    }

    /**
     * Filter the query on the street column
     *
     * Example usage:
     * <code>
     * $query->filterByStreet('fooValue');   // WHERE street = 'fooValue'
     * $query->filterByStreet('%fooValue%'); // WHERE street LIKE '%fooValue%'
     * </code>
     *
     * @param     string $street The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByStreet($street = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($street)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $street)) {
                $street = str_replace('*', '%', $street);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_STREET, $street, $comparison);
    }

    /**
     * Filter the query on the home column
     *
     * Example usage:
     * <code>
     * $query->filterByHome('fooValue');   // WHERE home = 'fooValue'
     * $query->filterByHome('%fooValue%'); // WHERE home LIKE '%fooValue%'
     * </code>
     *
     * @param     string $home The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByHome($home = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($home)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $home)) {
                $home = str_replace('*', '%', $home);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_HOME, $home, $comparison);
    }

    /**
     * Filter the query on the office column
     *
     * Example usage:
     * <code>
     * $query->filterByOffice('fooValue');   // WHERE office = 'fooValue'
     * $query->filterByOffice('%fooValue%'); // WHERE office LIKE '%fooValue%'
     * </code>
     *
     * @param     string $office The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByOffice($office = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($office)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $office)) {
                $office = str_replace('*', '%', $office);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_OFFICE, $office, $comparison);
    }

    /**
     * Filter the query on the main_category column
     *
     * Example usage:
     * <code>
     * $query->filterByMainCategory(1234); // WHERE main_category = 1234
     * $query->filterByMainCategory(array(12, 34)); // WHERE main_category IN (12, 34)
     * $query->filterByMainCategory(array('min' => 12)); // WHERE main_category > 12
     * </code>
     *
     * @param     mixed $mainCategory The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByMainCategory($mainCategory = null, $comparison = null)
    {
        if (is_array($mainCategory)) {
            $useMinMax = false;
            if (isset($mainCategory['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_MAIN_CATEGORY, $mainCategory['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($mainCategory['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_MAIN_CATEGORY, $mainCategory['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_MAIN_CATEGORY, $mainCategory, $comparison);
    }

    /**
     * Filter the query on the worktime column
     *
     * Example usage:
     * <code>
     * $query->filterByWorktime('fooValue');   // WHERE worktime = 'fooValue'
     * $query->filterByWorktime('%fooValue%'); // WHERE worktime LIKE '%fooValue%'
     * </code>
     *
     * @param     string $worktime The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByWorktime($worktime = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($worktime)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $worktime)) {
                $worktime = str_replace('*', '%', $worktime);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_WORKTIME, $worktime, $comparison);
    }

    /**
     * Filter the query on the views column
     *
     * Example usage:
     * <code>
     * $query->filterByViews(1234); // WHERE views = 1234
     * $query->filterByViews(array(12, 34)); // WHERE views IN (12, 34)
     * $query->filterByViews(array('min' => 12)); // WHERE views > 12
     * </code>
     *
     * @param     mixed $views The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByViews($views = null, $comparison = null)
    {
        if (is_array($views)) {
            $useMinMax = false;
            if (isset($views['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_VIEWS, $views['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($views['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_VIEWS, $views['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_VIEWS, $views, $comparison);
    }

    /**
     * Filter the query on the created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreated(1234); // WHERE created = 1234
     * $query->filterByCreated(array(12, 34)); // WHERE created IN (12, 34)
     * $query->filterByCreated(array('min' => 12)); // WHERE created > 12
     * </code>
     *
     * @param     mixed $created The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByCreated($created = null, $comparison = null)
    {
        if (is_array($created)) {
            $useMinMax = false;
            if (isset($created['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_CREATED, $created['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($created['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_CREATED, $created['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_CREATED, $created, $comparison);
    }

    /**
     * Filter the query on the moderation_time column
     *
     * Example usage:
     * <code>
     * $query->filterByModerationTime(1234); // WHERE moderation_time = 1234
     * $query->filterByModerationTime(array(12, 34)); // WHERE moderation_time IN (12, 34)
     * $query->filterByModerationTime(array('min' => 12)); // WHERE moderation_time > 12
     * </code>
     *
     * @param     mixed $moderationTime The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByModerationTime($moderationTime = null, $comparison = null)
    {
        if (is_array($moderationTime)) {
            $useMinMax = false;
            if (isset($moderationTime['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_MODERATION_TIME, $moderationTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($moderationTime['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_MODERATION_TIME, $moderationTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_MODERATION_TIME, $moderationTime, $comparison);
    }

    /**
     * Filter the query on the changed_time column
     *
     * Example usage:
     * <code>
     * $query->filterByChangedTime(1234); // WHERE changed_time = 1234
     * $query->filterByChangedTime(array(12, 34)); // WHERE changed_time IN (12, 34)
     * $query->filterByChangedTime(array('min' => 12)); // WHERE changed_time > 12
     * </code>
     *
     * @param     mixed $changedTime The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByChangedTime($changedTime = null, $comparison = null)
    {
        if (is_array($changedTime)) {
            $useMinMax = false;
            if (isset($changedTime['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_CHANGED_TIME, $changedTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($changedTime['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_CHANGED_TIME, $changedTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_CHANGED_TIME, $changedTime, $comparison);
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
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByLon($lon = null, $comparison = null)
    {
        if (is_array($lon)) {
            $useMinMax = false;
            if (isset($lon['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_LON, $lon['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lon['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_LON, $lon['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_LON, $lon, $comparison);
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
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByLat($lat = null, $comparison = null)
    {
        if (is_array($lat)) {
            $useMinMax = false;
            if (isset($lat['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_LAT, $lat['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lat['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_LAT, $lat['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_LAT, $lat, $comparison);
    }

    /**
     * Filter the query on the random column
     *
     * Example usage:
     * <code>
     * $query->filterByRandom(1234); // WHERE random = 1234
     * $query->filterByRandom(array(12, 34)); // WHERE random IN (12, 34)
     * $query->filterByRandom(array('min' => 12)); // WHERE random > 12
     * </code>
     *
     * @param     mixed $random The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByRandom($random = null, $comparison = null)
    {
        if (is_array($random)) {
            $useMinMax = false;
            if (isset($random['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_RANDOM, $random['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($random['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_RANDOM, $random['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_RANDOM, $random, $comparison);
    }

    /**
     * Filter the query on the logo column
     *
     * Example usage:
     * <code>
     * $query->filterByLogo('fooValue');   // WHERE logo = 'fooValue'
     * $query->filterByLogo('%fooValue%'); // WHERE logo LIKE '%fooValue%'
     * </code>
     *
     * @param     string $logo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByLogo($logo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($logo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $logo)) {
                $logo = str_replace('*', '%', $logo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_LOGO, $logo, $comparison);
    }

    /**
     * Filter the query on the redirect_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRedirectID(1234); // WHERE redirect_id = 1234
     * $query->filterByRedirectID(array(12, 34)); // WHERE redirect_id IN (12, 34)
     * $query->filterByRedirectID(array('min' => 12)); // WHERE redirect_id > 12
     * </code>
     *
     * @param     mixed $redirectID The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function filterByRedirectID($redirectID = null, $comparison = null)
    {
        if (is_array($redirectID)) {
            $useMinMax = false;
            if (isset($redirectID['min'])) {
                $this->addUsingAlias(FirmTableMap::COL_REDIRECT_ID, $redirectID['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($redirectID['max'])) {
                $this->addUsingAlias(FirmTableMap::COL_REDIRECT_ID, $redirectID['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FirmTableMap::COL_REDIRECT_ID, $redirectID, $comparison);
    }

    /**
     * Filter the query by a related \PropelModel\Region object
     *
     * @param \PropelModel\Region|ObjectCollection $region The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByRegion($region, $comparison = null)
    {
        if ($region instanceof \PropelModel\Region) {
            return $this
                ->addUsingAlias(FirmTableMap::COL_CITY_ID, $region->getId(), $comparison);
        } elseif ($region instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FirmTableMap::COL_CITY_ID, $region->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function joinRegion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useRegionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRegion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Region', '\PropelModel\RegionQuery');
    }

    /**
     * Filter the query by a related \PropelModel\District object
     *
     * @param \PropelModel\District|ObjectCollection $district The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByDistrict($district, $comparison = null)
    {
        if ($district instanceof \PropelModel\District) {
            return $this
                ->addUsingAlias(FirmTableMap::COL_DISTRICT_ID, $district->getId(), $comparison);
        } elseif ($district instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FirmTableMap::COL_DISTRICT_ID, $district->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDistrict() only accepts arguments of type \PropelModel\District or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the District relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function joinDistrict($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('District');

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
            $this->addJoinObject($join, 'District');
        }

        return $this;
    }

    /**
     * Use the District relation District object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\DistrictQuery A secondary query class using the current class as primary query
     */
    public function useDistrictQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistrict($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'District', '\PropelModel\DistrictQuery');
    }

    /**
     * Filter the query by a related \PropelModel\LegalInfo object
     *
     * @param \PropelModel\LegalInfo|ObjectCollection $legalInfo The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByLegalInfoRelatedById($legalInfo, $comparison = null)
    {
        if ($legalInfo instanceof \PropelModel\LegalInfo) {
            return $this
                ->addUsingAlias(FirmTableMap::COL_ID, $legalInfo->getFirmId(), $comparison);
        } elseif ($legalInfo instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FirmTableMap::COL_ID, $legalInfo->toKeyValue('PrimaryKey', 'FirmId'), $comparison);
        } else {
            throw new PropelException('filterByLegalInfoRelatedById() only accepts arguments of type \PropelModel\LegalInfo or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LegalInfoRelatedById relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function joinLegalInfoRelatedById($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LegalInfoRelatedById');

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
            $this->addJoinObject($join, 'LegalInfoRelatedById');
        }

        return $this;
    }

    /**
     * Use the LegalInfoRelatedById relation LegalInfo object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\LegalInfoQuery A secondary query class using the current class as primary query
     */
    public function useLegalInfoRelatedByIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLegalInfoRelatedById($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LegalInfoRelatedById', '\PropelModel\LegalInfoQuery');
    }

    /**
     * Filter the query by a related \PropelModel\FirmUp object
     *
     * @param \PropelModel\FirmUp|ObjectCollection $firmUp the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByFirmUp($firmUp, $comparison = null)
    {
        if ($firmUp instanceof \PropelModel\FirmUp) {
            return $this
                ->addUsingAlias(FirmTableMap::COL_ID, $firmUp->getFirmId(), $comparison);
        } elseif ($firmUp instanceof ObjectCollection) {
            return $this
                ->useFirmUpQuery()
                ->filterByPrimaryKeys($firmUp->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFirmUp() only accepts arguments of type \PropelModel\FirmUp or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FirmUp relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function joinFirmUp($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FirmUp');

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
            $this->addJoinObject($join, 'FirmUp');
        }

        return $this;
    }

    /**
     * Use the FirmUp relation FirmUp object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\FirmUpQuery A secondary query class using the current class as primary query
     */
    public function useFirmUpQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFirmUp($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FirmUp', '\PropelModel\FirmUpQuery');
    }

    /**
     * Filter the query by a related \PropelModel\AdvServerOrders object
     *
     * @param \PropelModel\AdvServerOrders|ObjectCollection $advServerOrders the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByAdvServerOrders($advServerOrders, $comparison = null)
    {
        if ($advServerOrders instanceof \PropelModel\AdvServerOrders) {
            return $this
                ->addUsingAlias(FirmTableMap::COL_ID, $advServerOrders->getFirmId(), $comparison);
        } elseif ($advServerOrders instanceof ObjectCollection) {
            return $this
                ->useAdvServerOrdersQuery()
                ->filterByPrimaryKeys($advServerOrders->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAdvServerOrders() only accepts arguments of type \PropelModel\AdvServerOrders or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AdvServerOrders relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function joinAdvServerOrders($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AdvServerOrders');

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
            $this->addJoinObject($join, 'AdvServerOrders');
        }

        return $this;
    }

    /**
     * Use the AdvServerOrders relation AdvServerOrders object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\AdvServerOrdersQuery A secondary query class using the current class as primary query
     */
    public function useAdvServerOrdersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAdvServerOrders($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AdvServerOrders', '\PropelModel\AdvServerOrdersQuery');
    }

    /**
     * Filter the query by a related \PropelModel\Comment object
     *
     * @param \PropelModel\Comment|ObjectCollection $comment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByComment($comment, $comparison = null)
    {
        if ($comment instanceof \PropelModel\Comment) {
            return $this
                ->addUsingAlias(FirmTableMap::COL_ID, $comment->getFirmId(), $comparison);
        } elseif ($comment instanceof ObjectCollection) {
            return $this
                ->useCommentQuery()
                ->filterByPrimaryKeys($comment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByComment() only accepts arguments of type \PropelModel\Comment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Comment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function joinComment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Comment');

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
            $this->addJoinObject($join, 'Comment');
        }

        return $this;
    }

    /**
     * Use the Comment relation Comment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\CommentQuery A secondary query class using the current class as primary query
     */
    public function useCommentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Comment', '\PropelModel\CommentQuery');
    }

    /**
     * Filter the query by a related \PropelModel\Child object
     *
     * @param \PropelModel\Child|ObjectCollection $child the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByChild($child, $comparison = null)
    {
        if ($child instanceof \PropelModel\Child) {
            return $this
                ->addUsingAlias(FirmTableMap::COL_ID, $child->getFirmId(), $comparison);
        } elseif ($child instanceof ObjectCollection) {
            return $this
                ->useChildQuery()
                ->filterByPrimaryKeys($child->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByChild() only accepts arguments of type \PropelModel\Child or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Child relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function joinChild($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Child');

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
            $this->addJoinObject($join, 'Child');
        }

        return $this;
    }

    /**
     * Use the Child relation Child object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\ChildQuery A secondary query class using the current class as primary query
     */
    public function useChildQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinChild($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Child', '\PropelModel\ChildQuery');
    }

    /**
     * Filter the query by a related \PropelModel\Contact object
     *
     * @param \PropelModel\Contact|ObjectCollection $contact the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByContact($contact, $comparison = null)
    {
        if ($contact instanceof \PropelModel\Contact) {
            return $this
                ->addUsingAlias(FirmTableMap::COL_ID, $contact->getFirmId(), $comparison);
        } elseif ($contact instanceof ObjectCollection) {
            return $this
                ->useContactQuery()
                ->filterByPrimaryKeys($contact->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByContact() only accepts arguments of type \PropelModel\Contact or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Contact relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function joinContact($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Contact');

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
            $this->addJoinObject($join, 'Contact');
        }

        return $this;
    }

    /**
     * Use the Contact relation Contact object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\ContactQuery A secondary query class using the current class as primary query
     */
    public function useContactQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinContact($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Contact', '\PropelModel\ContactQuery');
    }

    /**
     * Filter the query by a related \PropelModel\FirmGroup object
     *
     * @param \PropelModel\FirmGroup|ObjectCollection $firmGroup the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByFirmGroup($firmGroup, $comparison = null)
    {
        if ($firmGroup instanceof \PropelModel\FirmGroup) {
            return $this
                ->addUsingAlias(FirmTableMap::COL_ID, $firmGroup->getFirmId(), $comparison);
        } elseif ($firmGroup instanceof ObjectCollection) {
            return $this
                ->useFirmGroupQuery()
                ->filterByPrimaryKeys($firmGroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFirmGroup() only accepts arguments of type \PropelModel\FirmGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FirmGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function joinFirmGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FirmGroup');

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
            $this->addJoinObject($join, 'FirmGroup');
        }

        return $this;
    }

    /**
     * Use the FirmGroup relation FirmGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\FirmGroupQuery A secondary query class using the current class as primary query
     */
    public function useFirmGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFirmGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FirmGroup', '\PropelModel\FirmGroupQuery');
    }

    /**
     * Filter the query by a related \PropelModel\FirmPhotos object
     *
     * @param \PropelModel\FirmPhotos|ObjectCollection $firmPhotos the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByFirmPhotos($firmPhotos, $comparison = null)
    {
        if ($firmPhotos instanceof \PropelModel\FirmPhotos) {
            return $this
                ->addUsingAlias(FirmTableMap::COL_ID, $firmPhotos->getFirmId(), $comparison);
        } elseif ($firmPhotos instanceof ObjectCollection) {
            return $this
                ->useFirmPhotosQuery()
                ->filterByPrimaryKeys($firmPhotos->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFirmPhotos() only accepts arguments of type \PropelModel\FirmPhotos or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FirmPhotos relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function joinFirmPhotos($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FirmPhotos');

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
            $this->addJoinObject($join, 'FirmPhotos');
        }

        return $this;
    }

    /**
     * Use the FirmPhotos relation FirmPhotos object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\FirmPhotosQuery A secondary query class using the current class as primary query
     */
    public function useFirmPhotosQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFirmPhotos($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FirmPhotos', '\PropelModel\FirmPhotosQuery');
    }

    /**
     * Filter the query by a related \PropelModel\FirmTags object
     *
     * @param \PropelModel\FirmTags|ObjectCollection $firmTags the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByFirmTags($firmTags, $comparison = null)
    {
        if ($firmTags instanceof \PropelModel\FirmTags) {
            return $this
                ->addUsingAlias(FirmTableMap::COL_ID, $firmTags->getFirmId(), $comparison);
        } elseif ($firmTags instanceof ObjectCollection) {
            return $this
                ->useFirmTagsQuery()
                ->filterByPrimaryKeys($firmTags->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFirmTags() only accepts arguments of type \PropelModel\FirmTags or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FirmTags relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function joinFirmTags($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FirmTags');

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
            $this->addJoinObject($join, 'FirmTags');
        }

        return $this;
    }

    /**
     * Use the FirmTags relation FirmTags object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\FirmTagsQuery A secondary query class using the current class as primary query
     */
    public function useFirmTagsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFirmTags($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FirmTags', '\PropelModel\FirmTagsQuery');
    }

    /**
     * Filter the query by a related \PropelModel\FirmUser object
     *
     * @param \PropelModel\FirmUser|ObjectCollection $firmUser the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByFirmUser($firmUser, $comparison = null)
    {
        if ($firmUser instanceof \PropelModel\FirmUser) {
            return $this
                ->addUsingAlias(FirmTableMap::COL_ID, $firmUser->getFirmId(), $comparison);
        } elseif ($firmUser instanceof ObjectCollection) {
            return $this
                ->useFirmUserQuery()
                ->filterByPrimaryKeys($firmUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFirmUser() only accepts arguments of type \PropelModel\FirmUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FirmUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function joinFirmUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FirmUser');

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
            $this->addJoinObject($join, 'FirmUser');
        }

        return $this;
    }

    /**
     * Use the FirmUser relation FirmUser object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\FirmUserQuery A secondary query class using the current class as primary query
     */
    public function useFirmUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFirmUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FirmUser', '\PropelModel\FirmUserQuery');
    }

    /**
     * Filter the query by a related \PropelModel\LegalInfo object
     *
     * @param \PropelModel\LegalInfo|ObjectCollection $legalInfo the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByLegalInfoRelatedByFirmId($legalInfo, $comparison = null)
    {
        if ($legalInfo instanceof \PropelModel\LegalInfo) {
            return $this
                ->addUsingAlias(FirmTableMap::COL_ID, $legalInfo->getFirmId(), $comparison);
        } elseif ($legalInfo instanceof ObjectCollection) {
            return $this
                ->useLegalInfoRelatedByFirmIdQuery()
                ->filterByPrimaryKeys($legalInfo->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLegalInfoRelatedByFirmId() only accepts arguments of type \PropelModel\LegalInfo or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LegalInfoRelatedByFirmId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function joinLegalInfoRelatedByFirmId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LegalInfoRelatedByFirmId');

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
            $this->addJoinObject($join, 'LegalInfoRelatedByFirmId');
        }

        return $this;
    }

    /**
     * Use the LegalInfoRelatedByFirmId relation LegalInfo object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PropelModel\LegalInfoQuery A secondary query class using the current class as primary query
     */
    public function useLegalInfoRelatedByFirmIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLegalInfoRelatedByFirmId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LegalInfoRelatedByFirmId', '\PropelModel\LegalInfoQuery');
    }

    /**
     * Filter the query by a related Group object
     * using the firm_group table as cross reference
     *
     * @param Group $group the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByGroup($group, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useFirmGroupQuery()
            ->filterByGroup($group, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related User object
     * using the firm_user table as cross reference
     *
     * @param User $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildFirmQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useFirmUserQuery()
            ->filterByUser($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildFirm $firm Object to remove from the list of results
     *
     * @return $this|ChildFirmQuery The current query, for fluid interface
     */
    public function prune($firm = null)
    {
        if ($firm) {
            $this->addUsingAlias(FirmTableMap::COL_ID, $firm->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the firm table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FirmTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FirmTableMap::clearInstancePool();
            FirmTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(FirmTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FirmTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FirmTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FirmTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // FirmQuery
