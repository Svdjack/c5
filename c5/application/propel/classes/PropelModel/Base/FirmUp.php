<?php

namespace PropelModel\Base;

use \Exception;
use \PDO;
use PropelModel\Firm as ChildFirm;
use PropelModel\FirmQuery as ChildFirmQuery;
use PropelModel\FirmUpQuery as ChildFirmUpQuery;
use PropelModel\Map\FirmUpTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'firm_up' table.
 *
 *
 *
* @package    propel.generator.PropelModel.Base
*/
abstract class FirmUp implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\PropelModel\\Map\\FirmUpTableMap';


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
     * The value for the firm_id field.
     * @var        int
     */
    protected $firm_id;

    /**
     * The value for the time_start field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $time_start;

    /**
     * The value for the time_end field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $time_end;

    /**
     * The value for the cash field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $cash;

    /**
     * The value for the type field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $type;

    /**
     * The value for the email field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $email;

    /**
     * The value for the status field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $status;

    /**
     * The value for the spam_type field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $spam_type;

    /**
     * The value for the last_mail_send field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $last_mail_send;

    /**
     * The value for the last_days field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $last_days;

    /**
     * @var        ChildFirm
     */
    protected $aFirm;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->time_start = 0;
        $this->time_end = 0;
        $this->cash = 0;
        $this->type = '';
        $this->email = '';
        $this->status = 0;
        $this->spam_type = 0;
        $this->last_mail_send = 0;
        $this->last_days = 0;
    }

    /**
     * Initializes internal state of PropelModel\Base\FirmUp object.
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
     * Compares this with another <code>FirmUp</code> instance.  If
     * <code>obj</code> is an instance of <code>FirmUp</code>, delegates to
     * <code>equals(FirmUp)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|FirmUp The current object, for fluid interface
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
     * Get the [firm_id] column value.
     *
     * @return int
     */
    public function getFirmId()
    {
        return $this->firm_id;
    }

    /**
     * Get the [time_start] column value.
     *
     * @return int
     */
    public function getTimeStart()
    {
        return $this->time_start;
    }

    /**
     * Get the [time_end] column value.
     *
     * @return int
     */
    public function getTimeEnd()
    {
        return $this->time_end;
    }

    /**
     * Get the [cash] column value.
     *
     * @return int
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * Get the [type] column value.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [status] column value.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the [spam_type] column value.
     *
     * @return int
     */
    public function getSpamType()
    {
        return $this->spam_type;
    }

    /**
     * Get the [last_mail_send] column value.
     *
     * @return int
     */
    public function getLastMailSend()
    {
        return $this->last_mail_send;
    }

    /**
     * Get the [last_days] column value.
     *
     * @return int
     */
    public function getLastDays()
    {
        return $this->last_days;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\FirmUp The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[FirmUpTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [firm_id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\FirmUp The current object (for fluent API support)
     */
    public function setFirmId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->firm_id !== $v) {
            $this->firm_id = $v;
            $this->modifiedColumns[FirmUpTableMap::COL_FIRM_ID] = true;
        }

        if ($this->aFirm !== null && $this->aFirm->getId() !== $v) {
            $this->aFirm = null;
        }

        return $this;
    } // setFirmId()

    /**
     * Set the value of [time_start] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\FirmUp The current object (for fluent API support)
     */
    public function setTimeStart($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->time_start !== $v) {
            $this->time_start = $v;
            $this->modifiedColumns[FirmUpTableMap::COL_TIME_START] = true;
        }

        return $this;
    } // setTimeStart()

    /**
     * Set the value of [time_end] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\FirmUp The current object (for fluent API support)
     */
    public function setTimeEnd($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->time_end !== $v) {
            $this->time_end = $v;
            $this->modifiedColumns[FirmUpTableMap::COL_TIME_END] = true;
        }

        return $this;
    } // setTimeEnd()

    /**
     * Set the value of [cash] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\FirmUp The current object (for fluent API support)
     */
    public function setCash($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cash !== $v) {
            $this->cash = $v;
            $this->modifiedColumns[FirmUpTableMap::COL_CASH] = true;
        }

        return $this;
    } // setCash()

    /**
     * Set the value of [type] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\FirmUp The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[FirmUpTableMap::COL_TYPE] = true;
        }

        return $this;
    } // setType()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\FirmUp The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[FirmUpTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [status] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\FirmUp The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[FirmUpTableMap::COL_STATUS] = true;
        }

        return $this;
    } // setStatus()

    /**
     * Set the value of [spam_type] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\FirmUp The current object (for fluent API support)
     */
    public function setSpamType($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->spam_type !== $v) {
            $this->spam_type = $v;
            $this->modifiedColumns[FirmUpTableMap::COL_SPAM_TYPE] = true;
        }

        return $this;
    } // setSpamType()

    /**
     * Set the value of [last_mail_send] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\FirmUp The current object (for fluent API support)
     */
    public function setLastMailSend($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->last_mail_send !== $v) {
            $this->last_mail_send = $v;
            $this->modifiedColumns[FirmUpTableMap::COL_LAST_MAIL_SEND] = true;
        }

        return $this;
    } // setLastMailSend()

    /**
     * Set the value of [last_days] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\FirmUp The current object (for fluent API support)
     */
    public function setLastDays($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->last_days !== $v) {
            $this->last_days = $v;
            $this->modifiedColumns[FirmUpTableMap::COL_LAST_DAYS] = true;
        }

        return $this;
    } // setLastDays()

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
            if ($this->time_start !== 0) {
                return false;
            }

            if ($this->time_end !== 0) {
                return false;
            }

            if ($this->cash !== 0) {
                return false;
            }

            if ($this->type !== '') {
                return false;
            }

            if ($this->email !== '') {
                return false;
            }

            if ($this->status !== 0) {
                return false;
            }

            if ($this->spam_type !== 0) {
                return false;
            }

            if ($this->last_mail_send !== 0) {
                return false;
            }

            if ($this->last_days !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : FirmUpTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : FirmUpTableMap::translateFieldName('FirmId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->firm_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : FirmUpTableMap::translateFieldName('TimeStart', TableMap::TYPE_PHPNAME, $indexType)];
            $this->time_start = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : FirmUpTableMap::translateFieldName('TimeEnd', TableMap::TYPE_PHPNAME, $indexType)];
            $this->time_end = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : FirmUpTableMap::translateFieldName('Cash', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cash = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : FirmUpTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : FirmUpTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : FirmUpTableMap::translateFieldName('Status', TableMap::TYPE_PHPNAME, $indexType)];
            $this->status = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : FirmUpTableMap::translateFieldName('SpamType', TableMap::TYPE_PHPNAME, $indexType)];
            $this->spam_type = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : FirmUpTableMap::translateFieldName('LastMailSend', TableMap::TYPE_PHPNAME, $indexType)];
            $this->last_mail_send = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : FirmUpTableMap::translateFieldName('LastDays', TableMap::TYPE_PHPNAME, $indexType)];
            $this->last_days = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 11; // 11 = FirmUpTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\PropelModel\\FirmUp'), 0, $e);
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
        if ($this->aFirm !== null && $this->firm_id !== $this->aFirm->getId()) {
            $this->aFirm = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(FirmUpTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildFirmUpQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aFirm = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see FirmUp::setDeleted()
     * @see FirmUp::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(FirmUpTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildFirmUpQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(FirmUpTableMap::DATABASE_NAME);
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
                FirmUpTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aFirm !== null) {
                if ($this->aFirm->isModified() || $this->aFirm->isNew()) {
                    $affectedRows += $this->aFirm->save($con);
                }
                $this->setFirm($this->aFirm);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
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

        $this->modifiedColumns[FirmUpTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . FirmUpTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(FirmUpTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_FIRM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'firm_id';
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_TIME_START)) {
            $modifiedColumns[':p' . $index++]  = 'time_start';
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_TIME_END)) {
            $modifiedColumns[':p' . $index++]  = 'time_end';
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_CASH)) {
            $modifiedColumns[':p' . $index++]  = 'cash';
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'type';
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_STATUS)) {
            $modifiedColumns[':p' . $index++]  = 'status';
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_SPAM_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'spam_type';
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_LAST_MAIL_SEND)) {
            $modifiedColumns[':p' . $index++]  = 'last_mail_send';
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_LAST_DAYS)) {
            $modifiedColumns[':p' . $index++]  = 'last_days';
        }

        $sql = sprintf(
            'INSERT INTO firm_up (%s) VALUES (%s)',
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
                    case 'firm_id':
                        $stmt->bindValue($identifier, $this->firm_id, PDO::PARAM_INT);
                        break;
                    case 'time_start':
                        $stmt->bindValue($identifier, $this->time_start, PDO::PARAM_INT);
                        break;
                    case 'time_end':
                        $stmt->bindValue($identifier, $this->time_end, PDO::PARAM_INT);
                        break;
                    case 'cash':
                        $stmt->bindValue($identifier, $this->cash, PDO::PARAM_INT);
                        break;
                    case 'type':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_STR);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'status':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case 'spam_type':
                        $stmt->bindValue($identifier, $this->spam_type, PDO::PARAM_INT);
                        break;
                    case 'last_mail_send':
                        $stmt->bindValue($identifier, $this->last_mail_send, PDO::PARAM_INT);
                        break;
                    case 'last_days':
                        $stmt->bindValue($identifier, $this->last_days, PDO::PARAM_INT);
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
        $pos = FirmUpTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getFirmId();
                break;
            case 2:
                return $this->getTimeStart();
                break;
            case 3:
                return $this->getTimeEnd();
                break;
            case 4:
                return $this->getCash();
                break;
            case 5:
                return $this->getType();
                break;
            case 6:
                return $this->getEmail();
                break;
            case 7:
                return $this->getStatus();
                break;
            case 8:
                return $this->getSpamType();
                break;
            case 9:
                return $this->getLastMailSend();
                break;
            case 10:
                return $this->getLastDays();
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

        if (isset($alreadyDumpedObjects['FirmUp'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['FirmUp'][$this->hashCode()] = true;
        $keys = FirmUpTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getFirmId(),
            $keys[2] => $this->getTimeStart(),
            $keys[3] => $this->getTimeEnd(),
            $keys[4] => $this->getCash(),
            $keys[5] => $this->getType(),
            $keys[6] => $this->getEmail(),
            $keys[7] => $this->getStatus(),
            $keys[8] => $this->getSpamType(),
            $keys[9] => $this->getLastMailSend(),
            $keys[10] => $this->getLastDays(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aFirm) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'firm';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'firm';
                        break;
                    default:
                        $key = 'Firm';
                }

                $result[$key] = $this->aFirm->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\PropelModel\FirmUp
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = FirmUpTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\PropelModel\FirmUp
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setFirmId($value);
                break;
            case 2:
                $this->setTimeStart($value);
                break;
            case 3:
                $this->setTimeEnd($value);
                break;
            case 4:
                $this->setCash($value);
                break;
            case 5:
                $this->setType($value);
                break;
            case 6:
                $this->setEmail($value);
                break;
            case 7:
                $this->setStatus($value);
                break;
            case 8:
                $this->setSpamType($value);
                break;
            case 9:
                $this->setLastMailSend($value);
                break;
            case 10:
                $this->setLastDays($value);
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
        $keys = FirmUpTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setFirmId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTimeStart($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setTimeEnd($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setCash($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setType($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setEmail($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setStatus($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setSpamType($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setLastMailSend($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setLastDays($arr[$keys[10]]);
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
     * @return $this|\PropelModel\FirmUp The current object, for fluid interface
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
        $criteria = new Criteria(FirmUpTableMap::DATABASE_NAME);

        if ($this->isColumnModified(FirmUpTableMap::COL_ID)) {
            $criteria->add(FirmUpTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_FIRM_ID)) {
            $criteria->add(FirmUpTableMap::COL_FIRM_ID, $this->firm_id);
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_TIME_START)) {
            $criteria->add(FirmUpTableMap::COL_TIME_START, $this->time_start);
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_TIME_END)) {
            $criteria->add(FirmUpTableMap::COL_TIME_END, $this->time_end);
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_CASH)) {
            $criteria->add(FirmUpTableMap::COL_CASH, $this->cash);
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_TYPE)) {
            $criteria->add(FirmUpTableMap::COL_TYPE, $this->type);
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_EMAIL)) {
            $criteria->add(FirmUpTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_STATUS)) {
            $criteria->add(FirmUpTableMap::COL_STATUS, $this->status);
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_SPAM_TYPE)) {
            $criteria->add(FirmUpTableMap::COL_SPAM_TYPE, $this->spam_type);
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_LAST_MAIL_SEND)) {
            $criteria->add(FirmUpTableMap::COL_LAST_MAIL_SEND, $this->last_mail_send);
        }
        if ($this->isColumnModified(FirmUpTableMap::COL_LAST_DAYS)) {
            $criteria->add(FirmUpTableMap::COL_LAST_DAYS, $this->last_days);
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
        $criteria = ChildFirmUpQuery::create();
        $criteria->add(FirmUpTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \PropelModel\FirmUp (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setFirmId($this->getFirmId());
        $copyObj->setTimeStart($this->getTimeStart());
        $copyObj->setTimeEnd($this->getTimeEnd());
        $copyObj->setCash($this->getCash());
        $copyObj->setType($this->getType());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setSpamType($this->getSpamType());
        $copyObj->setLastMailSend($this->getLastMailSend());
        $copyObj->setLastDays($this->getLastDays());
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
     * @return \PropelModel\FirmUp Clone of current object.
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
     * Declares an association between this object and a ChildFirm object.
     *
     * @param  ChildFirm $v
     * @return $this|\PropelModel\FirmUp The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFirm(ChildFirm $v = null)
    {
        if ($v === null) {
            $this->setFirmId(NULL);
        } else {
            $this->setFirmId($v->getId());
        }

        $this->aFirm = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildFirm object, it will not be re-added.
        if ($v !== null) {
            $v->addFirmUp($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildFirm object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildFirm The associated ChildFirm object.
     * @throws PropelException
     */
    public function getFirm(ConnectionInterface $con = null)
    {
        if ($this->aFirm === null && ($this->firm_id !== null)) {
            $this->aFirm = ChildFirmQuery::create()->findPk($this->firm_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFirm->addFirmUps($this);
             */
        }

        return $this->aFirm;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aFirm) {
            $this->aFirm->removeFirmUp($this);
        }
        $this->id = null;
        $this->firm_id = null;
        $this->time_start = null;
        $this->time_end = null;
        $this->cash = null;
        $this->type = null;
        $this->email = null;
        $this->status = null;
        $this->spam_type = null;
        $this->last_mail_send = null;
        $this->last_days = null;
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
        } // if ($deep)

        $this->aFirm = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(FirmUpTableMap::DEFAULT_STRING_FORMAT);
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
