<?php

namespace PropelModel\Map;

use PropelModel\Comment;
use PropelModel\CommentQuery;
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
 * This class defines the structure of the 'comment' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class CommentTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'PropelModel.Map.CommentTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'comment';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\PropelModel\\Comment';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'PropelModel.Comment';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 13;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 13;

    /**
     * the column name for the id field
     */
    const COL_ID = 'comment.id';

    /**
     * the column name for the firm_id field
     */
    const COL_FIRM_ID = 'comment.firm_id';

    /**
     * the column name for the user field
     */
    const COL_USER = 'comment.user';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'comment.email';

    /**
     * the column name for the text field
     */
    const COL_TEXT = 'comment.text';

    /**
     * the column name for the date field
     */
    const COL_DATE = 'comment.date';

    /**
     * the column name for the moderation_time field
     */
    const COL_MODERATION_TIME = 'comment.moderation_time';

    /**
     * the column name for the score field
     */
    const COL_SCORE = 'comment.score';

    /**
     * the column name for the ip field
     */
    const COL_IP = 'comment.ip';

    /**
     * the column name for the edited field
     */
    const COL_EDITED = 'comment.edited';

    /**
     * the column name for the emotion field
     */
    const COL_EMOTION = 'comment.emotion';

    /**
     * the column name for the twitter field
     */
    const COL_TWITTER = 'comment.twitter';

    /**
     * the column name for the active field
     */
    const COL_ACTIVE = 'comment.active';

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
        self::TYPE_PHPNAME       => array('Id', 'FirmId', 'User', 'Email', 'Text', 'Date', 'ModerationTime', 'Score', 'Ip', 'Edited', 'Emotion', 'Twitter', 'Active', ),
        self::TYPE_CAMELNAME     => array('id', 'firmId', 'user', 'email', 'text', 'date', 'moderationTime', 'score', 'ip', 'edited', 'emotion', 'twitter', 'active', ),
        self::TYPE_COLNAME       => array(CommentTableMap::COL_ID, CommentTableMap::COL_FIRM_ID, CommentTableMap::COL_USER, CommentTableMap::COL_EMAIL, CommentTableMap::COL_TEXT, CommentTableMap::COL_DATE, CommentTableMap::COL_MODERATION_TIME, CommentTableMap::COL_SCORE, CommentTableMap::COL_IP, CommentTableMap::COL_EDITED, CommentTableMap::COL_EMOTION, CommentTableMap::COL_TWITTER, CommentTableMap::COL_ACTIVE, ),
        self::TYPE_FIELDNAME     => array('id', 'firm_id', 'user', 'email', 'text', 'date', 'moderation_time', 'score', 'ip', 'edited', 'emotion', 'twitter', 'active', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'FirmId' => 1, 'User' => 2, 'Email' => 3, 'Text' => 4, 'Date' => 5, 'ModerationTime' => 6, 'Score' => 7, 'Ip' => 8, 'Edited' => 9, 'Emotion' => 10, 'Twitter' => 11, 'Active' => 12, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'firmId' => 1, 'user' => 2, 'email' => 3, 'text' => 4, 'date' => 5, 'moderationTime' => 6, 'score' => 7, 'ip' => 8, 'edited' => 9, 'emotion' => 10, 'twitter' => 11, 'active' => 12, ),
        self::TYPE_COLNAME       => array(CommentTableMap::COL_ID => 0, CommentTableMap::COL_FIRM_ID => 1, CommentTableMap::COL_USER => 2, CommentTableMap::COL_EMAIL => 3, CommentTableMap::COL_TEXT => 4, CommentTableMap::COL_DATE => 5, CommentTableMap::COL_MODERATION_TIME => 6, CommentTableMap::COL_SCORE => 7, CommentTableMap::COL_IP => 8, CommentTableMap::COL_EDITED => 9, CommentTableMap::COL_EMOTION => 10, CommentTableMap::COL_TWITTER => 11, CommentTableMap::COL_ACTIVE => 12, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'firm_id' => 1, 'user' => 2, 'email' => 3, 'text' => 4, 'date' => 5, 'moderation_time' => 6, 'score' => 7, 'ip' => 8, 'edited' => 9, 'emotion' => 10, 'twitter' => 11, 'active' => 12, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
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
        $this->setName('comment');
        $this->setPhpName('Comment');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\PropelModel\\Comment');
        $this->setPackage('PropelModel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 10, null);
        $this->addForeignKey('firm_id', 'FirmId', 'INTEGER', 'firm', 'id', true, null, null);
        $this->addColumn('user', 'User', 'VARCHAR', true, 200, '0');
        $this->addColumn('email', 'Email', 'VARCHAR', true, 200, '');
        $this->addColumn('text', 'Text', 'LONGVARCHAR', true, null, null);
        $this->addColumn('date', 'Date', 'TIMESTAMP', true, null, null);
        $this->addColumn('moderation_time', 'ModerationTime', 'TIMESTAMP', true, null, null);
        $this->addColumn('score', 'Score', 'TINYINT', true, null, 0);
        $this->addColumn('ip', 'Ip', 'VARCHAR', true, 100, '');
        $this->addColumn('edited', 'Edited', 'TINYINT', true, 3, 0);
        $this->addColumn('emotion', 'Emotion', 'CHAR', true, null, null);
        $this->addColumn('twitter', 'Twitter', 'TINYINT', true, 3, 0);
        $this->addColumn('active', 'Active', 'TINYINT', true, 3, 1);
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
), 'CASCADE', null, null, false);
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
        return $withPrefix ? CommentTableMap::CLASS_DEFAULT : CommentTableMap::OM_CLASS;
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
     * @return array           (Comment object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CommentTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CommentTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CommentTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CommentTableMap::OM_CLASS;
            /** @var Comment $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CommentTableMap::addInstanceToPool($obj, $key);
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
            $key = CommentTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CommentTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Comment $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CommentTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CommentTableMap::COL_ID);
            $criteria->addSelectColumn(CommentTableMap::COL_FIRM_ID);
            $criteria->addSelectColumn(CommentTableMap::COL_USER);
            $criteria->addSelectColumn(CommentTableMap::COL_EMAIL);
            $criteria->addSelectColumn(CommentTableMap::COL_TEXT);
            $criteria->addSelectColumn(CommentTableMap::COL_DATE);
            $criteria->addSelectColumn(CommentTableMap::COL_MODERATION_TIME);
            $criteria->addSelectColumn(CommentTableMap::COL_SCORE);
            $criteria->addSelectColumn(CommentTableMap::COL_IP);
            $criteria->addSelectColumn(CommentTableMap::COL_EDITED);
            $criteria->addSelectColumn(CommentTableMap::COL_EMOTION);
            $criteria->addSelectColumn(CommentTableMap::COL_TWITTER);
            $criteria->addSelectColumn(CommentTableMap::COL_ACTIVE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.firm_id');
            $criteria->addSelectColumn($alias . '.user');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.text');
            $criteria->addSelectColumn($alias . '.date');
            $criteria->addSelectColumn($alias . '.moderation_time');
            $criteria->addSelectColumn($alias . '.score');
            $criteria->addSelectColumn($alias . '.ip');
            $criteria->addSelectColumn($alias . '.edited');
            $criteria->addSelectColumn($alias . '.emotion');
            $criteria->addSelectColumn($alias . '.twitter');
            $criteria->addSelectColumn($alias . '.active');
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
        return Propel::getServiceContainer()->getDatabaseMap(CommentTableMap::DATABASE_NAME)->getTable(CommentTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CommentTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(CommentTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new CommentTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Comment or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Comment object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CommentTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \PropelModel\Comment) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CommentTableMap::DATABASE_NAME);
            $criteria->add(CommentTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = CommentQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CommentTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CommentTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the comment table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CommentQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Comment or Criteria object.
     *
     * @param mixed               $criteria Criteria or Comment object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CommentTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Comment object
        }

        if ($criteria->containsKey(CommentTableMap::COL_ID) && $criteria->keyContainsValue(CommentTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CommentTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = CommentQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // CommentTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CommentTableMap::buildTableMap();
