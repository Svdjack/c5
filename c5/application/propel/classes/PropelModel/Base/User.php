<?php

namespace PropelModel\Base;

use \Exception;
use \PDO;
use PropelModel\Firm as ChildFirm;
use PropelModel\FirmQuery as ChildFirmQuery;
use PropelModel\FirmUser as ChildFirmUser;
use PropelModel\FirmUserQuery as ChildFirmUserQuery;
use PropelModel\User as ChildUser;
use PropelModel\UserQuery as ChildUserQuery;
use PropelModel\Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Collection\ObjectCombinationCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'user' table.
 *
 *
 *
* @package    propel.generator.PropelModel.Base
*/
abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\PropelModel\\Map\\UserTableMap';


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
     * The value for the login field.
     * @var        string
     */
    protected $login;

    /**
     * The value for the hash field.
     * @var        string
     */
    protected $hash;

    /**
     * The value for the email field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $email;

    /**
     * The value for the name field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $name;

    /**
     * The value for the last_login field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $last_login;

    /**
     * The value for the secret field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $secret;

    /**
     * The value for the reg_date field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $reg_date;

    /**
     * The value for the role field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $role;

    /**
     * The value for the ip field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $ip;

    /**
     * @var        ObjectCollection|ChildFirmUser[] Collection to store aggregation of ChildFirmUser objects.
     */
    protected $collFirmUsers;
    protected $collFirmUsersPartial;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildFirm combinations.
     */
    protected $combinationCollFirmids;

    /**
     * @var bool
     */
    protected $combinationCollFirmidsPartial;

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
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildFirm combinations.
     */
    protected $combinationCollFirmidsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFirmUser[]
     */
    protected $firmUsersScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->email = '';
        $this->name = '';
        $this->last_login = 0;
        $this->secret = '';
        $this->reg_date = 0;
        $this->role = 0;
        $this->ip = '';
    }

    /**
     * Initializes internal state of PropelModel\Base\User object.
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
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|User The current object, for fluid interface
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
     * Get the [login] column value.
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Get the [hash] column value.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [last_login] column value.
     *
     * @return int
     */
    public function getLastLogin()
    {
        return $this->last_login;
    }

    /**
     * Get the [secret] column value.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Get the [reg_date] column value.
     *
     * @return int
     */
    public function getRegDate()
    {
        return $this->reg_date;
    }

    /**
     * Get the [role] column value.
     *
     * @return int
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get the [ip] column value.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [login] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\User The current object (for fluent API support)
     */
    public function setLogin($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->login !== $v) {
            $this->login = $v;
            $this->modifiedColumns[UserTableMap::COL_LOGIN] = true;
        }

        return $this;
    } // setLogin()

    /**
     * Set the value of [hash] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\User The current object (for fluent API support)
     */
    public function setHash($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->hash !== $v) {
            $this->hash = $v;
            $this->modifiedColumns[UserTableMap::COL_HASH] = true;
        }

        return $this;
    } // setHash()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\User The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[UserTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [last_login] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\User The current object (for fluent API support)
     */
    public function setLastLogin($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->last_login !== $v) {
            $this->last_login = $v;
            $this->modifiedColumns[UserTableMap::COL_LAST_LOGIN] = true;
        }

        return $this;
    } // setLastLogin()

    /**
     * Set the value of [secret] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\User The current object (for fluent API support)
     */
    public function setSecret($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->secret !== $v) {
            $this->secret = $v;
            $this->modifiedColumns[UserTableMap::COL_SECRET] = true;
        }

        return $this;
    } // setSecret()

    /**
     * Set the value of [reg_date] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\User The current object (for fluent API support)
     */
    public function setRegDate($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->reg_date !== $v) {
            $this->reg_date = $v;
            $this->modifiedColumns[UserTableMap::COL_REG_DATE] = true;
        }

        return $this;
    } // setRegDate()

    /**
     * Set the value of [role] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\User The current object (for fluent API support)
     */
    public function setRole($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->role !== $v) {
            $this->role = $v;
            $this->modifiedColumns[UserTableMap::COL_ROLE] = true;
        }

        return $this;
    } // setRole()

    /**
     * Set the value of [ip] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\User The current object (for fluent API support)
     */
    public function setIp($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->ip !== $v) {
            $this->ip = $v;
            $this->modifiedColumns[UserTableMap::COL_IP] = true;
        }

        return $this;
    } // setIp()

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
            if ($this->email !== '') {
                return false;
            }

            if ($this->name !== '') {
                return false;
            }

            if ($this->last_login !== 0) {
                return false;
            }

            if ($this->secret !== '') {
                return false;
            }

            if ($this->reg_date !== 0) {
                return false;
            }

            if ($this->role !== 0) {
                return false;
            }

            if ($this->ip !== '') {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Login', TableMap::TYPE_PHPNAME, $indexType)];
            $this->login = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Hash', TableMap::TYPE_PHPNAME, $indexType)];
            $this->hash = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('LastLogin', TableMap::TYPE_PHPNAME, $indexType)];
            $this->last_login = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('Secret', TableMap::TYPE_PHPNAME, $indexType)];
            $this->secret = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('RegDate', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reg_date = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('Role', TableMap::TYPE_PHPNAME, $indexType)];
            $this->role = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('Ip', TableMap::TYPE_PHPNAME, $indexType)];
            $this->ip = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\PropelModel\\User'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collFirmUsers = null;

            $this->collFirmids = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
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
                UserTableMap::addInstanceToPool($this);
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
                $this->resetModified();
            }

            if ($this->combinationCollFirmidsScheduledForDeletion !== null) {
                if (!$this->combinationCollFirmidsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->combinationCollFirmidsScheduledForDeletion as $combination) {
                        $entryPk = [];

                        $entryPk[2] = $this->getId();
                        $entryPk[1] = $combination[0]->getId();
                        //$combination[1] = id;
                        $entryPk[0] = $combination[1];

                        $pks[] = $entryPk;
                    }

                    \PropelModel\FirmUserQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->combinationCollFirmidsScheduledForDeletion = null;
                }

            }

            if (null !== $this->combinationCollFirmids) {
                foreach ($this->combinationCollFirmids as $combination) {

                    //$combination[0] = Firm (firm_user_fk_049fe5)
                    if (!$combination[0]->isDeleted() && ($combination[0]->isNew() || $combination[0]->isModified())) {
                        $combination[0]->save($con);
                    }

                    //$combination[1] = id; Nothing to save.
                }
            }


            if ($this->firmUsersScheduledForDeletion !== null) {
                if (!$this->firmUsersScheduledForDeletion->isEmpty()) {
                    \PropelModel\FirmUserQuery::create()
                        ->filterByPrimaryKeys($this->firmUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->firmUsersScheduledForDeletion = null;
                }
            }

            if ($this->collFirmUsers !== null) {
                foreach ($this->collFirmUsers as $referrerFK) {
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

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UserTableMap::COL_LOGIN)) {
            $modifiedColumns[':p' . $index++]  = 'login';
        }
        if ($this->isColumnModified(UserTableMap::COL_HASH)) {
            $modifiedColumns[':p' . $index++]  = 'hash';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(UserTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(UserTableMap::COL_LAST_LOGIN)) {
            $modifiedColumns[':p' . $index++]  = 'last_login';
        }
        if ($this->isColumnModified(UserTableMap::COL_SECRET)) {
            $modifiedColumns[':p' . $index++]  = 'secret';
        }
        if ($this->isColumnModified(UserTableMap::COL_REG_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'reg_date';
        }
        if ($this->isColumnModified(UserTableMap::COL_ROLE)) {
            $modifiedColumns[':p' . $index++]  = 'role';
        }
        if ($this->isColumnModified(UserTableMap::COL_IP)) {
            $modifiedColumns[':p' . $index++]  = 'ip';
        }

        $sql = sprintf(
            'INSERT INTO user (%s) VALUES (%s)',
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
                    case 'login':
                        $stmt->bindValue($identifier, $this->login, PDO::PARAM_STR);
                        break;
                    case 'hash':
                        $stmt->bindValue($identifier, $this->hash, PDO::PARAM_STR);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'last_login':
                        $stmt->bindValue($identifier, $this->last_login, PDO::PARAM_INT);
                        break;
                    case 'secret':
                        $stmt->bindValue($identifier, $this->secret, PDO::PARAM_STR);
                        break;
                    case 'reg_date':
                        $stmt->bindValue($identifier, $this->reg_date, PDO::PARAM_INT);
                        break;
                    case 'role':
                        $stmt->bindValue($identifier, $this->role, PDO::PARAM_INT);
                        break;
                    case 'ip':
                        $stmt->bindValue($identifier, $this->ip, PDO::PARAM_STR);
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
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getLogin();
                break;
            case 2:
                return $this->getHash();
                break;
            case 3:
                return $this->getEmail();
                break;
            case 4:
                return $this->getName();
                break;
            case 5:
                return $this->getLastLogin();
                break;
            case 6:
                return $this->getSecret();
                break;
            case 7:
                return $this->getRegDate();
                break;
            case 8:
                return $this->getRole();
                break;
            case 9:
                return $this->getIp();
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

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getLogin(),
            $keys[2] => $this->getHash(),
            $keys[3] => $this->getEmail(),
            $keys[4] => $this->getName(),
            $keys[5] => $this->getLastLogin(),
            $keys[6] => $this->getSecret(),
            $keys[7] => $this->getRegDate(),
            $keys[8] => $this->getRole(),
            $keys[9] => $this->getIp(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collFirmUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'firmUsers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'firm_users';
                        break;
                    default:
                        $key = 'FirmUsers';
                }

                $result[$key] = $this->collFirmUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\PropelModel\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\PropelModel\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setLogin($value);
                break;
            case 2:
                $this->setHash($value);
                break;
            case 3:
                $this->setEmail($value);
                break;
            case 4:
                $this->setName($value);
                break;
            case 5:
                $this->setLastLogin($value);
                break;
            case 6:
                $this->setSecret($value);
                break;
            case 7:
                $this->setRegDate($value);
                break;
            case 8:
                $this->setRole($value);
                break;
            case 9:
                $this->setIp($value);
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
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setLogin($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setHash($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setEmail($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setName($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setLastLogin($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setSecret($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setRegDate($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setRole($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setIp($arr[$keys[9]]);
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
     * @return $this|\PropelModel\User The current object, for fluid interface
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_LOGIN)) {
            $criteria->add(UserTableMap::COL_LOGIN, $this->login);
        }
        if ($this->isColumnModified(UserTableMap::COL_HASH)) {
            $criteria->add(UserTableMap::COL_HASH, $this->hash);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_NAME)) {
            $criteria->add(UserTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(UserTableMap::COL_LAST_LOGIN)) {
            $criteria->add(UserTableMap::COL_LAST_LOGIN, $this->last_login);
        }
        if ($this->isColumnModified(UserTableMap::COL_SECRET)) {
            $criteria->add(UserTableMap::COL_SECRET, $this->secret);
        }
        if ($this->isColumnModified(UserTableMap::COL_REG_DATE)) {
            $criteria->add(UserTableMap::COL_REG_DATE, $this->reg_date);
        }
        if ($this->isColumnModified(UserTableMap::COL_ROLE)) {
            $criteria->add(UserTableMap::COL_ROLE, $this->role);
        }
        if ($this->isColumnModified(UserTableMap::COL_IP)) {
            $criteria->add(UserTableMap::COL_IP, $this->ip);
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
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \PropelModel\User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setLogin($this->getLogin());
        $copyObj->setHash($this->getHash());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setName($this->getName());
        $copyObj->setLastLogin($this->getLastLogin());
        $copyObj->setSecret($this->getSecret());
        $copyObj->setRegDate($this->getRegDate());
        $copyObj->setRole($this->getRole());
        $copyObj->setIp($this->getIp());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getFirmUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFirmUser($relObj->copy($deepCopy));
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
     * @return \PropelModel\User Clone of current object.
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
        if ('FirmUser' == $relationName) {
            return $this->initFirmUsers();
        }
    }

    /**
     * Clears out the collFirmUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFirmUsers()
     */
    public function clearFirmUsers()
    {
        $this->collFirmUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFirmUsers collection loaded partially.
     */
    public function resetPartialFirmUsers($v = true)
    {
        $this->collFirmUsersPartial = $v;
    }

    /**
     * Initializes the collFirmUsers collection.
     *
     * By default this just sets the collFirmUsers collection to an empty array (like clearcollFirmUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFirmUsers($overrideExisting = true)
    {
        if (null !== $this->collFirmUsers && !$overrideExisting) {
            return;
        }
        $this->collFirmUsers = new ObjectCollection();
        $this->collFirmUsers->setModel('\PropelModel\FirmUser');
    }

    /**
     * Gets an array of ChildFirmUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFirmUser[] List of ChildFirmUser objects
     * @throws PropelException
     */
    public function getFirmUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmUsersPartial && !$this->isNew();
        if (null === $this->collFirmUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFirmUsers) {
                // return empty collection
                $this->initFirmUsers();
            } else {
                $collFirmUsers = ChildFirmUserQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFirmUsersPartial && count($collFirmUsers)) {
                        $this->initFirmUsers(false);

                        foreach ($collFirmUsers as $obj) {
                            if (false == $this->collFirmUsers->contains($obj)) {
                                $this->collFirmUsers->append($obj);
                            }
                        }

                        $this->collFirmUsersPartial = true;
                    }

                    return $collFirmUsers;
                }

                if ($partial && $this->collFirmUsers) {
                    foreach ($this->collFirmUsers as $obj) {
                        if ($obj->isNew()) {
                            $collFirmUsers[] = $obj;
                        }
                    }
                }

                $this->collFirmUsers = $collFirmUsers;
                $this->collFirmUsersPartial = false;
            }
        }

        return $this->collFirmUsers;
    }

    /**
     * Sets a collection of ChildFirmUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $firmUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setFirmUsers(Collection $firmUsers, ConnectionInterface $con = null)
    {
        /** @var ChildFirmUser[] $firmUsersToDelete */
        $firmUsersToDelete = $this->getFirmUsers(new Criteria(), $con)->diff($firmUsers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->firmUsersScheduledForDeletion = clone $firmUsersToDelete;

        foreach ($firmUsersToDelete as $firmUserRemoved) {
            $firmUserRemoved->setUser(null);
        }

        $this->collFirmUsers = null;
        foreach ($firmUsers as $firmUser) {
            $this->addFirmUser($firmUser);
        }

        $this->collFirmUsers = $firmUsers;
        $this->collFirmUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related FirmUser objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related FirmUser objects.
     * @throws PropelException
     */
    public function countFirmUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmUsersPartial && !$this->isNew();
        if (null === $this->collFirmUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFirmUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFirmUsers());
            }

            $query = ChildFirmUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collFirmUsers);
    }

    /**
     * Method called to associate a ChildFirmUser object to this object
     * through the ChildFirmUser foreign key attribute.
     *
     * @param  ChildFirmUser $l ChildFirmUser
     * @return $this|\PropelModel\User The current object (for fluent API support)
     */
    public function addFirmUser(ChildFirmUser $l)
    {
        if ($this->collFirmUsers === null) {
            $this->initFirmUsers();
            $this->collFirmUsersPartial = true;
        }

        if (!$this->collFirmUsers->contains($l)) {
            $this->doAddFirmUser($l);
        }

        return $this;
    }

    /**
     * @param ChildFirmUser $firmUser The ChildFirmUser object to add.
     */
    protected function doAddFirmUser(ChildFirmUser $firmUser)
    {
        $this->collFirmUsers[]= $firmUser;
        $firmUser->setUser($this);
    }

    /**
     * @param  ChildFirmUser $firmUser The ChildFirmUser object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeFirmUser(ChildFirmUser $firmUser)
    {
        if ($this->getFirmUsers()->contains($firmUser)) {
            $pos = $this->collFirmUsers->search($firmUser);
            $this->collFirmUsers->remove($pos);
            if (null === $this->firmUsersScheduledForDeletion) {
                $this->firmUsersScheduledForDeletion = clone $this->collFirmUsers;
                $this->firmUsersScheduledForDeletion->clear();
            }
            $this->firmUsersScheduledForDeletion[]= clone $firmUser;
            $firmUser->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related FirmUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFirmUser[] List of ChildFirmUser objects
     */
    public function getFirmUsersJoinFirm(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFirmUserQuery::create(null, $criteria);
        $query->joinWith('Firm', $joinBehavior);

        return $this->getFirmUsers($query, $con);
    }

    /**
     * Clears out the collFirmids collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFirmids()
     */
    public function clearFirmids()
    {
        $this->collFirmids = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the combinationCollFirmids crossRef collection.
     *
     * By default this just sets the combinationCollFirmids collection to an empty collection (like clearFirmids());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initFirmids()
    {
        $this->combinationCollFirmids = new ObjectCombinationCollection();
        $this->combinationCollFirmidsPartial = true;

    }

    /**
     * Checks if the combinationCollFirmids collection is loaded.
     *
     * @return bool
     */
    public function isFirmidsLoaded()
    {
        return null !== $this->combinationCollFirmids;
    }

    /**
     * Returns a new query object pre configured with filters from current object and given arguments to query the database.
     *
     * @param int $id
     * @param Criteria $criteria
     *
     * @return ChildFirmQuery
     */
    public function createFirmsQuery($id = null, Criteria $criteria = null)
    {
        $criteria = ChildFirmQuery::create($criteria)
            ->filterByUser($this);

        $firmUserQuery = $criteria->useFirmUserQuery();

        if (null !== $id) {
            $firmUserQuery->filterByid($id);
        }

        $firmUserQuery->endUse();

        return $criteria;
    }

    /**
     * Gets a combined collection of ChildFirm objects related by a many-to-many relationship
     * to the current object by way of the firm_user cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCombinationCollection Combination list of ChildFirm objects
     */
    public function getFirmids($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollFirmidsPartial && !$this->isNew();
        if (null === $this->combinationCollFirmids || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->combinationCollFirmids) {
                    $this->initFirmids();
                }
            } else {

                $query = ChildFirmUserQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->joinFirm()
                ;

                $items = $query->find($con);
                $combinationCollFirmids = new ObjectCombinationCollection();
                foreach ($items as $item) {
                    $combination = [];

                    $combination[] = $item->getFirm();
                    $combination[] = $item->getid();
                    $combinationCollFirmids[] = $combination;
                }

                if (null !== $criteria) {
                    return $combinationCollFirmids;
                }

                if ($partial && $this->combinationCollFirmids) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->combinationCollFirmids as $obj) {
                        if (!call_user_func_array([$combinationCollFirmids, 'contains'], $obj)) {
                            $combinationCollFirmids[] = $obj;
                        }
                    }
                }

                $this->combinationCollFirmids = $combinationCollFirmids;
                $this->combinationCollFirmidsPartial = false;
            }
        }

        return $this->combinationCollFirmids;
    }

    /**
     * Returns a not cached ObjectCollection of ChildFirm objects. This will hit always the databases.
     * If you have attached new ChildFirm object to this object you need to call `save` first to get
     * the correct return value. Use getFirmids() to get the current internal state.
     *
     * @param int $id
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return ChildFirm[]|ObjectCollection
     */
    public function getFirms($id = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createFirmsQuery($id, $criteria)->find($con);
    }

    /**
     * Sets a collection of ChildFirm objects related by a many-to-many relationship
     * to the current object by way of the firm_user cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $firmids A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setFirmids(Collection $firmids, ConnectionInterface $con = null)
    {
        $this->clearFirmids();
        $currentFirmids = $this->getFirmids();

        $combinationCollFirmidsScheduledForDeletion = $currentFirmids->diff($firmids);

        foreach ($combinationCollFirmidsScheduledForDeletion as $toDelete) {
            call_user_func_array([$this, 'removeFirmid'], $toDelete);
        }

        foreach ($firmids as $firmid) {
            if (!call_user_func_array([$currentFirmids, 'contains'], $firmid)) {
                call_user_func_array([$this, 'doAddFirmid'], $firmid);
            }
        }

        $this->combinationCollFirmidsPartial = false;
        $this->combinationCollFirmids = $firmids;

        return $this;
    }

    /**
     * Gets the number of ChildFirm objects related by a many-to-many relationship
     * to the current object by way of the firm_user cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildFirm objects
     */
    public function countFirmids(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollFirmidsPartial && !$this->isNew();
        if (null === $this->combinationCollFirmids || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->combinationCollFirmids) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getFirmids());
                }

                $query = ChildFirmUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUser($this)
                    ->count($con);
            }
        } else {
            return count($this->combinationCollFirmids);
        }
    }

    /**
     * Returns the not cached count of ChildFirm objects. This will hit always the databases.
     * If you have attached new ChildFirm object to this object you need to call `save` first to get
     * the correct return value. Use getFirmids() to get the current internal state.
     *
     * @param int $id
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return integer
     */
    public function countFirms($id = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createFirmsQuery($id, $criteria)->count($con);
    }

    /**
     * Associate a ChildFirm to this object
     * through the firm_user cross reference table.
     *
     * @param ChildFirm $firm,
     * @param int $id
     * @return ChildUser The current object (for fluent API support)
     */
    public function addFirm(ChildFirm $firm, $id)
    {
        if ($this->combinationCollFirmids === null) {
            $this->initFirmids();
        }

        if (!$this->getFirmids()->contains($firm, $id)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollFirmids->push($firm, $id);
            $this->doAddFirmid($firm, $id);
        }

        return $this;
    }

    /**
     *
     * @param ChildFirm $firm,
     * @param int $id
     */
    protected function doAddFirmid(ChildFirm $firm, $id)
    {
        $firmUser = new ChildFirmUser();

        $firmUser->setFirm($firm);
        $firmUser->setid($id);


        $firmUser->setUser($this);

        $this->addFirmUser($firmUser);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($firm->isUseridsLoaded()) {
            $firm->initUserids();
            $firm->getUserids()->push($this, $id);
        } elseif (!$firm->getUserids()->contains($this, $id)) {
            $firm->getUserids()->push($this, $id);
        }

    }

    /**
     * Remove firm, id of this object
     * through the firm_user cross reference table.
     *
     * @param ChildFirm $firm,
     * @param int $id
     * @return ChildUser The current object (for fluent API support)
     */
    public function removeFirmid(ChildFirm $firm, $id)
    {
        if ($this->getFirmids()->contains($firm, $id)) { $firmUser = new ChildFirmUser();

            $firmUser->setFirm($firm);
            if ($firm->isUseridsLoaded()) {
                //remove the back reference if available
                $firm->getUserids()->removeObject($this, $id);
            }

            $firmUser->setid($id);
            $firmUser->setUser($this);
            $this->removeFirmUser(clone $firmUser);
            $firmUser->clear();

            $this->combinationCollFirmids->remove($this->combinationCollFirmids->search($firm, $id));

            if (null === $this->combinationCollFirmidsScheduledForDeletion) {
                $this->combinationCollFirmidsScheduledForDeletion = clone $this->combinationCollFirmids;
                $this->combinationCollFirmidsScheduledForDeletion->clear();
            }

            $this->combinationCollFirmidsScheduledForDeletion->push($firm, $id);
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
        $this->login = null;
        $this->hash = null;
        $this->email = null;
        $this->name = null;
        $this->last_login = null;
        $this->secret = null;
        $this->reg_date = null;
        $this->role = null;
        $this->ip = null;
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
            if ($this->collFirmUsers) {
                foreach ($this->collFirmUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->combinationCollFirmids) {
                foreach ($this->combinationCollFirmids as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collFirmUsers = null;
        $this->combinationCollFirmids = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
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
