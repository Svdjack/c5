<?php
//phpinfo();
//die();
// Worthless MVC Engine v0.0.1
// by Bapewka 2015
$timer = microtime(true);


define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'application' . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . "/vendor/autoload.php"); // composer
require_once(APP_PATH . "/propel/conf/config.php"); // setup Propel
require APP_PATH . 'Core' . DIRECTORY_SEPARATOR . 'core.php';

use Propel\Runtime\ActiveQuery\Criteria;
use PropelModel\Base\GroupQuery;
use PropelModel\Base\RegionQuery;
use PropelModel\FirmQuery;
use PropelModel\Map\FirmTableMap;
use PropelModel\TagsQuery;
use wMVC\Core\Application;

$_SERVER['HTTP_HOST'] = 'localhost';
error_reporting(E_ALL);


$domain = 'xn--80adsqinks2h.xn--p1ai/';

$sitemap = new Sitemap($domain);
$sitemap->setPath('/var/www/tvoyafirma/data/www/xn--80adsqinks2h.xn--p1ai/public/asset/sitemap/');
$sitemap->setDir('asset/sitemap/');
$categories = GroupQuery::create()->find(); // кэшируем в памяти коллекцию групп
$categories = $categories->toArray('Id');

$cities = RegionQuery::create()->filterByArea(null,
    Criteria::NOT_EQUAL)->find();

