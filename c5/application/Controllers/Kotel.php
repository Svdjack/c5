<?php
namespace wMVC\Controllers;

use PropelModel\Contact;
use PropelModel\Firm;
use PropelModel\FirmGroup;
use PropelModel\FirmQuery;
use PropelModel\FirmTags;
use PropelModel\FirmUser;
use PropelModel\GroupQuery;
use PropelModel\LegalInfo;
use PropelModel\Map\CommentTableMap;
use PropelModel\RegionQuery;
use PropelModel\Tags;
use PropelModel\TagsQuery;
use PropelModel\UrlAliases;
use PropelModel\UrlAliasesQuery;
use PropelModel\UserQuery;
use PropelModel\Comment;
use PropelModel\CommentQuery;
use wMVC\Config;
use wMVC\Core\abstractController;
use wMVC\Core\Exceptions\SystemExit;
use wMVC\Entity\Lang;

Class Kotel extends abstractController
{
    const PROJECT_ID=3;
    const COMMENT_URL='http://kotel:rjnkj,fpf@kotel.spravka.today/comments/send';
    const FIRM_URL='http://kotel:rjnkj,fpf@kotel.spravka.today/api/send';

    public static function outcoming(\PropelModel\Firm $firm)
    {
        $firm_array = [
            'name'          => $firm->getName(),
            'subtitle'      => $firm->getSubtitle(),
            'official_name' => $firm->getOfficialName(),
            'description'   => $firm->getDescription(),
            'postal'        => $firm->getPostal(),
            'address'       => $firm->getAddress(),
            'building'      => $firm->getHome(),
            'office'        => $firm->getOffice(),
            'worktime'      => Lang::kotel_worktime_to_gis($firm->getWorktime()),
            'lon'           => $firm->getLon(),
            'lat'           => $firm->getLat(),
            'groups'        => [

            ],
            'keywords'      => [

            ],
            'attributes'    => [

            ],
            'city'          => [
                'name' => $firm->getRegion()->getName()
            ],
            'contacts'      => [

            ],
            'street'        => [
                'name' => $firm->getStreet()
            ],
            'district'      => '',
            'project'       => [
                'name' => 'твояфирма.рф'
            ]
        ];

        if($firm->getDistrict()){
            $firm_array['district'] = ['name' => $firm->getDistrict()];

        }

        foreach($firm->getGroups() as $g){
            $firm_array['groups'][] = ['name' => $g->getOriginal()];
        }

        foreach($firm->getFirmTagssJoinTags() as $t){
            $firm_array['keywords'][] = ['name' => $t->getTags()->getTag()];
        }

        foreach($firm->getContacts() as $c){
            $firm_array['contacts'][] = [
                'value' => $c->getValue(),
                'type' => $c->getType()
            ];
        }

        self::kotel_send_firm($firm_array);
        return true;
    }

    public static function outcomingComment($comment)
    {
        $firm = $comment->getFirm();
        $city = $firm->getRegion();
        $date = $comment->getDate(null);
        $main_cat = GroupQuery::create()->findPk($firm->getMainCategory())->getUrl();
        $url = Lang::toUrl($firm->getName());

        $firmUrl = 'https://xn--80adsqinks2h.xn--p1ai/'.urlencode($city->getName()).'/'.urlencode($main_cat).'/'.urlencode($url);


        if ($comment->getText()){
            $request = [
                "user"              => $comment->getUser(),
                "email"             => $comment->getEmail() ?: null,
                "text"              => $comment->getText(),
                "score"             => $comment->getScore(),
                "ip"                => $comment->getIp(),
                "date"              => $date->getTimestamp(),
                "firm_name"         => $firm->getName(),
                "firm_subtitle"     => $firm->getSubtitle() ?: null,
                "city_name"         => $city->getName(),
                "project_id"        => self::PROJECT_ID,
                'firm_url'          => $firmUrl,
            ];
            $output = self::kotel_post($request,self::COMMENT_URL);
            return ['answer'=>$output,'request'=>$request];
        }

    }


    public static function kotel_send_firm($firm)
    {
        $data = json_encode($firm);
        $result = [
            'data'    => $data,
            'project' => 'твояфирма.рф'
        ];
        self::kotel_post($result,self::FIRM_URL);
    }

    /**
     * @param  $data
     * @param  $url
     * @return  mixed
     */
    public static function kotel_post($data,$url)
    {
        $data = http_build_query($data);

        if (!function_exists('curl_init')) {
            die('CURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $output;
    }


    public function incoming()
    {
        $post = $_POST;

        $response = [
            'response'  => '',
            'check_url' => ''
        ];

        if ($post['key'] != 'kotlobaza') {
            $response['response'] = 'wrong key';
        }

        $data = json_decode($post['data'], true);


        //add comment or firm?

        if ($this->isComment($data))
            return $this->commentSave($data);

        //double check

        $double_firm = FirmQuery::create()
            ->filterByName($data['name'])
            ->filterBySubtitle($data['subtitle'])
            ->filterByStreet($data['street']['name'])
            ->filterByHome($data['building'])
            ->filterByOffice($data['office'])
            ->useRegionQuery()
            ->filterByName($data['city']['name'])
            ->endUse()
            ->count();

        if($double_firm){
            $response['response'] = 'double!';
            $this->respond($response);
        }



        //sanity check

        if (
            empty($data['city']['name'])
            || empty($data['street']['name'])
            || empty($data['name'])
            || empty($data['building'])
        ) {
            $response['response'] = 'coudnt pass sanity check';
            $this->respond($response);
        }

        $region = RegionQuery::create()->findOneByName($data['city']['name']);
        if (!$region) {
            $response['response'] = 'city not found';
            $this->respond($response);
        }

        $groups_array = array_filter($data['groups']);

        $groups = [];
        foreach ($groups_array as $item) {
            if (empty($item['name'])) {
                continue;
            }
            $item = GroupQuery::create()->findOneByOriginal($item['name']);
            $groups[] = $item;
        }

        if (count($groups) < 1) {
            $response['response'] = 'groups not found';
            $this->respond($response);
        }

        $firm = new Firm();
        $firm->setName($data['name']);
        $firm->setSubtitle($data['subtitle']);
        $firm->setOfficialName($data['official_name']);
        $firm->setDescription($data['description']);
        $firm->setCityId($region->getId());
        $firm->setWorktime(Lang::kotel_worktime($data['worktime']));
        $firm->setPostal($data['postal']);
        $firm->setStreet($data['street']['name']);
        $firm->setHome($data['building']);
        $firm->setOffice($data['office']);
        $firm->setActive(1);
        $firm->setStatus(1);
        $firm->setLat($data['lat']);
        $firm->setLon($data['lon']);
        $firm->setChangedTime(time());
        $firm->setChanged(0);
        $firm->setModerationTime(time());
        $firm->setCreated(time());

        $firm->setMainCategory($groups[0]->getId());

        $firm->setCoordsByAddress(
            $region->getName() .
            " {$data['postal']} {$data['street']['name']} {$data['building']}"
        );

        $firm->save();




        $city = $region->getUrl();
        $main_cat = GroupQuery::create()->findPk($firm->getMainCategory())->getUrl();
        $url = Lang::toUrl($firm->getName());

        $alias = "/{$city}/{$main_cat}/{$url}";

        if (UrlAliasesQuery::create()->filterByAlias($alias)->count()) {
            $alias .= '-' . $firm->getId();
        }

        $alias_obj = new UrlAliases();
        $alias_obj->setSource('/firm/show/' . $firm->getId());
        $alias_obj->setAlias($alias);
        $alias_obj->save();


        $legal = new LegalInfo();
        $legal->setFirmId($firm->getId());
        $legal->save();

        foreach($groups as $group){
            $grp = new FirmGroup();
            $grp->setFirm($firm);
            $grp->setCity($region->getId());
            $grp->setGroupId($group->getId());
            $grp->save();
        }

        $keywords = $data['keywords'];
        foreach($keywords as $keyword){
            if(empty($keyword['name'])){
                continue;
            }
            $tag = TagsQuery::create()->findOneByTag($keyword);
            if (!$tag) {
                $tag = new Tags();
                $tag->setTag($keyword);
                $tag->save();
            }
            $firm_to_tag = new FirmTags();
            $firm_to_tag->setFirm($firm);
            $firm_to_tag->setTags($tag);
            $firm_to_tag->setCityId($firm->getCityId());
            $firm_to_tag->save();
        }


        foreach($data['contacts'] as $value){
            $contact = new Contact();
            $contact->setFirm($firm);
            $contact->setType($value['type']);
            $contact->setValue($value['value']);
            $contact->save();
        }

        $user = UserQuery::create()->findOneByName('kotel21');

        $firm_user = new FirmUser();
        $firm_user->setFirm($firm);
        $firm_user->setUser($user);
        $firm_user->save();

        $response['response'] = 'OK';

        $response['check_url'] = sprintf(
            'http://%s%s',
            Config::$site_url_puny,
            $firm->getAlias()
        );
        $this->respond($response);
    }

    protected function commentSave($data){

       //find_firm
       $firm = FirmQuery::create()
            ->filterByName($data['firm']['name'])
            ->filterBySubtitle($data['firm']['subtitle'])
            ->filterByStreet(self::streetName($data))
            ->filterByHome($data['firm']['building'])
            ->filterByOffice($data['firm']['office'])
            ->useRegionQuery()
            ->filterByName($data['firm']['city']['name'])
            ->endUse()
            ->findOne();

        if (!$firm)
        {
            $this->respond([
                'response'=>'firm not found',
                'check_url'=>''
            ]);

        }


        $region = RegionQuery::create()->findOneByName($data['firm']['city']['name']);
        $city = $region->getUrl();
        $main_cat = GroupQuery::create()->findPk($firm->getMainCategory())->getUrl();
        $url = Lang::toUrl($firm->getName());
        $firmUrl = 'https://xn--80adsqinks2h.xn--p1ai/'.urlencode($city).'/'.urlencode($main_cat).'/'.urlencode($url);

        //is comment exists?

        $comment = CommentQuery::create()
            ->filterByFirmId($firm->getId())
            ->filterByText($data['comment'])
            ->count();
       if ($comment)
        {
            $this->respond([
                'response'=>'comment already exists',
                'check_url'=>$firmUrl
            ]);
        }

        //insert comment
        $comment = new Comment();
        switch($data['score']){
            case '5':
                $emotion=1;
                break;
            case '1':
                $emotion=2;
                break;
            default:
                $emotion=0;
        }
        $comment->setFirmId($firm->getId())
                ->setUser($data['user'])
                ->setText($data['comment'])
                ->setDate($data['created_at'])
                ->setModerationTime($data['updated_at'])
                ->setScore($data['score'])
                ->setIp($data['ip'])
                ->setEmotion($emotion)
                ->setEdited('1');
        $comment->save();

        $this->respond([
            'response'=>'OK',
            'check_url'=>$firmUrl
        ]);
    }

    private function respond($response)
    {
        header("Content-Type: application/json");
        print json_encode($response);
        die();
    }

    public static function debug($arr,$die=true){
        print '<pre>';
        print_r($arr);
        print '</pre>';
        $die && die;
    }

    protected function isComment($data){
        return isset($data['comment']) ? true:false;
    }

    protected static function streetName($data)
    {
        //на этом проекте улицы сохраняются со всеми преффиксами
        $street_exclude = [
            $data['firm']['building'],
            ',',
//            'ул.',
//            'улица',
//            'пос.',
//            'поселок',
//            'пгт.',
//            'пр.',
//            'проезд',
//            'тупик',
//            'пркт.',
//            'проспект',
//            'бул.',
//            'бульвар',
        ];
        $street = trim(str_replace($street_exclude, '', $data['firm']['address']));
        return $street;
    }
}
