<?php

namespace PropelModel\Map;

use PropelModel\AdvServerOrders;
use PropelModel\AdvServerOrdersQuery;
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
 * This class defines the structure of the 'adv_server_orders' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class AdvServerOrdersTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'PropelModel.Map.AdvServerOrdersTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'adv_server_orders';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\PropelModel\\AdvServerOrders';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'PropelModel.AdvServerOrders';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the id field
     */
    const COL_ID = 'adv_server_orders.id';

    /**
     * the column name for the months field
     */
    const COL_MONTHS = 'adv_server_orders.months';

    /**
     * the column name for the cash field
     */
    const COL_CASH = 'adv_server_orders.cash';

    /**
     * the column name for the type field
     */
    const COL_TYPE = 'adv_server_orders.type';

    /**
     * the column name for the city_url field
     */
    const COL_CITY_URL = 'adv_server_orders.city_url';

    /**
     * the column name for the firm_id field
     */
    const COL_FIRM_ID = 'adv_server_orders.firm_id';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'adv_server_orders.email';

    /**
     * the column name for the status field
     */
    const COL_STATUS = 'adv_server_orders.status';

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
        self::TYPE_PHPNAME       => array('Id', 'Months', 'Cash', 'Type', 'CityUrl', 'FirmId', 'Email', 'Status', ),
        self::TYPE_CAMELNAME     => array('id', 'months', 'cash', 'type', 'cityUrl', 'firmId', 'email', 'status', ),
        self::TYPE_COLNAME       => array(AdvServerOrdersTableMap::COL_ID, AdvServerOrdersTableMap::COL_MONTHS, AdvServerOrdersTableMap::COL_CASH, AdvServerOrdersTableMap::COL_TYPE, AdvServerOrdersTableMap::COL_CITY_URL, AdvServerOrdersTableMap::COL_FIRM_ID, AdvServerOrdersTableMap::COL_EMAIL, AdvServerOrdersTableMap::COL_STATUS, ),
        self::TYPE_FIELDNAME     => array('id', 'months', 'cash', 'type', 'city_url', 'firm_id', 'email', 'status', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Months' => 1, 'Cash' => 2, 'Type' => 3, 'CityUrl' => 4, 'FirmId' => 5, 'Email' => 6, 'Status' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'months' => 1, 'cash' => 2, 'type' => 3, 'cityUrl' => 4, 'firmId' => 5, 'email' => 6, 'status' => 7, ),
        self::TYPE_COLNAME       => array(AdvServerOrdersTableMap::COL_ID => 0, AdvServerOrdersTableMap::COL_MONTHS => 1, AdvServerOrdersTableMap::COL_CASH => 2, AdvServerOrdersTableMap::COL_TYPE => 3, AdvServerOrdersTableMap::COL_CITY_URL => 4, AdvServerOrdersTableMap::COL_FIRM_ID => 5, AdvServerOrdersTableMap::COL_EMAIL => 6, AdvServerOrdersTableMap::COL_STATUS => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'months' => 1, 'cash' => 2, 'type' => 3, 'city_url' => 4, 'firm_id' => 5, 'email' => 6, 'status' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
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
        $this->setName('adv_server_orders');
        $this->setPhpName('AdvServerOrders');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\PropelModel\\AdvServerOrders');
        $this->setPackage('PropelModel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('months', 'Months', 'INTEGER', true, null, null);
        $this->addColumn('cash', 'Cash', 'INTEGER', true, null, null);
        $this->addColumn('type', 'Type', 'VARCHAR', true, 255, '');
        $this->addColumn('city_url', 'CityUrl', 'VARCHAR', true, 255, null);
        $this->addForeignKey('firm_id', 'FirmId', 'INTEGER', 'firm', 'id', true, null, null);
        $this->addColumn('email', 'Email', 'VARCHAR', true, 255, null);
        $this->addColumn('status', 'Status', 'INTEGER', true, 10, 0);
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
        $this->addRelation('AdvServerFirmUp', '\\PropelModel\\AdvServerFirmUp', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':order_id',
    1 => ':id',
  ),
), null, null, 'AdvServerFirmUps', false);
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
        return $withPrefix ? AdvServerOrdersTableMap::CLASS_DEFAULT : AdvServerOrdersTableMap::OM_CLASS;
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
     * @return array           (AdvServerOrders object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = AdvServerOrdersTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AdvServerOrdersTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AdvServerOrdersTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AdvServerOrdersTableMap::OM_CLASS;
            /** @var AdvServerOrders $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AdvServerOrdersTableMap::addInstanceToPool($obj, $key);
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
            $key = AdvServerOrdersTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AdvServerOrdersTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var AdvServerOrders $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AdvServerOrdersTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AdvServerOrdersTableMap::COL_ID);
            $criteria->addSelectColumn(AdvServerOrdersTableMap::COL_MONTHS);
            $criteria->addSelectColumn(AdvServerOrdersTableMap::COL_CASH);
            $criteria->addSelectColumn(AdvServerOrdersTableMap::COL_TYPE);
            $criteria->addSelectColumn(AdvServerOrdersTableMap::COL_CITY_URL);
            $criteria->addSelectColumn(AdvServerOrdersTableMap::COL_FIRM_ID);
            $criteria->addSelectColumn(AdvServerOrdersTableMap::COL_EMAIL);
            $criteria->addSelectColumn(AdvServerOrdersTableMap::COL_STATUS);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.months');
            $criteria->addSelectColumn($alias . '.cash');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.city_url');
            $criteria->addSelectColumn($alias . '.firm_id');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.status');
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
        return Propel::getServiceContainer()->getDatabaseMap(AdvServerOrdersTableMap::DATABASE_NAME)->getTable(AdvServerOrdersTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(AdvServerOrdersTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(AdvServerOrdersTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new AdvServerOrdersTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a AdvServerOrders or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or AdvServerOrders object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AdvServerOrdersTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \PropelModel\AdvServerOrders) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AdvServerOrdersTableMap::DATABASE_NAME);
            $criteria->add(AdvServerOrdersTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AdvServerOrdersQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AdvServerOrdersTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AdvServerOrdersTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the adv_server_orders table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return AdvServerOrdersQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a AdvServerOrders or Criteria object.
     *
     * @param mixed               $criteria Criteria or AdvServerOrders object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AdvServerOrdersTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from AdvServerOrders object
        }

        if ($criteria->containsKey(AdvServerOrdersTableMap::COL_ID) && $criteria->keyContainsValue(AdvServerOrdersTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AdvServerOrdersTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = AdvServerOrdersQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // AdvServerOrdersTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
AdvServerOrdersTableMap::buildTableMap();
