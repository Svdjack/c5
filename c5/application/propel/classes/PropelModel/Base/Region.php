<?php

namespace PropelModel\Base;

use \Exception;
use \PDO;
use PropelModel\AdvServerPrices as ChildAdvServerPrices;
use PropelModel\AdvServerPricesQuery as ChildAdvServerPricesQuery;
use PropelModel\Firm as ChildFirm;
use PropelModel\FirmQuery as ChildFirmQuery;
use PropelModel\Region as ChildRegion;
use PropelModel\RegionQuery as ChildRegionQuery;
use PropelModel\Stat as ChildStat;
use PropelModel\StatQuery as ChildStatQuery;
use PropelModel\Map\RegionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'region' table.
 *
 *
 *
* @package    propel.generator.PropelModel.Base
*/
abstract class Region implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\PropelModel\\Map\\RegionTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the area field.
     * @var        int
     */
    protected $area;

    /**
     * The value for the telcode field.
     * @var        int
     */
    protected $telcode;

    /**
     * The value for the timezone field.
     * @var        string
     */
    protected $timezone;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the url field.
     * @var        string
     */
    protected $url;

    /**
     * The value for the count field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $count;

    /**
     * The value for the data field.
     * @var        resource
     */
    protected $data;

    /**
     * The value for the lon field.
     * @var        double
     */
    protected $lon;

    /**
     * The value for the lat field.
     * @var        double
     */
    protected $lat;

    /**
     * @var        ObjectCollection|ChildStat[] Collection to store aggregation of ChildStat objects.
     */
    protected $collStats;
    protected $collStatsPartial;

    /**
     * @var        ObjectCollection|ChildAdvServerPrices[] Collection to store aggregation of ChildAdvServerPrices objects.
     */
    protected $collAdvServerPricess;
    protected $collAdvServerPricessPartial;

    /**
     * @var        ObjectCollection|ChildFirm[] Collection to store aggregation of ChildFirm objects.
     */
    protected $collFirms;
    protected $collFirmsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildStat[]
     */
    protected $statsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAdvServerPrices[]
     */
    protected $advServerPricessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFirm[]
     */
    protected $firmsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->count = 0;
    }

    /**
     * Initializes internal state of PropelModel\Base\Region object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Region</code> instance.  If
     * <code>obj</code> is an instance of <code>Region</code>, delegates to
     * <code>equals(Region)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Region The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [area] column value.
     *
     * @return int
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Get the [telcode] column value.
     *
     * @return int
     */
    public function getTelcode()
    {
        return $this->telcode;
    }

    /**
     * Get the [timezone] column value.
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [url] column value.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get the [count] column value.
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Get the [data] column value.
     *
     * @return resource
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the [lon] column value.
     *
     * @return double
     */
    public function getLon()
    {
        return $this->lon ?? 0;
    }

    /**
     * Get the [lat] column value.
     *
     * @return double
     */
    public function getLat()
    {
        return $this->lat ?? 0;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Region The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[RegionTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [area] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Region The current object (for fluent API support)
     */
    public function setArea($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->area !== $v) {
            $this->area = $v;
            $this->modifiedColumns[RegionTableMap::COL_AREA] = true;
        }

        return $this;
    } // setArea()

    /**
     * Set the value of [telcode] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Region The current object (for fluent API support)
     */
    public function setTelcode($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->telcode !== $v) {
            $this->telcode = $v;
            $this->modifiedColumns[RegionTableMap::COL_TELCODE] = true;
        }

        return $this;
    } // setTelcode()

    /**
     * Set the value of [timezone] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Region The current object (for fluent API support)
     */
    public function setTimezone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->timezone !== $v) {
            $this->timezone = $v;
            $this->modifiedColumns[RegionTableMap::COL_TIMEZONE] = true;
        }

        return $this;
    } // setTimezone()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Region The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[RegionTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [url] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Region The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->url !== $v) {
            $this->url = $v;
            $this->modifiedColumns[RegionTableMap::COL_URL] = true;
        }

        return $this;
    } // setUrl()

    /**
     * Set the value of [count] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Region The current object (for fluent API support)
     */
    public function setCount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->count !== $v) {
            $this->count = $v;
            $this->modifiedColumns[RegionTableMap::COL_COUNT] = true;
        }

        return $this;
    } // setCount()

    /**
     * Set the value of [data] column.
     *
     * @param resource $v new value
     * @return $this|\PropelModel\Region The current object (for fluent API support)
     */
    public function setData($v)
    {
        // Because BLOB columns are streams in PDO we have to assume that they are
        // always modified when a new value is passed in.  For example, the contents
        // of the stream itself may have changed externally.
        if (!is_resource($v) && $v !== null) {
            $this->data = fopen('php://memory', 'r+');
            fwrite($this->data, $v);
            rewind($this->data);
        } else { // it's already a stream
            $this->data = $v;
        }
        $this->modifiedColumns[RegionTableMap::COL_DATA] = true;

        return $this;
    } // setData()

    /**
     * Set the value of [lon] column.
     *
     * @param double $v new value
     * @return $this|\PropelModel\Region The current object (for fluent API support)
     */
    public function setLon($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->lon !== $v) {
            $this->lon = $v;
            $this->modifiedColumns[RegionTableMap::COL_LON] = true;
        }

        return $this;
    } // setLon()

    /**
     * Set the value of [lat] column.
     *
     * @param double $v new value
     * @return $this|\PropelModel\Region The current object (for fluent API support)
     */
    public function setLat($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->lat !== $v) {
            $this->lat = $v;
            $this->modifiedColumns[RegionTableMap::COL_LAT] = true;
        }

        return $this;
    } // setLat()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->count !== 0) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : RegionTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : RegionTableMap::translateFieldName('Area', TableMap::TYPE_PHPNAME, $indexType)];
            $this->area = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : RegionTableMap::translateFieldName('Telcode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->telcode = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : RegionTableMap::translateFieldName('Timezone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->timezone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : RegionTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : RegionTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : RegionTableMap::translateFieldName('Count', TableMap::TYPE_PHPNAME, $indexType)];
            $this->count = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : RegionTableMap::translateFieldName('Data', TableMap::TYPE_PHPNAME, $indexType)];
            if (null !== $col) {
                $this->data = fopen('php://memory', 'r+');
                fwrite($this->data, $col);
                rewind($this->data);
            } else {
                $this->data = null;
            }

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : RegionTableMap::translateFieldName('Lon', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lon = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : RegionTableMap::translateFieldName('Lat', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lat = (null !== $col) ? (double) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = RegionTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\PropelModel\\Region'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(RegionTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildRegionQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collStats = null;

            $this->collAdvServerPricess = null;

            $this->collFirms = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Region::setDeleted()
     * @see Region::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(RegionTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildRegionQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(RegionTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                RegionTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                // Rewind the data LOB column, since PDO does not rewind after inserting value.
                if ($this->data !== null && is_resource($this->data)) {
                    rewind($this->data);
                }

                $this->resetModified();
            }

            if ($this->statsScheduledForDeletion !== null) {
                if (!$this->statsScheduledForDeletion->isEmpty()) {
                    \PropelModel\StatQuery::create()
                        ->filterByPrimaryKeys($this->statsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->statsScheduledForDeletion = null;
                }
            }

            if ($this->collStats !== null) {
                foreach ($this->collStats as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->advServerPricessScheduledForDeletion !== null) {
                if (!$this->advServerPricessScheduledForDeletion->isEmpty()) {
                    foreach ($this->advServerPricessScheduledForDeletion as $advServerPrices) {
                        // need to save related object because we set the relation to null
                        $advServerPrices->save($con);
                    }
                    $this->advServerPricessScheduledForDeletion = null;
                }
            }

            if ($this->collAdvServerPricess !== null) {
                foreach ($this->collAdvServerPricess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->firmsScheduledForDeletion !== null) {
                if (!$this->firmsScheduledForDeletion->isEmpty()) {
                    \PropelModel\FirmQuery::create()
                        ->filterByPrimaryKeys($this->firmsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->firmsScheduledForDeletion = null;
                }
            }

            if ($this->collFirms !== null) {
                foreach ($this->collFirms as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[RegionTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . RegionTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(RegionTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(RegionTableMap::COL_AREA)) {
            $modifiedColumns[':p' . $index++]  = 'area';
        }
        if ($this->isColumnModified(RegionTableMap::COL_TELCODE)) {
            $modifiedColumns[':p' . $index++]  = 'telcode';
        }
        if ($this->isColumnModified(RegionTableMap::COL_TIMEZONE)) {
            $modifiedColumns[':p' . $index++]  = 'timezone';
        }
        if ($this->isColumnModified(RegionTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(RegionTableMap::COL_URL)) {
            $modifiedColumns[':p' . $index++]  = 'url';
        }
        if ($this->isColumnModified(RegionTableMap::COL_COUNT)) {
            $modifiedColumns[':p' . $index++]  = 'count';
        }
        if ($this->isColumnModified(RegionTableMap::COL_DATA)) {
            $modifiedColumns[':p' . $index++]  = 'data';
        }
        if ($this->isColumnModified(RegionTableMap::COL_LON)) {
            $modifiedColumns[':p' . $index++]  = 'lon';
        }
        if ($this->isColumnModified(RegionTableMap::COL_LAT)) {
            $modifiedColumns[':p' . $index++]  = 'lat';
        }

        $sql = sprintf(
            'INSERT INTO region (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'area':
                        $stmt->bindValue($identifier, $this->area, PDO::PARAM_INT);
                        break;
                    case 'telcode':
                        $stmt->bindValue($identifier, $this->telcode, PDO::PARAM_INT);
                        break;
                    case 'timezone':
                        $stmt->bindValue($identifier, $this->timezone, PDO::PARAM_STR);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'url':
                        $stmt->bindValue($identifier, $this->url, PDO::PARAM_STR);
                        break;
                    case 'count':
                        $stmt->bindValue($identifier, $this->count, PDO::PARAM_INT);
                        break;
                    case 'data':
                        if (is_resource($this->data)) {
                            rewind($this->data);
                        }
                        $stmt->bindValue($identifier, $this->data, PDO::PARAM_LOB);
                        break;
                    case 'lon':
                        $stmt->bindValue($identifier, $this->lon, PDO::PARAM_STR);
                        break;
                    case 'lat':
                        $stmt->bindValue($identifier, $this->lat, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = RegionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getArea();
                break;
            case 2:
                return $this->getTelcode();
                break;
            case 3:
                return $this->getTimezone();
                break;
            case 4:
                return $this->getName();
                break;
            case 5:
                return $this->getUrl();
                break;
            case 6:
                return $this->getCount();
                break;
            case 7:
                return $this->getData();
                break;
            case 8:
                return $this->getLon();
                break;
            case 9:
                return $this->getLat();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Region'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Region'][$this->hashCode()] = true;
        $keys = RegionTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getArea(),
            $keys[2] => $this->getTelcode(),
            $keys[3] => $this->getTimezone(),
            $keys[4] => $this->getName(),
            $keys[5] => $this->getUrl(),
            $keys[6] => $this->getCount(),
            $keys[7] => $this->getData(),
            $keys[8] => $this->getLon(),
            $keys[9] => $this->getLat(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collStats) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'stats';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'stats';
                        break;
                    default:
                        $key = 'Stats';
                }

                $result[$key] = $this->collStats->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAdvServerPricess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'advServerPricess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'adv_server_pricess';
                        break;
                    default:
                        $key = 'AdvServerPricess';
                }

                $result[$key] = $this->collAdvServerPricess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFirms) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'firms';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'firms';
                        break;
                    default:
                        $key = 'Firms';
                }

                $result[$key] = $this->collFirms->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\PropelModel\Region
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = RegionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\PropelModel\Region
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setArea($value);
                break;
            case 2:
                $this->setTelcode($value);
                break;
            case 3:
                $this->setTimezone($value);
                break;
            case 4:
                $this->setName($value);
                break;
            case 5:
                $this->setUrl($value);
                break;
            case 6:
                $this->setCount($value);
                break;
            case 7:
                $this->setData($value);
                break;
            case 8:
                $this->setLon($value);
                break;
            case 9:
                $this->setLat($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = RegionTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setArea($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTelcode($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setTimezone($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setName($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setUrl($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setCount($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setData($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setLon($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setLat($arr[$keys[9]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\PropelModel\Region The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(RegionTableMap::DATABASE_NAME);

        if ($this->isColumnModified(RegionTableMap::COL_ID)) {
            $criteria->add(RegionTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(RegionTableMap::COL_AREA)) {
            $criteria->add(RegionTableMap::COL_AREA, $this->area);
        }
        if ($this->isColumnModified(RegionTableMap::COL_TELCODE)) {
            $criteria->add(RegionTableMap::COL_TELCODE, $this->telcode);
        }
        if ($this->isColumnModified(RegionTableMap::COL_TIMEZONE)) {
            $criteria->add(RegionTableMap::COL_TIMEZONE, $this->timezone);
        }
        if ($this->isColumnModified(RegionTableMap::COL_NAME)) {
            $criteria->add(RegionTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(RegionTableMap::COL_URL)) {
            $criteria->add(RegionTableMap::COL_URL, $this->url);
        }
        if ($this->isColumnModified(RegionTableMap::COL_COUNT)) {
            $criteria->add(RegionTableMap::COL_COUNT, $this->count);
        }
        if ($this->isColumnModified(RegionTableMap::COL_DATA)) {
            $criteria->add(RegionTableMap::COL_DATA, $this->data);
        }
        if ($this->isColumnModified(RegionTableMap::COL_LON)) {
            $criteria->add(RegionTableMap::COL_LON, $this->lon);
        }
        if ($this->isColumnModified(RegionTableMap::COL_LAT)) {
            $criteria->add(RegionTableMap::COL_LAT, $this->lat);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildRegionQuery::create();
        $criteria->add(RegionTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \PropelModel\Region (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setArea($this->getArea());
        $copyObj->setTelcode($this->getTelcode());
        $copyObj->setTimezone($this->getTimezone());
        $copyObj->setName($this->getName());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setCount($this->getCount());
        $copyObj->setData($this->getData());
        $copyObj->setLon($this->getLon());
        $copyObj->setLat($this->getLat());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getStats() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStat($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAdvServerPricess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAdvServerPrices($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFirms() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFirm($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \PropelModel\Region Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Stat' == $relationName) {
            return $this->initStats();
        }
        if ('AdvServerPrices' == $relationName) {
            return $this->initAdvServerPricess();
        }
        if ('Firm' == $relationName) {
            return $this->initFirms();
        }
    }

    /**
     * Clears out the collStats collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addStats()
     */
    public function clearStats()
    {
        $this->collStats = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collStats collection loaded partially.
     */
    public function resetPartialStats($v = true)
    {
        $this->collStatsPartial = $v;
    }

    /**
     * Initializes the collStats collection.
     *
     * By default this just sets the collStats collection to an empty array (like clearcollStats());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStats($overrideExisting = true)
    {
        if (null !== $this->collStats && !$overrideExisting) {
            return;
        }
        $this->collStats = new ObjectCollection();
        $this->collStats->setModel('\PropelModel\Stat');
    }

    /**
     * Gets an array of ChildStat objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRegion is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildStat[] List of ChildStat objects
     * @throws PropelException
     */
    public function getStats(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collStatsPartial && !$this->isNew();
        if (null === $this->collStats || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStats) {
                // return empty collection
                $this->initStats();
            } else {
                $collStats = ChildStatQuery::create(null, $criteria)
                    ->filterByRegion($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collStatsPartial && count($collStats)) {
                        $this->initStats(false);

                        foreach ($collStats as $obj) {
                            if (false == $this->collStats->contains($obj)) {
                                $this->collStats->append($obj);
                            }
                        }

                        $this->collStatsPartial = true;
                    }

                    return $collStats;
                }

                if ($partial && $this->collStats) {
                    foreach ($this->collStats as $obj) {
                        if ($obj->isNew()) {
                            $collStats[] = $obj;
                        }
                    }
                }

                $this->collStats = $collStats;
                $this->collStatsPartial = false;
            }
        }

        return $this->collStats;
    }

    /**
     * Sets a collection of ChildStat objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $stats A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRegion The current object (for fluent API support)
     */
    public function setStats(Collection $stats, ConnectionInterface $con = null)
    {
        /** @var ChildStat[] $statsToDelete */
        $statsToDelete = $this->getStats(new Criteria(), $con)->diff($stats);


        $this->statsScheduledForDeletion = $statsToDelete;

        foreach ($statsToDelete as $statRemoved) {
            $statRemoved->setRegion(null);
        }

        $this->collStats = null;
        foreach ($stats as $stat) {
            $this->addStat($stat);
        }

        $this->collStats = $stats;
        $this->collStatsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Stat objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Stat objects.
     * @throws PropelException
     */
    public function countStats(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collStatsPartial && !$this->isNew();
        if (null === $this->collStats || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStats) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStats());
            }

            $query = ChildStatQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRegion($this)
                ->count($con);
        }

        return count($this->collStats);
    }

    /**
     * Method called to associate a ChildStat object to this object
     * through the ChildStat foreign key attribute.
     *
     * @param  ChildStat $l ChildStat
     * @return $this|\PropelModel\Region The current object (for fluent API support)
     */
    public function addStat(ChildStat $l)
    {
        if ($this->collStats === null) {
            $this->initStats();
            $this->collStatsPartial = true;
        }

        if (!$this->collStats->contains($l)) {
            $this->doAddStat($l);
        }

        return $this;
    }

    /**
     * @param ChildStat $stat The ChildStat object to add.
     */
    protected function doAddStat(ChildStat $stat)
    {
        $this->collStats[]= $stat;
        $stat->setRegion($this);
    }

    /**
     * @param  ChildStat $stat The ChildStat object to remove.
     * @return $this|ChildRegion The current object (for fluent API support)
     */
    public function removeStat(ChildStat $stat)
    {
        if ($this->getStats()->contains($stat)) {
            $pos = $this->collStats->search($stat);
            $this->collStats->remove($pos);
            if (null === $this->statsScheduledForDeletion) {
                $this->statsScheduledForDeletion = clone $this->collStats;
                $this->statsScheduledForDeletion->clear();
            }
            $this->statsScheduledForDeletion[]= clone $stat;
            $stat->setRegion(null);
        }

        return $this;
    }

    /**
     * Clears out the collAdvServerPricess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAdvServerPricess()
     */
    public function clearAdvServerPricess()
    {
        $this->collAdvServerPricess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAdvServerPricess collection loaded partially.
     */
    public function resetPartialAdvServerPricess($v = true)
    {
        $this->collAdvServerPricessPartial = $v;
    }

    /**
     * Initializes the collAdvServerPricess collection.
     *
     * By default this just sets the collAdvServerPricess collection to an empty array (like clearcollAdvServerPricess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAdvServerPricess($overrideExisting = true)
    {
        if (null !== $this->collAdvServerPricess && !$overrideExisting) {
            return;
        }
        $this->collAdvServerPricess = new ObjectCollection();
        $this->collAdvServerPricess->setModel('\PropelModel\AdvServerPrices');
    }

    /**
     * Gets an array of ChildAdvServerPrices objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRegion is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAdvServerPrices[] List of ChildAdvServerPrices objects
     * @throws PropelException
     */
    public function getAdvServerPricess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAdvServerPricessPartial && !$this->isNew();
        if (null === $this->collAdvServerPricess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAdvServerPricess) {
                // return empty collection
                $this->initAdvServerPricess();
            } else {
                $collAdvServerPricess = ChildAdvServerPricesQuery::create(null, $criteria)
                    ->filterByRegion($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAdvServerPricessPartial && count($collAdvServerPricess)) {
                        $this->initAdvServerPricess(false);

                        foreach ($collAdvServerPricess as $obj) {
                            if (false == $this->collAdvServerPricess->contains($obj)) {
                                $this->collAdvServerPricess->append($obj);
                            }
                        }

                        $this->collAdvServerPricessPartial = true;
                    }

                    return $collAdvServerPricess;
                }

                if ($partial && $this->collAdvServerPricess) {
                    foreach ($this->collAdvServerPricess as $obj) {
                        if ($obj->isNew()) {
                            $collAdvServerPricess[] = $obj;
                        }
                    }
                }

                $this->collAdvServerPricess = $collAdvServerPricess;
                $this->collAdvServerPricessPartial = false;
            }
        }

        return $this->collAdvServerPricess;
    }

    /**
     * Sets a collection of ChildAdvServerPrices objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $advServerPricess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRegion The current object (for fluent API support)
     */
    public function setAdvServerPricess(Collection $advServerPricess, ConnectionInterface $con = null)
    {
        /** @var ChildAdvServerPrices[] $advServerPricessToDelete */
        $advServerPricessToDelete = $this->getAdvServerPricess(new Criteria(), $con)->diff($advServerPricess);


        $this->advServerPricessScheduledForDeletion = $advServerPricessToDelete;

        foreach ($advServerPricessToDelete as $advServerPricesRemoved) {
            $advServerPricesRemoved->setRegion(null);
        }

        $this->collAdvServerPricess = null;
        foreach ($advServerPricess as $advServerPrices) {
            $this->addAdvServerPrices($advServerPrices);
        }

        $this->collAdvServerPricess = $advServerPricess;
        $this->collAdvServerPricessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AdvServerPrices objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related AdvServerPrices objects.
     * @throws PropelException
     */
    public function countAdvServerPricess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAdvServerPricessPartial && !$this->isNew();
        if (null === $this->collAdvServerPricess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAdvServerPricess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAdvServerPricess());
            }

            $query = ChildAdvServerPricesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRegion($this)
                ->count($con);
        }

        return count($this->collAdvServerPricess);
    }

    /**
     * Method called to associate a ChildAdvServerPrices object to this object
     * through the ChildAdvServerPrices foreign key attribute.
     *
     * @param  ChildAdvServerPrices $l ChildAdvServerPrices
     * @return $this|\PropelModel\Region The current object (for fluent API support)
     */
    public function addAdvServerPrices(ChildAdvServerPrices $l)
    {
        if ($this->collAdvServerPricess === null) {
            $this->initAdvServerPricess();
            $this->collAdvServerPricessPartial = true;
        }

        if (!$this->collAdvServerPricess->contains($l)) {
            $this->doAddAdvServerPrices($l);
        }

        return $this;
    }

    /**
     * @param ChildAdvServerPrices $advServerPrices The ChildAdvServerPrices object to add.
     */
    protected function doAddAdvServerPrices(ChildAdvServerPrices $advServerPrices)
    {
        $this->collAdvServerPricess[]= $advServerPrices;
        $advServerPrices->setRegion($this);
    }

    /**
     * @param  ChildAdvServerPrices $advServerPrices The ChildAdvServerPrices object to remove.
     * @return $this|ChildRegion The current object (for fluent API support)
     */
    public function removeAdvServerPrices(ChildAdvServerPrices $advServerPrices)
    {
        if ($this->getAdvServerPricess()->contains($advServerPrices)) {
            $pos = $this->collAdvServerPricess->search($advServerPrices);
            $this->collAdvServerPricess->remove($pos);
            if (null === $this->advServerPricessScheduledForDeletion) {
                $this->advServerPricessScheduledForDeletion = clone $this->collAdvServerPricess;
                $this->advServerPricessScheduledForDeletion->clear();
            }
            $this->advServerPricessScheduledForDeletion[]= $advServerPrices;
            $advServerPrices->setRegion(null);
        }

        return $this;
    }

    /**
     * Clears out the collFirms collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFirms()
     */
    public function clearFirms()
    {
        $this->collFirms = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFirms collection loaded partially.
     */
    public function resetPartialFirms($v = true)
    {
        $this->collFirmsPartial = $v;
    }

    /**
     * Initializes the collFirms collection.
     *
     * By default this just sets the collFirms collection to an empty array (like clearcollFirms());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFirms($overrideExisting = true)
    {
        if (null !== $this->collFirms && !$overrideExisting) {
            return;
        }
        $this->collFirms = new ObjectCollection();
        $this->collFirms->setModel('\PropelModel\Firm');
    }

    /**
     * Gets an array of ChildFirm objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRegion is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFirm[] List of ChildFirm objects
     * @throws PropelException
     */
    public function getFirms(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmsPartial && !$this->isNew();
        if (null === $this->collFirms || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFirms) {
                // return empty collection
                $this->initFirms();
            } else {
                $collFirms = ChildFirmQuery::create(null, $criteria)
                    ->filterByRegion($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFirmsPartial && count($collFirms)) {
                        $this->initFirms(false);

                        foreach ($collFirms as $obj) {
                            if (false == $this->collFirms->contains($obj)) {
                                $this->collFirms->append($obj);
                            }
                        }

                        $this->collFirmsPartial = true;
                    }

                    return $collFirms;
                }

                if ($partial && $this->collFirms) {
                    foreach ($this->collFirms as $obj) {
                        if ($obj->isNew()) {
                            $collFirms[] = $obj;
                        }
                    }
                }

                $this->collFirms = $collFirms;
                $this->collFirmsPartial = false;
            }
        }

        return $this->collFirms;
    }

    /**
     * Sets a collection of ChildFirm objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $firms A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRegion The current object (for fluent API support)
     */
    public function setFirms(Collection $firms, ConnectionInterface $con = null)
    {
        /** @var ChildFirm[] $firmsToDelete */
        $firmsToDelete = $this->getFirms(new Criteria(), $con)->diff($firms);


        $this->firmsScheduledForDeletion = $firmsToDelete;

        foreach ($firmsToDelete as $firmRemoved) {
            $firmRemoved->setRegion(null);
        }

        $this->collFirms = null;
        foreach ($firms as $firm) {
            $this->addFirm($firm);
        }

        $this->collFirms = $firms;
        $this->collFirmsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Firm objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Firm objects.
     * @throws PropelException
     */
    public function countFirms(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmsPartial && !$this->isNew();
        if (null === $this->collFirms || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFirms) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFirms());
            }

            $query = ChildFirmQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRegion($this)
                ->count($con);
        }

        return count($this->collFirms);
    }

    /**
     * Method called to associate a ChildFirm object to this object
     * through the ChildFirm foreign key attribute.
     *
     * @param  ChildFirm $l ChildFirm
     * @return $this|\PropelModel\Region The current object (for fluent API support)
     */
    public function addFirm(ChildFirm $l)
    {
        if ($this->collFirms === null) {
            $this->initFirms();
            $this->collFirmsPartial = true;
        }

        if (!$this->collFirms->contains($l)) {
            $this->doAddFirm($l);
        }

        return $this;
    }

    /**
     * @param ChildFirm $firm The ChildFirm object to add.
     */
    protected function doAddFirm(ChildFirm $firm)
    {
        $this->collFirms[]= $firm;
        $firm->setRegion($this);
    }

    /**
     * @param  ChildFirm $firm The ChildFirm object to remove.
     * @return $this|ChildRegion The current object (for fluent API support)
     */
    public function removeFirm(ChildFirm $firm)
    {
        if ($this->getFirms()->contains($firm)) {
            $pos = $this->collFirms->search($firm);
            $this->collFirms->remove($pos);
            if (null === $this->firmsScheduledForDeletion) {
                $this->firmsScheduledForDeletion = clone $this->collFirms;
                $this->firmsScheduledForDeletion->clear();
            }
            $this->firmsScheduledForDeletion[]= clone $firm;
            $firm->setRegion(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Region is new, it will return
     * an empty collection; or if this Region has previously
     * been saved, it will retrieve related Firms from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Region.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFirm[] List of ChildFirm objects
     */
    public function getFirmsJoinDistrict(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFirmQuery::create(null, $criteria);
        $query->joinWith('District', $joinBehavior);

        return $this->getFirms($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Region is new, it will return
     * an empty collection; or if this Region has previously
     * been saved, it will retrieve related Firms from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Region.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFirm[] List of ChildFirm objects
     */
    public function getFirmsJoinLegalInfoRelatedById(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFirmQuery::create(null, $criteria);
        $query->joinWith('LegalInfoRelatedById', $joinBehavior);

        return $this->getFirms($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->area = null;
        $this->telcode = null;
        $this->timezone = null;
        $this->name = null;
        $this->url = null;
        $this->count = null;
        $this->data = null;
        $this->lon = null;
        $this->lat = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collStats) {
                foreach ($this->collStats as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAdvServerPricess) {
                foreach ($this->collAdvServerPricess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFirms) {
                foreach ($this->collFirms as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collStats = null;
        $this->collAdvServerPricess = null;
        $this->collFirms = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(RegionTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
