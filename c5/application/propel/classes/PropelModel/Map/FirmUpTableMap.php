<?php

namespace PropelModel\Map;

use PropelModel\FirmUp;
use PropelModel\FirmUpQuery;
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
 * This class defines the structure of the 'firm_up' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class FirmUpTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'PropelModel.Map.FirmUpTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'firm_up';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\PropelModel\\FirmUp';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'PropelModel.FirmUp';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 11;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 11;

    /**
     * the column name for the id field
     */
    const COL_ID = 'firm_up.id';

    /**
     * the column name for the firm_id field
     */
    const COL_FIRM_ID = 'firm_up.firm_id';

    /**
     * the column name for the time_start field
     */
    const COL_TIME_START = 'firm_up.time_start';

    /**
     * the column name for the time_end field
     */
    const COL_TIME_END = 'firm_up.time_end';

    /**
     * the column name for the cash field
     */
    const COL_CASH = 'firm_up.cash';

    /**
     * the column name for the type field
     */
    const COL_TYPE = 'firm_up.type';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'firm_up.email';

    /**
     * the column name for the status field
     */
    const COL_STATUS = 'firm_up.status';

    /**
     * the column name for the spam_type field
     */
    const COL_SPAM_TYPE = 'firm_up.spam_type';

    /**
     * the column name for the last_mail_send field
     */
    const COL_LAST_MAIL_SEND = 'firm_up.last_mail_send';

    /**
     * the column name for the last_days field
     */
    const COL_LAST_DAYS = 'firm_up.last_days';

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
        self::TYPE_PHPNAME       => array('Id', 'FirmId', 'TimeStart', 'TimeEnd', 'Cash', 'Type', 'Email', 'Status', 'SpamType', 'LastMailSend', 'LastDays', ),
        self::TYPE_CAMELNAME     => array('id', 'firmId', 'timeStart', 'timeEnd', 'cash', 'type', 'email', 'status', 'spamType', 'lastMailSend', 'lastDays', ),
        self::TYPE_COLNAME       => array(FirmUpTableMap::COL_ID, FirmUpTableMap::COL_FIRM_ID, FirmUpTableMap::COL_TIME_START, FirmUpTableMap::COL_TIME_END, FirmUpTableMap::COL_CASH, FirmUpTableMap::COL_TYPE, FirmUpTableMap::COL_EMAIL, FirmUpTableMap::COL_STATUS, FirmUpTableMap::COL_SPAM_TYPE, FirmUpTableMap::COL_LAST_MAIL_SEND, FirmUpTableMap::COL_LAST_DAYS, ),
        self::TYPE_FIELDNAME     => array('id', 'firm_id', 'time_start', 'time_end', 'cash', 'type', 'email', 'status', 'spam_type', 'last_mail_send', 'last_days', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'FirmId' => 1, 'TimeStart' => 2, 'TimeEnd' => 3, 'Cash' => 4, 'Type' => 5, 'Email' => 6, 'Status' => 7, 'SpamType' => 8, 'LastMailSend' => 9, 'LastDays' => 10, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'firmId' => 1, 'timeStart' => 2, 'timeEnd' => 3, 'cash' => 4, 'type' => 5, 'email' => 6, 'status' => 7, 'spamType' => 8, 'lastMailSend' => 9, 'lastDays' => 10, ),
        self::TYPE_COLNAME       => array(FirmUpTableMap::COL_ID => 0, FirmUpTableMap::COL_FIRM_ID => 1, FirmUpTableMap::COL_TIME_START => 2, FirmUpTableMap::COL_TIME_END => 3, FirmUpTableMap::COL_CASH => 4, FirmUpTableMap::COL_TYPE => 5, FirmUpTableMap::COL_EMAIL => 6, FirmUpTableMap::COL_STATUS => 7, FirmUpTableMap::COL_SPAM_TYPE => 8, FirmUpTableMap::COL_LAST_MAIL_SEND => 9, FirmUpTableMap::COL_LAST_DAYS => 10, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'firm_id' => 1, 'time_start' => 2, 'time_end' => 3, 'cash' => 4, 'type' => 5, 'email' => 6, 'status' => 7, 'spam_type' => 8, 'last_mail_send' => 9, 'last_days' => 10, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
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
        $this->setName('firm_up');
        $this->setPhpName('FirmUp');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\PropelModel\\FirmUp');
        $this->setPackage('PropelModel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 10, null);
        $this->addForeignKey('firm_id', 'FirmId', 'INTEGER', 'firm', 'id', false, null, null);
        $this->addColumn('time_start', 'TimeStart', 'INTEGER', true, 10, 0);
        $this->addColumn('time_end', 'TimeEnd', 'INTEGER', true, 10, 0);
        $this->addColumn('cash', 'Cash', 'INTEGER', true, 10, 0);
        $this->addColumn('type', 'Type', 'VARCHAR', true, 255, '');
        $this->addColumn('email', 'Email', 'VARCHAR', true, 255, '');
        $this->addColumn('status', 'Status', 'INTEGER', true, 10, 0);
        $this->addColumn('spam_type', 'SpamType', 'INTEGER', true, 10, 0);
        $this->addColumn('last_mail_send', 'LastMailSend', 'INTEGER', true, 10, 0);
        $this->addColumn('last_days', 'LastDays', 'INTEGER', true, 10, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Firm', '\\PropelModel\\Firm', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':firm_id',
    1 => ':id',
  ),
), null, null, null, false);
    } // buildRelations()

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
        return $withPrefix ? FirmUpTableMap::CLASS_DEFAULT : FirmUpTableMap::OM_CLASS;
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
     * @return array           (FirmUp object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = FirmUpTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = FirmUpTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + FirmUpTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = FirmUpTableMap::OM_CLASS;
            /** @var FirmUp $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            FirmUpTableMap::addInstanceToPool($obj, $key);
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
            $key = FirmUpTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = FirmUpTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var FirmUp $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                FirmUpTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(FirmUpTableMap::COL_ID);
            $criteria->addSelectColumn(FirmUpTableMap::COL_FIRM_ID);
            $criteria->addSelectColumn(FirmUpTableMap::COL_TIME_START);
            $criteria->addSelectColumn(FirmUpTableMap::COL_TIME_END);
            $criteria->addSelectColumn(FirmUpTableMap::COL_CASH);
            $criteria->addSelectColumn(FirmUpTableMap::COL_TYPE);
            $criteria->addSelectColumn(FirmUpTableMap::COL_EMAIL);
            $criteria->addSelectColumn(FirmUpTableMap::COL_STATUS);
            $criteria->addSelectColumn(FirmUpTableMap::COL_SPAM_TYPE);
            $criteria->addSelectColumn(FirmUpTableMap::COL_LAST_MAIL_SEND);
            $criteria->addSelectColumn(FirmUpTableMap::COL_LAST_DAYS);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.firm_id');
            $criteria->addSelectColumn($alias . '.time_start');
            $criteria->addSelectColumn($alias . '.time_end');
            $criteria->addSelectColumn($alias . '.cash');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.spam_type');
            $criteria->addSelectColumn($alias . '.last_mail_send');
            $criteria->addSelectColumn($alias . '.last_days');
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
        return Propel::getServiceContainer()->getDatabaseMap(FirmUpTableMap::DATABASE_NAME)->getTable(FirmUpTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(FirmUpTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(FirmUpTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new FirmUpTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a FirmUp or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or FirmUp object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(FirmUpTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \PropelModel\FirmUp) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(FirmUpTableMap::DATABASE_NAME);
            $criteria->add(FirmUpTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = FirmUpQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            FirmUpTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                FirmUpTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the firm_up table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return FirmUpQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a FirmUp or Criteria object.
     *
     * @param mixed               $criteria Criteria or FirmUp object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FirmUpTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from FirmUp object
        }

        if ($criteria->containsKey(FirmUpTableMap::COL_ID) && $criteria->keyContainsValue(FirmUpTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.FirmUpTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = FirmUpQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // FirmUpTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
FirmUpTableMap::buildTableMap();
