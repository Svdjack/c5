<?php

namespace PropelModel\Base;

use \Exception;
use \PDO;
use PropelModel\Firm as ChildFirm;
use PropelModel\FirmGroup as ChildFirmGroup;
use PropelModel\FirmGroupQuery as ChildFirmGroupQuery;
use PropelModel\FirmQuery as ChildFirmQuery;
use PropelModel\Group as ChildGroup;
use PropelModel\GroupQuery as ChildGroupQuery;
use PropelModel\Map\GroupTableMap;
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
 * Base class that represents a row from the 'groups' table.
 *
 *
 *
* @package    propel.generator.PropelModel.Base
*/
abstract class Group implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\PropelModel\\Map\\GroupTableMap';


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
     * The value for the parent field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $parent;

    /**
     * The value for the level field.
     * Note: this column has a database default value of: 1
     * @var        int
     */
    protected $level;

    /**
     * The value for the name field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $name;

    /**
     * The value for the original field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $original;

    /**
     * The value for the url field.
     * @var        string
     */
    protected $url;

    /**
     * The value for the worktime field.
     * @var        string
     */
    protected $worktime;

    /**
     * The value for the live field.
     * @var        string
     */
    protected $live;

    /**
     * @var        ObjectCollection|ChildFirmGroup[] Collection to store aggregation of ChildFirmGroup objects.
     */
    protected $collFirmGroups;
    protected $collFirmGroupsPartial;

    /**
     * @var        ObjectCollection|ChildFirm[] Cross Collection to store aggregation of ChildFirm objects.
     */
    protected $collFirms;

    /**
     * @var bool
     */
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
     * @var ObjectCollection|ChildFirm[]
     */
    protected $firmsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFirmGroup[]
     */
    protected $firmGroupsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->parent = 0;
        $this->level = 1;
        $this->name = '';
        $this->original = '';
    }

    /**
     * Initializes internal state of PropelModel\Base\Group object.
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
     * Compares this with another <code>Group</code> instance.  If
     * <code>obj</code> is an instance of <code>Group</code>, delegates to
     * <code>equals(Group)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Group The current object, for fluid interface
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
     * Get the [parent] column value.
     *
     * @return int
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get the [level] column value.
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
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
     * Get the [original] column value.
     *
     * @return string
     */
    public function getOriginal()
    {
        return $this->original;
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
     * Get the [worktime] column value.
     *
     * @return string
     */
    public function getWorktime()
    {
        return $this->worktime;
    }

    /**
     * Get the [live] column value.
     *
     * @return string
     */
    public function getLive()
    {
        return $this->live;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Group The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[GroupTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [parent] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Group The current object (for fluent API support)
     */
    public function setParent($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->parent !== $v) {
            $this->parent = $v;
            $this->modifiedColumns[GroupTableMap::COL_PARENT] = true;
        }

        return $this;
    } // setParent()

    /**
     * Set the value of [level] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Group The current object (for fluent API support)
     */
    public function setLevel($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->level !== $v) {
            $this->level = $v;
            $this->modifiedColumns[GroupTableMap::COL_LEVEL] = true;
        }

        return $this;
    } // setLevel()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Group The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[GroupTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [original] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Group The current object (for fluent API support)
     */
    public function setOriginal($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->original !== $v) {
            $this->original = $v;
            $this->modifiedColumns[GroupTableMap::COL_ORIGINAL] = true;
        }

        return $this;
    } // setOriginal()

    /**
     * Set the value of [url] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Group The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->url !== $v) {
            $this->url = $v;
            $this->modifiedColumns[GroupTableMap::COL_URL] = true;
        }

        return $this;
    } // setUrl()

    /**
     * Set the value of [worktime] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Group The current object (for fluent API support)
     */
    public function setWorktime($v)
    {
        // Because BLOB columns are streams in PDO we have to assume that they are
        // always modified when a new value is passed in.  For example, the contents
        // of the stream itself may have changed externally.
        if (!is_resource($v) && $v !== null) {
            $this->worktime = fopen('php://memory', 'r+');
            fwrite($this->worktime, $v);
            rewind($this->worktime);
        } else { // it's already a stream
            $this->worktime = $v;
        }
        $this->modifiedColumns[GroupTableMap::COL_WORKTIME] = true;

        return $this;
    } // setWorktime()

    /**
     * Set the value of [live] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Group The current object (for fluent API support)
     */
    public function setLive($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->live !== $v) {
            $this->live = $v;
            $this->modifiedColumns[GroupTableMap::COL_LIVE] = true;
        }

        return $this;
    } // setLive()

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
            if ($this->parent !== 0) {
                return false;
            }

            if ($this->level !== 1) {
                return false;
            }

            if ($this->name !== '') {
                return false;
            }

            if ($this->original !== '') {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : GroupTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : GroupTableMap::translateFieldName('Parent', TableMap::TYPE_PHPNAME, $indexType)];
            $this->parent = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : GroupTableMap::translateFieldName('Level', TableMap::TYPE_PHPNAME, $indexType)];
            $this->level = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : GroupTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : GroupTableMap::translateFieldName('Original', TableMap::TYPE_PHPNAME, $indexType)];
            $this->original = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : GroupTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : GroupTableMap::translateFieldName('Worktime', TableMap::TYPE_PHPNAME, $indexType)];
            if (null !== $col) {
                $this->worktime = fopen('php://memory', 'r+');
                fwrite($this->worktime, $col);
                rewind($this->worktime);
            } else {
                $this->worktime = null;
            }

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : GroupTableMap::translateFieldName('Live', TableMap::TYPE_PHPNAME, $indexType)];
            $this->live = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 8; // 8 = GroupTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\PropelModel\\Group'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(GroupTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildGroupQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collFirmGroups = null;

            $this->collFirms = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Group::setDeleted()
     * @see Group::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildGroupQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(GroupTableMap::DATABASE_NAME);
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
                GroupTableMap::addInstanceToPool($this);
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
                // Rewind the worktime LOB column, since PDO does not rewind after inserting value.
                if ($this->worktime !== null && is_resource($this->worktime)) {
                    rewind($this->worktime);
                }

                $this->resetModified();
            }

            if ($this->firmsScheduledForDeletion !== null) {
                if (!$this->firmsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->firmsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \PropelModel\FirmGroupQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->firmsScheduledForDeletion = null;
                }

            }

            if ($this->collFirms) {
                foreach ($this->collFirms as $firm) {
                    if (!$firm->isDeleted() && ($firm->isNew() || $firm->isModified())) {
                        $firm->save($con);
                    }
                }
            }


            if ($this->firmGroupsScheduledForDeletion !== null) {
                if (!$this->firmGroupsScheduledForDeletion->isEmpty()) {
                    \PropelModel\FirmGroupQuery::create()
                        ->filterByPrimaryKeys($this->firmGroupsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->firmGroupsScheduledForDeletion = null;
                }
            }

            if ($this->collFirmGroups !== null) {
                foreach ($this->collFirmGroups as $referrerFK) {
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

        $this->modifiedColumns[GroupTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . GroupTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(GroupTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(GroupTableMap::COL_PARENT)) {
            $modifiedColumns[':p' . $index++]  = 'parent';
        }
        if ($this->isColumnModified(GroupTableMap::COL_LEVEL)) {
            $modifiedColumns[':p' . $index++]  = 'level';
        }
        if ($this->isColumnModified(GroupTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(GroupTableMap::COL_ORIGINAL)) {
            $modifiedColumns[':p' . $index++]  = 'original';
        }
        if ($this->isColumnModified(GroupTableMap::COL_URL)) {
            $modifiedColumns[':p' . $index++]  = 'url';
        }
        if ($this->isColumnModified(GroupTableMap::COL_WORKTIME)) {
            $modifiedColumns[':p' . $index++]  = 'worktime';
        }
        if ($this->isColumnModified(GroupTableMap::COL_LIVE)) {
            $modifiedColumns[':p' . $index++]  = 'live';
        }

        $sql = sprintf(
            'INSERT INTO groups (%s) VALUES (%s)',
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
                    case 'parent':
                        $stmt->bindValue($identifier, $this->parent, PDO::PARAM_INT);
                        break;
                    case 'level':
                        $stmt->bindValue($identifier, $this->level, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'original':
                        $stmt->bindValue($identifier, $this->original, PDO::PARAM_STR);
                        break;
                    case 'url':
                        $stmt->bindValue($identifier, $this->url, PDO::PARAM_STR);
                        break;
                    case 'worktime':
                        if (is_resource($this->worktime)) {
                            rewind($this->worktime);
                        }
                        $stmt->bindValue($identifier, $this->worktime, PDO::PARAM_LOB);
                        break;
                    case 'live':
                        $stmt->bindValue($identifier, $this->live, PDO::PARAM_STR);
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
        $pos = GroupTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getParent();
                break;
            case 2:
                return $this->getLevel();
                break;
            case 3:
                return $this->getName();
                break;
            case 4:
                return $this->getOriginal();
                break;
            case 5:
                return $this->getUrl();
                break;
            case 6:
                return $this->getWorktime();
                break;
            case 7:
                return $this->getLive();
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

        if (isset($alreadyDumpedObjects['Group'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Group'][$this->hashCode()] = true;
        $keys = GroupTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getParent(),
            $keys[2] => $this->getLevel(),
            $keys[3] => $this->getName(),
            $keys[4] => $this->getOriginal(),
            $keys[5] => $this->getUrl(),
            $keys[6] => $this->getWorktime(),
            $keys[7] => $this->getLive(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collFirmGroups) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'firmGroups';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'firm_groups';
                        break;
                    default:
                        $key = 'FirmGroups';
                }

                $result[$key] = $this->collFirmGroups->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\PropelModel\Group
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = GroupTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\PropelModel\Group
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setParent($value);
                break;
            case 2:
                $this->setLevel($value);
                break;
            case 3:
                $this->setName($value);
                break;
            case 4:
                $this->setOriginal($value);
                break;
            case 5:
                $this->setUrl($value);
                break;
            case 6:
                $this->setWorktime($value);
                break;
            case 7:
                $this->setLive($value);
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
        $keys = GroupTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setParent($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setLevel($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setName($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setOriginal($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setUrl($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setWorktime($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setLive($arr[$keys[7]]);
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
     * @return $this|\PropelModel\Group The current object, for fluid interface
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
        $criteria = new Criteria(GroupTableMap::DATABASE_NAME);

        if ($this->isColumnModified(GroupTableMap::COL_ID)) {
            $criteria->add(GroupTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(GroupTableMap::COL_PARENT)) {
            $criteria->add(GroupTableMap::COL_PARENT, $this->parent);
        }
        if ($this->isColumnModified(GroupTableMap::COL_LEVEL)) {
            $criteria->add(GroupTableMap::COL_LEVEL, $this->level);
        }
        if ($this->isColumnModified(GroupTableMap::COL_NAME)) {
            $criteria->add(GroupTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(GroupTableMap::COL_ORIGINAL)) {
            $criteria->add(GroupTableMap::COL_ORIGINAL, $this->original);
        }
        if ($this->isColumnModified(GroupTableMap::COL_URL)) {
            $criteria->add(GroupTableMap::COL_URL, $this->url);
        }
        if ($this->isColumnModified(GroupTableMap::COL_WORKTIME)) {
            $criteria->add(GroupTableMap::COL_WORKTIME, $this->worktime);
        }
        if ($this->isColumnModified(GroupTableMap::COL_LIVE)) {
            $criteria->add(GroupTableMap::COL_LIVE, $this->live);
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
        $criteria = ChildGroupQuery::create();
        $criteria->add(GroupTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \PropelModel\Group (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setParent($this->getParent());
        $copyObj->setLevel($this->getLevel());
        $copyObj->setName($this->getName());
        $copyObj->setOriginal($this->getOriginal());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setWorktime($this->getWorktime());
        $copyObj->setLive($this->getLive());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getFirmGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFirmGroup($relObj->copy($deepCopy));
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
     * @return \PropelModel\Group Clone of current object.
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
        if ('FirmGroup' == $relationName) {
            return $this->initFirmGroups();
        }
    }

    /**
     * Clears out the collFirmGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFirmGroups()
     */
    public function clearFirmGroups()
    {
        $this->collFirmGroups = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFirmGroups collection loaded partially.
     */
    public function resetPartialFirmGroups($v = true)
    {
        $this->collFirmGroupsPartial = $v;
    }

    /**
     * Initializes the collFirmGroups collection.
     *
     * By default this just sets the collFirmGroups collection to an empty array (like clearcollFirmGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFirmGroups($overrideExisting = true)
    {
        if (null !== $this->collFirmGroups && !$overrideExisting) {
            return;
        }
        $this->collFirmGroups = new ObjectCollection();
        $this->collFirmGroups->setModel('\PropelModel\FirmGroup');
    }

    /**
     * Gets an array of ChildFirmGroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFirmGroup[] List of ChildFirmGroup objects
     * @throws PropelException
     */
    public function getFirmGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmGroupsPartial && !$this->isNew();
        if (null === $this->collFirmGroups || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFirmGroups) {
                // return empty collection
                $this->initFirmGroups();
            } else {
                $collFirmGroups = ChildFirmGroupQuery::create(null, $criteria)
                    ->filterByGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFirmGroupsPartial && count($collFirmGroups)) {
                        $this->initFirmGroups(false);

                        foreach ($collFirmGroups as $obj) {
                            if (false == $this->collFirmGroups->contains($obj)) {
                                $this->collFirmGroups->append($obj);
                            }
                        }

                        $this->collFirmGroupsPartial = true;
                    }

                    return $collFirmGroups;
                }

                if ($partial && $this->collFirmGroups) {
                    foreach ($this->collFirmGroups as $obj) {
                        if ($obj->isNew()) {
                            $collFirmGroups[] = $obj;
                        }
                    }
                }

                $this->collFirmGroups = $collFirmGroups;
                $this->collFirmGroupsPartial = false;
            }
        }

        return $this->collFirmGroups;
    }

    /**
     * Sets a collection of ChildFirmGroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $firmGroups A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function setFirmGroups(Collection $firmGroups, ConnectionInterface $con = null)
    {
        /** @var ChildFirmGroup[] $firmGroupsToDelete */
        $firmGroupsToDelete = $this->getFirmGroups(new Criteria(), $con)->diff($firmGroups);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->firmGroupsScheduledForDeletion = clone $firmGroupsToDelete;

        foreach ($firmGroupsToDelete as $firmGroupRemoved) {
            $firmGroupRemoved->setGroup(null);
        }

        $this->collFirmGroups = null;
        foreach ($firmGroups as $firmGroup) {
            $this->addFirmGroup($firmGroup);
        }

        $this->collFirmGroups = $firmGroups;
        $this->collFirmGroupsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related FirmGroup objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related FirmGroup objects.
     * @throws PropelException
     */
    public function countFirmGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmGroupsPartial && !$this->isNew();
        if (null === $this->collFirmGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFirmGroups) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFirmGroups());
            }

            $query = ChildFirmGroupQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByGroup($this)
                ->count($con);
        }

        return count($this->collFirmGroups);
    }

    /**
     * Method called to associate a ChildFirmGroup object to this object
     * through the ChildFirmGroup foreign key attribute.
     *
     * @param  ChildFirmGroup $l ChildFirmGroup
     * @return $this|\PropelModel\Group The current object (for fluent API support)
     */
    public function addFirmGroup(ChildFirmGroup $l)
    {
        if ($this->collFirmGroups === null) {
            $this->initFirmGroups();
            $this->collFirmGroupsPartial = true;
        }

        if (!$this->collFirmGroups->contains($l)) {
            $this->doAddFirmGroup($l);
        }

        return $this;
    }

    /**
     * @param ChildFirmGroup $firmGroup The ChildFirmGroup object to add.
     */
    protected function doAddFirmGroup(ChildFirmGroup $firmGroup)
    {
        $this->collFirmGroups[]= $firmGroup;
        $firmGroup->setGroup($this);
    }

    /**
     * @param  ChildFirmGroup $firmGroup The ChildFirmGroup object to remove.
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function removeFirmGroup(ChildFirmGroup $firmGroup)
    {
        if ($this->getFirmGroups()->contains($firmGroup)) {
            $pos = $this->collFirmGroups->search($firmGroup);
            $this->collFirmGroups->remove($pos);
            if (null === $this->firmGroupsScheduledForDeletion) {
                $this->firmGroupsScheduledForDeletion = clone $this->collFirmGroups;
                $this->firmGroupsScheduledForDeletion->clear();
            }
            $this->firmGroupsScheduledForDeletion[]= clone $firmGroup;
            $firmGroup->setGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Group is new, it will return
     * an empty collection; or if this Group has previously
     * been saved, it will retrieve related FirmGroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Group.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFirmGroup[] List of ChildFirmGroup objects
     */
    public function getFirmGroupsJoinFirm(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFirmGroupQuery::create(null, $criteria);
        $query->joinWith('Firm', $joinBehavior);

        return $this->getFirmGroups($query, $con);
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
     * Initializes the collFirms crossRef collection.
     *
     * By default this just sets the collFirms collection to an empty collection (like clearFirms());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initFirms()
    {
        $this->collFirms = new ObjectCollection();
        $this->collFirmsPartial = true;

        $this->collFirms->setModel('\PropelModel\Firm');
    }

    /**
     * Checks if the collFirms collection is loaded.
     *
     * @return bool
     */
    public function isFirmsLoaded()
    {
        return null !== $this->collFirms;
    }

    /**
     * Gets a collection of ChildFirm objects related by a many-to-many relationship
     * to the current object by way of the firm_group cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildFirm[] List of ChildFirm objects
     */
    public function getFirms(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmsPartial && !$this->isNew();
        if (null === $this->collFirms || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collFirms) {
                    $this->initFirms();
                }
            } else {

                $query = ChildFirmQuery::create(null, $criteria)
                    ->filterByGroup($this);
                $collFirms = $query->find($con);
                if (null !== $criteria) {
                    return $collFirms;
                }

                if ($partial && $this->collFirms) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collFirms as $obj) {
                        if (!$collFirms->contains($obj)) {
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
     * Sets a collection of Firm objects related by a many-to-many relationship
     * to the current object by way of the firm_group cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $firms A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildGroup The current object (for fluent API support)
     */
    public function setFirms(Collection $firms, ConnectionInterface $con = null)
    {
        $this->clearFirms();
        $currentFirms = $this->getFirms();

        $firmsScheduledForDeletion = $currentFirms->diff($firms);

        foreach ($firmsScheduledForDeletion as $toDelete) {
            $this->removeFirm($toDelete);
        }

        foreach ($firms as $firm) {
            if (!$currentFirms->contains($firm)) {
                $this->doAddFirm($firm);
            }
        }

        $this->collFirmsPartial = false;
        $this->collFirms = $firms;

        return $this;
    }

    /**
     * Gets the number of Firm objects related by a many-to-many relationship
     * to the current object by way of the firm_group cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Firm objects
     */
    public function countFirms(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmsPartial && !$this->isNew();
        if (null === $this->collFirms || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFirms) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getFirms());
                }

                $query = ChildFirmQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByGroup($this)
                    ->count($con);
            }
        } else {
            return count($this->collFirms);
        }
    }

    /**
     * Associate a ChildFirm to this object
     * through the firm_group cross reference table.
     *
     * @param ChildFirm $firm
     * @return ChildGroup The current object (for fluent API support)
     */
    public function addFirm(ChildFirm $firm)
    {
        if ($this->collFirms === null) {
            $this->initFirms();
        }

        if (!$this->getFirms()->contains($firm)) {
            // only add it if the **same** object is not already associated
            $this->collFirms->push($firm);
            $this->doAddFirm($firm);
        }

        return $this;
    }

    /**
     *
     * @param ChildFirm $firm
     */
    protected function doAddFirm(ChildFirm $firm)
    {
        $firmGroup = new ChildFirmGroup();

        $firmGroup->setFirm($firm);

        $firmGroup->setGroup($this);

        $this->addFirmGroup($firmGroup);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$firm->isGroupsLoaded()) {
            $firm->initGroups();
            $firm->getGroups()->push($this);
        } elseif (!$firm->getGroups()->contains($this)) {
            $firm->getGroups()->push($this);
        }

    }

    /**
     * Remove firm of this object
     * through the firm_group cross reference table.
     *
     * @param ChildFirm $firm
     * @return ChildGroup The current object (for fluent API support)
     */
    public function removeFirm(ChildFirm $firm)
    {
        if ($this->getFirms()->contains($firm)) { $firmGroup = new ChildFirmGroup();

            $firmGroup->setFirm($firm);
            if ($firm->isGroupsLoaded()) {
                //remove the back reference if available
                $firm->getGroups()->removeObject($this);
            }

            $firmGroup->setGroup($this);
            $this->removeFirmGroup(clone $firmGroup);
            $firmGroup->clear();

            $this->collFirms->remove($this->collFirms->search($firm));

            if (null === $this->firmsScheduledForDeletion) {
                $this->firmsScheduledForDeletion = clone $this->collFirms;
                $this->firmsScheduledForDeletion->clear();
            }

            $this->firmsScheduledForDeletion->push($firm);
        }


        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->parent = null;
        $this->level = null;
        $this->name = null;
        $this->original = null;
        $this->url = null;
        $this->worktime = null;
        $this->live = null;
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
            if ($this->collFirmGroups) {
                foreach ($this->collFirmGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFirms) {
                foreach ($this->collFirms as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collFirmGroups = null;
        $this->collFirms = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(GroupTableMap::DEFAULT_STRING_FORMAT);
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
