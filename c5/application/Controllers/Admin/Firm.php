<?php

namespace wMVC\Controllers\Admin;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
use PropelModel\Child;
use PropelModel\ChildQuery;
use PropelModel\Contact;
use PropelModel\ContactQuery;
use PropelModel\FirmQuery;
use PropelModel\FirmTags;
use PropelModel\FirmTagsQuery;
use PropelModel\FirmUpQuery;
use PropelModel\FirmUser;
use PropelModel\FirmUserQuery;
use PropelModel\GroupQuery;
use PropelModel\LegalInfoQuery;
use PropelModel\Map\FirmTableMap;
use PropelModel\RegionQuery;
use PropelModel\Tags;
use PropelModel\TagsQuery;
use PropelModel\UserQuery;
use wMVC\Config;
use wMVC\Controllers\abstractAdmin;
use wMVC\Controllers\Kotel;
use wMVC\Controllers\Stat;
use wMVC\Core\Exceptions\SystemExit;
use wMVC\Entity\Notifier;

class Firm extends abstractAdmin
{
    const SEARCH_RESULTS = 35;
    
    public function getFirm($id)
    {
        self::requireAdmin();
        $firm = FirmQuery::create()->findPk($id);
        $result = array_change_key_case($firm->toArray(), CASE_LOWER);
        $region = RegionQuery::create()->findPk($firm->getCityId());
        $result['region'] = '';
        if ($region) {
            $result['region'] = $region->getArea();
        };



        $result['groups'] = [];
        foreach ($firm->getGroups() as $grp) {
            $result['groups'][] = array('name' => $grp->getName(), 'id' => $grp->getId());
        }

        $keys_query = $firm->getFirmTagss();
        $key_ids = [];
        foreach ($keys_query as $k) {
            $key_ids[] = $k->getTagId();
        }

        $keys_query = TagsQuery::create()->findPks($key_ids);

        $keys = array();
        foreach ($keys_query as $key) {
            $keys[] = $key->getTag();
        }
//        unset($result['worktime']);
        if ($worktime = json_decode($result['worktime'])) {
            $result['worktime'] = json_decode($result['worktime']);
        } else {
            unset($result['worktime']);
        }
        $result['keywords'] = $keys;

        Propel::disableInstancePooling();

        $result['children'] = [];
        foreach ($firm->getChildren()->toArray() as $child) {
            $result['children'][] = $child['Value'];
        }
        $result['children'] = array_filter($result['children']);
        Propel::enableInstancePooling();

        $result['phones'] = [];
        $result['sites'] = [];
        $result['faxes'] = [];
        $result['emails'] = [];

        $result['url'] = $firm->getAlias();

        $result['jur'] = array_change_key_case($firm->getLegalInfoRelatedById()->toArray(), CASE_LOWER);

        $result['user'] = '';
        $user =  UserQuery::create()
            ->useFirmUserQuery()
            ->filterByFirm($firm)
            ->endUse()
            ->findOne();
        if($user){
            $result['user'] = $user->getEmail();
        }


        if ($okved = unserialize($result['jur']['activities'])) {
            $result['jur']['activities'] = $okved;
        } else {
            $result['jur']['activities'] = array();
        }

        $contacts = $firm->getContacts();

        foreach ($contacts as $contact) {
            switch ($contact->getType()) {
                case 'phone':
                    $result['phones'][] = $contact->getValue();
                    break;

                case 'website':
                    $result['sites'][] = $contact->getValue();
                    break;

                case 'fax':
                    $result['faxes'][] = $contact->getValue();
                    break;

                case 'email':
                    $result['emails'][] = $contact->getValue();
                    break;

                default:
                    break;
            }
        }

        $firmUp = FirmUpQuery::create()->filterByStatus(1)->findOneByFirmId($firm->getId());
        if ($firmUp) {
            $result['up_time'] = date('d.m.Y', $firmUp->getTimeEnd());
            $result['tarif'] = $firmUp->getType();
        }

        self::response($result);
    }

