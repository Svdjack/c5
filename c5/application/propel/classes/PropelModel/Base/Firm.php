<?php

namespace PropelModel\Base;

use \Exception;
use \PDO;
use PropelModel\AdvServerOrders as ChildAdvServerOrders;
use PropelModel\AdvServerOrdersQuery as ChildAdvServerOrdersQuery;
use PropelModel\Child as ChildChild;
use PropelModel\ChildQuery as ChildChildQuery;
use PropelModel\Comment as ChildComment;
use PropelModel\CommentQuery as ChildCommentQuery;
use PropelModel\Contact as ChildContact;
use PropelModel\ContactQuery as ChildContactQuery;
use PropelModel\District as ChildDistrict;
use PropelModel\DistrictQuery as ChildDistrictQuery;
use PropelModel\Firm as ChildFirm;
use PropelModel\FirmGroup as ChildFirmGroup;
use PropelModel\FirmGroupQuery as ChildFirmGroupQuery;
use PropelModel\FirmPhotos as ChildFirmPhotos;
use PropelModel\FirmPhotosQuery as ChildFirmPhotosQuery;
use PropelModel\FirmQuery as ChildFirmQuery;
use PropelModel\FirmTags as ChildFirmTags;
use PropelModel\FirmTagsQuery as ChildFirmTagsQuery;
use PropelModel\FirmUp as ChildFirmUp;
use PropelModel\FirmUpQuery as ChildFirmUpQuery;
use PropelModel\FirmUser as ChildFirmUser;
use PropelModel\FirmUserQuery as ChildFirmUserQuery;
use PropelModel\Group as ChildGroup;
use PropelModel\GroupQuery as ChildGroupQuery;
use PropelModel\LegalInfo as ChildLegalInfo;
use PropelModel\LegalInfoQuery as ChildLegalInfoQuery;
use PropelModel\Region as ChildRegion;
use PropelModel\RegionQuery as ChildRegionQuery;
use PropelModel\User as ChildUser;
use PropelModel\UserQuery as ChildUserQuery;
use PropelModel\Map\FirmTableMap;
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
 * Base class that represents a row from the 'firm' table.
 *
 *
 *
