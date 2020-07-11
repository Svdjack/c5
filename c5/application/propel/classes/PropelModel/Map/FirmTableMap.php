<?php

namespace PropelModel\Map;

use PropelModel\Firm;
use PropelModel\FirmQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'firm' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class FirmTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'PropelModel.Map.FirmTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'firm';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\PropelModel\\Firm';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'PropelModel.Firm';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 27;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 27;

    /**
     * the column name for the id field
     */
    const COL_ID = 'firm.id';

    /**
     * the column name for the active field
     */
    const COL_ACTIVE = 'firm.active';

    /**
     * the column name for the status field
     */
    const COL_STATUS = 'firm.status';

    /**
     * the column name for the changed field
     */
    const COL_CHANGED = 'firm.changed';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'firm.name';

    /**
     * the column name for the official_name field
     */
    const COL_OFFICIAL_NAME = 'firm.official_name';

    /**
     * the column name for the url field
     */
    const COL_URL = 'firm.url';

    /**
     * the column name for the subtitle field
     */
    const COL_SUBTITLE = 'firm.subtitle';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'firm.description';

    /**
     * the column name for the postal field
     */
    const COL_POSTAL = 'firm.postal';

    /**
     * the column name for the district_id field
     */
    const COL_DISTRICT_ID = 'firm.district_id';

    /**
     * the column name for the address field
     */
    const COL_ADDRESS = 'firm.address';

    /**
     * the column name for the city_id field
     */
    const COL_CITY_ID = 'firm.city_id';

    /**
     * the column name for the street field
     */
    const COL_STREET = 'firm.street';

    /**
     * the column name for the home field
     */
    const COL_HOME = 'firm.home';

    /**
     * the column name for the office field
     */
    const COL_OFFICE = 'firm.office';

    /**
     * the column name for the main_category field
     */
    const COL_MAIN_CATEGORY = 'firm.main_category';

    /**
     * the column name for the worktime field
     */
    const COL_WORKTIME = 'firm.worktime';

    /**
     * the column name for the views field
     */
    const COL_VIEWS = 'firm.views';

    /**
     * the column name for the created field
     */
    const COL_CREATED = 'firm.created';

    /**
     * the column name for the moderation_time field
     */
    const COL_MODERATION_TIME = 'firm.moderation_time';

    /**
     * the column name for the changed_time field
     */
    const COL_CHANGED_TIME = 'firm.changed_time';

    /**
     * the column name for the lon field
     */
    const COL_LON = 'firm.lon';

    /**
     * the column name for the lat field
     */
    const COL_LAT = 'firm.lat';

    /**
     * the column name for the random field
     */
    const COL_RANDOM = 'firm.random';

    /**
     * the column name for the logo field
     */
    const COL_LOGO = 'firm.logo';

    /**
     * the column name for the redirect_id field
     */
    const COL_REDIRECT_ID = 'firm.redirect_id';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Active', 'Status', 'Changed', 'Name', 'OfficialName', 'Url', 'Subtitle', 'Description', 'Postal', 'DistrictId', 'Address', 'CityId', 'Street', 'Home', 'Office', 'MainCategory', 'Worktime', 'Views', 'Created', 'ModerationTime', 'ChangedTime', 'Lon', 'Lat', 'Random', 'Logo', 'RedirectID', ),
        self::TYPE_CAMELNAME     => array('id', 'active', 'status', 'changed', 'name', 'officialName', 'url', 'subtitle', 'description', 'postal', 'districtId', 'address', 'cityId', 'street', 'home', 'office', 'mainCategory', 'worktime', 'views', 'created', 'moderationTime', 'changedTime', 'lon', 'lat', 'random', 'logo', 'redirectID', ),
        self::TYPE_COLNAME       => array(FirmTableMap::COL_ID, FirmTableMap::COL_ACTIVE, FirmTableMap::COL_STATUS, FirmTableMap::COL_CHANGED, FirmTableMap::COL_NAME, FirmTableMap::COL_OFFICIAL_NAME, FirmTableMap::COL_URL, FirmTableMap::COL_SUBTITLE, FirmTableMap::COL_DESCRIPTION, FirmTableMap::COL_POSTAL, FirmTableMap::COL_DISTRICT_ID, FirmTableMap::COL_ADDRESS, FirmTableMap::COL_CITY_ID, FirmTableMap::COL_STREET, FirmTableMap::COL_HOME, FirmTableMap::COL_OFFICE, FirmTableMap::COL_MAIN_CATEGORY, FirmTableMap::COL_WORKTIME, FirmTableMap::COL_VIEWS, FirmTableMap::COL_CREATED, FirmTableMap::COL_MODERATION_TIME, FirmTableMap::COL_CHANGED_TIME, FirmTableMap::COL_LON, FirmTableMap::COL_LAT, FirmTableMap::COL_RANDOM, FirmTableMap::COL_LOGO, FirmTableMap::COL_REDIRECT_ID, ),
        self::TYPE_FIELDNAME     => array('id', 'active', 'status', 'changed', 'name', 'official_name', 'url', 'subtitle', 'description', 'postal', 'district_id', 'address', 'city_id', 'street', 'home', 'office', 'main_category', 'worktime', 'views', 'created', 'moderation_time', 'changed_time', 'lon', 'lat', 'random', 'logo', 'redirect_id', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Active' => 1, 'Status' => 2, 'Changed' => 3, 'Name' => 4, 'OfficialName' => 5, 'Url' => 6, 'Subtitle' => 7, 'Description' => 8, 'Postal' => 9, 'DistrictId' => 10, 'Address' => 11, 'CityId' => 12, 'Street' => 13, 'Home' => 14, 'Office' => 15, 'MainCategory' => 16, 'Worktime' => 17, 'Views' => 18, 'Created' => 19, 'ModerationTime' => 20, 'ChangedTime' => 21, 'Lon' => 22, 'Lat' => 23, 'Random' => 24, 'Logo' => 25, 'RedirectID' => 26, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'active' => 1, 'status' => 2, 'changed' => 3, 'name' => 4, 'officialName' => 5, 'url' => 6, 'subtitle' => 7, 'description' => 8, 'postal' => 9, 'districtId' => 10, 'address' => 11, 'cityId' => 12, 'street' => 13, 'home' => 14, 'office' => 15, 'mainCategory' => 16, 'worktime' => 17, 'views' => 18, 'created' => 19, 'moderationTime' => 20, 'changedTime' => 21, 'lon' => 22, 'lat' => 23, 'random' => 24, 'logo' => 25, 'redirectID' => 26, ),
        self::TYPE_COLNAME       => array(FirmTableMap::COL_ID => 0, FirmTableMap::COL_ACTIVE => 1, FirmTableMap::COL_STATUS => 2, FirmTableMap::COL_CHANGED => 3, FirmTableMap::COL_NAME => 4, FirmTableMap::COL_OFFICIAL_NAME => 5, FirmTableMap::COL_URL => 6, FirmTableMap::COL_SUBTITLE => 7, FirmTableMap::COL_DESCRIPTION => 8, FirmTableMap::COL_POSTAL => 9, FirmTableMap::COL_DISTRICT_ID => 10, FirmTableMap::COL_ADDRESS => 11, FirmTableMap::COL_CITY_ID => 12, FirmTableMap::COL_STREET => 13, FirmTableMap::COL_HOME => 14, FirmTableMap::COL_OFFICE => 15, FirmTableMap::COL_MAIN_CATEGORY => 16, FirmTableMap::COL_WORKTIME => 17, FirmTableMap::COL_VIEWS => 18, FirmTableMap::COL_CREATED => 19, FirmTableMap::COL_MODERATION_TIME => 20, FirmTableMap::COL_CHANGED_TIME => 21, FirmTableMap::COL_LON => 22, FirmTableMap::COL_LAT => 23, FirmTableMap::COL_RANDOM => 24, FirmTableMap::COL_LOGO => 25, FirmTableMap::COL_REDIRECT_ID => 26, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'active' => 1, 'status' => 2, 'changed' => 3, 'name' => 4, 'official_name' => 5, 'url' => 6, 'subtitle' => 7, 'description' => 8, 'postal' => 9, 'district_id' => 10, 'address' => 11, 'city_id' => 12, 'street' => 13, 'home' => 14, 'office' => 15, 'main_category' => 16, 'worktime' => 17, 'views' => 18, 'created' => 19, 'moderation_time' => 20, 'changed_time' => 21, 'lon' => 22, 'lat' => 23, 'random' => 24, 'logo' => 25, 'redirect_id' => 26, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('firm');
        $this->setPhpName('Firm');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\PropelModel\\Firm');
        $this->setPackage('PropelModel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addForeignPrimaryKey('id', 'Id', 'INTEGER' , 'jur_data', 'firm_id', true, null, null);
        $this->addColumn('active', 'Active', 'TINYINT', true, 3, 1);
        $this->addColumn('status', 'Status', 'TINYINT', true, 3, 1);
        $this->addColumn('changed', 'Changed', 'TINYINT', true, 3, 0);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('official_name', 'OfficialName', 'VARCHAR', false, 255, null);
        $this->addColumn('url', 'Url', 'VARCHAR', false, 255, null);
        $this->addColumn('subtitle', 'Subtitle', 'VARCHAR', false, 255, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('postal', 'Postal', 'VARCHAR', false, 10, null);
        $this->addForeignKey('district_id', 'DistrictId', 'INTEGER', 'district', 'id', true, 10, 0);
        $this->addColumn('address', 'Address', 'VARCHAR', true, 255, '');
        $this->addForeignKey('city_id', 'CityId', 'INTEGER', 'region', 'id', true, 10, 0);
        $this->addColumn('street', 'Street', 'VARCHAR', false, 100, null);
        $this->addColumn('home', 'Home', 'VARCHAR', false, 100, null);
        $this->addColumn('office', 'Office', 'VARCHAR', false, 255, null);
        $this->addColumn('main_category', 'MainCategory', 'INTEGER', true, 10, 0);
        $this->addColumn('worktime', 'Worktime', 'LONGVARCHAR', false, null, null);
        $this->addColumn('views', 'Views', 'INTEGER', true, null, 0);
        $this->addColumn('created', 'Created', 'INTEGER', true, null, 0);
        $this->addColumn('moderation_time', 'ModerationTime', 'INTEGER', true, null, null);
        $this->addColumn('changed_time', 'ChangedTime', 'INTEGER', true, null, 0);
        $this->addColumn('lon', 'Lon', 'FLOAT', false, 15, 0);
        $this->addColumn('lat', 'Lat', 'FLOAT', false, 15, 0);
        $this->addColumn('random', 'Random', 'INTEGER', true, 10, 0);
        $this->addColumn('logo', 'Logo', 'VARCHAR', false, 255, null);
        $this->addColumn('redirect_id', 'RedirectID', 'INTEGER', true, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Region', '\\PropelModel\\Region', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':city_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('District', '\\PropelModel\\District', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':district_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('LegalInfoRelatedById', '\\PropelModel\\LegalInfo', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':id',
    1 => ':firm_id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('FirmUp', '\\PropelModel\\FirmUp', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':firm_id',
    1 => ':id',
  ),
), null, null, 'FirmUps', false);
        $this->addRelation('AdvServerOrders', '\\PropelModel\\AdvServerOrders', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':firm_id',
    1 => ':id',
  ),
), null, null, 'AdvServerOrderss', false);
        $this->addRelation('Comment', '\\PropelModel\\Comment', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':firm_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'Comments', false);
        $this->addRelation('Child', '\\PropelModel\\Child', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':firm_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'Children', false);
        $this->addRelation('Contact', '\\PropelModel\\Contact', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':firm_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'Contacts', false);
        $this->addRelation('FirmGroup', '\\PropelModel\\FirmGroup', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':firm_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'FirmGroups', false);
        $this->addRelation('FirmPhotos', '\\PropelModel\\FirmPhotos', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':firm_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'FirmPhotoss', false);
        $this->addRelation('FirmTags', '\\PropelModel\\FirmTags', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':firm_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'FirmTagss', false);
        $this->addRelation('FirmUser', '\\PropelModel\\FirmUser', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':firm_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'FirmUsers', false);
        $this->addRelation('LegalInfoRelatedByFirmId', '\\PropelModel\\LegalInfo', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':firm_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'LegalInfosRelatedByFirmId', false);
        $this->addRelation('Group', '\\PropelModel\\Group', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Groups');
        $this->addRelation('User', '\\PropelModel\\User', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Users');
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to firm     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        CommentTableMap::clearInstancePool();
        ChildTableMap::clearInstancePool();
        ContactTableMap::clearInstancePool();
        FirmGroupTableMap::clearInstancePool();
        FirmPhotosTableMap::clearInstancePool();
        FirmTagsTableMap::clearInstancePool();
        FirmUserTableMap::clearInstancePool();
        LegalInfoTableMap::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? FirmTableMap::CLASS_DEFAULT : FirmTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Firm object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = FirmTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = FirmTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + FirmTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = FirmTableMap::OM_CLASS;
            /** @var Firm $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            FirmTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = FirmTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = FirmTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Firm $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                FirmTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(FirmTableMap::COL_ID);
            $criteria->addSelectColumn(FirmTableMap::COL_ACTIVE);
            $criteria->addSelectColumn(FirmTableMap::COL_STATUS);
            $criteria->addSelectColumn(FirmTableMap::COL_CHANGED);
            $criteria->addSelectColumn(FirmTableMap::COL_NAME);
            $criteria->addSelectColumn(FirmTableMap::COL_OFFICIAL_NAME);
            $criteria->addSelectColumn(FirmTableMap::COL_URL);
            $criteria->addSelectColumn(FirmTableMap::COL_SUBTITLE);
            $criteria->addSelectColumn(FirmTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(FirmTableMap::COL_POSTAL);
            $criteria->addSelectColumn(FirmTableMap::COL_DISTRICT_ID);
            $criteria->addSelectColumn(FirmTableMap::COL_ADDRESS);
            $criteria->addSelectColumn(FirmTableMap::COL_CITY_ID);
            $criteria->addSelectColumn(FirmTableMap::COL_STREET);
            $criteria->addSelectColumn(FirmTableMap::COL_HOME);
            $criteria->addSelectColumn(FirmTableMap::COL_OFFICE);
            $criteria->addSelectColumn(FirmTableMap::COL_MAIN_CATEGORY);
            $criteria->addSelectColumn(FirmTableMap::COL_WORKTIME);
            $criteria->addSelectColumn(FirmTableMap::COL_VIEWS);
            $criteria->addSelectColumn(FirmTableMap::COL_CREATED);
            $criteria->addSelectColumn(FirmTableMap::COL_MODERATION_TIME);
            $criteria->addSelectColumn(FirmTableMap::COL_CHANGED_TIME);
            $criteria->addSelectColumn(FirmTableMap::COL_LON);
            $criteria->addSelectColumn(FirmTableMap::COL_LAT);
            $criteria->addSelectColumn(FirmTableMap::COL_RANDOM);
            $criteria->addSelectColumn(FirmTableMap::COL_LOGO);
            $criteria->addSelectColumn(FirmTableMap::COL_REDIRECT_ID);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.active');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.changed');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.official_name');
            $criteria->addSelectColumn($alias . '.url');
            $criteria->addSelectColumn($alias . '.subtitle');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.postal');
            $criteria->addSelectColumn($alias . '.district_id');
            $criteria->addSelectColumn($alias . '.address');
            $criteria->addSelectColumn($alias . '.city_id');
            $criteria->addSelectColumn($alias . '.street');
            $criteria->addSelectColumn($alias . '.home');
            $criteria->addSelectColumn($alias . '.office');
            $criteria->addSelectColumn($alias . '.main_category');
            $criteria->addSelectColumn($alias . '.worktime');
            $criteria->addSelectColumn($alias . '.views');
            $criteria->addSelectColumn($alias . '.created');
            $criteria->addSelectColumn($alias . '.moderation_time');
            $criteria->addSelectColumn($alias . '.changed_time');
            $criteria->addSelectColumn($alias . '.lon');
            $criteria->addSelectColumn($alias . '.lat');
            $criteria->addSelectColumn($alias . '.random');
            $criteria->addSelectColumn($alias . '.logo');
            $criteria->addSelectColumn($alias . '.redirect_id');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(FirmTableMap::DATABASE_NAME)->getTable(FirmTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(FirmTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(FirmTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new FirmTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Firm or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Firm object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FirmTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \PropelModel\Firm) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(FirmTableMap::DATABASE_NAME);
            $criteria->add(FirmTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = FirmQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            FirmTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                FirmTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the firm table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return FirmQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Firm or Criteria object.
     *
     * @param mixed               $criteria Criteria or Firm object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FirmTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Firm object
        }

        if ($criteria->containsKey(FirmTableMap::COL_ID) && $criteria->keyContainsValue(FirmTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.FirmTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = FirmQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // FirmTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
FirmTableMap::buildTableMap();