    public function updateFirm($id)
    {
        self::requireAdmin();
        $firm = FirmQuery::create()->findPk($id);

        $content = json_decode(self::getInputContent());

        $firm->setName($content->name);
        $firm->setDescription($content->description);
        $firm->setHome($content->home);
        $firm->setOffice($content->office);
        $firm->setPostal($content->postal);
        $firm->setStreet($content->street);
        $firm->setSubtitle($content->subtitle);
        $firm->setWorktime(json_encode($content->worktime));
        $firm->setOfficialName($content->officialname);
        $firm->setCityId($content->cityid);
        $firm->setModerationTime(time());
        $firm->setChangedTime(time());
        if (isset($content->groups)) {
            $group_ids = array();
            foreach ($content->groups as $group) {
                $group_ids[] = $group->id;
            }
            if ($group_ids) {
                $groups = GroupQuery::create()->findPks($group_ids);
                $firm->setGroups($groups);
            }
        }

        $keywords_ids = array();
        if (isset($content->keywords)) {
            $keywords_ids = array();
            foreach ($content->keywords as $raw_keyword) {
                $keyword = TagsQuery::create()->findOneByTag($raw_keyword);
                if (!$keyword) {
                    $keyword = new Tags();
                    $keyword->setTag($raw_keyword);
                    $keyword->save();
                }

                $keywords_ids[] = $keyword->getId();
            }
        }


        $firm->setActive($content->active);
        $firm->setStatus($content->status);
        $firm->setChanged($content->changed);

        $firm->save();

        $original_user = '';
        $original_user_query =  UserQuery::create()
            ->useFirmUserQuery()
            ->filterByFirm($firm)
            ->endUse()
            ->findOne();
        if($original_user_query){
            $original_user = $original_user_query->getEmail();
        }
        $user = $original_user_query;
        if($content->user !== $original_user){
            FirmUserQuery::create()->findByFirmId($firm->getId())->delete();
            if(!empty($content->user)){
                $new_user = UserQuery::create()->findOneByEmail($content->user);
                if(!$new_user){
                    $new_user = \wMVC\Entity\User::add($content->user);
                }

                $firm_user = new FirmUser();
                $firm_user->setUserId($new_user->getId());
                $firm_user->setFirmId($firm->getId());
                $firm_user->save();

                Notifier::company_attached($new_user, $firm);
                $user = $new_user;
            }

        }

        if ($content->up_time) {
            $firmUp = FirmUpQuery::create()
                ->filterByFirmId($firm->getId())
                ->findOneOrCreate();
            $firmUp->setCash(0);
            $firmUp->setType($content->tarif);
            $firmUp->setStatus(1);
            $firmUp->setEmail(!empty($content->user) ? $user->getEmail() : Config::$mail['project']);
            $firmUp->setSpamType(1);
            $firmUp->isNew() && $firmUp->setTimeStart(time());
            $firmUp->setTimeEnd(strtotime($content->up_time));
            Stat::initFirmStat($firmUp->getFirm());
            $firmUp->save();
        } else {
            $firmUp = FirmUpQuery::create()->findOneByFirmId($firm->getId());
            if ($firmUp) {
                $firmUp->setStatus(0)->save();
            }
        }

        if (isset($content->form)) {
            ContactQuery::create()->findByFirmId($firm->getId())->delete();
            ChildQuery::create()->findByFirmId($firm->getId())->delete();

            foreach ($content->children as $child) {
                $obj = new Child();
                $obj->setFirm($firm);
                $obj->setValue($child);
                $obj->save();
            }

            foreach ($content->phones as $phone) {
                $obj = new Contact();
                $obj->setType('phone');
                $obj->setValue($phone);
                $obj->setFirm($firm);
                $obj->save();
            }

            foreach ($content->sites as $site) {
                $obj = new Contact();
                $obj->setType('website');
                $obj->setValue($site);
                $obj->setFirm($firm);
                $obj->save();
            }

            foreach ($content->faxes as $fax) {
                $obj = new Contact();
                $obj->setType('fax');
                $obj->setValue($fax);
                $obj->setFirm($firm);
                $obj->save();
            }

            foreach ($content->emails as $email) {
                $obj = new Contact();
                $obj->setType('email');
                $obj->setValue($email);
                $obj->setFirm($firm);
                $obj->save();
            }

            $legal_info = LegalInfoQuery::create()->findOneByFirmId($firm->getId());

            $legal_info->setActivities(serialize($content->jur->activities));
            $legal_info->setRegDate($content->jur_regdate);
            $legal_info->setInn($content->jur_inn);
            $legal_info->setKpp($content->jur_kpp);
            $legal_info->setOgrn($content->jur_ogrn);
            $legal_info->setOkpo($content->jur_okpo);
            $legal_info->setOkato($content->jur_okato);
            $legal_info->setFsfr($content->jur_fsfr);
            $legal_info->setOrgForm($content->jur_orgform);
            $legal_info->setOkogu($content->jur_okogu);

            $legal_info->save();

            FirmTagsQuery::create()->findByFirmId($firm->getId())->delete();

            foreach ($keywords_ids as $keyword_id) {
                $obj = new FirmTags();
                $obj->setFirmId($firm->getId());
                $obj->setTagId($keyword_id);
                $obj->save();
            }
        }

        Kotel::outcoming($firm);

        self::response(array('ok' => 1));
    }