* @package    propel.generator.PropelModel.Base
*/
abstract class Firm implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\PropelModel\\Map\\FirmTableMap';


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
     * The value for the active field.
     * Note: this column has a database default value of: 1
     * @var        int
     */
    protected $active;

    /**
     * The value for the status field.
     * Note: this column has a database default value of: 1
     * @var        int
     */
    protected $status;

    /**
     * The value for the changed field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $changed;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the official_name field.
     * @var        string
     */
    protected $official_name;

    /**
     * The value for the url field.
     * @var        string
     */
    protected $url;

    /**
     * The value for the subtitle field.
     * @var        string
     */
    protected $subtitle;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the postal field.
     * @var        string
     */
    protected $postal;

    /**
     * The value for the district_id field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $district_id;

    /**
     * The value for the address field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $address;

    /**
     * The value for the city_id field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $city_id;

    /**
     * The value for the street field.
     * @var        string
     */
    protected $street;

    /**
     * The value for the home field.
     * @var        string
     */
    protected $home;

    /**
     * The value for the office field.
     * @var        string
     */
    protected $office;

    /**
     * The value for the main_category field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $main_category;

    /**
     * The value for the worktime field.
     * @var        string
     */
    protected $worktime;

    /**
     * The value for the views field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $views;

    /**
     * The value for the created field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $created;

    /**
     * The value for the moderation_time field.
     * @var        int
     */
    protected $moderation_time;

    /**
     * The value for the changed_time field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $changed_time;

    /**
     * The value for the lon field.
     * Note: this column has a database default value of: 0.0
     * @var        double
     */
    protected $lon;

    /**
     * The value for the lat field.
     * Note: this column has a database default value of: 0.0
     * @var        double
     */
    protected $lat;

    /**
     * The value for the random field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $random;

    /**
     * The value for the logo field.
     * @var        string
     */
    protected $logo;

    /**
     * The value for the redirect_id field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $redirect_id;

    /**
     * @var        ChildRegion
     */
    protected $aRegion;

    /**
     * @var        ChildDistrict
     */
    protected $aDistrict;

    /**
     * @var        ChildLegalInfo
     */
    protected $aLegalInfoRelatedById;

    /**
     * @var        ObjectCollection|ChildFirmUp[] Collection to store aggregation of ChildFirmUp objects.
     */
    protected $collFirmUps;
    protected $collFirmUpsPartial;

    /**
     * @var        ObjectCollection|ChildAdvServerOrders[] Collection to store aggregation of ChildAdvServerOrders objects.
     */
    protected $collAdvServerOrderss;
    protected $collAdvServerOrderssPartial;

    /**
     * @var        ObjectCollection|ChildComment[] Collection to store aggregation of ChildComment objects.
     */
    protected $collComments;
    protected $collCommentsPartial;

    /**
     * @var        ObjectCollection|ChildChild[] Collection to store aggregation of ChildChild objects.
     */
    protected $collChildren;
    protected $collChildrenPartial;

    /**
     * @var        ObjectCollection|ChildContact[] Collection to store aggregation of ChildContact objects.
     */
    protected $collContacts;
    protected $collContactsPartial;

    /**
     * @var        ObjectCollection|ChildFirmGroup[] Collection to store aggregation of ChildFirmGroup objects.
     */
    protected $collFirmGroups;
    protected $collFirmGroupsPartial;

    /**
     * @var        ObjectCollection|ChildFirmPhotos[] Collection to store aggregation of ChildFirmPhotos objects.
     */
    protected $collFirmPhotoss;
    protected $collFirmPhotossPartial;

    /**
     * @var        ObjectCollection|ChildFirmTags[] Collection to store aggregation of ChildFirmTags objects.
     */
    protected $collFirmTagss;
    protected $collFirmTagssPartial;

    /**
     * @var        ObjectCollection|ChildFirmUser[] Collection to store aggregation of ChildFirmUser objects.
     */
    protected $collFirmUsers;
    protected $collFirmUsersPartial;

    /**
     * @var        ObjectCollection|ChildLegalInfo[] Collection to store aggregation of ChildLegalInfo objects.
     */
    protected $collLegalInfosRelatedByFirmId;
    protected $collLegalInfosRelatedByFirmIdPartial;

    /**
     * @var        ObjectCollection|ChildGroup[] Cross Collection to store aggregation of ChildGroup objects.
     */
    protected $collGroups;

    /**
     * @var bool
     */
    protected $collGroupsPartial;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildUser combinations.
     */
    protected $combinationCollUserids;

    /**
     * @var bool
     */
    protected $combinationCollUseridsPartial;

    /**
     * @var        ObjectCollection|ChildUser[] Cross Collection to store aggregation of ChildUser objects.
     */
    protected $collUsers;

    /**
     * @var bool
     */
    protected $collUsersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGroup[]
     */
    protected $groupsScheduledForDeletion = null;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildUser combinations.
     */
    protected $combinationCollUseridsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFirmUp[]
     */
    protected $firmUpsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAdvServerOrders[]
     */
    protected $advServerOrderssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildComment[]
     */
    protected $commentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildChild[]
     */
    protected $childrenScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildContact[]
     */
    protected $contactsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFirmGroup[]
     */
    protected $firmGroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFirmPhotos[]
     */
    protected $firmPhotossScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFirmTags[]
     */
    protected $firmTagssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFirmUser[]
     */
    protected $firmUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLegalInfo[]
     */
    protected $legalInfosRelatedByFirmIdScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->active = 1;
        $this->status = 1;
        $this->changed = 0;
        $this->district_id = 0;
        $this->address = '';
        $this->city_id = 0;
        $this->main_category = 0;
        $this->views = 0;
        $this->created = 0;
        $this->changed_time = 0;
        $this->lon = 0.0;
        $this->lat = 0.0;
        $this->random = 0;
        $this->redirect_id = 0;
    }

    /**
     * Initializes internal state of PropelModel\Base\Firm object.
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
     * Compares this with another <code>Firm</code> instance.  If
     * <code>obj</code> is an instance of <code>Firm</code>, delegates to
     * <code>equals(Firm)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Firm The current object, for fluid interface
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
     * Get the [active] column value.
     *
     * @return int
     */
    public function getActive()
    {
        return $this->active;
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
     * Get the [changed] column value.
     *
     * @return int
     */
    public function getChanged()
    {
        return $this->changed;
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
     * Get the [official_name] column value.
     *
     * @return string
     */
    public function getOfficialName()
    {
        return $this->official_name;
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
     * Get the [subtitle] column value.
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * Get the [district_id] column value.
     *
     * @return int
     */
    public function getDistrictId()
    {
        return $this->district_id;
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
     * Get the [city_id] column value.
     *
     * @return int
     */
    public function getCityId()
    {
        return $this->city_id;
    }

    /**
     * Get the [street] column value.
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Get the [home] column value.
     *
     * @return string
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * Get the [office] column value.
     *
     * @return string
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * Get the [main_category] column value.
     *
     * @return int
     */
    public function getMainCategory()
    {
        return $this->main_category;
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
     * Get the [views] column value.
     *
     * @return int
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Get the [created] column value.
     *
     * @return int
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get the [moderation_time] column value.
     *
     * @return int
     */
    public function getModerationTime()
    {
        return $this->moderation_time;
    }

    /**
     * Get the [changed_time] column value.
     *
     * @return int
     */
    public function getChangedTime()
    {
        return $this->changed_time;
    }

    /**
     * Get the [lon] column value.
     *
     * @return double
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * Get the [lat] column value.
     *
     * @return double
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Get the [random] column value.
     *
     * @return int
     */
    public function getRandom()
    {
        return $this->random;
    }

    /**
     * Get the [logo] column value.
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Get the [redirect_id] column value.
     *
     * @return int
     */
    public function getRedirectID()
    {
        return $this->redirect_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[FirmTableMap::COL_ID] = true;
        }

        if ($this->aLegalInfoRelatedById !== null && $this->aLegalInfoRelatedById->getFirmId() !== $v) {
            $this->aLegalInfoRelatedById = null;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [active] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setActive($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->active !== $v) {
            $this->active = $v;
            $this->modifiedColumns[FirmTableMap::COL_ACTIVE] = true;
        }

        return $this;
    } // setActive()

    /**
     * Set the value of [status] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[FirmTableMap::COL_STATUS] = true;
        }

        return $this;
    } // setStatus()

    /**
     * Set the value of [changed] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setChanged($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->changed !== $v) {
            $this->changed = $v;
            $this->modifiedColumns[FirmTableMap::COL_CHANGED] = true;
        }

        return $this;
    } // setChanged()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[FirmTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [official_name] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setOfficialName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->official_name !== $v) {
            $this->official_name = $v;
            $this->modifiedColumns[FirmTableMap::COL_OFFICIAL_NAME] = true;
        }

        return $this;
    } // setOfficialName()

    /**
     * Set the value of [url] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->url !== $v) {
            $this->url = $v;
            $this->modifiedColumns[FirmTableMap::COL_URL] = true;
        }

        return $this;
    } // setUrl()

    /**
     * Set the value of [subtitle] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setSubtitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->subtitle !== $v) {
            $this->subtitle = $v;
            $this->modifiedColumns[FirmTableMap::COL_SUBTITLE] = true;
        }

        return $this;
    } // setSubtitle()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[FirmTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [postal] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setPostal($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->postal !== $v) {
            $this->postal = $v;
            $this->modifiedColumns[FirmTableMap::COL_POSTAL] = true;
        }

        return $this;
    } // setPostal()

    /**
     * Set the value of [district_id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setDistrictId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->district_id !== $v) {
            $this->district_id = $v;
            $this->modifiedColumns[FirmTableMap::COL_DISTRICT_ID] = true;
        }

        if ($this->aDistrict !== null && $this->aDistrict->getId() !== $v) {
            $this->aDistrict = null;
        }

        return $this;
    } // setDistrictId()

    /**
     * Set the value of [address] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address !== $v) {
            $this->address = $v;
            $this->modifiedColumns[FirmTableMap::COL_ADDRESS] = true;
        }

        return $this;
    } // setAddress()

    /**
     * Set the value of [city_id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setCityId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->city_id !== $v) {
            $this->city_id = $v;
            $this->modifiedColumns[FirmTableMap::COL_CITY_ID] = true;
        }

        if ($this->aRegion !== null && $this->aRegion->getId() !== $v) {
            $this->aRegion = null;
        }

        return $this;
    } // setCityId()

    /**
     * Set the value of [street] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setStreet($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->street !== $v) {
            $this->street = $v;
            $this->modifiedColumns[FirmTableMap::COL_STREET] = true;
        }

        return $this;
    } // setStreet()

    /**
     * Set the value of [home] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setHome($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->home !== $v) {
            $this->home = $v;
            $this->modifiedColumns[FirmTableMap::COL_HOME] = true;
        }

        return $this;
    } // setHome()

    /**
     * Set the value of [office] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setOffice($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->office !== $v) {
            $this->office = $v;
            $this->modifiedColumns[FirmTableMap::COL_OFFICE] = true;
        }

        return $this;
    } // setOffice()

    /**
     * Set the value of [main_category] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setMainCategory($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->main_category !== $v) {
            $this->main_category = $v;
            $this->modifiedColumns[FirmTableMap::COL_MAIN_CATEGORY] = true;
        }

        return $this;
    } // setMainCategory()

    /**
     * Set the value of [worktime] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setWorktime($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->worktime !== $v) {
            $this->worktime = $v;
            $this->modifiedColumns[FirmTableMap::COL_WORKTIME] = true;
        }

        return $this;
    } // setWorktime()

    /**
     * Set the value of [views] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setViews($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->views !== $v) {
            $this->views = $v;
            $this->modifiedColumns[FirmTableMap::COL_VIEWS] = true;
        }

        return $this;
    } // setViews()

    /**
     * Set the value of [created] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setCreated($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->created !== $v) {
            $this->created = $v;
            $this->modifiedColumns[FirmTableMap::COL_CREATED] = true;
        }

        return $this;
    } // setCreated()

    /**
     * Set the value of [moderation_time] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setModerationTime($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->moderation_time !== $v) {
            $this->moderation_time = $v;
            $this->modifiedColumns[FirmTableMap::COL_MODERATION_TIME] = true;
        }

        return $this;
    } // setModerationTime()

    /**
     * Set the value of [changed_time] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setChangedTime($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->changed_time !== $v) {
            $this->changed_time = $v;
            $this->modifiedColumns[FirmTableMap::COL_CHANGED_TIME] = true;
        }

        return $this;
    } // setChangedTime()

    /**
     * Set the value of [lon] column.
     *
     * @param double $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setLon($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->lon !== $v) {
            $this->lon = $v;
            $this->modifiedColumns[FirmTableMap::COL_LON] = true;
        }

        return $this;
    } // setLon()

    /**
     * Set the value of [lat] column.
     *
     * @param double $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setLat($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->lat !== $v) {
            $this->lat = $v;
            $this->modifiedColumns[FirmTableMap::COL_LAT] = true;
        }

        return $this;
    } // setLat()

    /**
     * Set the value of [random] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setRandom($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->random !== $v) {
            $this->random = $v;
            $this->modifiedColumns[FirmTableMap::COL_RANDOM] = true;
        }

        return $this;
    } // setRandom()

    /**
     * Set the value of [logo] column.
     *
     * @param string $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setLogo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->logo !== $v) {
            $this->logo = $v;
            $this->modifiedColumns[FirmTableMap::COL_LOGO] = true;
        }

        return $this;
    } // setLogo()

    /**
     * Set the value of [redirect_id] column.
     *
     * @param int $v new value
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function setRedirectID($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->redirect_id !== $v) {
            $this->redirect_id = $v;
            $this->modifiedColumns[FirmTableMap::COL_REDIRECT_ID] = true;
        }

        return $this;
    } // setRedirectID()

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
            if ($this->active !== 1) {
                return false;
            }

            if ($this->status !== 1) {
                return false;
            }

            if ($this->changed !== 0) {
                return false;
            }

            if ($this->district_id !== 0) {
                return false;
            }

            if ($this->address !== '') {
                return false;
            }

            if ($this->city_id !== 0) {
                return false;
            }

            if ($this->main_category !== 0) {
                return false;
            }

            if ($this->views !== 0) {
                return false;
            }

            if ($this->created !== 0) {
                return false;
            }

            if ($this->changed_time !== 0) {
                return false;
            }

            if ($this->lon !== 0.0) {
                return false;
            }

            if ($this->lat !== 0.0) {
                return false;
            }

            if ($this->random !== 0) {
                return false;
            }

            if ($this->redirect_id !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : FirmTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : FirmTableMap::translateFieldName('Active', TableMap::TYPE_PHPNAME, $indexType)];
            $this->active = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : FirmTableMap::translateFieldName('Status', TableMap::TYPE_PHPNAME, $indexType)];
            $this->status = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : FirmTableMap::translateFieldName('Changed', TableMap::TYPE_PHPNAME, $indexType)];
            $this->changed = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : FirmTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : FirmTableMap::translateFieldName('OfficialName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->official_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : FirmTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : FirmTableMap::translateFieldName('Subtitle', TableMap::TYPE_PHPNAME, $indexType)];
            $this->subtitle = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : FirmTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : FirmTableMap::translateFieldName('Postal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->postal = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : FirmTableMap::translateFieldName('DistrictId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->district_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : FirmTableMap::translateFieldName('Address', TableMap::TYPE_PHPNAME, $indexType)];
            $this->address = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : FirmTableMap::translateFieldName('CityId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->city_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : FirmTableMap::translateFieldName('Street', TableMap::TYPE_PHPNAME, $indexType)];
            $this->street = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : FirmTableMap::translateFieldName('Home', TableMap::TYPE_PHPNAME, $indexType)];
            $this->home = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : FirmTableMap::translateFieldName('Office', TableMap::TYPE_PHPNAME, $indexType)];
            $this->office = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : FirmTableMap::translateFieldName('MainCategory', TableMap::TYPE_PHPNAME, $indexType)];
            $this->main_category = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : FirmTableMap::translateFieldName('Worktime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->worktime = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : FirmTableMap::translateFieldName('Views', TableMap::TYPE_PHPNAME, $indexType)];
            $this->views = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : FirmTableMap::translateFieldName('Created', TableMap::TYPE_PHPNAME, $indexType)];
            $this->created = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : FirmTableMap::translateFieldName('ModerationTime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->moderation_time = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : FirmTableMap::translateFieldName('ChangedTime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->changed_time = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : FirmTableMap::translateFieldName('Lon', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lon = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 23 + $startcol : FirmTableMap::translateFieldName('Lat', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lat = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 24 + $startcol : FirmTableMap::translateFieldName('Random', TableMap::TYPE_PHPNAME, $indexType)];
            $this->random = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 25 + $startcol : FirmTableMap::translateFieldName('Logo', TableMap::TYPE_PHPNAME, $indexType)];
            $this->logo = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 26 + $startcol : FirmTableMap::translateFieldName('RedirectID', TableMap::TYPE_PHPNAME, $indexType)];
            $this->redirect_id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 27; // 27 = FirmTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\PropelModel\\Firm'), 0, $e);
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
        if ($this->aLegalInfoRelatedById !== null && $this->id !== $this->aLegalInfoRelatedById->getFirmId()) {
            $this->aLegalInfoRelatedById = null;
        }
        if ($this->aDistrict !== null && $this->district_id !== $this->aDistrict->getId()) {
            $this->aDistrict = null;
        }
        if ($this->aRegion !== null && $this->city_id !== $this->aRegion->getId()) {
            $this->aRegion = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(FirmTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildFirmQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aRegion = null;
            $this->aDistrict = null;
            $this->aLegalInfoRelatedById = null;
            $this->collFirmUps = null;

            $this->collAdvServerOrderss = null;

            $this->collComments = null;

            $this->collChildren = null;

            $this->collContacts = null;

            $this->collFirmGroups = null;

            $this->collFirmPhotoss = null;

            $this->collFirmTagss = null;

            $this->collFirmUsers = null;

            $this->collLegalInfosRelatedByFirmId = null;

            $this->collGroups = null;
            $this->collUserids = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Firm::setDeleted()
     * @see Firm::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(FirmTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildFirmQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(FirmTableMap::DATABASE_NAME);
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
                FirmTableMap::addInstanceToPool($this);
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

            if ($this->aRegion !== null) {
                if ($this->aRegion->isModified() || $this->aRegion->isNew()) {
                    $affectedRows += $this->aRegion->save($con);
                }
                $this->setRegion($this->aRegion);
            }

            if ($this->aDistrict !== null) {
                if ($this->aDistrict->isModified() || $this->aDistrict->isNew()) {
                    $affectedRows += $this->aDistrict->save($con);
                }
                $this->setDistrict($this->aDistrict);
            }

            if ($this->aLegalInfoRelatedById !== null) {
                if ($this->aLegalInfoRelatedById->isModified() || $this->aLegalInfoRelatedById->isNew()) {
                    $affectedRows += $this->aLegalInfoRelatedById->save($con);
                }
                $this->setLegalInfoRelatedById($this->aLegalInfoRelatedById);
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

            if ($this->groupsScheduledForDeletion !== null) {
                if (!$this->groupsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->groupsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \PropelModel\FirmGroupQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->groupsScheduledForDeletion = null;
                }

            }

            if ($this->collGroups) {
                foreach ($this->collGroups as $group) {
                    if (!$group->isDeleted() && ($group->isNew() || $group->isModified())) {
                        $group->save($con);
                    }
                }
            }


            if ($this->combinationCollUseridsScheduledForDeletion !== null) {
                if (!$this->combinationCollUseridsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->combinationCollUseridsScheduledForDeletion as $combination) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[2] = $combination[0]->getId();
                        //$combination[1] = id;
                        $entryPk[0] = $combination[1];

                        $pks[] = $entryPk;
                    }

                    \PropelModel\FirmUserQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->combinationCollUseridsScheduledForDeletion = null;
                }

            }

            if (null !== $this->combinationCollUserids) {
                foreach ($this->combinationCollUserids as $combination) {

                    //$combination[0] = User (firm_user_fk_29554a)
                    if (!$combination[0]->isDeleted() && ($combination[0]->isNew() || $combination[0]->isModified())) {
                        $combination[0]->save($con);
                    }

                    //$combination[1] = id; Nothing to save.
                }
            }


            if ($this->firmUpsScheduledForDeletion !== null) {
                if (!$this->firmUpsScheduledForDeletion->isEmpty()) {
                    foreach ($this->firmUpsScheduledForDeletion as $firmUp) {
                        // need to save related object because we set the relation to null
                        $firmUp->save($con);
                    }
                    $this->firmUpsScheduledForDeletion = null;
                }
            }

            if ($this->collFirmUps !== null) {
                foreach ($this->collFirmUps as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->advServerOrderssScheduledForDeletion !== null) {
                if (!$this->advServerOrderssScheduledForDeletion->isEmpty()) {
                    \PropelModel\AdvServerOrdersQuery::create()
                        ->filterByPrimaryKeys($this->advServerOrderssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->advServerOrderssScheduledForDeletion = null;
                }
            }

            if ($this->collAdvServerOrderss !== null) {
                foreach ($this->collAdvServerOrderss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->commentsScheduledForDeletion !== null) {
                if (!$this->commentsScheduledForDeletion->isEmpty()) {
                    \PropelModel\CommentQuery::create()
                        ->filterByPrimaryKeys($this->commentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->commentsScheduledForDeletion = null;
                }
            }

            if ($this->collComments !== null) {
                foreach ($this->collComments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->childrenScheduledForDeletion !== null) {
                if (!$this->childrenScheduledForDeletion->isEmpty()) {
                    \PropelModel\ChildQuery::create()
                        ->filterByPrimaryKeys($this->childrenScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->childrenScheduledForDeletion = null;
                }
            }

            if ($this->collChildren !== null) {
                foreach ($this->collChildren as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->contactsScheduledForDeletion !== null) {
                if (!$this->contactsScheduledForDeletion->isEmpty()) {
                    \PropelModel\ContactQuery::create()
                        ->filterByPrimaryKeys($this->contactsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->contactsScheduledForDeletion = null;
                }
            }

            if ($this->collContacts !== null) {
                foreach ($this->collContacts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
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

            if ($this->firmPhotossScheduledForDeletion !== null) {
                if (!$this->firmPhotossScheduledForDeletion->isEmpty()) {
                    \PropelModel\FirmPhotosQuery::create()
                        ->filterByPrimaryKeys($this->firmPhotossScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->firmPhotossScheduledForDeletion = null;
                }
            }

            if ($this->collFirmPhotoss !== null) {
                foreach ($this->collFirmPhotoss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->firmTagssScheduledForDeletion !== null) {
                if (!$this->firmTagssScheduledForDeletion->isEmpty()) {
                    \PropelModel\FirmTagsQuery::create()
                        ->filterByPrimaryKeys($this->firmTagssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->firmTagssScheduledForDeletion = null;
                }
            }

            if ($this->collFirmTagss !== null) {
                foreach ($this->collFirmTagss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
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

            if ($this->legalInfosRelatedByFirmIdScheduledForDeletion !== null) {
                if (!$this->legalInfosRelatedByFirmIdScheduledForDeletion->isEmpty()) {
                    \PropelModel\LegalInfoQuery::create()
                        ->filterByPrimaryKeys($this->legalInfosRelatedByFirmIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->legalInfosRelatedByFirmIdScheduledForDeletion = null;
                }
            }

            if ($this->collLegalInfosRelatedByFirmId !== null) {
                foreach ($this->collLegalInfosRelatedByFirmId as $referrerFK) {
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

        $this->modifiedColumns[FirmTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . FirmTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(FirmTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(FirmTableMap::COL_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = 'active';
        }
        if ($this->isColumnModified(FirmTableMap::COL_STATUS)) {
            $modifiedColumns[':p' . $index++]  = 'status';
        }
        if ($this->isColumnModified(FirmTableMap::COL_CHANGED)) {
            $modifiedColumns[':p' . $index++]  = 'changed';
        }
        if ($this->isColumnModified(FirmTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(FirmTableMap::COL_OFFICIAL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'official_name';
        }
        if ($this->isColumnModified(FirmTableMap::COL_URL)) {
            $modifiedColumns[':p' . $index++]  = 'url';
        }
        if ($this->isColumnModified(FirmTableMap::COL_SUBTITLE)) {
            $modifiedColumns[':p' . $index++]  = 'subtitle';
        }
        if ($this->isColumnModified(FirmTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'description';
        }
        if ($this->isColumnModified(FirmTableMap::COL_POSTAL)) {
            $modifiedColumns[':p' . $index++]  = 'postal';
        }
        if ($this->isColumnModified(FirmTableMap::COL_DISTRICT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'district_id';
        }
        if ($this->isColumnModified(FirmTableMap::COL_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = 'address';
        }
        if ($this->isColumnModified(FirmTableMap::COL_CITY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'city_id';
        }
        if ($this->isColumnModified(FirmTableMap::COL_STREET)) {
            $modifiedColumns[':p' . $index++]  = 'street';
        }
        if ($this->isColumnModified(FirmTableMap::COL_HOME)) {
            $modifiedColumns[':p' . $index++]  = 'home';
        }
        if ($this->isColumnModified(FirmTableMap::COL_OFFICE)) {
            $modifiedColumns[':p' . $index++]  = 'office';
        }
        if ($this->isColumnModified(FirmTableMap::COL_MAIN_CATEGORY)) {
            $modifiedColumns[':p' . $index++]  = 'main_category';
        }
        if ($this->isColumnModified(FirmTableMap::COL_WORKTIME)) {
            $modifiedColumns[':p' . $index++]  = 'worktime';
        }
        if ($this->isColumnModified(FirmTableMap::COL_VIEWS)) {
            $modifiedColumns[':p' . $index++]  = 'views';
        }
        if ($this->isColumnModified(FirmTableMap::COL_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'created';
        }
        if ($this->isColumnModified(FirmTableMap::COL_MODERATION_TIME)) {
            $modifiedColumns[':p' . $index++]  = 'moderation_time';
        }
        if ($this->isColumnModified(FirmTableMap::COL_CHANGED_TIME)) {
            $modifiedColumns[':p' . $index++]  = 'changed_time';
        }
        if ($this->isColumnModified(FirmTableMap::COL_LON)) {
            $modifiedColumns[':p' . $index++]  = 'lon';
        }
        if ($this->isColumnModified(FirmTableMap::COL_LAT)) {
            $modifiedColumns[':p' . $index++]  = 'lat';
        }
        if ($this->isColumnModified(FirmTableMap::COL_RANDOM)) {
            $modifiedColumns[':p' . $index++]  = 'random';
        }
        if ($this->isColumnModified(FirmTableMap::COL_LOGO)) {
            $modifiedColumns[':p' . $index++]  = 'logo';
        }
        if ($this->isColumnModified(FirmTableMap::COL_REDIRECT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'redirect_id';
        }

        $sql = sprintf(
            'INSERT INTO firm (%s) VALUES (%s)',
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
                    case 'active':
                        $stmt->bindValue($identifier, $this->active, PDO::PARAM_INT);
                        break;
                    case 'status':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case 'changed':
                        $stmt->bindValue($identifier, $this->changed, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'official_name':
                        $stmt->bindValue($identifier, $this->official_name, PDO::PARAM_STR);
                        break;
                    case 'url':
                        $stmt->bindValue($identifier, $this->url, PDO::PARAM_STR);
                        break;
                    case 'subtitle':
                        $stmt->bindValue($identifier, $this->subtitle, PDO::PARAM_STR);
                        break;
                    case 'description':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case 'postal':
                        $stmt->bindValue($identifier, $this->postal, PDO::PARAM_STR);
                        break;
                    case 'district_id':
                        $stmt->bindValue($identifier, $this->district_id, PDO::PARAM_INT);
                        break;
                    case 'address':
                        $stmt->bindValue($identifier, $this->address, PDO::PARAM_STR);
                        break;
                    case 'city_id':
                        $stmt->bindValue($identifier, $this->city_id, PDO::PARAM_INT);
                        break;
                    case 'street':
                        $stmt->bindValue($identifier, $this->street, PDO::PARAM_STR);
                        break;
                    case 'home':
                        $stmt->bindValue($identifier, $this->home, PDO::PARAM_STR);
                        break;
                    case 'office':
                        $stmt->bindValue($identifier, $this->office, PDO::PARAM_STR);
                        break;
                    case 'main_category':
                        $stmt->bindValue($identifier, $this->main_category, PDO::PARAM_INT);
                        break;
                    case 'worktime':
                        $stmt->bindValue($identifier, $this->worktime, PDO::PARAM_STR);
                        break;
                    case 'views':
                        $stmt->bindValue($identifier, $this->views, PDO::PARAM_INT);
                        break;
                    case 'created':
                        $stmt->bindValue($identifier, $this->created, PDO::PARAM_INT);
                        break;
                    case 'moderation_time':
                        $stmt->bindValue($identifier, $this->moderation_time, PDO::PARAM_INT);
                        break;
                    case 'changed_time':
                        $stmt->bindValue($identifier, $this->changed_time, PDO::PARAM_INT);
                        break;
                    case 'lon':
                        $stmt->bindValue($identifier, $this->lon, PDO::PARAM_STR);
                        break;
                    case 'lat':
                        $stmt->bindValue($identifier, $this->lat, PDO::PARAM_STR);
                        break;
                    case 'random':
                        $stmt->bindValue($identifier, $this->random, PDO::PARAM_INT);
                        break;
                    case 'logo':
                        $stmt->bindValue($identifier, $this->logo, PDO::PARAM_STR);
                        break;
                    case 'redirect_id':
                        $stmt->bindValue($identifier, $this->redirect_id, PDO::PARAM_INT);
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
        $pos = FirmTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getActive();
                break;
            case 2:
                return $this->getStatus();
                break;
            case 3:
                return $this->getChanged();
                break;
            case 4:
                return $this->getName();
                break;
            case 5:
                return $this->getOfficialName();
                break;
            case 6:
                return $this->getUrl();
                break;
            case 7:
                return $this->getSubtitle();
                break;
            case 8:
                return $this->getDescription();
                break;
            case 9:
                return $this->getPostal();
                break;
            case 10:
                return $this->getDistrictId();
                break;
            case 11:
                return $this->getAddress();
                break;
            case 12:
                return $this->getCityId();
                break;
            case 13:
                return $this->getStreet();
                break;
            case 14:
                return $this->getHome();
                break;
            case 15:
                return $this->getOffice();
                break;
            case 16:
                return $this->getMainCategory();
                break;
            case 17:
                return $this->getWorktime();
                break;
            case 18:
                return $this->getViews();
                break;
            case 19:
                return $this->getCreated();
                break;
            case 20:
                return $this->getModerationTime();
                break;
            case 21:
                return $this->getChangedTime();
                break;
            case 22:
                return $this->getLon();
                break;
            case 23:
                return $this->getLat();
                break;
            case 24:
                return $this->getRandom();
                break;
            case 25:
                return $this->getLogo();
                break;
            case 26:
                return $this->getRedirectID();
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

        if (isset($alreadyDumpedObjects['Firm'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Firm'][$this->hashCode()] = true;
        $keys = FirmTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getActive(),
            $keys[2] => $this->getStatus(),
            $keys[3] => $this->getChanged(),
            $keys[4] => $this->getName(),
            $keys[5] => $this->getOfficialName(),
            $keys[6] => $this->getUrl(),
            $keys[7] => $this->getSubtitle(),
            $keys[8] => $this->getDescription(),
            $keys[9] => $this->getPostal(),
            $keys[10] => $this->getDistrictId(),
            $keys[11] => $this->getAddress(),
            $keys[12] => $this->getCityId(),
            $keys[13] => $this->getStreet(),
            $keys[14] => $this->getHome(),
            $keys[15] => $this->getOffice(),
            $keys[16] => $this->getMainCategory(),
            $keys[17] => $this->getWorktime(),
            $keys[18] => $this->getViews(),
            $keys[19] => $this->getCreated(),
            $keys[20] => $this->getModerationTime(),
            $keys[21] => $this->getChangedTime(),
            $keys[22] => $this->getLon(),
            $keys[23] => $this->getLat(),
            $keys[24] => $this->getRandom(),
            $keys[25] => $this->getLogo(),
            $keys[26] => $this->getRedirectID(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aRegion) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'region';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'region';
                        break;
                    default:
                        $key = 'Region';
                }

                $result[$key] = $this->aRegion->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDistrict) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'district';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'district';
                        break;
                    default:
                        $key = 'District';
                }

                $result[$key] = $this->aDistrict->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aLegalInfoRelatedById) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'legalInfo';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'jur_data';
                        break;
                    default:
                        $key = 'LegalInfo';
                }

                $result[$key] = $this->aLegalInfoRelatedById->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collFirmUps) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'firmUps';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'firm_ups';
                        break;
                    default:
                        $key = 'FirmUps';
                }

                $result[$key] = $this->collFirmUps->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAdvServerOrderss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'advServerOrderss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'adv_server_orderss';
                        break;
                    default:
                        $key = 'AdvServerOrderss';
                }

                $result[$key] = $this->collAdvServerOrderss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collComments) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'comments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'comments';
                        break;
                    default:
                        $key = 'Comments';
                }

                $result[$key] = $this->collComments->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collChildren) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'children';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'firm_childss';
                        break;
                    default:
                        $key = 'Children';
                }

                $result[$key] = $this->collChildren->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collContacts) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'contacts';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'firm_contactss';
                        break;
                    default:
                        $key = 'Contacts';
                }

                $result[$key] = $this->collContacts->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
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
            if (null !== $this->collFirmPhotoss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'firmPhotoss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'firm_photoss';
                        break;
                    default:
                        $key = 'FirmPhotoss';
                }

                $result[$key] = $this->collFirmPhotoss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFirmTagss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'firmTagss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'firm_tagss';
                        break;
                    default:
                        $key = 'FirmTagss';
                }

                $result[$key] = $this->collFirmTagss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
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
            if (null !== $this->collLegalInfosRelatedByFirmId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'legalInfos';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'jur_datas';
                        break;
                    default:
                        $key = 'LegalInfos';
                }

                $result[$key] = $this->collLegalInfosRelatedByFirmId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\PropelModel\Firm
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = FirmTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\PropelModel\Firm
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setActive($value);
                break;
            case 2:
                $this->setStatus($value);
                break;
            case 3:
                $this->setChanged($value);
                break;
            case 4:
                $this->setName($value);
                break;
            case 5:
                $this->setOfficialName($value);
                break;
            case 6:
                $this->setUrl($value);
                break;
            case 7:
                $this->setSubtitle($value);
                break;
            case 8:
                $this->setDescription($value);
                break;
            case 9:
                $this->setPostal($value);
                break;
            case 10:
                $this->setDistrictId($value);
                break;
            case 11:
                $this->setAddress($value);
                break;
            case 12:
                $this->setCityId($value);
                break;
            case 13:
                $this->setStreet($value);
                break;
            case 14:
                $this->setHome($value);
                break;
            case 15:
                $this->setOffice($value);
                break;
            case 16:
                $this->setMainCategory($value);
                break;
            case 17:
                $this->setWorktime($value);
                break;
            case 18:
                $this->setViews($value);
                break;
            case 19:
                $this->setCreated($value);
                break;
            case 20:
                $this->setModerationTime($value);
                break;
            case 21:
                $this->setChangedTime($value);
                break;
            case 22:
                $this->setLon($value);
                break;
            case 23:
                $this->setLat($value);
                break;
            case 24:
                $this->setRandom($value);
                break;
            case 25:
                $this->setLogo($value);
                break;
            case 26:
                $this->setRedirectID($value);
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
        $keys = FirmTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setActive($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setStatus($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setChanged($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setName($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setOfficialName($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setUrl($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setSubtitle($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setDescription($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setPostal($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setDistrictId($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setAddress($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setCityId($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setStreet($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setHome($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setOffice($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setMainCategory($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setWorktime($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setViews($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setCreated($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setModerationTime($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setChangedTime($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setLon($arr[$keys[22]]);
        }
        if (array_key_exists($keys[23], $arr)) {
            $this->setLat($arr[$keys[23]]);
        }
        if (array_key_exists($keys[24], $arr)) {
            $this->setRandom($arr[$keys[24]]);
        }
        if (array_key_exists($keys[25], $arr)) {
            $this->setLogo($arr[$keys[25]]);
        }
        if (array_key_exists($keys[26], $arr)) {
            $this->setRedirectID($arr[$keys[26]]);
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
     * @return $this|\PropelModel\Firm The current object, for fluid interface
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
        $criteria = new Criteria(FirmTableMap::DATABASE_NAME);

        if ($this->isColumnModified(FirmTableMap::COL_ID)) {
            $criteria->add(FirmTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(FirmTableMap::COL_ACTIVE)) {
            $criteria->add(FirmTableMap::COL_ACTIVE, $this->active);
        }
        if ($this->isColumnModified(FirmTableMap::COL_STATUS)) {
            $criteria->add(FirmTableMap::COL_STATUS, $this->status);
        }
        if ($this->isColumnModified(FirmTableMap::COL_CHANGED)) {
            $criteria->add(FirmTableMap::COL_CHANGED, $this->changed);
        }
        if ($this->isColumnModified(FirmTableMap::COL_NAME)) {
            $criteria->add(FirmTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(FirmTableMap::COL_OFFICIAL_NAME)) {
            $criteria->add(FirmTableMap::COL_OFFICIAL_NAME, $this->official_name);
        }
        if ($this->isColumnModified(FirmTableMap::COL_URL)) {
            $criteria->add(FirmTableMap::COL_URL, $this->url);
        }
        if ($this->isColumnModified(FirmTableMap::COL_SUBTITLE)) {
            $criteria->add(FirmTableMap::COL_SUBTITLE, $this->subtitle);
        }
        if ($this->isColumnModified(FirmTableMap::COL_DESCRIPTION)) {
            $criteria->add(FirmTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(FirmTableMap::COL_POSTAL)) {
            $criteria->add(FirmTableMap::COL_POSTAL, $this->postal);
        }
        if ($this->isColumnModified(FirmTableMap::COL_DISTRICT_ID)) {
            $criteria->add(FirmTableMap::COL_DISTRICT_ID, $this->district_id);
        }
        if ($this->isColumnModified(FirmTableMap::COL_ADDRESS)) {
            $criteria->add(FirmTableMap::COL_ADDRESS, $this->address);
        }
        if ($this->isColumnModified(FirmTableMap::COL_CITY_ID)) {
            $criteria->add(FirmTableMap::COL_CITY_ID, $this->city_id);
        }
        if ($this->isColumnModified(FirmTableMap::COL_STREET)) {
            $criteria->add(FirmTableMap::COL_STREET, $this->street);
        }
        if ($this->isColumnModified(FirmTableMap::COL_HOME)) {
            $criteria->add(FirmTableMap::COL_HOME, $this->home);
        }
        if ($this->isColumnModified(FirmTableMap::COL_OFFICE)) {
            $criteria->add(FirmTableMap::COL_OFFICE, $this->office);
        }
        if ($this->isColumnModified(FirmTableMap::COL_MAIN_CATEGORY)) {
            $criteria->add(FirmTableMap::COL_MAIN_CATEGORY, $this->main_category);
        }
        if ($this->isColumnModified(FirmTableMap::COL_WORKTIME)) {
            $criteria->add(FirmTableMap::COL_WORKTIME, $this->worktime);
        }
        if ($this->isColumnModified(FirmTableMap::COL_VIEWS)) {
            $criteria->add(FirmTableMap::COL_VIEWS, $this->views);
        }
        if ($this->isColumnModified(FirmTableMap::COL_CREATED)) {
            $criteria->add(FirmTableMap::COL_CREATED, $this->created);
        }
        if ($this->isColumnModified(FirmTableMap::COL_MODERATION_TIME)) {
            $criteria->add(FirmTableMap::COL_MODERATION_TIME, $this->moderation_time);
        }
        if ($this->isColumnModified(FirmTableMap::COL_CHANGED_TIME)) {
            $criteria->add(FirmTableMap::COL_CHANGED_TIME, $this->changed_time);
        }
        if ($this->isColumnModified(FirmTableMap::COL_LON)) {
            $criteria->add(FirmTableMap::COL_LON, $this->lon);
        }
        if ($this->isColumnModified(FirmTableMap::COL_LAT)) {
            $criteria->add(FirmTableMap::COL_LAT, $this->lat);
        }
        if ($this->isColumnModified(FirmTableMap::COL_RANDOM)) {
            $criteria->add(FirmTableMap::COL_RANDOM, $this->random);
        }
        if ($this->isColumnModified(FirmTableMap::COL_LOGO)) {
            $criteria->add(FirmTableMap::COL_LOGO, $this->logo);
        }
        if ($this->isColumnModified(FirmTableMap::COL_REDIRECT_ID)) {
            $criteria->add(FirmTableMap::COL_REDIRECT_ID, $this->redirect_id);
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
        $criteria = ChildFirmQuery::create();
        $criteria->add(FirmTableMap::COL_ID, $this->id);

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

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation firm_fk_6ce5fb to table jur_data
        if ($this->aLegalInfoRelatedById && $hash = spl_object_hash($this->aLegalInfoRelatedById)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

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
     * @param      object $copyObj An object of \PropelModel\Firm (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setActive($this->getActive());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setChanged($this->getChanged());
        $copyObj->setName($this->getName());
        $copyObj->setOfficialName($this->getOfficialName());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setSubtitle($this->getSubtitle());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setPostal($this->getPostal());
        $copyObj->setDistrictId($this->getDistrictId());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setCityId($this->getCityId());
        $copyObj->setStreet($this->getStreet());
        $copyObj->setHome($this->getHome());
        $copyObj->setOffice($this->getOffice());
        $copyObj->setMainCategory($this->getMainCategory());
        $copyObj->setWorktime($this->getWorktime());
        $copyObj->setViews($this->getViews());
        $copyObj->setCreated($this->getCreated());
        $copyObj->setModerationTime($this->getModerationTime());
        $copyObj->setChangedTime($this->getChangedTime());
        $copyObj->setLon($this->getLon());
        $copyObj->setLat($this->getLat());
        $copyObj->setRandom($this->getRandom());
        $copyObj->setLogo($this->getLogo());
        $copyObj->setRedirectID($this->getRedirectID());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getFirmUps() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFirmUp($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAdvServerOrderss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAdvServerOrders($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addComment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getChildren() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addChild($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getContacts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addContact($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFirmGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFirmGroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFirmPhotoss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFirmPhotos($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFirmTagss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFirmTags($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFirmUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFirmUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLegalInfosRelatedByFirmId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLegalInfoRelatedByFirmId($relObj->copy($deepCopy));
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
     * @return \PropelModel\Firm Clone of current object.
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
     * Declares an association between this object and a ChildRegion object.
     *
     * @param  ChildRegion $v
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     * @throws PropelException
     */
    public function setRegion(ChildRegion $v = null)
    {
        if ($v === null) {
            $this->setCityId(0);
        } else {
            $this->setCityId($v->getId());
        }

        $this->aRegion = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildRegion object, it will not be re-added.
        if ($v !== null) {
            $v->addFirm($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildRegion object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildRegion The associated ChildRegion object.
     * @throws PropelException
     */
    public function getRegion(ConnectionInterface $con = null)
    {
        if ($this->aRegion === null && ($this->city_id !== null)) {
            $this->aRegion = ChildRegionQuery::create()->findPk($this->city_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aRegion->addFirms($this);
             */
        }

        return $this->aRegion;
    }

    /**
     * Declares an association between this object and a ChildDistrict object.
     *
     * @param  ChildDistrict $v
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDistrict(ChildDistrict $v = null)
    {
        if ($v === null) {
            $this->setDistrictId(0);
        } else {
            $this->setDistrictId($v->getId());
        }

        $this->aDistrict = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildDistrict object, it will not be re-added.
        if ($v !== null) {
            $v->addFirm($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildDistrict object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildDistrict The associated ChildDistrict object.
     * @throws PropelException
     */
    public function getDistrict(ConnectionInterface $con = null)
    {
        if ($this->aDistrict === null && ($this->district_id !== null)) {
            $this->aDistrict = ChildDistrictQuery::create()->findPk($this->district_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDistrict->addFirms($this);
             */
        }

        return $this->aDistrict;
    }

    /**
     * Declares an association between this object and a ChildLegalInfo object.
     *
     * @param  ChildLegalInfo $v
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLegalInfoRelatedById(ChildLegalInfo $v = null)
    {
        if ($v === null) {
            $this->setId(NULL);
        } else {
            $this->setId($v->getFirmId());
        }

        $this->aLegalInfoRelatedById = $v;

        // Add binding for other direction of this 1:1 relationship.
        if ($v !== null) {
            $v->setFirmRelatedById($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildLegalInfo object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildLegalInfo The associated ChildLegalInfo object.
     * @throws PropelException
     */
    public function getLegalInfoRelatedById(ConnectionInterface $con = null)
    {
        if ($this->aLegalInfoRelatedById === null && ($this->id !== null)) {
            $this->aLegalInfoRelatedById = ChildLegalInfoQuery::create()
                ->filterByFirmRelatedById($this) // here
                ->findOne($con);
            // Because this foreign key represents a one-to-one relationship, we will create a bi-directional association.
            $this->aLegalInfoRelatedById && $this->aLegalInfoRelatedById->setFirmRelatedById($this);
        }

        return $this->aLegalInfoRelatedById;
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
        if ('FirmUp' == $relationName) {
            return $this->initFirmUps();
        }
        if ('AdvServerOrders' == $relationName) {
            return $this->initAdvServerOrderss();
        }
        if ('Comment' == $relationName) {
            return $this->initComments();
        }
        if ('Child' == $relationName) {
            return $this->initChildren();
        }
        if ('Contact' == $relationName) {
            return $this->initContacts();
        }
        if ('FirmGroup' == $relationName) {
            return $this->initFirmGroups();
        }
        if ('FirmPhotos' == $relationName) {
            return $this->initFirmPhotoss();
        }
        if ('FirmTags' == $relationName) {
            return $this->initFirmTagss();
        }
        if ('FirmUser' == $relationName) {
            return $this->initFirmUsers();
        }
        if ('LegalInfoRelatedByFirmId' == $relationName) {
            return $this->initLegalInfosRelatedByFirmId();
        }
    }

    /**
     * Clears out the collFirmUps collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFirmUps()
     */
    public function clearFirmUps()
    {
        $this->collFirmUps = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFirmUps collection loaded partially.
     */
    public function resetPartialFirmUps($v = true)
    {
        $this->collFirmUpsPartial = $v;
    }

    /**
     * Initializes the collFirmUps collection.
     *
     * By default this just sets the collFirmUps collection to an empty array (like clearcollFirmUps());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFirmUps($overrideExisting = true)
    {
        if (null !== $this->collFirmUps && !$overrideExisting) {
            return;
        }
        $this->collFirmUps = new ObjectCollection();
        $this->collFirmUps->setModel('\PropelModel\FirmUp');
    }

    /**
     * Gets an array of ChildFirmUp objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildFirm is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFirmUp[] List of ChildFirmUp objects
     * @throws PropelException
     */
    public function getFirmUps(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmUpsPartial && !$this->isNew();
        if (null === $this->collFirmUps || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFirmUps) {
                // return empty collection
                $this->initFirmUps();
            } else {
                $collFirmUps = ChildFirmUpQuery::create(null, $criteria)
                    ->filterByFirm($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFirmUpsPartial && count($collFirmUps)) {
                        $this->initFirmUps(false);

                        foreach ($collFirmUps as $obj) {
                            if (false == $this->collFirmUps->contains($obj)) {
                                $this->collFirmUps->append($obj);
                            }
                        }

                        $this->collFirmUpsPartial = true;
                    }

                    return $collFirmUps;
                }

                if ($partial && $this->collFirmUps) {
                    foreach ($this->collFirmUps as $obj) {
                        if ($obj->isNew()) {
                            $collFirmUps[] = $obj;
                        }
                    }
                }

                $this->collFirmUps = $collFirmUps;
                $this->collFirmUpsPartial = false;
            }
        }

        return $this->collFirmUps;
    }

    /**
     * Sets a collection of ChildFirmUp objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $firmUps A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function setFirmUps(Collection $firmUps, ConnectionInterface $con = null)
    {
        /** @var ChildFirmUp[] $firmUpsToDelete */
        $firmUpsToDelete = $this->getFirmUps(new Criteria(), $con)->diff($firmUps);


        $this->firmUpsScheduledForDeletion = $firmUpsToDelete;

        foreach ($firmUpsToDelete as $firmUpRemoved) {
            $firmUpRemoved->setFirm(null);
        }

        $this->collFirmUps = null;
        foreach ($firmUps as $firmUp) {
            $this->addFirmUp($firmUp);
        }

        $this->collFirmUps = $firmUps;
        $this->collFirmUpsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related FirmUp objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related FirmUp objects.
     * @throws PropelException
     */
    public function countFirmUps(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmUpsPartial && !$this->isNew();
        if (null === $this->collFirmUps || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFirmUps) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFirmUps());
            }

            $query = ChildFirmUpQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFirm($this)
                ->count($con);
        }

        return count($this->collFirmUps);
    }

    /**
     * Method called to associate a ChildFirmUp object to this object
     * through the ChildFirmUp foreign key attribute.
     *
     * @param  ChildFirmUp $l ChildFirmUp
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function addFirmUp(ChildFirmUp $l)
    {
        if ($this->collFirmUps === null) {
            $this->initFirmUps();
            $this->collFirmUpsPartial = true;
        }

        if (!$this->collFirmUps->contains($l)) {
            $this->doAddFirmUp($l);
        }

        return $this;
    }

    /**
     * @param ChildFirmUp $firmUp The ChildFirmUp object to add.
     */
    protected function doAddFirmUp(ChildFirmUp $firmUp)
    {
        $this->collFirmUps[]= $firmUp;
        $firmUp->setFirm($this);
    }

    /**
     * @param  ChildFirmUp $firmUp The ChildFirmUp object to remove.
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function removeFirmUp(ChildFirmUp $firmUp)
    {
        if ($this->getFirmUps()->contains($firmUp)) {
            $pos = $this->collFirmUps->search($firmUp);
            $this->collFirmUps->remove($pos);
            if (null === $this->firmUpsScheduledForDeletion) {
                $this->firmUpsScheduledForDeletion = clone $this->collFirmUps;
                $this->firmUpsScheduledForDeletion->clear();
            }
            $this->firmUpsScheduledForDeletion[]= $firmUp;
            $firmUp->setFirm(null);
        }

        return $this;
    }

    /**
     * Clears out the collAdvServerOrderss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAdvServerOrderss()
     */
    public function clearAdvServerOrderss()
    {
        $this->collAdvServerOrderss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAdvServerOrderss collection loaded partially.
     */
    public function resetPartialAdvServerOrderss($v = true)
    {
        $this->collAdvServerOrderssPartial = $v;
    }

    /**
     * Initializes the collAdvServerOrderss collection.
     *
     * By default this just sets the collAdvServerOrderss collection to an empty array (like clearcollAdvServerOrderss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAdvServerOrderss($overrideExisting = true)
    {
        if (null !== $this->collAdvServerOrderss && !$overrideExisting) {
            return;
        }
        $this->collAdvServerOrderss = new ObjectCollection();
        $this->collAdvServerOrderss->setModel('\PropelModel\AdvServerOrders');
    }

    /**
     * Gets an array of ChildAdvServerOrders objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildFirm is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAdvServerOrders[] List of ChildAdvServerOrders objects
     * @throws PropelException
     */
    public function getAdvServerOrderss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAdvServerOrderssPartial && !$this->isNew();
        if (null === $this->collAdvServerOrderss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAdvServerOrderss) {
                // return empty collection
                $this->initAdvServerOrderss();
            } else {
                $collAdvServerOrderss = ChildAdvServerOrdersQuery::create(null, $criteria)
                    ->filterByFirm($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAdvServerOrderssPartial && count($collAdvServerOrderss)) {
                        $this->initAdvServerOrderss(false);

                        foreach ($collAdvServerOrderss as $obj) {
                            if (false == $this->collAdvServerOrderss->contains($obj)) {
                                $this->collAdvServerOrderss->append($obj);
                            }
                        }

                        $this->collAdvServerOrderssPartial = true;
                    }

                    return $collAdvServerOrderss;
                }

                if ($partial && $this->collAdvServerOrderss) {
                    foreach ($this->collAdvServerOrderss as $obj) {
                        if ($obj->isNew()) {
                            $collAdvServerOrderss[] = $obj;
                        }
                    }
                }

                $this->collAdvServerOrderss = $collAdvServerOrderss;
                $this->collAdvServerOrderssPartial = false;
            }
        }

        return $this->collAdvServerOrderss;
    }

    /**
     * Sets a collection of ChildAdvServerOrders objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $advServerOrderss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function setAdvServerOrderss(Collection $advServerOrderss, ConnectionInterface $con = null)
    {
        /** @var ChildAdvServerOrders[] $advServerOrderssToDelete */
        $advServerOrderssToDelete = $this->getAdvServerOrderss(new Criteria(), $con)->diff($advServerOrderss);


        $this->advServerOrderssScheduledForDeletion = $advServerOrderssToDelete;

        foreach ($advServerOrderssToDelete as $advServerOrdersRemoved) {
            $advServerOrdersRemoved->setFirm(null);
        }

        $this->collAdvServerOrderss = null;
        foreach ($advServerOrderss as $advServerOrders) {
            $this->addAdvServerOrders($advServerOrders);
        }

        $this->collAdvServerOrderss = $advServerOrderss;
        $this->collAdvServerOrderssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AdvServerOrders objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related AdvServerOrders objects.
     * @throws PropelException
     */
    public function countAdvServerOrderss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAdvServerOrderssPartial && !$this->isNew();
        if (null === $this->collAdvServerOrderss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAdvServerOrderss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAdvServerOrderss());
            }

            $query = ChildAdvServerOrdersQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFirm($this)
                ->count($con);
        }

        return count($this->collAdvServerOrderss);
    }

    /**
     * Method called to associate a ChildAdvServerOrders object to this object
     * through the ChildAdvServerOrders foreign key attribute.
     *
     * @param  ChildAdvServerOrders $l ChildAdvServerOrders
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function addAdvServerOrders(ChildAdvServerOrders $l)
    {
        if ($this->collAdvServerOrderss === null) {
            $this->initAdvServerOrderss();
            $this->collAdvServerOrderssPartial = true;
        }

        if (!$this->collAdvServerOrderss->contains($l)) {
            $this->doAddAdvServerOrders($l);
        }

        return $this;
    }

    /**
     * @param ChildAdvServerOrders $advServerOrders The ChildAdvServerOrders object to add.
     */
    protected function doAddAdvServerOrders(ChildAdvServerOrders $advServerOrders)
    {
        $this->collAdvServerOrderss[]= $advServerOrders;
        $advServerOrders->setFirm($this);
    }

    /**
     * @param  ChildAdvServerOrders $advServerOrders The ChildAdvServerOrders object to remove.
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function removeAdvServerOrders(ChildAdvServerOrders $advServerOrders)
    {
        if ($this->getAdvServerOrderss()->contains($advServerOrders)) {
            $pos = $this->collAdvServerOrderss->search($advServerOrders);
            $this->collAdvServerOrderss->remove($pos);
            if (null === $this->advServerOrderssScheduledForDeletion) {
                $this->advServerOrderssScheduledForDeletion = clone $this->collAdvServerOrderss;
                $this->advServerOrderssScheduledForDeletion->clear();
            }
            $this->advServerOrderssScheduledForDeletion[]= clone $advServerOrders;
            $advServerOrders->setFirm(null);
        }

        return $this;
    }

    /**
     * Clears out the collComments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addComments()
     */
    public function clearComments()
    {
        $this->collComments = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collComments collection loaded partially.
     */
    public function resetPartialComments($v = true)
    {
        $this->collCommentsPartial = $v;
    }

    /**
     * Initializes the collComments collection.
     *
     * By default this just sets the collComments collection to an empty array (like clearcollComments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initComments($overrideExisting = true)
    {
        if (null !== $this->collComments && !$overrideExisting) {
            return;
        }
        $this->collComments = new ObjectCollection();
        $this->collComments->setModel('\PropelModel\Comment');
    }

    /**
     * Gets an array of ChildComment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildFirm is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildComment[] List of ChildComment objects
     * @throws PropelException
     */
    public function getComments(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCommentsPartial && !$this->isNew();
        if (null === $this->collComments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collComments) {
                // return empty collection
                $this->initComments();
            } else {
                $collComments = ChildCommentQuery::create(null, $criteria)
                    ->filterByFirm($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCommentsPartial && count($collComments)) {
                        $this->initComments(false);

                        foreach ($collComments as $obj) {
                            if (false == $this->collComments->contains($obj)) {
                                $this->collComments->append($obj);
                            }
                        }

                        $this->collCommentsPartial = true;
                    }

                    return $collComments;
                }

                if ($partial && $this->collComments) {
                    foreach ($this->collComments as $obj) {
                        if ($obj->isNew()) {
                            $collComments[] = $obj;
                        }
                    }
                }

                $this->collComments = $collComments;
                $this->collCommentsPartial = false;
            }
        }

        return $this->collComments;
    }

    /**
     * Sets a collection of ChildComment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $comments A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function setComments(Collection $comments, ConnectionInterface $con = null)
    {
        /** @var ChildComment[] $commentsToDelete */
        $commentsToDelete = $this->getComments(new Criteria(), $con)->diff($comments);


        $this->commentsScheduledForDeletion = $commentsToDelete;

        foreach ($commentsToDelete as $commentRemoved) {
            $commentRemoved->setFirm(null);
        }

        $this->collComments = null;
        foreach ($comments as $comment) {
            $this->addComment($comment);
        }

        $this->collComments = $comments;
        $this->collCommentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Comment objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Comment objects.
     * @throws PropelException
     */
    public function countComments(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCommentsPartial && !$this->isNew();
        if (null === $this->collComments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collComments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getComments());
            }

            $query = ChildCommentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFirm($this)
                ->count($con);
        }

        return count($this->collComments);
    }

    /**
     * Method called to associate a ChildComment object to this object
     * through the ChildComment foreign key attribute.
     *
     * @param  ChildComment $l ChildComment
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function addComment(ChildComment $l)
    {
        if ($this->collComments === null) {
            $this->initComments();
            $this->collCommentsPartial = true;
        }

        if (!$this->collComments->contains($l)) {
            $this->doAddComment($l);
        }

        return $this;
    }

    /**
     * @param ChildComment $comment The ChildComment object to add.
     */
    protected function doAddComment(ChildComment $comment)
    {
        $this->collComments[]= $comment;
        $comment->setFirm($this);
    }

    /**
     * @param  ChildComment $comment The ChildComment object to remove.
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function removeComment(ChildComment $comment)
    {
        if ($this->getComments()->contains($comment)) {
            $pos = $this->collComments->search($comment);
            $this->collComments->remove($pos);
            if (null === $this->commentsScheduledForDeletion) {
                $this->commentsScheduledForDeletion = clone $this->collComments;
                $this->commentsScheduledForDeletion->clear();
            }
            $this->commentsScheduledForDeletion[]= clone $comment;
            $comment->setFirm(null);
        }

        return $this;
    }

    /**
     * Clears out the collChildren collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addChildren()
     */
    public function clearChildren()
    {
        $this->collChildren = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collChildren collection loaded partially.
     */
    public function resetPartialChildren($v = true)
    {
        $this->collChildrenPartial = $v;
    }

    /**
     * Initializes the collChildren collection.
     *
     * By default this just sets the collChildren collection to an empty array (like clearcollChildren());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initChildren($overrideExisting = true)
    {
        if (null !== $this->collChildren && !$overrideExisting) {
            return;
        }
        $this->collChildren = new ObjectCollection();
        $this->collChildren->setModel('\PropelModel\Child');
    }

    /**
     * Gets an array of ChildChild objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildFirm is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildChild[] List of ChildChild objects
     * @throws PropelException
     */
    public function getChildren(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collChildrenPartial && !$this->isNew();
        if (null === $this->collChildren || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collChildren) {
                // return empty collection
                $this->initChildren();
            } else {
                $collChildren = ChildChildQuery::create(null, $criteria)
                    ->filterByFirm($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collChildrenPartial && count($collChildren)) {
                        $this->initChildren(false);

                        foreach ($collChildren as $obj) {
                            if (false == $this->collChildren->contains($obj)) {
                                $this->collChildren->append($obj);
                            }
                        }

                        $this->collChildrenPartial = true;
                    }

                    return $collChildren;
                }

                if ($partial && $this->collChildren) {
                    foreach ($this->collChildren as $obj) {
                        if ($obj->isNew()) {
                            $collChildren[] = $obj;
                        }
                    }
                }

                $this->collChildren = $collChildren;
                $this->collChildrenPartial = false;
            }
        }

        return $this->collChildren;
    }

    /**
     * Sets a collection of ChildChild objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $children A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function setChildren(Collection $children, ConnectionInterface $con = null)
    {
        /** @var ChildChild[] $childrenToDelete */
        $childrenToDelete = $this->getChildren(new Criteria(), $con)->diff($children);


        $this->childrenScheduledForDeletion = $childrenToDelete;

        foreach ($childrenToDelete as $childRemoved) {
            $childRemoved->setFirm(null);
        }

        $this->collChildren = null;
        foreach ($children as $child) {
            $this->addChild($child);
        }

        $this->collChildren = $children;
        $this->collChildrenPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Child objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Child objects.
     * @throws PropelException
     */
    public function countChildren(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collChildrenPartial && !$this->isNew();
        if (null === $this->collChildren || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collChildren) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getChildren());
            }

            $query = ChildChildQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFirm($this)
                ->count($con);
        }

        return count($this->collChildren);
    }

    /**
     * Method called to associate a ChildChild object to this object
     * through the ChildChild foreign key attribute.
     *
     * @param  ChildChild $l ChildChild
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function addChild(ChildChild $l)
    {
        if ($this->collChildren === null) {
            $this->initChildren();
            $this->collChildrenPartial = true;
        }

        if (!$this->collChildren->contains($l)) {
            $this->doAddChild($l);
        }

        return $this;
    }

    /**
     * @param ChildChild $child The ChildChild object to add.
     */
    protected function doAddChild(ChildChild $child)
    {
        $this->collChildren[]= $child;
        $child->setFirm($this);
    }

    /**
     * @param  ChildChild $child The ChildChild object to remove.
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function removeChild(ChildChild $child)
    {
        if ($this->getChildren()->contains($child)) {
            $pos = $this->collChildren->search($child);
            $this->collChildren->remove($pos);
            if (null === $this->childrenScheduledForDeletion) {
                $this->childrenScheduledForDeletion = clone $this->collChildren;
                $this->childrenScheduledForDeletion->clear();
            }
            $this->childrenScheduledForDeletion[]= clone $child;
            $child->setFirm(null);
        }

        return $this;
    }

    /**
     * Clears out the collContacts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addContacts()
     */
    public function clearContacts()
    {
        $this->collContacts = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collContacts collection loaded partially.
     */
    public function resetPartialContacts($v = true)
    {
        $this->collContactsPartial = $v;
    }

    /**
     * Initializes the collContacts collection.
     *
     * By default this just sets the collContacts collection to an empty array (like clearcollContacts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initContacts($overrideExisting = true)
    {
        if (null !== $this->collContacts && !$overrideExisting) {
            return;
        }
        $this->collContacts = new ObjectCollection();
        $this->collContacts->setModel('\PropelModel\Contact');
    }

    /**
     * Gets an array of ChildContact objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildFirm is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildContact[] List of ChildContact objects
     * @throws PropelException
     */
    public function getContacts(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collContactsPartial && !$this->isNew();
        if (null === $this->collContacts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collContacts) {
                // return empty collection
                $this->initContacts();
            } else {
                $collContacts = ChildContactQuery::create(null, $criteria)
                    ->filterByFirm($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collContactsPartial && count($collContacts)) {
                        $this->initContacts(false);

                        foreach ($collContacts as $obj) {
                            if (false == $this->collContacts->contains($obj)) {
                                $this->collContacts->append($obj);
                            }
                        }

                        $this->collContactsPartial = true;
                    }

                    return $collContacts;
                }

                if ($partial && $this->collContacts) {
                    foreach ($this->collContacts as $obj) {
                        if ($obj->isNew()) {
                            $collContacts[] = $obj;
                        }
                    }
                }

                $this->collContacts = $collContacts;
                $this->collContactsPartial = false;
            }
        }

        return $this->collContacts;
    }

    /**
     * Sets a collection of ChildContact objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $contacts A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function setContacts(Collection $contacts, ConnectionInterface $con = null)
    {
        /** @var ChildContact[] $contactsToDelete */
        $contactsToDelete = $this->getContacts(new Criteria(), $con)->diff($contacts);


        $this->contactsScheduledForDeletion = $contactsToDelete;

        foreach ($contactsToDelete as $contactRemoved) {
            $contactRemoved->setFirm(null);
        }

        $this->collContacts = null;
        foreach ($contacts as $contact) {
            $this->addContact($contact);
        }

        $this->collContacts = $contacts;
        $this->collContactsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Contact objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Contact objects.
     * @throws PropelException
     */
    public function countContacts(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collContactsPartial && !$this->isNew();
        if (null === $this->collContacts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collContacts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getContacts());
            }

            $query = ChildContactQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFirm($this)
                ->count($con);
        }

        return count($this->collContacts);
    }

    /**
     * Method called to associate a ChildContact object to this object
     * through the ChildContact foreign key attribute.
     *
     * @param  ChildContact $l ChildContact
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function addContact(ChildContact $l)
    {
        if ($this->collContacts === null) {
            $this->initContacts();
            $this->collContactsPartial = true;
        }

        if (!$this->collContacts->contains($l)) {
            $this->doAddContact($l);
        }

        return $this;
    }

    /**
     * @param ChildContact $contact The ChildContact object to add.
     */
    protected function doAddContact(ChildContact $contact)
    {
        $this->collContacts[]= $contact;
        $contact->setFirm($this);
    }

    /**
     * @param  ChildContact $contact The ChildContact object to remove.
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function removeContact(ChildContact $contact)
    {
        if ($this->getContacts()->contains($contact)) {
            $pos = $this->collContacts->search($contact);
            $this->collContacts->remove($pos);
            if (null === $this->contactsScheduledForDeletion) {
                $this->contactsScheduledForDeletion = clone $this->collContacts;
                $this->contactsScheduledForDeletion->clear();
            }
            $this->contactsScheduledForDeletion[]= clone $contact;
            $contact->setFirm(null);
        }

        return $this;
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
     * If this ChildFirm is new, it will return
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
                    ->filterByFirm($this)
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
     * @return $this|ChildFirm The current object (for fluent API support)
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
            $firmGroupRemoved->setFirm(null);
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
                ->filterByFirm($this)
                ->count($con);
        }

        return count($this->collFirmGroups);
    }

    /**
     * Method called to associate a ChildFirmGroup object to this object
     * through the ChildFirmGroup foreign key attribute.
     *
     * @param  ChildFirmGroup $l ChildFirmGroup
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
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
        $firmGroup->setFirm($this);
    }

    /**
     * @param  ChildFirmGroup $firmGroup The ChildFirmGroup object to remove.
     * @return $this|ChildFirm The current object (for fluent API support)
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
            $firmGroup->setFirm(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Firm is new, it will return
     * an empty collection; or if this Firm has previously
     * been saved, it will retrieve related FirmGroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Firm.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFirmGroup[] List of ChildFirmGroup objects
     */
    public function getFirmGroupsJoinGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFirmGroupQuery::create(null, $criteria);
        $query->joinWith('Group', $joinBehavior);

        return $this->getFirmGroups($query, $con);
    }

    /**
     * Clears out the collFirmPhotoss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFirmPhotoss()
     */
    public function clearFirmPhotoss()
    {
        $this->collFirmPhotoss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFirmPhotoss collection loaded partially.
     */
    public function resetPartialFirmPhotoss($v = true)
    {
        $this->collFirmPhotossPartial = $v;
    }

    /**
     * Initializes the collFirmPhotoss collection.
     *
     * By default this just sets the collFirmPhotoss collection to an empty array (like clearcollFirmPhotoss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFirmPhotoss($overrideExisting = true)
    {
        if (null !== $this->collFirmPhotoss && !$overrideExisting) {
            return;
        }
        $this->collFirmPhotoss = new ObjectCollection();
        $this->collFirmPhotoss->setModel('\PropelModel\FirmPhotos');
    }

    /**
     * Gets an array of ChildFirmPhotos objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildFirm is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFirmPhotos[] List of ChildFirmPhotos objects
     * @throws PropelException
     */
    public function getFirmPhotoss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmPhotossPartial && !$this->isNew();
        if (null === $this->collFirmPhotoss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFirmPhotoss) {
                // return empty collection
                $this->initFirmPhotoss();
            } else {
                $collFirmPhotoss = ChildFirmPhotosQuery::create(null, $criteria)
                    ->filterByFirm($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFirmPhotossPartial && count($collFirmPhotoss)) {
                        $this->initFirmPhotoss(false);

                        foreach ($collFirmPhotoss as $obj) {
                            if (false == $this->collFirmPhotoss->contains($obj)) {
                                $this->collFirmPhotoss->append($obj);
                            }
                        }

                        $this->collFirmPhotossPartial = true;
                    }

                    return $collFirmPhotoss;
                }

                if ($partial && $this->collFirmPhotoss) {
                    foreach ($this->collFirmPhotoss as $obj) {
                        if ($obj->isNew()) {
                            $collFirmPhotoss[] = $obj;
                        }
                    }
                }

                $this->collFirmPhotoss = $collFirmPhotoss;
                $this->collFirmPhotossPartial = false;
            }
        }

        return $this->collFirmPhotoss;
    }

    /**
     * Sets a collection of ChildFirmPhotos objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $firmPhotoss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function setFirmPhotoss(Collection $firmPhotoss, ConnectionInterface $con = null)
    {
        /** @var ChildFirmPhotos[] $firmPhotossToDelete */
        $firmPhotossToDelete = $this->getFirmPhotoss(new Criteria(), $con)->diff($firmPhotoss);


        $this->firmPhotossScheduledForDeletion = $firmPhotossToDelete;

        foreach ($firmPhotossToDelete as $firmPhotosRemoved) {
            $firmPhotosRemoved->setFirm(null);
        }

        $this->collFirmPhotoss = null;
        foreach ($firmPhotoss as $firmPhotos) {
            $this->addFirmPhotos($firmPhotos);
        }

        $this->collFirmPhotoss = $firmPhotoss;
        $this->collFirmPhotossPartial = false;

        return $this;
    }

    /**
     * Returns the number of related FirmPhotos objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related FirmPhotos objects.
     * @throws PropelException
     */
    public function countFirmPhotoss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmPhotossPartial && !$this->isNew();
        if (null === $this->collFirmPhotoss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFirmPhotoss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFirmPhotoss());
            }

            $query = ChildFirmPhotosQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFirm($this)
                ->count($con);
        }

        return count($this->collFirmPhotoss);
    }

    /**
     * Method called to associate a ChildFirmPhotos object to this object
     * through the ChildFirmPhotos foreign key attribute.
     *
     * @param  ChildFirmPhotos $l ChildFirmPhotos
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function addFirmPhotos(ChildFirmPhotos $l)
    {
        if ($this->collFirmPhotoss === null) {
            $this->initFirmPhotoss();
            $this->collFirmPhotossPartial = true;
        }

        if (!$this->collFirmPhotoss->contains($l)) {
            $this->doAddFirmPhotos($l);
        }

        return $this;
    }

    /**
     * @param ChildFirmPhotos $firmPhotos The ChildFirmPhotos object to add.
     */
    protected function doAddFirmPhotos(ChildFirmPhotos $firmPhotos)
    {
        $this->collFirmPhotoss[]= $firmPhotos;
        $firmPhotos->setFirm($this);
    }

    /**
     * @param  ChildFirmPhotos $firmPhotos The ChildFirmPhotos object to remove.
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function removeFirmPhotos(ChildFirmPhotos $firmPhotos)
    {
        if ($this->getFirmPhotoss()->contains($firmPhotos)) {
            $pos = $this->collFirmPhotoss->search($firmPhotos);
            $this->collFirmPhotoss->remove($pos);
            if (null === $this->firmPhotossScheduledForDeletion) {
                $this->firmPhotossScheduledForDeletion = clone $this->collFirmPhotoss;
                $this->firmPhotossScheduledForDeletion->clear();
            }
            $this->firmPhotossScheduledForDeletion[]= $firmPhotos;
            $firmPhotos->setFirm(null);
        }

        return $this;
    }

    /**
     * Clears out the collFirmTagss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFirmTagss()
     */
    public function clearFirmTagss()
    {
        $this->collFirmTagss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collFirmTagss collection loaded partially.
     */
    public function resetPartialFirmTagss($v = true)
    {
        $this->collFirmTagssPartial = $v;
    }

    /**
     * Initializes the collFirmTagss collection.
     *
     * By default this just sets the collFirmTagss collection to an empty array (like clearcollFirmTagss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFirmTagss($overrideExisting = true)
    {
        if (null !== $this->collFirmTagss && !$overrideExisting) {
            return;
        }
        $this->collFirmTagss = new ObjectCollection();
        $this->collFirmTagss->setModel('\PropelModel\FirmTags');
    }

    /**
     * Gets an array of ChildFirmTags objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildFirm is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFirmTags[] List of ChildFirmTags objects
     * @throws PropelException
     */
    public function getFirmTagss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmTagssPartial && !$this->isNew();
        if (null === $this->collFirmTagss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFirmTagss) {
                // return empty collection
                $this->initFirmTagss();
            } else {
                $collFirmTagss = ChildFirmTagsQuery::create(null, $criteria)
                    ->filterByFirm($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFirmTagssPartial && count($collFirmTagss)) {
                        $this->initFirmTagss(false);

                        foreach ($collFirmTagss as $obj) {
                            if (false == $this->collFirmTagss->contains($obj)) {
                                $this->collFirmTagss->append($obj);
                            }
                        }

                        $this->collFirmTagssPartial = true;
                    }

                    return $collFirmTagss;
                }

                if ($partial && $this->collFirmTagss) {
                    foreach ($this->collFirmTagss as $obj) {
                        if ($obj->isNew()) {
                            $collFirmTagss[] = $obj;
                        }
                    }
                }

                $this->collFirmTagss = $collFirmTagss;
                $this->collFirmTagssPartial = false;
            }
        }

        return $this->collFirmTagss;
    }

    /**
     * Sets a collection of ChildFirmTags objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $firmTagss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function setFirmTagss(Collection $firmTagss, ConnectionInterface $con = null)
    {
        /** @var ChildFirmTags[] $firmTagssToDelete */
        $firmTagssToDelete = $this->getFirmTagss(new Criteria(), $con)->diff($firmTagss);


        $this->firmTagssScheduledForDeletion = $firmTagssToDelete;

        foreach ($firmTagssToDelete as $firmTagsRemoved) {
            $firmTagsRemoved->setFirm(null);
        }

        $this->collFirmTagss = null;
        foreach ($firmTagss as $firmTags) {
            $this->addFirmTags($firmTags);
        }

        $this->collFirmTagss = $firmTagss;
        $this->collFirmTagssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related FirmTags objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related FirmTags objects.
     * @throws PropelException
     */
    public function countFirmTagss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFirmTagssPartial && !$this->isNew();
        if (null === $this->collFirmTagss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFirmTagss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFirmTagss());
            }

            $query = ChildFirmTagsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFirm($this)
                ->count($con);
        }

        return count($this->collFirmTagss);
    }

    /**
     * Method called to associate a ChildFirmTags object to this object
     * through the ChildFirmTags foreign key attribute.
     *
     * @param  ChildFirmTags $l ChildFirmTags
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function addFirmTags(ChildFirmTags $l)
    {
        if ($this->collFirmTagss === null) {
            $this->initFirmTagss();
            $this->collFirmTagssPartial = true;
        }

        if (!$this->collFirmTagss->contains($l)) {
            $this->doAddFirmTags($l);
        }

        return $this;
    }

    /**
     * @param ChildFirmTags $firmTags The ChildFirmTags object to add.
     */
    protected function doAddFirmTags(ChildFirmTags $firmTags)
    {
        $this->collFirmTagss[]= $firmTags;
        $firmTags->setFirm($this);
    }

    /**
     * @param  ChildFirmTags $firmTags The ChildFirmTags object to remove.
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function removeFirmTags(ChildFirmTags $firmTags)
    {
        if ($this->getFirmTagss()->contains($firmTags)) {
            $pos = $this->collFirmTagss->search($firmTags);
            $this->collFirmTagss->remove($pos);
            if (null === $this->firmTagssScheduledForDeletion) {
                $this->firmTagssScheduledForDeletion = clone $this->collFirmTagss;
                $this->firmTagssScheduledForDeletion->clear();
            }
            $this->firmTagssScheduledForDeletion[]= clone $firmTags;
            $firmTags->setFirm(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Firm is new, it will return
     * an empty collection; or if this Firm has previously
     * been saved, it will retrieve related FirmTagss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Firm.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFirmTags[] List of ChildFirmTags objects
     */
    public function getFirmTagssJoinTags(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFirmTagsQuery::create(null, $criteria);
        $query->joinWith('Tags', $joinBehavior);

        return $this->getFirmTagss($query, $con);
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
     * If this ChildFirm is new, it will return
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
                    ->filterByFirm($this)
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
     * @return $this|ChildFirm The current object (for fluent API support)
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
            $firmUserRemoved->setFirm(null);
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
                ->filterByFirm($this)
                ->count($con);
        }

        return count($this->collFirmUsers);
    }

    /**
     * Method called to associate a ChildFirmUser object to this object
     * through the ChildFirmUser foreign key attribute.
     *
     * @param  ChildFirmUser $l ChildFirmUser
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
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
        $firmUser->setFirm($this);
    }

    /**
     * @param  ChildFirmUser $firmUser The ChildFirmUser object to remove.
     * @return $this|ChildFirm The current object (for fluent API support)
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
            $firmUser->setFirm(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Firm is new, it will return
     * an empty collection; or if this Firm has previously
     * been saved, it will retrieve related FirmUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Firm.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFirmUser[] List of ChildFirmUser objects
     */
    public function getFirmUsersJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFirmUserQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getFirmUsers($query, $con);
    }

    /**
     * Clears out the collLegalInfosRelatedByFirmId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLegalInfosRelatedByFirmId()
     */
    public function clearLegalInfosRelatedByFirmId()
    {
        $this->collLegalInfosRelatedByFirmId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLegalInfosRelatedByFirmId collection loaded partially.
     */
    public function resetPartialLegalInfosRelatedByFirmId($v = true)
    {
        $this->collLegalInfosRelatedByFirmIdPartial = $v;
    }

    /**
     * Initializes the collLegalInfosRelatedByFirmId collection.
     *
     * By default this just sets the collLegalInfosRelatedByFirmId collection to an empty array (like clearcollLegalInfosRelatedByFirmId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLegalInfosRelatedByFirmId($overrideExisting = true)
    {
        if (null !== $this->collLegalInfosRelatedByFirmId && !$overrideExisting) {
            return;
        }
        $this->collLegalInfosRelatedByFirmId = new ObjectCollection();
        $this->collLegalInfosRelatedByFirmId->setModel('\PropelModel\LegalInfo');
    }

    /**
     * Gets an array of ChildLegalInfo objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildFirm is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLegalInfo[] List of ChildLegalInfo objects
     * @throws PropelException
     */
    public function getLegalInfosRelatedByFirmId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLegalInfosRelatedByFirmIdPartial && !$this->isNew();
        if (null === $this->collLegalInfosRelatedByFirmId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLegalInfosRelatedByFirmId) {
                // return empty collection
                $this->initLegalInfosRelatedByFirmId();
            } else {
                $collLegalInfosRelatedByFirmId = ChildLegalInfoQuery::create(null, $criteria)
                    ->filterByFirmRelatedByFirmId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLegalInfosRelatedByFirmIdPartial && count($collLegalInfosRelatedByFirmId)) {
                        $this->initLegalInfosRelatedByFirmId(false);

                        foreach ($collLegalInfosRelatedByFirmId as $obj) {
                            if (false == $this->collLegalInfosRelatedByFirmId->contains($obj)) {
                                $this->collLegalInfosRelatedByFirmId->append($obj);
                            }
                        }

                        $this->collLegalInfosRelatedByFirmIdPartial = true;
                    }

                    return $collLegalInfosRelatedByFirmId;
                }

                if ($partial && $this->collLegalInfosRelatedByFirmId) {
                    foreach ($this->collLegalInfosRelatedByFirmId as $obj) {
                        if ($obj->isNew()) {
                            $collLegalInfosRelatedByFirmId[] = $obj;
                        }
                    }
                }

                $this->collLegalInfosRelatedByFirmId = $collLegalInfosRelatedByFirmId;
                $this->collLegalInfosRelatedByFirmIdPartial = false;
            }
        }

        return $this->collLegalInfosRelatedByFirmId;
    }

    /**
     * Sets a collection of ChildLegalInfo objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $legalInfosRelatedByFirmId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function setLegalInfosRelatedByFirmId(Collection $legalInfosRelatedByFirmId, ConnectionInterface $con = null)
    {
        /** @var ChildLegalInfo[] $legalInfosRelatedByFirmIdToDelete */
        $legalInfosRelatedByFirmIdToDelete = $this->getLegalInfosRelatedByFirmId(new Criteria(), $con)->diff($legalInfosRelatedByFirmId);


        $this->legalInfosRelatedByFirmIdScheduledForDeletion = $legalInfosRelatedByFirmIdToDelete;

        foreach ($legalInfosRelatedByFirmIdToDelete as $legalInfoRelatedByFirmIdRemoved) {
            $legalInfoRelatedByFirmIdRemoved->setFirmRelatedByFirmId(null);
        }

        $this->collLegalInfosRelatedByFirmId = null;
        foreach ($legalInfosRelatedByFirmId as $legalInfoRelatedByFirmId) {
            $this->addLegalInfoRelatedByFirmId($legalInfoRelatedByFirmId);
        }

        $this->collLegalInfosRelatedByFirmId = $legalInfosRelatedByFirmId;
        $this->collLegalInfosRelatedByFirmIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related LegalInfo objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related LegalInfo objects.
     * @throws PropelException
     */
    public function countLegalInfosRelatedByFirmId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLegalInfosRelatedByFirmIdPartial && !$this->isNew();
        if (null === $this->collLegalInfosRelatedByFirmId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLegalInfosRelatedByFirmId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLegalInfosRelatedByFirmId());
            }

            $query = ChildLegalInfoQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFirmRelatedByFirmId($this)
                ->count($con);
        }

        return count($this->collLegalInfosRelatedByFirmId);
    }

    /**
     * Method called to associate a ChildLegalInfo object to this object
     * through the ChildLegalInfo foreign key attribute.
     *
     * @param  ChildLegalInfo $l ChildLegalInfo
     * @return $this|\PropelModel\Firm The current object (for fluent API support)
     */
    public function addLegalInfoRelatedByFirmId(ChildLegalInfo $l)
    {
        if ($this->collLegalInfosRelatedByFirmId === null) {
            $this->initLegalInfosRelatedByFirmId();
            $this->collLegalInfosRelatedByFirmIdPartial = true;
        }

        if (!$this->collLegalInfosRelatedByFirmId->contains($l)) {
            $this->doAddLegalInfoRelatedByFirmId($l);
        }

        return $this;
    }

    /**
     * @param ChildLegalInfo $legalInfoRelatedByFirmId The ChildLegalInfo object to add.
     */
    protected function doAddLegalInfoRelatedByFirmId(ChildLegalInfo $legalInfoRelatedByFirmId)
    {
        $this->collLegalInfosRelatedByFirmId[]= $legalInfoRelatedByFirmId;
        $legalInfoRelatedByFirmId->setFirmRelatedByFirmId($this);
    }

    /**
     * @param  ChildLegalInfo $legalInfoRelatedByFirmId The ChildLegalInfo object to remove.
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function removeLegalInfoRelatedByFirmId(ChildLegalInfo $legalInfoRelatedByFirmId)
    {
        if ($this->getLegalInfosRelatedByFirmId()->contains($legalInfoRelatedByFirmId)) {
            $pos = $this->collLegalInfosRelatedByFirmId->search($legalInfoRelatedByFirmId);
            $this->collLegalInfosRelatedByFirmId->remove($pos);
            if (null === $this->legalInfosRelatedByFirmIdScheduledForDeletion) {
                $this->legalInfosRelatedByFirmIdScheduledForDeletion = clone $this->collLegalInfosRelatedByFirmId;
                $this->legalInfosRelatedByFirmIdScheduledForDeletion->clear();
            }
            $this->legalInfosRelatedByFirmIdScheduledForDeletion[]= $legalInfoRelatedByFirmId;
            $legalInfoRelatedByFirmId->setFirmRelatedByFirmId(null);
        }

        return $this;
    }

    /**
     * Clears out the collGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGroups()
     */
    public function clearGroups()
    {
        $this->collGroups = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collGroups crossRef collection.
     *
     * By default this just sets the collGroups collection to an empty collection (like clearGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initGroups()
    {
        $this->collGroups = new ObjectCollection();
        $this->collGroupsPartial = true;

        $this->collGroups->setModel('\PropelModel\Group');
    }

    /**
     * Checks if the collGroups collection is loaded.
     *
     * @return bool
     */
    public function isGroupsLoaded()
    {
        return null !== $this->collGroups;
    }

    /**
     * Gets a collection of ChildGroup objects related by a many-to-many relationship
     * to the current object by way of the firm_group cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildFirm is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildGroup[] List of ChildGroup objects
     */
    public function getGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupsPartial && !$this->isNew();
        if (null === $this->collGroups || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collGroups) {
                    $this->initGroups();
                }
            } else {

                $query = ChildGroupQuery::create(null, $criteria)
                    ->filterByFirm($this);
                $collGroups = $query->find($con);
                if (null !== $criteria) {
                    return $collGroups;
                }

                if ($partial && $this->collGroups) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collGroups as $obj) {
                        if (!$collGroups->contains($obj)) {
                            $collGroups[] = $obj;
                        }
                    }
                }

                $this->collGroups = $collGroups;
                $this->collGroupsPartial = false;
            }
        }

        return $this->collGroups;
    }

    /**
     * Sets a collection of Group objects related by a many-to-many relationship
     * to the current object by way of the firm_group cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $groups A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function setGroups(Collection $groups, ConnectionInterface $con = null)
    {
        $this->clearGroups();
        $currentGroups = $this->getGroups();

        $groupsScheduledForDeletion = $currentGroups->diff($groups);

        foreach ($groupsScheduledForDeletion as $toDelete) {
            $this->removeGroup($toDelete);
        }

        foreach ($groups as $group) {
            if (!$currentGroups->contains($group)) {
                $this->doAddGroup($group);
            }
        }

        $this->collGroupsPartial = false;
        $this->collGroups = $groups;

        return $this;
    }

    /**
     * Gets the number of Group objects related by a many-to-many relationship
     * to the current object by way of the firm_group cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Group objects
     */
    public function countGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupsPartial && !$this->isNew();
        if (null === $this->collGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGroups) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getGroups());
                }

                $query = ChildGroupQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByFirm($this)
                    ->count($con);
            }
        } else {
            return count($this->collGroups);
        }
    }

    /**
     * Associate a ChildGroup to this object
     * through the firm_group cross reference table.
     *
     * @param ChildGroup $group
     * @return ChildFirm The current object (for fluent API support)
     */
    public function addGroup(ChildGroup $group)
    {
        if ($this->collGroups === null) {
            $this->initGroups();
        }

        if (!$this->getGroups()->contains($group)) {
            // only add it if the **same** object is not already associated
            $this->collGroups->push($group);
            $this->doAddGroup($group);
        }

        return $this;
    }

    /**
     *
     * @param ChildGroup $group
     */
    protected function doAddGroup(ChildGroup $group)
    {
        $firmGroup = new ChildFirmGroup();

        $firmGroup->setGroup($group);

        $firmGroup->setFirm($this);

        $this->addFirmGroup($firmGroup);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$group->isFirmsLoaded()) {
            $group->initFirms();
            $group->getFirms()->push($this);
        } elseif (!$group->getFirms()->contains($this)) {
            $group->getFirms()->push($this);
        }

    }

    /**
     * Remove group of this object
     * through the firm_group cross reference table.
     *
     * @param ChildGroup $group
     * @return ChildFirm The current object (for fluent API support)
     */
    public function removeGroup(ChildGroup $group)
    {
        if ($this->getGroups()->contains($group)) { $firmGroup = new ChildFirmGroup();

            $firmGroup->setGroup($group);
            if ($group->isFirmsLoaded()) {
                //remove the back reference if available
                $group->getFirms()->removeObject($this);
            }

            $firmGroup->setFirm($this);
            $this->removeFirmGroup(clone $firmGroup);
            $firmGroup->clear();

            $this->collGroups->remove($this->collGroups->search($group));

            if (null === $this->groupsScheduledForDeletion) {
                $this->groupsScheduledForDeletion = clone $this->collGroups;
                $this->groupsScheduledForDeletion->clear();
            }

            $this->groupsScheduledForDeletion->push($group);
        }


        return $this;
    }

    /**
     * Clears out the collUserids collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserids()
     */
    public function clearUserids()
    {
        $this->collUserids = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the combinationCollUserids crossRef collection.
     *
     * By default this just sets the combinationCollUserids collection to an empty collection (like clearUserids());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initUserids()
    {
        $this->combinationCollUserids = new ObjectCombinationCollection();
        $this->combinationCollUseridsPartial = true;

    }

    /**
     * Checks if the combinationCollUserids collection is loaded.
     *
     * @return bool
     */
    public function isUseridsLoaded()
    {
        return null !== $this->combinationCollUserids;
    }

    /**
     * Returns a new query object pre configured with filters from current object and given arguments to query the database.
     *
     * @param int $id
     * @param Criteria $criteria
     *
     * @return ChildUserQuery
     */
    public function createUsersQuery($id = null, Criteria $criteria = null)
    {
        $criteria = ChildUserQuery::create($criteria)
            ->filterByFirm($this);

        $firmUserQuery = $criteria->useFirmUserQuery();

        if (null !== $id) {
            $firmUserQuery->filterByid($id);
        }

        $firmUserQuery->endUse();

        return $criteria;
    }

    /**
     * Gets a combined collection of ChildUser objects related by a many-to-many relationship
     * to the current object by way of the firm_user cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildFirm is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCombinationCollection Combination list of ChildUser objects
     */
    public function getUserids($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollUseridsPartial && !$this->isNew();
        if (null === $this->combinationCollUserids || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->combinationCollUserids) {
                    $this->initUserids();
                }
            } else {

                $query = ChildFirmUserQuery::create(null, $criteria)
                    ->filterByFirm($this)
                    ->joinUser()
                ;

                $items = $query->find($con);
                $combinationCollUserids = new ObjectCombinationCollection();
                foreach ($items as $item) {
                    $combination = [];

                    $combination[] = $item->getUser();
                    $combination[] = $item->getid();
                    $combinationCollUserids[] = $combination;
                }

                if (null !== $criteria) {
                    return $combinationCollUserids;
                }

                if ($partial && $this->combinationCollUserids) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->combinationCollUserids as $obj) {
                        if (!call_user_func_array([$combinationCollUserids, 'contains'], $obj)) {
                            $combinationCollUserids[] = $obj;
                        }
                    }
                }

                $this->combinationCollUserids = $combinationCollUserids;
                $this->combinationCollUseridsPartial = false;
            }
        }

        return $this->combinationCollUserids;
    }

    /**
     * Returns a not cached ObjectCollection of ChildUser objects. This will hit always the databases.
     * If you have attached new ChildUser object to this object you need to call `save` first to get
     * the correct return value. Use getUserids() to get the current internal state.
     *
     * @param int $id
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return ChildUser[]|ObjectCollection
     */
    public function getUsers($id = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createUsersQuery($id, $criteria)->find($con);
    }

    /**
     * Sets a collection of ChildUser objects related by a many-to-many relationship
     * to the current object by way of the firm_user cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $userids A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildFirm The current object (for fluent API support)
     */
    public function setUserids(Collection $userids, ConnectionInterface $con = null)
    {
        $this->clearUserids();
        $currentUserids = $this->getUserids();

        $combinationCollUseridsScheduledForDeletion = $currentUserids->diff($userids);

        foreach ($combinationCollUseridsScheduledForDeletion as $toDelete) {
            call_user_func_array([$this, 'removeUserid'], $toDelete);
        }

        foreach ($userids as $userid) {
            if (!call_user_func_array([$currentUserids, 'contains'], $userid)) {
                call_user_func_array([$this, 'doAddUserid'], $userid);
            }
        }

        $this->combinationCollUseridsPartial = false;
        $this->combinationCollUserids = $userids;

        return $this;
    }

    /**
     * Gets the number of ChildUser objects related by a many-to-many relationship
     * to the current object by way of the firm_user cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildUser objects
     */
    public function countUserids(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollUseridsPartial && !$this->isNew();
        if (null === $this->combinationCollUserids || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->combinationCollUserids) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getUserids());
                }

                $query = ChildFirmUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByFirm($this)
                    ->count($con);
            }
        } else {
            return count($this->combinationCollUserids);
        }
    }

    /**
     * Returns the not cached count of ChildUser objects. This will hit always the databases.
     * If you have attached new ChildUser object to this object you need to call `save` first to get
     * the correct return value. Use getUserids() to get the current internal state.
     *
     * @param int $id
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return integer
     */
    public function countUsers($id = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createUsersQuery($id, $criteria)->count($con);
    }

    /**
     * Associate a ChildUser to this object
     * through the firm_user cross reference table.
     *
     * @param ChildUser $user,
     * @param int $id
     * @return ChildFirm The current object (for fluent API support)
     */
    public function addUser(ChildUser $user, $id)
    {
        if ($this->combinationCollUserids === null) {
            $this->initUserids();
        }

        if (!$this->getUserids()->contains($user, $id)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollUserids->push($user, $id);
            $this->doAddUserid($user, $id);
        }

        return $this;
    }

    /**
     *
     * @param ChildUser $user,
     * @param int $id
     */
    protected function doAddUserid(ChildUser $user, $id)
    {
        $firmUser = new ChildFirmUser();

        $firmUser->setUser($user);
        $firmUser->setid($id);


        $firmUser->setFirm($this);

        $this->addFirmUser($firmUser);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($user->isFirmidsLoaded()) {
            $user->initFirmids();
            $user->getFirmids()->push($this, $id);
        } elseif (!$user->getFirmids()->contains($this, $id)) {
            $user->getFirmids()->push($this, $id);
        }

    }

    /**
     * Remove user, id of this object
     * through the firm_user cross reference table.
     *
     * @param ChildUser $user,
     * @param int $id
     * @return ChildFirm The current object (for fluent API support)
     */
    public function removeUserid(ChildUser $user, $id)
    {
        if ($this->getUserids()->contains($user, $id)) { $firmUser = new ChildFirmUser();

            $firmUser->setUser($user);
            if ($user->isFirmidsLoaded()) {
                //remove the back reference if available
                $user->getFirmids()->removeObject($this, $id);
            }

            $firmUser->setid($id);
            $firmUser->setFirm($this);
            $this->removeFirmUser(clone $firmUser);
            $firmUser->clear();

            $this->combinationCollUserids->remove($this->combinationCollUserids->search($user, $id));

            if (null === $this->combinationCollUseridsScheduledForDeletion) {
                $this->combinationCollUseridsScheduledForDeletion = clone $this->combinationCollUserids;
                $this->combinationCollUseridsScheduledForDeletion->clear();
            }

            $this->combinationCollUseridsScheduledForDeletion->push($user, $id);
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
        if (null !== $this->aRegion) {
            $this->aRegion->removeFirm($this);
        }
        if (null !== $this->aDistrict) {
            $this->aDistrict->removeFirm($this);
        }
        if (null !== $this->aLegalInfoRelatedById) {
            $this->aLegalInfoRelatedById->removeFirmRelatedById($this);
        }
        $this->id = null;
        $this->active = null;
        $this->status = null;
        $this->changed = null;
        $this->name = null;
        $this->official_name = null;
        $this->url = null;
        $this->subtitle = null;
        $this->description = null;
        $this->postal = null;
        $this->district_id = null;
        $this->address = null;
        $this->city_id = null;
        $this->street = null;
        $this->home = null;
        $this->office = null;
        $this->main_category = null;
        $this->worktime = null;
        $this->views = null;
        $this->created = null;
        $this->moderation_time = null;
        $this->changed_time = null;
        $this->lon = null;
        $this->lat = null;
        $this->random = null;
        $this->logo = null;
        $this->redirect_id = null;
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
            if ($this->collFirmUps) {
                foreach ($this->collFirmUps as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAdvServerOrderss) {
                foreach ($this->collAdvServerOrderss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collComments) {
                foreach ($this->collComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collChildren) {
                foreach ($this->collChildren as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collContacts) {
                foreach ($this->collContacts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFirmGroups) {
                foreach ($this->collFirmGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFirmPhotoss) {
                foreach ($this->collFirmPhotoss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFirmTagss) {
                foreach ($this->collFirmTagss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFirmUsers) {
                foreach ($this->collFirmUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLegalInfosRelatedByFirmId) {
                foreach ($this->collLegalInfosRelatedByFirmId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGroups) {
                foreach ($this->collGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->combinationCollUserids) {
                foreach ($this->combinationCollUserids as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collFirmUps = null;
        $this->collAdvServerOrderss = null;
        $this->collComments = null;
        $this->collChildren = null;
        $this->collContacts = null;
        $this->collFirmGroups = null;
        $this->collFirmPhotoss = null;
        $this->collFirmTagss = null;
        $this->collFirmUsers = null;
        $this->collLegalInfosRelatedByFirmId = null;
        $this->collGroups = null;
        $this->combinationCollUserids = null;
        $this->aRegion = null;
        $this->aDistrict = null;
        $this->aLegalInfoRelatedById = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(FirmTableMap::DEFAULT_STRING_FORMAT);
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