foreach ($cities AS $city) {
    FirmTableMap::clearInstancePool();
    print ("create sitemap {$city->getName()}" . PHP_EOL);
    $loc = $city->getUrl();
    $sitemap->addItem(getValidUrl($loc), '0.9', 'weekly', time());

    $tags = TagsQuery::create()
        ->useFirmTagsQuery()
        ->filterByCityId($city->getId())
        ->endUse()
        ->groupById()
        ->addAsColumn('count', 'COUNT(firm_tags.firm_id)')
        ->find();

    foreach ($tags AS $tag) {
        $loc = $city->getUrl() . '/ключевое-слово/' . $tag->getUrl();
        $sitemap->addItem(getValidUrl($loc), '0.8', 'weekly', time());
        $page_count = ceil($tag->getVirtualColumn('count') / 30);
        for ($index = 1; $index < $page_count; ++$index) {
            $pagination = $loc . '/стр/' . ($index + 1);
            $sitemap->addItem(getValidUrl($pagination), '0.8', 'weekly', time());
        }

    }

    print ("tags done" . PHP_EOL);

    $tags_districts = TagsQuery::create()
        ->useFirmTagsQuery()
        ->filterByCityId($city->getId())
        ->useFirmQuery()
        ->useDistrictQuery()
        ->groupByName()
        ->endUse()
        ->endUse()
        ->endUse()
        ->groupById()
        ->addAsColumn('count', 'COUNT(firm_tags.firm_id)')
        ->addAsColumn('district_name', 'district.name')
        ->find();

    foreach ($tags_districts AS $tag) {
        $loc = $city->getUrl() . '/ключевое-слово/' . $tag->getUrl();
        $sitemap->addItem(getValidUrl($loc) . '?район=' . getValidUrl($tag->getVirtualColumn('district_name')), '0.8', 'weekly', time());
        $page_count = ceil($tag->getVirtualColumn('count') / 30);
        for ($index = 1; $index < $page_count; ++$index) {
            $pagination = $loc . '/стр/' . ($index + 1);
            $sitemap->addItem(getValidUrl($pagination) . '?район=' . getValidUrl($tag->getVirtualColumn('district_name')), '0.8', 'weekly', time());
        }
    }

    print ("tags districts" . PHP_EOL);
    $tags_streets = TagsQuery::create()
        ->useFirmTagsQuery()
        ->filterByCityId($city->getId())
        ->useFirmQuery()
        ->groupByStreet()
        ->endUse()
        ->endUse()
        ->groupById()
        ->addAsColumn('count', 'COUNT(firm_tags.firm_id)')
        ->addAsColumn('street_name', 'firm.street')
        ->find();
    print ("got tags districts" . PHP_EOL);
    foreach ($tags_streets AS $tag) {
        $loc = $city->getUrl() . '/ключевое-слово/' . $tag->getUrl();
        $sitemap->addItem(getValidUrl($loc) . '?улица=' . getValidUrl($tag->getVirtualColumn('street_name')), '0.8', 'weekly', time());
        $page_count = ceil($tag->getVirtualColumn('count') / 30);
        for ($index = 1; $index < $page_count; ++$index) {
            $pagination = $loc . '/стр/' . ($index + 1);
            $sitemap->addItem(getValidUrl($pagination) . '?улица=' . getValidUrl($tag->getVirtualColumn('street_name')), '0.8', 'weekly', time());
        }
    }

    print ("tags streets done" . PHP_EOL);

    $counters_for_bullshit = array();

    $firms = FirmQuery::create()
        ->filterByStatus(1)
        ->filterByActive(1)
        ->findByCityId($city->getId());

    $countfirms = $firms->count();
    $count_temp = 0;
    foreach ($firms as $firm) {
        $loc = trim($firm->getAlias(), '/');
        $sitemap->addItem(getValidUrl($loc), '0.7', 'weekly', time());

        $count_temp++;
        print "($count_temp/$countfirms)" . PHP_EOL;
        $groups = array();
        foreach ($firm->getGroups() as $item) {
            $group = $item;
            if ($item->getLevel() == 3) {
                $group = \PropelModel\GroupQuery::create()->findPk($item->getParent());
            }
            if (empty($counters_for_bullshit[$group->getUrl()])) {
                $counters_for_bullshit[$group->getUrl()] = array('counter' => 1, 'streets' => array(), 'districts' => array());
                if ($firm->getStreet())
                    $counters_for_bullshit[$group->getUrl()]['streets'][$firm->getStreet()] = 1;
                if ($firm->getDistrict())
                    $counters_for_bullshit[$group->getUrl()]['districts'][$firm->getDistrict()->getName()] = 1;
            } else {
                $counters_for_bullshit[$group->getUrl()]['counter']++;
                if ($firm->getStreet()) {
                    if (empty($counters_for_bullshit[$group->getUrl()]['streets'][$firm->getStreet()])) {
                        $counters_for_bullshit[$group->getUrl()]['streets'][$firm->getStreet()] = 1;
                    } else {
                        $counters_for_bullshit[$group->getUrl()]['streets'][$firm->getStreet()]++;
                    }
                }
                if ($firm->getDistrict()) {
                    if (empty($counters_for_bullshit[$group->getUrl()]['districts'][$firm->getDistrict()->getName()])) {
                        $counters_for_bullshit[$group->getUrl()]['districts'][$firm->getDistrict()->getName()] = 1;
                    } else {
                        $counters_for_bullshit[$group->getUrl()]['districts'][$firm->getDistrict()->getName()]++;
                    }
                }
            }
        }
    }

    print ("strapped groups" . PHP_EOL);

    print memory_get_peak_usage() . PHP_EOL;

    foreach ($counters_for_bullshit as $name => $value) {
        $loc = $city->getUrl() . '/' . $name;
        $sitemap->addItem(getValidUrl($loc), '0.8', 'weekly', time());
        $page_count = ceil($value['counter'] / 30);
        for ($index = 1; $index < $page_count; ++$index) {
            $new_loc = $loc . '/стр/' . ($index + 1);
            $sitemap->addItem(getValidUrl($new_loc), '0.8', 'weekly', time());
        }

        foreach ($value['streets'] as $street => $counter) {
            $sitemap->addItem(getValidUrl($loc) . '?улица=' . getValidUrl($street), '0.8', 'weekly', time());
            $page_count = ceil($counter / 30);
            for ($index = 1; $index < $page_count; ++$index) {
                $new_loc = $loc . '/стр/' . ($index + 1);
                $sitemap->addItem(getValidUrl($new_loc) . '?улица=' . getValidUrl($street), '0.8', 'weekly', time());
            }
        }

        foreach ($value['districts'] as $district => $counter) {
            $sitemap->addItem(getValidUrl($loc) . '?район=' . getValidUrl($district), '0.8', 'weekly', time());
            $page_count = ceil($counter / 30);
            for ($index = 1; $index < $page_count; ++$index) {
                $new_loc = $loc . '/стр/' . ($index + 1);
                $sitemap->addItem(getValidUrl($new_loc) . '?район=' . getValidUrl($district), '0.8', 'weekly', time());
            }
        }
    }

    print ("groups done" . PHP_EOL);

    print memory_get_peak_usage() . PHP_EOL;

}