    public function deleteFirm($id)
    {
        self::requireAdmin();
        $firm = FirmQuery::create()->findPk($id);
        $firm->setActive(0);
        $firm->setModerationTime(time());
        $firm->save();

        self::response((array('ok' => 1)));
    }

    public function getFirmsList($type, $page = 0, $search = '')
    {
        self::requireAdmin();
        $con = Propel::getWriteConnection(FirmTableMap::DATABASE_NAME);
        $con->useDebug(true);
        $firms = FirmQuery::create();
        switch ($type) {
            case 'firms':
                $firms->condition('status', 'Firm.Status = ?', 0)
                    ->condition('active', 'Firm.Active = ?', 1)
                    ->condition('changed', 'Firm.Changed = ?', 1)
                    ->combine(array('status', 'changed'), 'or', 'first')
                    ->condition('active1', 'Firm.Id IN (SELECT id FROM firm f2 where f2.active = 1)')
                    ->where(array('first', 'active1'), 'and');
                break;

            case 'firms-updated':
                $firms->filterByActive(1)
                    ->filterByChanged(1);
                break;

            case 'firms-new':
                $firms->filterByActive(1)
                    ->filterByStatus(0)
                    ->addAscendingOrderByColumn(FirmTableMap::COL_CREATED);
                break;

            case 'firms-approved':
                $firms->filterByActive(1)
                    ->filterByStatus(1);
                break;

            case 'firms-deleted':
                $firms->filterByActive(0)
                ->filterByRedirectID(0)
                    ->addDescendingOrderByColumn(FirmTableMap::COL_MODERATION_TIME);
                break;

            case 'firms-up':
                $firms->useFirmUpQuery(null, Criteria::INNER_JOIN)
                    ->addAsColumn('time_start', 'firm_up.time_start')
                    ->addAsColumn('time_end', 'firm_up.time_end')
                    ->addAsColumn('email', 'firm_up.email')
                    ->addAsColumn('tarif', 'firm_up.type')
                    ->addAsColumn('status_up', 'firm_up.status')
                    ->orderByTimeStart(Criteria::DESC)
                    ->groupByFirmId()
                    ->endUse();
                break;

            default:
                throw new SystemExit('not found', 404);
                break;
        }
        
        if ($search) {
            $firms->filterByName('%' . $search . '%')
                    ->limit(self::SEARCH_RESULTS);
            $firms = $firms->find();
            /* @var $firms \Propel\Runtime\Collection\ObjectCollection */
            
            $queryDebug = $con->getLastExecutedQuery();

            $data = [];
            foreach ($firms as $firm) {
                /* @var $firm \PropelModel\Firm */
                $arr = $firm->toArray();
                
                $u = $firm->getUser();
                
                if($u) {
                    $arr['username'] = $u->getLogin();
                } else {
                    $arr['username'] = '';
                }
                
                $data[] = array_change_key_case($arr, CASE_LOWER);
            }

            $totalPages = \count($firms) > 0 ? \ceil(\count($firms) / 100) : 1;

            $result = array('data' => $data, 'currentPage' => $page, 'totalPages' => $totalPages);
            $result['debug'] = $queryDebug;
            self::response($result);
            return true;
        }
        
        $firms = $firms->useFirmUserQuery()
                ->joinUser()
                ->addAsColumn('username', 'User.Login')
                ->endUse()
                ->paginate($page,
                100);
        $totalPages = $firms->getLastPage();
        $data = [];
        foreach ($firms as $firm) {
            $data[] = array_change_key_case($firm->toArray(), CASE_LOWER);
        }

        $currentPage = $page;
        $result = array('data' => $data, 'currentPage' => $currentPage, 'totalPages' => $totalPages);
        $result['centir'] = $firms->getNbResults();
        $result['debug'] = $con->getLastExecutedQuery();
        self::response($result);
    }

