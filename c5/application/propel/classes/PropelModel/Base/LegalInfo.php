<?php

namespace PropelModel\Base;

use \Exception;
use \PDO;
use PropelModel\Firm as ChildFirm;
use PropelModel\FirmQuery as ChildFirmQuery;
use PropelModel\LegalInfoQuery as ChildLegalInfoQuery;
use PropelModel\Map\LegalInfoTableMap;
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
 * Base class that represents a row from the 'jur_data' table.
 *
 *
 *
* @package    propel.generator.PropelModel.Base
*/
abstract class LegalInfo implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\PropelModel\\Map\\LegalInfoTableMap';


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
     * The value for the rusprofile_id field.
     * @var        int
     */
    protected $rusprofile_id;

    /**
     * The value for the firm_id field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $firm_id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the region field.
     * @var        string
     */
    protected $region;

    /**
     * The value for the city field.
     * @var        string
     */
    protected $city;

    /**
     * The value for the postal field.
     * @var        string
     */
    protected $postal;

    /**
     * The value for the address field.
     * @var        string
     */
    protected $address;

    /**
     * The value for the director field.
     * @var        string
     */
    protected $director;

    /**
     * The value for the phone field.
     * @var        string
     */
    protected $phone;

    /**
     * The value for the inn field.
     * @var        string
     */
    protected $inn;

    /**
     * The value for the okato field.
     * @var        string
     */
    protected $okato;

    /**
     * The value for the fsfr field.
     * @var        string
     */
    protected $fsfr;

    /**
     * The value for the ogrn field.
     * @var        string
     */
    protected $ogrn;

    /**
     * The value for the okpo field.
     * @var        string
     */
    protected $okpo;

    /**
     * The value for the org_form field.
     * @var        string
     */
    protected $org_form;

    /**
     * The value for the okogu field.
     * @var        string
     */
    protected $okogu;

    /**
     * The value for the reg_date field.
     * @var        string
     */
    protected $reg_date;

    /**
     * The value for the is_liquidated field.
     * @var        string
     */
    protected $is_liquidated;

    /**
     * The value for the capital field.
     * @var        string
     */
    protected $capital;

    /**
     * The value for the activities field.
     * @var        string
     */
    protected $activities;

    /**
     * The value for the kpp field.
     * @var        string
     */
    protected $kpp;

    /**
     * @var        ChildFirm
     */
    protected $aFirmRelatedByFirmId;

    /**
     * @var        ChildFirm one-to-one related ChildFirm object
     */
    protected $singleFirmRelatedById;

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
        $this->firm_id = 0;
    }

    /**
     * Initializes internal state of PropelModel\Base\LegalInfo object.
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
     * Compares this with another <code>LegalInfo</code> instance.  If
     * <code>obj</code> is an instance of <code>LegalInfo</code>, delegates to
     * <code>equals(LegalInfo)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|LegalInfo The current object, for fluid interface
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
     * Get the [rusprofile_id] column value.
     *
     * @return int
     */
    public function getRusprofileId()
    {
        return $this->rusprofile_id;
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [region] column value.
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Get the [city] column value.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get the [postal] column value.
     *
     * @return string
     */
    public function getPostal()
    {
        return $this->postal;
    }

    /**
     * Get the [address] column value.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get the [director] column value.
     *
     * @return string
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Get the [phone] column value.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get the [inn] column value.
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Get the [okato] column value.
     *
     * @return string
     */
    public function getOkato()
    {
        return $this->okato;
    }

    /**
     * Get the [fsfr] column value.
     *
     * @return string
     */
    public function getFsfr()
    {
        return $this->fsfr;
    }

    /**
     * Get the [ogrn] column value.
     *
     * @return string
     */
    public function getOgrn()
    {
        return $this->ogrn;
    }

    /**
     * Get the [okpo] column value.
     *
     * @return string
     */
    public function getOkpo()
    {
        return $this->okpo;
    }

    /**
     * Get the [org_form] column value.
     *
     * @return string
     */
    public function getOrgForm()
    {
        return $this->org_form;
    }

    /**
     * Get the [okogu] column value.
     *
     * @return string
     */
    public function getOkogu()
    {
        return $this->okogu;
    }

    /**
     * Get the [reg_date] column value.
     *
     * @return string
     */
    public function getRegDate()
    {
        return $this->reg_date;
    }

    /**
     * Get the [is_liquidated] column value.
     *
     * @return string
     */
    public function getIsLiquidated()
    {
        return $this->is_liquidated;
    }

    /**
     * Get the [capital] column value.
     *
     * @return string
     */
    public function getCapital()
    {
        return $this->capital;
    }

    /**
     * Get the [activities] column value.
     *
     * @return string
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * Get the [kpp] column value.
     *
     * @return string
     */
    public function getKpp()
    {
        return $this->kpp;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [rusprofile_id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setRusprofileId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->rusprofile_id !== $v) {
            $this->rusprofile_id = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_RUSPROFILE_ID] = true;
        }

        return $this;
    } // setRusprofileId()

    /**
     * Set the value of [firm_id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setFirmId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->firm_id !== $v) {
            $this->firm_id = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_FIRM_ID] = true;
        }

        if ($this->aFirmRelatedByFirmId !== null && $this->aFirmRelatedByFirmId->getId() !== $v) {
            $this->aFirmRelatedByFirmId = null;
        }

        return $this;
    } // setFirmId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [region] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setRegion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->region !== $v) {
            $this->region = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_REGION] = true;
        }

        return $this;
    } // setRegion()

    /**
     * Set the value of [city] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_CITY] = true;
        }

        return $this;
    } // setCity()

    /**
     * Set the value of [postal] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setPostal($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->postal !== $v) {
            $this->postal = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_POSTAL] = true;
        }

        return $this;
    } // setPostal()

    /**
     * Set the value of [address] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address !== $v) {
            $this->address = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_ADDRESS] = true;
        }

        return $this;
    } // setAddress()

    /**
     * Set the value of [director] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setDirector($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->director !== $v) {
            $this->director = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_DIRECTOR] = true;
        }

        return $this;
    } // setDirector()

    /**
     * Set the value of [phone] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_PHONE] = true;
        }

        return $this;
    } // setPhone()

    /**
     * Set the value of [inn] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setInn($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->inn !== $v) {
            $this->inn = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_INN] = true;
        }

        return $this;
    } // setInn()

    /**
     * Set the value of [okato] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setOkato($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->okato !== $v) {
            $this->okato = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_OKATO] = true;
        }

        return $this;
    } // setOkato()

    /**
     * Set the value of [fsfr] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setFsfr($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->fsfr !== $v) {
            $this->fsfr = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_FSFR] = true;
        }

        return $this;
    } // setFsfr()

    /**
     * Set the value of [ogrn] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setOgrn($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->ogrn !== $v) {
            $this->ogrn = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_OGRN] = true;
        }

        return $this;
    } // setOgrn()

    /**
     * Set the value of [okpo] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setOkpo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->okpo !== $v) {
            $this->okpo = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_OKPO] = true;
        }

        return $this;
    } // setOkpo()

    /**
     * Set the value of [org_form] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setOrgForm($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->org_form !== $v) {
            $this->org_form = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_ORG_FORM] = true;
        }

        return $this;
    } // setOrgForm()

    /**
     * Set the value of [okogu] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setOkogu($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->okogu !== $v) {
            $this->okogu = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_OKOGU] = true;
        }

        return $this;
    } // setOkogu()

    /**
     * Set the value of [reg_date] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setRegDate($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->reg_date !== $v) {
            $this->reg_date = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_REG_DATE] = true;
        }

        return $this;
    } // setRegDate()

    /**
     * Set the value of [is_liquidated] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setIsLiquidated($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->is_liquidated !== $v) {
            $this->is_liquidated = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_IS_LIQUIDATED] = true;
        }

        return $this;
    } // setIsLiquidated()

    /**
     * Set the value of [capital] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setCapital($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->capital !== $v) {
            $this->capital = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_CAPITAL] = true;
        }

        return $this;
    } // setCapital()

    /**
     * Set the value of [activities] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setActivities($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->activities !== $v) {
            $this->activities = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_ACTIVITIES] = true;
        }

        return $this;
    } // setActivities()

    /**
     * Set the value of [kpp] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     */
    public function setKpp($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->kpp !== $v) {
            $this->kpp = $v;
            $this->modifiedColumns[LegalInfoTableMap::COL_KPP] = true;
        }

        return $this;
    } // setKpp()

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
            if ($this->firm_id !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : LegalInfoTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : LegalInfoTableMap::translateFieldName('RusprofileId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rusprofile_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : LegalInfoTableMap::translateFieldName('FirmId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->firm_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : LegalInfoTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : LegalInfoTableMap::translateFieldName('Region', TableMap::TYPE_PHPNAME, $indexType)];
            $this->region = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : LegalInfoTableMap::translateFieldName('City', TableMap::TYPE_PHPNAME, $indexType)];
            $this->city = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : LegalInfoTableMap::translateFieldName('Postal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->postal = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : LegalInfoTableMap::translateFieldName('Address', TableMap::TYPE_PHPNAME, $indexType)];
            $this->address = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : LegalInfoTableMap::translateFieldName('Director', TableMap::TYPE_PHPNAME, $indexType)];
            $this->director = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : LegalInfoTableMap::translateFieldName('Phone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->phone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : LegalInfoTableMap::translateFieldName('Inn', TableMap::TYPE_PHPNAME, $indexType)];
            $this->inn = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : LegalInfoTableMap::translateFieldName('Okato', TableMap::TYPE_PHPNAME, $indexType)];
            $this->okato = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : LegalInfoTableMap::translateFieldName('Fsfr', TableMap::TYPE_PHPNAME, $indexType)];
            $this->fsfr = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : LegalInfoTableMap::translateFieldName('Ogrn', TableMap::TYPE_PHPNAME, $indexType)];
            $this->ogrn = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : LegalInfoTableMap::translateFieldName('Okpo', TableMap::TYPE_PHPNAME, $indexType)];
            $this->okpo = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : LegalInfoTableMap::translateFieldName('OrgForm', TableMap::TYPE_PHPNAME, $indexType)];
            $this->org_form = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : LegalInfoTableMap::translateFieldName('Okogu', TableMap::TYPE_PHPNAME, $indexType)];
            $this->okogu = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : LegalInfoTableMap::translateFieldName('RegDate', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reg_date = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : LegalInfoTableMap::translateFieldName('IsLiquidated', TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_liquidated = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : LegalInfoTableMap::translateFieldName('Capital', TableMap::TYPE_PHPNAME, $indexType)];
            $this->capital = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : LegalInfoTableMap::translateFieldName('Activities', TableMap::TYPE_PHPNAME, $indexType)];
            $this->activities = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : LegalInfoTableMap::translateFieldName('Kpp', TableMap::TYPE_PHPNAME, $indexType)];
            $this->kpp = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 22; // 22 = LegalInfoTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\PropelModel\\LegalInfo'), 0, $e);
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
        if ($this->aFirmRelatedByFirmId !== null && $this->firm_id !== $this->aFirmRelatedByFirmId->getId()) {
            $this->aFirmRelatedByFirmId = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(LegalInfoTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildLegalInfoQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aFirmRelatedByFirmId = null;
            $this->singleFirmRelatedById = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see LegalInfo::setDeleted()
     * @see LegalInfo::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(LegalInfoTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildLegalInfoQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(LegalInfoTableMap::DATABASE_NAME);
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
                LegalInfoTableMap::addInstanceToPool($this);
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

            if ($this->aFirmRelatedByFirmId !== null) {
                if ($this->aFirmRelatedByFirmId->isModified() || $this->aFirmRelatedByFirmId->isNew()) {
                    $affectedRows += $this->aFirmRelatedByFirmId->save($con);
                }
                $this->setFirmRelatedByFirmId($this->aFirmRelatedByFirmId);
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

            if ($this->singleFirmRelatedById !== null) {
                if (!$this->singleFirmRelatedById->isDeleted() && ($this->singleFirmRelatedById->isNew() || $this->singleFirmRelatedById->isModified())) {
                    $affectedRows += $this->singleFirmRelatedById->save($con);
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

        $this->modifiedColumns[LegalInfoTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . LegalInfoTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(LegalInfoTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_RUSPROFILE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'rusprofile_id';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_FIRM_ID)) {
            $modifiedColumns[':p' . $index++]  = 'firm_id';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_REGION)) {
            $modifiedColumns[':p' . $index++]  = 'region';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_CITY)) {
            $modifiedColumns[':p' . $index++]  = 'city';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_POSTAL)) {
            $modifiedColumns[':p' . $index++]  = 'postal';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = 'address';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_DIRECTOR)) {
            $modifiedColumns[':p' . $index++]  = 'director';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_PHONE)) {
            $modifiedColumns[':p' . $index++]  = 'phone';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_INN)) {
            $modifiedColumns[':p' . $index++]  = 'inn';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_OKATO)) {
            $modifiedColumns[':p' . $index++]  = 'okato';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_FSFR)) {
            $modifiedColumns[':p' . $index++]  = 'fsfr';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_OGRN)) {
            $modifiedColumns[':p' . $index++]  = 'ogrn';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_OKPO)) {
            $modifiedColumns[':p' . $index++]  = 'okpo';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_ORG_FORM)) {
            $modifiedColumns[':p' . $index++]  = 'org_form';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_OKOGU)) {
            $modifiedColumns[':p' . $index++]  = 'okogu';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_REG_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'reg_date';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_IS_LIQUIDATED)) {
            $modifiedColumns[':p' . $index++]  = 'is_liquidated';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_CAPITAL)) {
            $modifiedColumns[':p' . $index++]  = 'capital';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_ACTIVITIES)) {
            $modifiedColumns[':p' . $index++]  = 'activities';
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_KPP)) {
            $modifiedColumns[':p' . $index++]  = 'kpp';
        }

        $sql = sprintf(
            'INSERT INTO jur_data (%s) VALUES (%s)',
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
                    case 'rusprofile_id':
                        $stmt->bindValue($identifier, $this->rusprofile_id, PDO::PARAM_INT);
                        break;
                    case 'firm_id':
                        $stmt->bindValue($identifier, $this->firm_id, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'region':
                        $stmt->bindValue($identifier, $this->region, PDO::PARAM_STR);
                        break;
                    case 'city':
                        $stmt->bindValue($identifier, $this->city, PDO::PARAM_STR);
                        break;
                    case 'postal':
                        $stmt->bindValue($identifier, $this->postal, PDO::PARAM_STR);
                        break;
                    case 'address':
                        $stmt->bindValue($identifier, $this->address, PDO::PARAM_STR);
                        break;
                    case 'director':
                        $stmt->bindValue($identifier, $this->director, PDO::PARAM_STR);
                        break;
                    case 'phone':
                        $stmt->bindValue($identifier, $this->phone, PDO::PARAM_STR);
                        break;
                    case 'inn':
                        $stmt->bindValue($identifier, $this->inn, PDO::PARAM_STR);
                        break;
                    case 'okato':
                        $stmt->bindValue($identifier, $this->okato, PDO::PARAM_STR);
                        break;
                    case 'fsfr':
                        $stmt->bindValue($identifier, $this->fsfr, PDO::PARAM_STR);
                        break;
                    case 'ogrn':
                        $stmt->bindValue($identifier, $this->ogrn, PDO::PARAM_STR);
                        break;
                    case 'okpo':
                        $stmt->bindValue($identifier, $this->okpo, PDO::PARAM_STR);
                        break;
                    case 'org_form':
                        $stmt->bindValue($identifier, $this->org_form, PDO::PARAM_STR);
                        break;
                    case 'okogu':
                        $stmt->bindValue($identifier, $this->okogu, PDO::PARAM_STR);
                        break;
                    case 'reg_date':
                        $stmt->bindValue($identifier, $this->reg_date, PDO::PARAM_STR);
                        break;
                    case 'is_liquidated':
                        $stmt->bindValue($identifier, $this->is_liquidated, PDO::PARAM_STR);
                        break;
                    case 'capital':
                        $stmt->bindValue($identifier, $this->capital, PDO::PARAM_STR);
                        break;
                    case 'activities':
                        $stmt->bindValue($identifier, $this->activities, PDO::PARAM_STR);
                        break;
                    case 'kpp':
                        $stmt->bindValue($identifier, $this->kpp, PDO::PARAM_STR);
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
        $pos = LegalInfoTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getRusprofileId();
                break;
            case 2:
                return $this->getFirmId();
                break;
            case 3:
                return $this->getName();
                break;
            case 4:
                return $this->getRegion();
                break;
            case 5:
                return $this->getCity();
                break;
            case 6:
                return $this->getPostal();
                break;
            case 7:
                return $this->getAddress();
                break;
            case 8:
                return $this->getDirector();
                break;
            case 9:
                return $this->getPhone();
                break;
            case 10:
                return $this->getInn();
                break;
            case 11:
                return $this->getOkato();
                break;
            case 12:
                return $this->getFsfr();
                break;
            case 13:
                return $this->getOgrn();
                break;
            case 14:
                return $this->getOkpo();
                break;
            case 15:
                return $this->getOrgForm();
                break;
            case 16:
                return $this->getOkogu();
                break;
            case 17:
                return $this->getRegDate();
                break;
            case 18:
                return $this->getIsLiquidated();
                break;
            case 19:
                return $this->getCapital();
                break;
            case 20:
                return $this->getActivities();
                break;
            case 21:
                return $this->getKpp();
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

        if (isset($alreadyDumpedObjects['LegalInfo'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['LegalInfo'][$this->hashCode()] = true;
        $keys = LegalInfoTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getRusprofileId(),
            $keys[2] => $this->getFirmId(),
            $keys[3] => $this->getName(),
            $keys[4] => $this->getRegion(),
            $keys[5] => $this->getCity(),
            $keys[6] => $this->getPostal(),
            $keys[7] => $this->getAddress(),
            $keys[8] => $this->getDirector(),
            $keys[9] => $this->getPhone(),
            $keys[10] => $this->getInn(),
            $keys[11] => $this->getOkato(),
            $keys[12] => $this->getFsfr(),
            $keys[13] => $this->getOgrn(),
            $keys[14] => $this->getOkpo(),
            $keys[15] => $this->getOrgForm(),
            $keys[16] => $this->getOkogu(),
            $keys[17] => $this->getRegDate(),
            $keys[18] => $this->getIsLiquidated(),
            $keys[19] => $this->getCapital(),
            $keys[20] => $this->getActivities(),
            $keys[21] => $this->getKpp(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aFirmRelatedByFirmId) {

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

                $result[$key] = $this->aFirmRelatedByFirmId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->singleFirmRelatedById) {

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

                $result[$key] = $this->singleFirmRelatedById->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
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
     * @return $this|\PropelModel\LegalInfo
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = LegalInfoTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\PropelModel\LegalInfo
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setRusprofileId($value);
                break;
            case 2:
                $this->setFirmId($value);
                break;
            case 3:
                $this->setName($value);
                break;
            case 4:
                $this->setRegion($value);
                break;
            case 5:
                $this->setCity($value);
                break;
            case 6:
                $this->setPostal($value);
                break;
            case 7:
                $this->setAddress($value);
                break;
            case 8:
                $this->setDirector($value);
                break;
            case 9:
                $this->setPhone($value);
                break;
            case 10:
                $this->setInn($value);
                break;
            case 11:
                $this->setOkato($value);
                break;
            case 12:
                $this->setFsfr($value);
                break;
            case 13:
                $this->setOgrn($value);
                break;
            case 14:
                $this->setOkpo($value);
                break;
            case 15:
                $this->setOrgForm($value);
                break;
            case 16:
                $this->setOkogu($value);
                break;
            case 17:
                $this->setRegDate($value);
                break;
            case 18:
                $this->setIsLiquidated($value);
                break;
            case 19:
                $this->setCapital($value);
                break;
            case 20:
                $this->setActivities($value);
                break;
            case 21:
                $this->setKpp($value);
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
        $keys = LegalInfoTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setRusprofileId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setFirmId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setName($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setRegion($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCity($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setPostal($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setAddress($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setDirector($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setPhone($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setInn($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setOkato($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setFsfr($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setOgrn($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setOkpo($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setOrgForm($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setOkogu($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setRegDate($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setIsLiquidated($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setCapital($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setActivities($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setKpp($arr[$keys[21]]);
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
     * @return $this|\PropelModel\LegalInfo The current object, for fluid interface
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
        $criteria = new Criteria(LegalInfoTableMap::DATABASE_NAME);

        if ($this->isColumnModified(LegalInfoTableMap::COL_ID)) {
            $criteria->add(LegalInfoTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_RUSPROFILE_ID)) {
            $criteria->add(LegalInfoTableMap::COL_RUSPROFILE_ID, $this->rusprofile_id);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_FIRM_ID)) {
            $criteria->add(LegalInfoTableMap::COL_FIRM_ID, $this->firm_id);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_NAME)) {
            $criteria->add(LegalInfoTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_REGION)) {
            $criteria->add(LegalInfoTableMap::COL_REGION, $this->region);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_CITY)) {
            $criteria->add(LegalInfoTableMap::COL_CITY, $this->city);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_POSTAL)) {
            $criteria->add(LegalInfoTableMap::COL_POSTAL, $this->postal);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_ADDRESS)) {
            $criteria->add(LegalInfoTableMap::COL_ADDRESS, $this->address);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_DIRECTOR)) {
            $criteria->add(LegalInfoTableMap::COL_DIRECTOR, $this->director);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_PHONE)) {
            $criteria->add(LegalInfoTableMap::COL_PHONE, $this->phone);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_INN)) {
            $criteria->add(LegalInfoTableMap::COL_INN, $this->inn);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_OKATO)) {
            $criteria->add(LegalInfoTableMap::COL_OKATO, $this->okato);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_FSFR)) {
            $criteria->add(LegalInfoTableMap::COL_FSFR, $this->fsfr);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_OGRN)) {
            $criteria->add(LegalInfoTableMap::COL_OGRN, $this->ogrn);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_OKPO)) {
            $criteria->add(LegalInfoTableMap::COL_OKPO, $this->okpo);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_ORG_FORM)) {
            $criteria->add(LegalInfoTableMap::COL_ORG_FORM, $this->org_form);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_OKOGU)) {
            $criteria->add(LegalInfoTableMap::COL_OKOGU, $this->okogu);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_REG_DATE)) {
            $criteria->add(LegalInfoTableMap::COL_REG_DATE, $this->reg_date);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_IS_LIQUIDATED)) {
            $criteria->add(LegalInfoTableMap::COL_IS_LIQUIDATED, $this->is_liquidated);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_CAPITAL)) {
            $criteria->add(LegalInfoTableMap::COL_CAPITAL, $this->capital);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_ACTIVITIES)) {
            $criteria->add(LegalInfoTableMap::COL_ACTIVITIES, $this->activities);
        }
        if ($this->isColumnModified(LegalInfoTableMap::COL_KPP)) {
            $criteria->add(LegalInfoTableMap::COL_KPP, $this->kpp);
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
        $criteria = ChildLegalInfoQuery::create();
        $criteria->add(LegalInfoTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \PropelModel\LegalInfo (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setRusprofileId($this->getRusprofileId());
        $copyObj->setFirmId($this->getFirmId());
        $copyObj->setName($this->getName());
        $copyObj->setRegion($this->getRegion());
        $copyObj->setCity($this->getCity());
        $copyObj->setPostal($this->getPostal());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setDirector($this->getDirector());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setInn($this->getInn());
        $copyObj->setOkato($this->getOkato());
        $copyObj->setFsfr($this->getFsfr());
        $copyObj->setOgrn($this->getOgrn());
        $copyObj->setOkpo($this->getOkpo());
        $copyObj->setOrgForm($this->getOrgForm());
        $copyObj->setOkogu($this->getOkogu());
        $copyObj->setRegDate($this->getRegDate());
        $copyObj->setIsLiquidated($this->getIsLiquidated());
        $copyObj->setCapital($this->getCapital());
        $copyObj->setActivities($this->getActivities());
        $copyObj->setKpp($this->getKpp());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            $relObj = $this->getFirmRelatedById();
            if ($relObj) {
                $copyObj->setFirmRelatedById($relObj->copy($deepCopy));
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
     * @return \PropelModel\LegalInfo Clone of current object.
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
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFirmRelatedByFirmId(ChildFirm $v = null)
    {
        if ($v === null) {
            $this->setFirmId(0);
        } else {
            $this->setFirmId($v->getId());
        }

        $this->aFirmRelatedByFirmId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildFirm object, it will not be re-added.
        if ($v !== null) {
            $v->addLegalInfoRelatedByFirmId($this);
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
    public function getFirmRelatedByFirmId(ConnectionInterface $con = null)
    {
        if ($this->aFirmRelatedByFirmId === null && ($this->firm_id !== null)) {
            $this->aFirmRelatedByFirmId = ChildFirmQuery::create()->findPk($this->firm_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFirmRelatedByFirmId->addLegalInfosRelatedByFirmId($this);
             */
        }

        return $this->aFirmRelatedByFirmId;
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
    }

    /**
     * Gets a single ChildFirm object, which is related to this object by a one-to-one relationship.
     *
     * @param  ConnectionInterface $con optional connection object
     * @return ChildFirm
     * @throws PropelException
     */
    public function getFirmRelatedById(ConnectionInterface $con = null)
    {

        if ($this->singleFirmRelatedById === null && !$this->isNew()) {
            $this->singleFirmRelatedById = ChildFirmQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singleFirmRelatedById;
    }

    /**
     * Sets a single ChildFirm object as related to this object by a one-to-one relationship.
     *
     * @param  ChildFirm $v ChildFirm
     * @return $this|\PropelModel\LegalInfo The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFirmRelatedById(ChildFirm $v = null)
    {
        $this->singleFirmRelatedById = $v;

        // Make sure that that the passed-in ChildFirm isn't already associated with this object
        if ($v !== null && $v->getLegalInfoRelatedById(null, false) === null) {
            $v->setLegalInfoRelatedById($this);
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
        if (null !== $this->aFirmRelatedByFirmId) {
            $this->aFirmRelatedByFirmId->removeLegalInfoRelatedByFirmId($this);
        }
        $this->id = null;
        $this->rusprofile_id = null;
        $this->firm_id = null;
        $this->name = null;
        $this->region = null;
        $this->city = null;
        $this->postal = null;
        $this->address = null;
        $this->director = null;
        $this->phone = null;
        $this->inn = null;
        $this->okato = null;
        $this->fsfr = null;
        $this->ogrn = null;
        $this->okpo = null;
        $this->org_form = null;
        $this->okogu = null;
        $this->reg_date = null;
        $this->is_liquidated = null;
        $this->capital = null;
        $this->activities = null;
        $this->kpp = null;
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
            if ($this->singleFirmRelatedById) {
                $this->singleFirmRelatedById->clearAllReferences($deep);
            }
        } // if ($deep)

        $this->singleFirmRelatedById = null;
        $this->aFirmRelatedByFirmId = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LegalInfoTableMap::DEFAULT_STRING_FORMAT);
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
