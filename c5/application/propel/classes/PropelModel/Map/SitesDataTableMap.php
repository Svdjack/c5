<?php

namespace PropelModel\Map;

use PropelModel\SitesData;
use PropelModel\SitesDataQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'sites_data' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SitesDataTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'PropelModel.Map.SitesDataTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'sites_data';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\PropelModel\\SitesData';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'PropelModel.SitesData';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the url field
     */
    const COL_URL = 'sites_data.url';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'sites_data.title';

    /**
     * the column name for the keywords field
     */
    const COL_KEYWORDS = 'sites_data.keywords';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'sites_data.description';

    /**
     * the column name for the status field
     */
    const COL_STATUS = 'sites_data.status';

    /**
     * the column name for the screen field
     */
    const COL_SCREEN = 'sites_data.screen';

    /**
     * the column name for the date field
     */
    const COL_DATE = 'sites_data.date';

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
        self::TYPE_PHPNAME       => array('Url', 'Title', 'Keywords', 'Description', 'Status', 'Screen', 'Date', ),
        self::TYPE_CAMELNAME     => array('url', 'title', 'keywords', 'description', 'status', 'screen', 'date', ),
        self::TYPE_COLNAME       => array(SitesDataTableMap::COL_URL, SitesDataTableMap::COL_TITLE, SitesDataTableMap::COL_KEYWORDS, SitesDataTableMap::COL_DESCRIPTION, SitesDataTableMap::COL_STATUS, SitesDataTableMap::COL_SCREEN, SitesDataTableMap::COL_DATE, ),
        self::TYPE_FIELDNAME     => array('url', 'title', 'keywords', 'description', 'status', 'screen', 'date', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Url' => 0, 'Title' => 1, 'Keywords' => 2, 'Description' => 3, 'Status' => 4, 'Screen' => 5, 'Date' => 6, ),
        self::TYPE_CAMELNAME     => array('url' => 0, 'title' => 1, 'keywords' => 2, 'description' => 3, 'status' => 4, 'screen' => 5, 'date' => 6, ),
        self::TYPE_COLNAME       => array(SitesDataTableMap::COL_URL => 0, SitesDataTableMap::COL_TITLE => 1, SitesDataTableMap::COL_KEYWORDS => 2, SitesDataTableMap::COL_DESCRIPTION => 3, SitesDataTableMap::COL_STATUS => 4, SitesDataTableMap::COL_SCREEN => 5, SitesDataTableMap::COL_DATE => 6, ),
        self::TYPE_FIELDNAME     => array('url' => 0, 'title' => 1, 'keywords' => 2, 'description' => 3, 'status' => 4, 'screen' => 5, 'date' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
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
        $this->setName('sites_data');
        $this->setPhpName('SitesData');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\PropelModel\\SitesData');
        $this->setPackage('PropelModel');
        $this->setUseIdGenerator(false);
        // columns
        $this->addColumn('url', 'Url', 'VARCHAR', true, 255, null);
        $this->addColumn('title', 'Title', 'LONGVARCHAR', false, null, null);
        $this->addColumn('keywords', 'Keywords', 'LONGVARCHAR', false, null, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('status', 'Status', 'INTEGER', false, 1, null);
        $this->addColumn('screen', 'Screen', 'VARCHAR', false, 255, null);
        $this->addColumn('date', 'Date', 'INTEGER', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
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
        return null;
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
        return '';
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
        return $withPrefix ? SitesDataTableMap::CLASS_DEFAULT : SitesDataTableMap::OM_CLASS;
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
     * @return array           (SitesData object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SitesDataTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SitesDataTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SitesDataTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SitesDataTableMap::OM_CLASS;
            /** @var SitesData $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SitesDataTableMap::addInstanceToPool($obj, $key);
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
            $key = SitesDataTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SitesDataTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var SitesData $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SitesDataTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SitesDataTableMap::COL_URL);
            $criteria->addSelectColumn(SitesDataTableMap::COL_TITLE);
            $criteria->addSelectColumn(SitesDataTableMap::COL_KEYWORDS);
            $criteria->addSelectColumn(SitesDataTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(SitesDataTableMap::COL_STATUS);
            $criteria->addSelectColumn(SitesDataTableMap::COL_SCREEN);
            $criteria->addSelectColumn(SitesDataTableMap::COL_DATE);
        } else {
            $criteria->addSelectColumn($alias . '.url');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.keywords');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.screen');
            $criteria->addSelectColumn($alias . '.date');
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
        return Propel::getServiceContainer()->getDatabaseMap(SitesDataTableMap::DATABASE_NAME)->getTable(SitesDataTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SitesDataTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SitesDataTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SitesDataTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a SitesData or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or SitesData object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SitesDataTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \PropelModel\SitesData) { // it's a model object
            // create criteria based on pk value
            $criteria = $values->buildCriteria();
        } else { // it's a primary key, or an array of pks
            throw new LogicException('The SitesData object has no primary key');
        }

        $query = SitesDataQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SitesDataTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SitesDataTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the sites_data table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SitesDataQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a SitesData or Criteria object.
     *
     * @param mixed               $criteria Criteria or SitesData object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SitesDataTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from SitesData object
        }


        // Set the correct dbName
        $query = SitesDataQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SitesDataTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SitesDataTableMap::buildTableMap();
