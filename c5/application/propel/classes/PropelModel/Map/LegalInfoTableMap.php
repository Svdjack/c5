<?php

namespace PropelModel\Map;

use PropelModel\LegalInfo;
use PropelModel\LegalInfoQuery;
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
 * This class defines the structure of the 'jur_data' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class LegalInfoTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'PropelModel.Map.LegalInfoTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'jur_data';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\PropelModel\\LegalInfo';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'PropelModel.LegalInfo';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 22;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 22;

    /**
     * the column name for the id field
     */
    const COL_ID = 'jur_data.id';

    /**
     * the column name for the rusprofile_id field
     */
    const COL_RUSPROFILE_ID = 'jur_data.rusprofile_id';

    /**
     * the column name for the firm_id field
     */
    const COL_FIRM_ID = 'jur_data.firm_id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'jur_data.name';

    /**
     * the column name for the region field
     */
    const COL_REGION = 'jur_data.region';

    /**
     * the column name for the city field
     */
    const COL_CITY = 'jur_data.city';

    /**
     * the column name for the postal field
     */
    const COL_POSTAL = 'jur_data.postal';

    /**
     * the column name for the address field
     */
    const COL_ADDRESS = 'jur_data.address';

    /**
     * the column name for the director field
     */
    const COL_DIRECTOR = 'jur_data.director';

    /**
     * the column name for the phone field
     */
    const COL_PHONE = 'jur_data.phone';

    /**
     * the column name for the inn field
     */
    const COL_INN = 'jur_data.inn';

    /**
     * the column name for the okato field
     */
    const COL_OKATO = 'jur_data.okato';

    /**
     * the column name for the fsfr field
     */
    const COL_FSFR = 'jur_data.fsfr';

    /**
     * the column name for the ogrn field
     */
    const COL_OGRN = 'jur_data.ogrn';

    /**
     * the column name for the okpo field
     */
    const COL_OKPO = 'jur_data.okpo';

    /**
     * the column name for the org_form field
     */
    const COL_ORG_FORM = 'jur_data.org_form';

    /**
     * the column name for the okogu field
     */
    const COL_OKOGU = 'jur_data.okogu';

    /**
     * the column name for the reg_date field
     */
    const COL_REG_DATE = 'jur_data.reg_date';

    /**
     * the column name for the is_liquidated field
     */
    const COL_IS_LIQUIDATED = 'jur_data.is_liquidated';

    /**
     * the column name for the capital field
     */
    const COL_CAPITAL = 'jur_data.capital';

    /**
     * the column name for the activities field
     */
    const COL_ACTIVITIES = 'jur_data.activities';

    /**
     * the column name for the kpp field
     */
    const COL_KPP = 'jur_data.kpp';

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
        self::TYPE_PHPNAME       => array('Id', 'RusprofileId', 'FirmId', 'Name', 'Region', 'City', 'Postal', 'Address', 'Director', 'Phone', 'Inn', 'Okato', 'Fsfr', 'Ogrn', 'Okpo', 'OrgForm', 'Okogu', 'RegDate', 'IsLiquidated', 'Capital', 'Activities', 'Kpp', ),
        self::TYPE_CAMELNAME     => array('id', 'rusprofileId', 'firmId', 'name', 'region', 'city', 'postal', 'address', 'director', 'phone', 'inn', 'okato', 'fsfr', 'ogrn', 'okpo', 'orgForm', 'okogu', 'regDate', 'isLiquidated', 'capital', 'activities', 'kpp', ),
        self::TYPE_COLNAME       => array(LegalInfoTableMap::COL_ID, LegalInfoTableMap::COL_RUSPROFILE_ID, LegalInfoTableMap::COL_FIRM_ID, LegalInfoTableMap::COL_NAME, LegalInfoTableMap::COL_REGION, LegalInfoTableMap::COL_CITY, LegalInfoTableMap::COL_POSTAL, LegalInfoTableMap::COL_ADDRESS, LegalInfoTableMap::COL_DIRECTOR, LegalInfoTableMap::COL_PHONE, LegalInfoTableMap::COL_INN, LegalInfoTableMap::COL_OKATO, LegalInfoTableMap::COL_FSFR, LegalInfoTableMap::COL_OGRN, LegalInfoTableMap::COL_OKPO, LegalInfoTableMap::COL_ORG_FORM, LegalInfoTableMap::COL_OKOGU, LegalInfoTableMap::COL_REG_DATE, LegalInfoTableMap::COL_IS_LIQUIDATED, LegalInfoTableMap::COL_CAPITAL, LegalInfoTableMap::COL_ACTIVITIES, LegalInfoTableMap::COL_KPP, ),
        self::TYPE_FIELDNAME     => array('id', 'rusprofile_id', 'firm_id', 'name', 'region', 'city', 'postal', 'address', 'director', 'phone', 'inn', 'okato', 'fsfr', 'ogrn', 'okpo', 'org_form', 'okogu', 'reg_date', 'is_liquidated', 'capital', 'activities', 'kpp', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'RusprofileId' => 1, 'FirmId' => 2, 'Name' => 3, 'Region' => 4, 'City' => 5, 'Postal' => 6, 'Address' => 7, 'Director' => 8, 'Phone' => 9, 'Inn' => 10, 'Okato' => 11, 'Fsfr' => 12, 'Ogrn' => 13, 'Okpo' => 14, 'OrgForm' => 15, 'Okogu' => 16, 'RegDate' => 17, 'IsLiquidated' => 18, 'Capital' => 19, 'Activities' => 20, 'Kpp' => 21, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'rusprofileId' => 1, 'firmId' => 2, 'name' => 3, 'region' => 4, 'city' => 5, 'postal' => 6, 'address' => 7, 'director' => 8, 'phone' => 9, 'inn' => 10, 'okato' => 11, 'fsfr' => 12, 'ogrn' => 13, 'okpo' => 14, 'orgForm' => 15, 'okogu' => 16, 'regDate' => 17, 'isLiquidated' => 18, 'capital' => 19, 'activities' => 20, 'kpp' => 21, ),
        self::TYPE_COLNAME       => array(LegalInfoTableMap::COL_ID => 0, LegalInfoTableMap::COL_RUSPROFILE_ID => 1, LegalInfoTableMap::COL_FIRM_ID => 2, LegalInfoTableMap::COL_NAME => 3, LegalInfoTableMap::COL_REGION => 4, LegalInfoTableMap::COL_CITY => 5, LegalInfoTableMap::COL_POSTAL => 6, LegalInfoTableMap::COL_ADDRESS => 7, LegalInfoTableMap::COL_DIRECTOR => 8, LegalInfoTableMap::COL_PHONE => 9, LegalInfoTableMap::COL_INN => 10, LegalInfoTableMap::COL_OKATO => 11, LegalInfoTableMap::COL_FSFR => 12, LegalInfoTableMap::COL_OGRN => 13, LegalInfoTableMap::COL_OKPO => 14, LegalInfoTableMap::COL_ORG_FORM => 15, LegalInfoTableMap::COL_OKOGU => 16, LegalInfoTableMap::COL_REG_DATE => 17, LegalInfoTableMap::COL_IS_LIQUIDATED => 18, LegalInfoTableMap::COL_CAPITAL => 19, LegalInfoTableMap::COL_ACTIVITIES => 20, LegalInfoTableMap::COL_KPP => 21, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'rusprofile_id' => 1, 'firm_id' => 2, 'name' => 3, 'region' => 4, 'city' => 5, 'postal' => 6, 'address' => 7, 'director' => 8, 'phone' => 9, 'inn' => 10, 'okato' => 11, 'fsfr' => 12, 'ogrn' => 13, 'okpo' => 14, 'org_form' => 15, 'okogu' => 16, 'reg_date' => 17, 'is_liquidated' => 18, 'capital' => 19, 'activities' => 20, 'kpp' => 21, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, )
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
        $this->setName('jur_data');
        $this->setPhpName('LegalInfo');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\PropelModel\\LegalInfo');
        $this->setPackage('PropelModel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('rusprofile_id', 'RusprofileId', 'INTEGER', false, null, null);
        $this->addForeignKey('firm_id', 'FirmId', 'INTEGER', 'firm', 'id', false, null, 0);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('region', 'Region', 'VARCHAR', false, 127, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 127, null);
        $this->addColumn('postal', 'Postal', 'VARCHAR', false, 7, null);
        $this->addColumn('address', 'Address', 'VARCHAR', false, 127, null);
        $this->addColumn('director', 'Director', 'VARCHAR', false, 63, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 127, null);
        $this->addColumn('inn', 'Inn', 'VARCHAR', false, 15, null);
        $this->addColumn('okato', 'Okato', 'VARCHAR', false, 15, null);
        $this->addColumn('fsfr', 'Fsfr', 'VARCHAR', false, 15, null);
        $this->addColumn('ogrn', 'Ogrn', 'VARCHAR', false, 15, null);
        $this->addColumn('okpo', 'Okpo', 'VARCHAR', false, 15, null);
        $this->addColumn('org_form', 'OrgForm', 'VARCHAR', false, 63, null);
        $this->addColumn('okogu', 'Okogu', 'VARCHAR', false, 63, null);
        $this->addColumn('reg_date', 'RegDate', 'VARCHAR', false, 63, null);
        $this->addColumn('is_liquidated', 'IsLiquidated', 'VARCHAR', false, 1, null);
        $this->addColumn('capital', 'Capital', 'VARCHAR', false, 63, null);
        $this->addColumn('activities', 'Activities', 'LONGVARCHAR', false, null, null);
        $this->addColumn('kpp', 'Kpp', 'VARCHAR', false, 255, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('FirmRelatedByFirmId', '\\PropelModel\\Firm', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':firm_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('FirmRelatedById', '\\PropelModel\\Firm', RelationMap::ONE_TO_ONE, array (
  0 =>
  array (
    0 => ':id',
    1 => ':firm_id',
  ),
), 'CASCADE', null, null, false);
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to jur_data     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        FirmTableMap::clearInstancePool();
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
        return $withPrefix ? LegalInfoTableMap::CLASS_DEFAULT : LegalInfoTableMap::OM_CLASS;
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
     * @return array           (LegalInfo object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = LegalInfoTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = LegalInfoTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + LegalInfoTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LegalInfoTableMap::OM_CLASS;
            /** @var LegalInfo $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            LegalInfoTableMap::addInstanceToPool($obj, $key);
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
            $key = LegalInfoTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = LegalInfoTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var LegalInfo $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LegalInfoTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(LegalInfoTableMap::COL_ID);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_RUSPROFILE_ID);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_FIRM_ID);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_NAME);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_REGION);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_CITY);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_POSTAL);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_ADDRESS);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_DIRECTOR);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_PHONE);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_INN);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_OKATO);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_FSFR);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_OGRN);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_OKPO);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_ORG_FORM);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_OKOGU);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_REG_DATE);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_IS_LIQUIDATED);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_CAPITAL);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_ACTIVITIES);
            $criteria->addSelectColumn(LegalInfoTableMap::COL_KPP);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.rusprofile_id');
            $criteria->addSelectColumn($alias . '.firm_id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.region');
            $criteria->addSelectColumn($alias . '.city');
            $criteria->addSelectColumn($alias . '.postal');
            $criteria->addSelectColumn($alias . '.address');
            $criteria->addSelectColumn($alias . '.director');
            $criteria->addSelectColumn($alias . '.phone');
            $criteria->addSelectColumn($alias . '.inn');
            $criteria->addSelectColumn($alias . '.okato');
            $criteria->addSelectColumn($alias . '.fsfr');
            $criteria->addSelectColumn($alias . '.ogrn');
            $criteria->addSelectColumn($alias . '.okpo');
            $criteria->addSelectColumn($alias . '.org_form');
            $criteria->addSelectColumn($alias . '.okogu');
            $criteria->addSelectColumn($alias . '.reg_date');
            $criteria->addSelectColumn($alias . '.is_liquidated');
            $criteria->addSelectColumn($alias . '.capital');
            $criteria->addSelectColumn($alias . '.activities');
            $criteria->addSelectColumn($alias . '.kpp');
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
        return Propel::getServiceContainer()->getDatabaseMap(LegalInfoTableMap::DATABASE_NAME)->getTable(LegalInfoTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(LegalInfoTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(LegalInfoTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new LegalInfoTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a LegalInfo or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or LegalInfo object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(LegalInfoTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \PropelModel\LegalInfo) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LegalInfoTableMap::DATABASE_NAME);
            $criteria->add(LegalInfoTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = LegalInfoQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            LegalInfoTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                LegalInfoTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the jur_data table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return LegalInfoQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a LegalInfo or Criteria object.
     *
     * @param mixed               $criteria Criteria or LegalInfo object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LegalInfoTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from LegalInfo object
        }

        if ($criteria->containsKey(LegalInfoTableMap::COL_ID) && $criteria->keyContainsValue(LegalInfoTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LegalInfoTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = LegalInfoQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // LegalInfoTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
LegalInfoTableMap::buildTableMap();