$sitemap->createSitemapIndex($domain, time());


function getValidUrl($url)
{
    return implode('/', array_map('rawurlencode', explode('/', $url)));
}

/**
 * Sitemap
 *
 * This class used for generating Google Sitemap files
 *
 * @package    Sitemap
 * @author     Osman Üngür <osmanungur@gmail.com>
 * @copyright  2009-2015 Osman Üngür
 * @license    http://opensource.org/licenses/MIT MIT License
 * @link       http://github.com/o/sitemap-php
 */
class Sitemap
{
    const EXT = '.xml';
    const SCHEMA = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    const DEFAULT_PRIORITY = 0.5;
    const ITEM_PER_SITEMAP = 10000;
    const SEPERATOR = '-';
    const INDEX_SUFFIX = 'index';
    /**
     *
     * @var XMLWriter
     */
    private $writer;
    private $domain;
    private $path;
    private $dir;
    private $filename = 'sitemap';
    private $current_item = 0;
    private $current_sitemap = 0;

    /**
     *
     * @param string $domain
     */
    public function __construct($domain)
    {
        $this->setDomain($domain);
    }

    /**
     * Sets root path of the website, starting with http:// or https://
     *
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * Sets paths of sitemaps
     *
     * @param string $path
     * @return Sitemap
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Sets filename of sitemap file
     *
     * @param string $filename
     * @return Sitemap
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Adds an item to sitemap
     *
     * @param string $loc URL of the page. This value must be less than 2,048 characters.
     * @param string $priority The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0.
     * @param string $changefreq How frequently the page is likely to change. Valid values are always, hourly, daily, weekly, monthly, yearly and never.
     * @param string|int $lastmod The date of last modification of url. Unix timestamp or any English textual datetime description.
     * @return Sitemap
     */
    public function addItem($loc, $priority = self::DEFAULT_PRIORITY, $changefreq = NULL, $lastmod = NULL)
    {

        if (($this->getCurrentItem() % self::ITEM_PER_SITEMAP) == 0) {
            if ($this->getWriter() instanceof XMLWriter) {
                $this->endSitemap();
            }
            $this->startSitemap();
            $this->incCurrentSitemap();
        }
        $this->incCurrentItem();
        $this->getWriter()->startElement('url');
        $this->getWriter()->writeElement('loc', 'https://' . $this->getDomain() . $loc);
        $this->getWriter()->writeElement('priority', $priority);
        if ($changefreq)
            $this->getWriter()->writeElement('changefreq', $changefreq);
        if ($lastmod)
            $this->getWriter()->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
        $this->getWriter()->endElement();

        if (($this->getCurrentItem() % self::ITEM_PER_SITEMAP) == 0) {
            if ($this->getWriter() instanceof XMLWriter) {
                $this->endSitemap();
            }
            $this->startSitemap();
            $this->incCurrentSitemap();
        }
        $this->incCurrentItem();
        $this->getWriter()->startElement('url');
        $this->getWriter()->writeElement('loc', 'http://' . $this->getDomain() . $loc);
        $this->getWriter()->writeElement('priority', $priority);
        if ($changefreq)
            $this->getWriter()->writeElement('changefreq', $changefreq);
        if ($lastmod)
            $this->getWriter()->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
        $this->getWriter()->endElement();


        return $this;
    }

    /**
     * Returns current item count
     *
     * @return int
     */
    private function getCurrentItem()
    {
        return $this->current_item;
    }

    /**
     * Returns XMLWriter object instance
     *
     * @return XMLWriter
     */
    private function getWriter()
    {
        return $this->writer;
    }

