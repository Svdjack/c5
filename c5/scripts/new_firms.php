<?php

require_once __DIR__."/db.php";

require_once __DIR__."/aliases.php";

$timer = microtime(true);

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'application' . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . "/vendor/autoload.php"); // composer
require_once(APP_PATH . "/propel/conf/config.php"); // setup Propel
require APP_PATH . 'Core' . DIRECTORY_SEPARATOR . 'core.php';

use PropelModel\FirmGroup;
use PropelModel\GroupQuery;
use PropelModel\LegalInfo;
use PropelModel\RegionQuery;
use PropelModel\UrlAliases;
use PropelModel\UrlAliasesQuery;
use wMVC\Core\Application;
use wMVC\Entity\Lang;

//
//new Application();

//print "<!-- Page generated in " . (microtime(true) - $timer) . "s -->\n";

$_SERVER['HTTP_HOST'] = 'localhost';

$days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

query('USE tvoyafirma_new');

$firms = query('SELECT id FROM tvoyafirma_valet.firm where created > "2016-07-01"');
$firms_count = query('SELECT count(id) FROM tvoyafirma_valet.firm where created > "2016-07-01"')->fetch_row();
$firms_count = $firms_count[0];
$count = 0;
$doubles_count = 0;
$problems = 0;
$maincats_count = 0;
$cities_count = 0;
$groups_count = 0;
$i = 0;
foreach($firms as $firm_id){
    print "saved $count/$firms_count with $doubles_count doubles and $problems problems:".PHP_EOL."(mcat: $maincats_count, groups: $groups_count, city: $cities_count)";
    print PHP_EOL;
    print PHP_EOL;
    $count++;
    $firm = query('SELECT firm.name, firm.subtitle, firm.description, firm.postal, firm.building, firm.office, firm.worktime, firm.lon, firm.lat, district.name district, street.name street, city.name city
                    FROM tvoyafirma_valet.firm
                    LEFT JOIN tvoyafirma_valet.district ON district.id = firm.district_id
                    LEFT JOIN tvoyafirma_valet.street ON street.id = firm.street_id
                    LEFT JOIN tvoyafirma_valet.city ON city.id = firm.city_id
                    WHERE firm.id = '. $firm_id['id'])->fetch_assoc();


    $double_firm = \PropelModel\FirmQuery::create()
        ->filterByName($firm['name'])
        ->filterBySubtitle($firm['subtitle'])
        ->filterByStreet($firm['street'])
        ->filterByHome($firm['building'])
        ->findOne();

    if($double_firm){
        $doubles_count++;
        print "seems like double, skipping...".PHP_EOL;
        continue;
    }

    $contacts = array();
    foreach(query('SELECT * FROM tvoyafirma_valet.firm_contacts where firm_id = '.$firm_id['id']) as $cc){
        $contacts[] = $cc;
    }

    $groups = array();

    foreach(query('SELECT groups.name FROM tvoyafirma_valet.groups LEFT JOIN tvoyafirma_valet.firm_group fg ON fg.group_id = groups.id WHERE parent != 0 and fg.firm_id = '.$firm_id['id']) as $cc){
        $groups[] = $cc['name'];
    }

    //всю инфу собрали, ищем всякие айдишники для вставки из живой базы...

    $city_id = query('SELECT id FROM region WHERE name = "'.htmlspecialchars($firm['city']).'"')->fetch_row();

    $city_id = $city_id[0];
    if(!$city_id){
        $problems++;
        $cities_count++;
        continue;
    }

    $group_ids = array();
    foreach($groups as $cc){
        $name = @$aliases[$cc];
        if(!$name){
            continue;
        }
        $grep = query('SELECT id FROM `groups` where name = "'.$name.'"')->fetch_assoc();
        $group_ids[] = $grep['id'];
    }

    $group_ids = array_filter($group_ids);

    if(count($group_ids) < 1){
        $groups_count++;
        $problems++;
        continue;
    }

    $worktime = $firm['worktime'];

    $worktime = unserialize($worktime);
    $worktime && $worktime = array_values($worktime);
    $new_worktime = [];

    for ($d = 0; $d < 7; $d++) {
        if (!empty($worktime[$d]['working_hours'])) {
            $day = $worktime[$d]['working_hours'];
            if (count($day) == 1) {
                $day = reset($day);

                $new_worktime[$days[$d]]['start'] = explode(':', $day['from'])[0].":00";
                $new_worktime[$days[$d]]['end'] = explode(':', $day['to'])[0].":00";
                $new_worktime[$days[$d]]['type'] = "normal";


            } else {
                $from = array_shift($day);
                $to = array_pop($day);

                $new_worktime[$days[$d]]['type'] = "normal_with_rest";

                $new_worktime[$days[$d]]['start'] = explode(':', $from['from'])[0].":00";
                $new_worktime[$days[$d]]['end'] = explode(':', $to['to'])[0].":00";

                $new_worktime[$days[$d]]['obed']['start'] = explode(':', $from['to'])[0].":00";
                $new_worktime[$days[$d]]['obed']['end'] = explode(':', $to['from'])[0].":00";



            }
        } else {
            $new_worktime[$days[$d]]['type'] = "rest";
        }
    }


    $new_worktime = json_encode($new_worktime, JSON_UNESCAPED_UNICODE);

    $phones = array();
    $sites = array();
    $fax = '';
    $email = '';

    foreach($contacts as $cc){
        if(in_array($cc['type'], array('mobile', 'phone'))){
            $phones[] = $cc['value'];
        }
        if(in_array($cc['type'], array('website'))){
            $sites[] = $cc['value'];
        }
        if($cc['type'] == 'fax'){
            $fax = $cc['value'];
        }
        if($cc['type'] == 'email'){
            $email = $cc['value'];
        }
    }

    $maincat = $group_ids[0];
    if(!$maincat){
        $problems++;
        $maincats_count++;
        continue;
    }

    $new_firm = new \PropelModel\Firm();

    $new_firm->setName($firm['name']);
    $new_firm->setSubtitle($firm['subtitle']);
    $new_firm->setCityId($city_id);
    $new_firm->setStreet($firm['street']);
    $new_firm->setHome($firm['building']);
    $new_firm->setOffice($firm['office']);
    $new_firm->setPostal($firm['postal']);
    $new_firm->setDescription($firm['description']);
    $new_firm->setWorktime($new_worktime);
    $new_firm->setMainCategory($maincat);

    $new_firm->setLat($firm['lat']);
    $new_firm->setLon($firm['lon']);

    $region = RegionQuery::create()->findPk($city_id);

    $new_firm->save();

    $city = $region->getUrl();
    $main_cat = GroupQuery::create()->findPk($new_firm->getMainCategory())->getUrl();
    $url = Lang::toUrl($new_firm->getName());

    $alias = "/{$city}/{$main_cat}/{$url}";

    if (UrlAliasesQuery::create()->filterByAlias($alias)->count()) {
        $alias .= '-' . $new_firm->getId();
    }

    $alias_obj = new UrlAliases();
    $alias_obj->setSource('/firm/show/' . $new_firm->getId());
    $alias_obj->setAlias($alias);
    $alias_obj->save();



    $legal = new LegalInfo();
    $legal->setFirmId($new_firm->getId());
    $legal->setRegDate(time());
    $legal->setActivities(serialize(array()));
    $legal->save();

    foreach ($group_ids as $category) {
        $grp = new FirmGroup();
        $grp->setCity($city_id);
        $grp->setFirm($new_firm);
        $grp->setGroupId($category);
        $grp->save();
    }



    //факс
    if($fax){
        $c = new \PropelModel\Contact();
        $c->setType('fax');
        $c->setValue($fax);
        $c->setFirm($new_firm);
        $c->save();
    }

    //мыло
    if($email){
        $c = new \PropelModel\Contact();
        $c->setType('email');
        $c->setValue($email);
        $c->setFirm($new_firm);
        $c->save();
    }

    //сайтики
    foreach($sites as $site){
        $c = new \PropelModel\Contact();
        $c->setType('website');
        $c->setValue($site);
        $c->setFirm($new_firm);
        $c->save();
    }

    //телефончики
    foreach($phones as $phone){
        $c = new \PropelModel\Contact();
        $c->setType('phone');
        $c->setValue($phone);
        $c->setFirm($new_firm);
        $c->save();
    }



//    $post = array(
//        'tid' => implode(',', $group_ids),
//        'field_spec' => $firm['description'],
//        'title' => $firm['subtitle'] . ' ' . $firm['title'],
//        'city_id' => $city_id,
//        'field_street' => $firm['street'],
//        'field_home' => $firm['building'],
//        'field_office' => $firm['office'],
//        'field_index' => $firm['postal'],
//        'field_coords' => $firm['lon'] . ',' . $firm['lat'],
//        'worktime_s' => $new_worktime,
//        'field_fax' => $fax,
//        'field_email' => $email,
//        'field_phones' => $phones,
//        'field_site' => $sites
//    );
//
//    $firm_object = new \Magick\Firm(['data_to_save' => $post]);
//    $firm_object->save();

}