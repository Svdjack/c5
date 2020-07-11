<?php

namespace PropelModel\Base;

use \DateTime;
use \Exception;
use \PDO;
use PropelModel\CommentQuery as ChildCommentQuery;
use PropelModel\Firm as ChildFirm;
use PropelModel\FirmQuery as ChildFirmQuery;
use PropelModel\Map\CommentTableMap;
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
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'comment' table.
 *
 *
 *
* @package    propel.generator.PropelModel.Base
*/
abstract class Comment implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\PropelModel\\Map\\CommentTableMap';


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
     * The value for the user field.
     * Note: this column has a database default value of: '0'
     * @var        string
     */
    protected $user;

    /**
     * The value for the email field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $email;

    /**
     * The value for the text field.
     * @var        string
     */
    protected $text;

    /**
     * The value for the date field.
     * @var        \DateTime
     */
    protected $date;

    /**
     * The value for the moderation_time field.
     * @var        \DateTime
     */
    protected $moderation_time;

    /**
     * The value for the score field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $score;

    /**
     * The value for the ip field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $ip;

    /**
     * The value for the edited field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $edited;

    /**
     * The value for the emotion field.
     * @var        string
     */
    protected $emotion;

    /**
     * The value for the twitter field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $twitter;

    /**
     * The value for the active field.
     * Note: this column has a database default value of: 1
     * @var        int
     */
    protected $active;

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
        $this->user = '0';
        $this->email = '';
        $this->score = 0;
        $this->ip = '';
        $this->edited = 0;
        $this->twitter = 0;
        $this->active = 1;
    }

    /**
     * Initializes internal state of PropelModel\Base\Comment object.
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
     * Compares this with another <code>Comment</code> instance.  If
     * <code>obj</code> is an instance of <code>Comment</code>, delegates to
     * <code>equals(Comment)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Comment The current object, for fluid interface
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
     * Get the [user] column value.
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
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
     * Get the [text] column value.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get the [optionally formatted] temporal [date] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDate($format = NULL)
    {
        if ($format === null) {
            return $this->date;
        } else {
            return $this->date instanceof \DateTime ? $this->date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [moderation_time] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getModerationTime($format = NULL)
    {
        if ($format === null) {
            return $this->moderation_time;
        } else {
            return $this->moderation_time instanceof \DateTime ? $this->moderation_time->format($format) : null;
        }
    }

    /**
     * Get the [score] column value.
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
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
     * Get the [edited] column value.
     *
     * @return int
     */
    public function getEdited()
    {
        return $this->edited;
    }

    /**
     * Get the [emotion] column value.
     *
     * @return string
     */
    public function getEmotion()
    {
        return $this->emotion;
    }

    /**
     * Get the [twitter] column value.
     *
     * @return int
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Get the [active] column value.
     *
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[CommentTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [firm_id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
     */
    public function setFirmId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->firm_id !== $v) {
            $this->firm_id = $v;
            $this->modifiedColumns[CommentTableMap::COL_FIRM_ID] = true;
        }

        if ($this->aFirm !== null && $this->aFirm->getId() !== $v) {
            $this->aFirm = null;
        }

        return $this;
    } // setFirmId()

    /**
     * Set the value of [user] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
     */
    public function setUser($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user !== $v) {
            $this->user = $v;
            $this->modifiedColumns[CommentTableMap::COL_USER] = true;
        }

        return $this;
    } // setUser()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[CommentTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [text] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
     */
    public function setText($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->text !== $v) {
            $this->text = $v;
            $this->modifiedColumns[CommentTableMap::COL_TEXT] = true;
        }

        return $this;
    } // setText()

    /**
     * Sets the value of [date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date !== null || $dt !== null) {
            if ($this->date === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->date->format("Y-m-d H:i:s")) {
                $this->date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CommentTableMap::COL_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setDate()

    /**
     * Sets the value of [moderation_time] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
     */
    public function setModerationTime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->moderation_time !== null || $dt !== null) {
            if ($this->moderation_time === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->moderation_time->format("Y-m-d H:i:s")) {
                $this->moderation_time = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CommentTableMap::COL_MODERATION_TIME] = true;
            }
        } // if either are not null

        return $this;
    } // setModerationTime()

    /**
     * Set the value of [score] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
     */
    public function setScore($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->score !== $v) {
            $this->score = $v;
            $this->modifiedColumns[CommentTableMap::COL_SCORE] = true;
        }

        return $this;
    } // setScore()

    /**
     * Set the value of [ip] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
     */
    public function setIp($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->ip !== $v) {
            $this->ip = $v;
            $this->modifiedColumns[CommentTableMap::COL_IP] = true;
        }

        return $this;
    } // setIp()

    /**
     * Set the value of [edited] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
     */
    public function setEdited($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->edited !== $v) {
            $this->edited = $v;
            $this->modifiedColumns[CommentTableMap::COL_EDITED] = true;
        }

        return $this;
    } // setEdited()

    /**
     * Set the value of [emotion] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
     */
    public function setEmotion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->emotion !== $v) {
            $this->emotion = $v;
            $this->modifiedColumns[CommentTableMap::COL_EMOTION] = true;
        }

        return $this;
    } // setEmotion()

    /**
     * Set the value of [twitter] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
     */
    public function setTwitter($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->twitter !== $v) {
            $this->twitter = $v;
            $this->modifiedColumns[CommentTableMap::COL_TWITTER] = true;
        }

        return $this;
    } // setTwitter()

    /**
     * Set the value of [active] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
     */
    public function setActive($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->active !== $v) {
            $this->active = $v;
            $this->modifiedColumns[CommentTableMap::COL_ACTIVE] = true;
        }

        return $this;
    } // setActive()

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
            if ($this->user !== '0') {
                return false;
            }

            if ($this->email !== '') {
                return false;
            }

            if ($this->score !== 0) {
                return false;
            }

            if ($this->ip !== '') {
                return false;
            }

            if ($this->edited !== 0) {
                return false;
            }

            if ($this->twitter !== 0) {
                return false;
            }

            if ($this->active !== 1) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CommentTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CommentTableMap::translateFieldName('FirmId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->firm_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CommentTableMap::translateFieldName('User', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CommentTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CommentTableMap::translateFieldName('Text', TableMap::TYPE_PHPNAME, $indexType)];
            $this->text = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : CommentTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : CommentTableMap::translateFieldName('ModerationTime', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->moderation_time = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : CommentTableMap::translateFieldName('Score', TableMap::TYPE_PHPNAME, $indexType)];
            $this->score = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : CommentTableMap::translateFieldName('Ip', TableMap::TYPE_PHPNAME, $indexType)];
            $this->ip = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : CommentTableMap::translateFieldName('Edited', TableMap::TYPE_PHPNAME, $indexType)];
            $this->edited = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : CommentTableMap::translateFieldName('Emotion', TableMap::TYPE_PHPNAME, $indexType)];
            $this->emotion = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : CommentTableMap::translateFieldName('Twitter', TableMap::TYPE_PHPNAME, $indexType)];
            $this->twitter = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : CommentTableMap::translateFieldName('Active', TableMap::TYPE_PHPNAME, $indexType)];
            $this->active = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 13; // 13 = CommentTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\PropelModel\\Comment'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(CommentTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCommentQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
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
     * @see Comment::setDeleted()
     * @see Comment::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CommentTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCommentQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(CommentTableMap::DATABASE_NAME);
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
                CommentTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[CommentTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CommentTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CommentTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(CommentTableMap::COL_FIRM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'firm_id';
        }
        if ($this->isColumnModified(CommentTableMap::COL_USER)) {
            $modifiedColumns[':p' . $index++]  = 'user';
        }
        if ($this->isColumnModified(CommentTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(CommentTableMap::COL_TEXT)) {
            $modifiedColumns[':p' . $index++]  = 'text';
        }
        if ($this->isColumnModified(CommentTableMap::COL_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'date';
        }
        if ($this->isColumnModified(CommentTableMap::COL_MODERATION_TIME)) {
            $modifiedColumns[':p' . $index++]  = 'moderation_time';
        }
        if ($this->isColumnModified(CommentTableMap::COL_SCORE)) {
            $modifiedColumns[':p' . $index++]  = 'score';
        }
        if ($this->isColumnModified(CommentTableMap::COL_IP)) {
            $modifiedColumns[':p' . $index++]  = 'ip';
        }
        if ($this->isColumnModified(CommentTableMap::COL_EDITED)) {
            $modifiedColumns[':p' . $index++]  = 'edited';
        }
        if ($this->isColumnModified(CommentTableMap::COL_EMOTION)) {
            $modifiedColumns[':p' . $index++]  = 'emotion';
        }
        if ($this->isColumnModified(CommentTableMap::COL_TWITTER)) {
            $modifiedColumns[':p' . $index++]  = 'twitter';
        }
        if ($this->isColumnModified(CommentTableMap::COL_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = 'active';
        }

        $sql = sprintf(
            'INSERT INTO comment (%s) VALUES (%s)',
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
                    case 'user':
                        $stmt->bindValue($identifier, $this->user, PDO::PARAM_STR);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'text':
                        $stmt->bindValue($identifier, $this->text, PDO::PARAM_STR);
                        break;
                    case 'date':
                        $stmt->bindValue($identifier, $this->date ? $this->date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'moderation_time':
                        $stmt->bindValue($identifier, $this->moderation_time ? $this->moderation_time->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'score':
                        $stmt->bindValue($identifier, $this->score, PDO::PARAM_INT);
                        break;
                    case 'ip':
                        $stmt->bindValue($identifier, $this->ip, PDO::PARAM_STR);
                        break;
                    case 'edited':
                        $stmt->bindValue($identifier, $this->edited, PDO::PARAM_INT);
                        break;
                    case 'emotion':
                        $stmt->bindValue($identifier, $this->emotion, PDO::PARAM_STR);
                        break;
                    case 'twitter':
                        $stmt->bindValue($identifier, $this->twitter, PDO::PARAM_INT);
                        break;
                    case 'active':
                        $stmt->bindValue($identifier, $this->active, PDO::PARAM_INT);
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
        $pos = CommentTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getUser();
                break;
            case 3:
                return $this->getEmail();
                break;
            case 4:
                return $this->getText();
                break;
            case 5:
                return $this->getDate();
                break;
            case 6:
                return $this->getModerationTime();
                break;
            case 7:
                return $this->getScore();
                break;
            case 8:
                return $this->getIp();
                break;
            case 9:
                return $this->getEdited();
                break;
            case 10:
                return $this->getEmotion();
                break;
            case 11:
                return $this->getTwitter();
                break;
            case 12:
                return $this->getActive();
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

        if (isset($alreadyDumpedObjects['Comment'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Comment'][$this->hashCode()] = true;
        $keys = CommentTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getFirmId(),
            $keys[2] => $this->getUser(),
            $keys[3] => $this->getEmail(),
            $keys[4] => $this->getText(),
            $keys[5] => $this->getDate(),
            $keys[6] => $this->getModerationTime(),
            $keys[7] => $this->getScore(),
            $keys[8] => $this->getIp(),
            $keys[9] => $this->getEdited(),
            $keys[10] => $this->getEmotion(),
            $keys[11] => $this->getTwitter(),
            $keys[12] => $this->getActive(),
        );

        $utc = new \DateTimeZone('utc');
        if ($result[$keys[5]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[5]];
            $result[$keys[5]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        if ($result[$keys[6]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[6]];
            $result[$keys[6]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

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
     * @return $this|\PropelModel\Comment
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CommentTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\PropelModel\Comment
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
                $this->setUser($value);
                break;
            case 3:
                $this->setEmail($value);
                break;
            case 4:
                $this->setText($value);
                break;
            case 5:
                $this->setDate($value);
                break;
            case 6:
                $this->setModerationTime($value);
                break;
            case 7:
                $this->setScore($value);
                break;
            case 8:
                $this->setIp($value);
                break;
            case 9:
                $this->setEdited($value);
                break;
            case 10:
                $this->setEmotion($value);
                break;
            case 11:
                $this->setTwitter($value);
                break;
            case 12:
                $this->setActive($value);
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
        $keys = CommentTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setFirmId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUser($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setEmail($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setText($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setDate($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setModerationTime($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setScore($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setIp($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setEdited($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setEmotion($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setTwitter($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setActive($arr[$keys[12]]);
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
     * @return $this|\PropelModel\Comment The current object, for fluid interface
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
        $criteria = new Criteria(CommentTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CommentTableMap::COL_ID)) {
            $criteria->add(CommentTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(CommentTableMap::COL_FIRM_ID)) {
            $criteria->add(CommentTableMap::COL_FIRM_ID, $this->firm_id);
        }
        if ($this->isColumnModified(CommentTableMap::COL_USER)) {
            $criteria->add(CommentTableMap::COL_USER, $this->user);
        }
        if ($this->isColumnModified(CommentTableMap::COL_EMAIL)) {
            $criteria->add(CommentTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(CommentTableMap::COL_TEXT)) {
            $criteria->add(CommentTableMap::COL_TEXT, $this->text);
        }
        if ($this->isColumnModified(CommentTableMap::COL_DATE)) {
            $criteria->add(CommentTableMap::COL_DATE, $this->date);
        }
        if ($this->isColumnModified(CommentTableMap::COL_MODERATION_TIME)) {
            $criteria->add(CommentTableMap::COL_MODERATION_TIME, $this->moderation_time);
        }
        if ($this->isColumnModified(CommentTableMap::COL_SCORE)) {
            $criteria->add(CommentTableMap::COL_SCORE, $this->score);
        }
        if ($this->isColumnModified(CommentTableMap::COL_IP)) {
            $criteria->add(CommentTableMap::COL_IP, $this->ip);
        }
        if ($this->isColumnModified(CommentTableMap::COL_EDITED)) {
            $criteria->add(CommentTableMap::COL_EDITED, $this->edited);
        }
        if ($this->isColumnModified(CommentTableMap::COL_EMOTION)) {
            $criteria->add(CommentTableMap::COL_EMOTION, $this->emotion);
        }
        if ($this->isColumnModified(CommentTableMap::COL_TWITTER)) {
            $criteria->add(CommentTableMap::COL_TWITTER, $this->twitter);
        }
        if ($this->isColumnModified(CommentTableMap::COL_ACTIVE)) {
            $criteria->add(CommentTableMap::COL_ACTIVE, $this->active);
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
        $criteria = ChildCommentQuery::create();
        $criteria->add(CommentTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \PropelModel\Comment (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setFirmId($this->getFirmId());
        $copyObj->setUser($this->getUser());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setText($this->getText());
        $copyObj->setDate($this->getDate());
        $copyObj->setModerationTime($this->getModerationTime());
        $copyObj->setScore($this->getScore());
        $copyObj->setIp($this->getIp());
        $copyObj->setEdited($this->getEdited());
        $copyObj->setEmotion($this->getEmotion());
        $copyObj->setTwitter($this->getTwitter());
        $copyObj->setActive($this->getActive());
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
     * @return \PropelModel\Comment Clone of current object.
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
     * @return $this|\PropelModel\Comment The current object (for fluent API support)
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
            $v->addComment($this);
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
                $this->aFirm->addComments($this);
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
            $this->aFirm->removeComment($this);
        }
        $this->id = null;
        $this->firm_id = null;
        $this->user = null;
        $this->email = null;
        $this->text = null;
        $this->date = null;
        $this->moderation_time = null;
        $this->score = null;
        $this->ip = null;
        $this->edited = null;
        $this->emotion = null;
        $this->twitter = null;
        $this->active = null;
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
        return (string) $this->exportTo(CommentTableMap::DEFAULT_STRING_FORMAT);
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