    /**
     * Finalizes tags of sitemap XML document.
     *
     */
    private function endSitemap()
    {
        if (!$this->getWriter()) {
            $this->startSitemap();
        }
        $this->getWriter()->endElement();
        $this->getWriter()->endDocument();
    }

    /**
     * Prepares sitemap XML document
     *
     */
    private function startSitemap()
    {
        $this->setWriter(new XMLWriter());
        if ($this->getCurrentSitemap()) {
            $this->getWriter()->openURI($this->getPath() . $this->getFilename() . self::SEPERATOR . $this->getCurrentSitemap() . self::EXT);
        } else {
            $this->getWriter()->openURI($this->getPath() . $this->getFilename() . self::EXT);
        }
        $this->getWriter()->startDocument('1.0', 'UTF-8');
        $this->getWriter()->setIndent(true);
        $this->getWriter()->startElement('urlset');
        $this->getWriter()->writeAttribute('xmlns', self::SCHEMA);
    }

    /**
     * Assigns XMLWriter object instance
     *
     * @param XMLWriter $writer
     */
    private function setWriter(XMLWriter $writer)
    {
        $this->writer = $writer;
    }

    /**
     * Returns current sitemap file count
     *
     * @return int
     */
    private function getCurrentSitemap()
    {
        return $this->current_sitemap;
    }

    /**
     * Returns path of sitemaps
     *
     * @return string
     */
    private function getPath()
    {
        return $this->path;
    }

    /**
     * Returns filename of sitemap file
     *
     * @return string
     */
    private function getFilename()
    {
        return $this->filename;
    }

    /**
     * Increases sitemap file count
     *
     */
    private function incCurrentSitemap()
    {
        $this->current_sitemap = $this->current_sitemap + 1;
    }

    /**
     * Increases item counter
     *
     */
    private function incCurrentItem()
    {
        $this->current_item = $this->current_item + 1;
    }

    /**
     * Returns root path of the website
     *
     * @return string
     */
    private function getDomain()
    {
        return $this->domain;
    }

    /**
     * Prepares given date for sitemap
     *
     * @param string $date Unix timestamp or any English textual datetime description
     * @return string Year-Month-Day formatted date.
     */
    private function getLastModifiedDate($date)
    {
        if (ctype_digit($date)) {
            return date('Y-m-d', $date);
        } else {
            $date = strtotime($date);
            return date('Y-m-d', $date);
        }
    }

    /**
     * Writes Google sitemap index for generated sitemap files
     *
     * @param string $loc Accessible URL path of sitemaps
     * @param string|int $lastmod The date of last modification of sitemap. Unix timestamp or any English textual datetime description.
     */
    public function createSitemapIndex($loc, $lastmod = 'Today')
    {
        $this->endSitemap();
        $indexwriter = new XMLWriter();
        $indexwriter->openURI($this->getPath() . $this->getFilename() . self::SEPERATOR . self::INDEX_SUFFIX . self::EXT);
        $indexwriter->startDocument('1.0', 'UTF-8');
        $indexwriter->setIndent(true);
        $indexwriter->startElement('sitemapindex');
        $indexwriter->writeAttribute('xmlns', self::SCHEMA);
        for ($index = 0; $index < $this->getCurrentSitemap(); $index++) {
            $indexwriter->startElement('sitemap');
            $indexwriter->writeElement('loc', 'https://' . $loc . $this->getDir() . $this->getFilename() . ($index ? self::SEPERATOR . $index : '') . self::EXT);
            $indexwriter->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
            $indexwriter->endElement();
            $indexwriter->startElement('sitemap');
            $indexwriter->writeElement('loc', 'http://' . $loc . $this->getDir() . $this->getFilename() . ($index ? self::SEPERATOR . $index : '') . self::EXT);
            $indexwriter->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
            $indexwriter->endElement();
        }
        $indexwriter->endElement();
        $indexwriter->endDocument();
    }

    public function getDir()
    {
        return $this->dir;
    }

    public function setDir($dir)
    {
        $this->dir = $dir;
        return $this;
    }
}