    public function attachUser($firm_id, $user_id)
    {
        if(!\wMVC\Entity\User::authorized()){
            self::response(['error' => 'Вы не авторизованы'], 403);
            return;
        }
        if(!\wMVC\Entity\User::$data['is_admin']){
            self::response(['error' => 'Вы не авторизованы'], 403);
            return;
        }
        $user = UserQuery::create()->findPk($user_id);
        if (!$user){
            self::response(['error' => 'Пользователь не найден!'], 404);
            return;
        }

        $firm = FirmQuery::create()->findPk($firm_id);
        if (!$firm){
            self::response(['error' => 'Компания не найдена!'], 404);
            return;
        }
        FirmUserQuery::create()->filterByFirmId($firm_id)->delete();
        $firmuser = new FirmUser();
        $firmuser->setFirmId($firm_id);
        $firmuser->setUserId($user_id);
        $firmuser->save();

        Notifier::company_attached($user, $firm);

        self::response(['ok' => 1]);
    }

    public function firmCounter()
    {
        self::requireAdmin();
        $con = Propel::getWriteConnection(FirmTableMap::DATABASE_NAME);
        $con->useDebug(true);
        $types = ['firms'];
        $counters = [];
        foreach ($types as $type) {
            $firms = FirmQuery::create();
            switch ($type) {
                case 'firms':
                    $firms->condition('status', 'Firm.Status = ?', 0)
                        ->condition('active', 'Firm.Active = ?', 1)
                        ->condition('changed', 'Firm.Changed = ?', 1)
                        ->combine(array('status', 'changed'), 'or', 'first')
                        ->condition('active1', 'Firm.Id IN (SELECT id FROM firm f2 where f2.active = 1)')
                        ->where(array('first', 'active1'), 'and');
                    break;

                case 'firms-updated':
                    $firms->filterByChanged(1)
                        ->filterByActive(1);
                    break;

                case 'firms-new':
                    $firms->filterByStatus(1)
                        ->filterByActive(0)
                        ->addAscendingOrderByColumn(FirmTableMap::COL_CREATED);
                    break;

                case 'firms-approved':
                    $firms->filterByStatus(1)
                        ->filterByActive(1);
                    break;

                case 'firms-deleted':
                    $firms->filterByActive(0);
                    break;

                default:
                    throw new SystemExit('not found', 404);
                    break;
            }
            $firms = $firms->useFirmUserQuery()
                ->joinUser()
                ->addAsColumn('username','User.Login')
                ->endUse();

            $counters[$type] = $firms->count();
        }
        $counters['debug'] = $con->getLastExecutedQuery();
        self::response($counters);
    }
    
    protected function getLogoUploadsDir() {
        return THUMBS_PATH . 'company_logo/company_logo/';
}
    
    public function uploadLogo($id)
    {
        self::requireAdmin();

        if (empty($_FILES["file"])) {
            die('Нету файла');
        }

        $uploads_dir = $this->getLogoUploadsDir();

        if (!\file_exists($uploads_dir)) {
            \mkdir($uploads_dir, 0777, true);
        }

        $name = $id . '.png';

        //print_r($id);
        //print_r($_POST);
        //print_r($_FILES);

        if (!move_uploaded_file($_FILES["file"]['tmp_name'], $uploads_dir . $name)) {
            die('Не получилось скопернуть');
        }

        $firm = FirmQuery::create()->findPk($id);
        $firm->setLogo($name);
        $firm->save();

        echo 'OK';
    }
    
    public function deleteLogo($id)
    {
        self::requireAdmin();

        $firm = FirmQuery::create()->findPk($id);
        $name = $firm->getLogo();
        $firm->setLogo('');
        $firm->save();

        $uploads_dir = $this->getLogoUploadsDir();

        if (\file_exists($uploads_dir . $name)) {
            \unlink($uploads_dir . $name);
        }

        echo 'OK';
    }

}