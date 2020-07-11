<?php

namespace wMVC\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Endroid\QrCode\QrCode;
use Plasticbrain\FlashMessages\FlashMessages;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Propel;
use PropelModel\ChildQuery;
use PropelModel\Comment;
use PropelModel\CommentQuery;
use PropelModel\ContactQuery;
use PropelModel\FirmPhotos;
use PropelModel\FirmPhotosQuery;
use PropelModel\FirmUpQuery;
use PropelModel\GroupQuery;
use PropelModel\FirmQuery;
use PropelModel\LegalInfoQuery;
use PropelModel\Region;
use PropelModel\RegionQuery;
use PropelModel\SitesDataQuery;
use PropelModel\TagsQuery;
use wMVC\Config;
use wMVC\Core\abstractController;
use wMVC\Core\Exceptions\SystemExit;
use wMVC\Core\Router;
use wMVC\Entity\Breadcrumb;
use wMVC\Entity\Lang;
use wMVC\Entity\Notifier;
use wMVC\Entity\User;
use wMVC\Components\Ip;

Class Firm extends abstractController
{
    public function show($firmId = null)
    {
        if (!$firmId) {
            throw new SystemExit('not found', 404);
        }

        if (!$firm = FirmQuery::create()->findPk($firmId)) {
            throw new SystemExit('not found', 404);
        }

        if ($firm->getRedirectID()) {
            if (!$new_firm = FirmQuery::create()->findPk($firm->getRedirectID())) {
                throw new SystemExit('not found', 404);
            }
            (new Router)->redirect($new_firm->getAlias());
            die();
        }


        if ($firm->getActive() == 0) {
            throw new SystemExit('not found', 404);
        }

        $region = $firm->getRegion();
        $parentRegion = RegionQuery::create()->findPk($region->getArea());

        $randomGroup = GroupQuery::getRandomGroup();

        $regionsListCollection = RegionQuery::create()->findByArea($region->getArea());
        $cityUrl = $region->getUrl();
        $groups = $firm->getGroups();
        $mainGroup = GroupQuery::create()->findPk($firm->getMainCategory());
        if ($mainGroup->getLevel() > 2) {
            $mainGroup = GroupQuery::create()->findPk($mainGroup->getParent());
        }

        $regionsList = [];

        $groupsLevel3 = $this->getLevel3($groups);


        foreach ($regionsListCollection as $item) {
            $regionsList[] = [
                'alt'  => $item->getCase(3),
                'name' => $item->getName(),
                'url'  => $item->getUrl()
            ];
        }

        $regionInfo = array(
            'name'   => $region->getCase(5),
            'prefix' => $region->getPrefix(),
            'list'   => $regionsList
        );


        //generating breadcrumbs
        $breadcrumbs = new Breadcrumb();
        $breadcrumbs = $breadcrumbs->addCrumb('Россия', '/')
            ->addCrumb($parentRegion->getName(), '/' . $parentRegion->getUrl(), $parentRegion->getName())
            ->addCrumb($region->getName(), '/' . $region->getUrl(), $region->getName())
            ->addCrumb(
                $mainGroup->getName(), '/' . $region->getUrl() . '/' . $mainGroup->getUrl(),
                $mainGroup->getName() . ' ' . $region->getPrefix() . ' ' . $region->getCase(5)
            )
            ->addCrumb($firm->getName())
            ->getArray();

        $h2 = null;

        $seb = $firm->getSubtitle();
        if (!empty($seb) && $firm->getLegalInfoRelatedById()) {
            $inn = $firm->getLegalInfoRelatedById()->getInn();
            $ogrn = $firm->getLegalInfoRelatedById()->getOgrn();
            $h2 = $firm->getSubtitle()
                . (!empty($inn) ? " ИНН {$firm->getLegalInfoRelatedById()->getInn()}" : "")
                . (!empty($ogrn) ? " / ОГРН {$firm->getLegalInfoRelatedById()->getOgrn()}" : "");
        };

        $contactTypes = ['email', 'fax', 'phone', 'website'];
        $contacts = [];

        foreach (ContactQuery::create()->filterByFirm($firm)->find() as $item) {
            $value = $item->getValue();
            if ($item->getType() == 'website') {
                $value = [
                    'url'  => $item->getid(),
                    'name' => $item->getValue()
                ];
                $value['name'] = str_replace(['http://', 'https://'], '', $value['name']);
                $value['name'] = 'http://' . idn_to_utf8($value['name'], 0, INTL_IDNA_VARIANT_UTS46);
            }
            $contacts[$item->getType()][] = $value;
        }


        if (!empty($contacts['phone'][0])) {
            $contacts['clearPhone'] = $this->clearPhone($contacts['phone'][0]);
        }

        $address = $this->generateAddress($firm);

        $specialization = '';
        if (count($groupsLevel3)) {
            $specs = '';
            foreach ($groupsLevel3 as $item) {
                $specs .= mb_strtolower($item) . ", ";
            }
            $specialization = "Работает в сферах: " . trim($specs, ', ') . ".";
            unset($specs);
        }

        //$nearby = $firm->getNearby($this->cache);
        $district = null;
        if ($district = $firm->getDistrict()) {
            $district = $district->getName();
        }

        $mainCategory = ['name'  => $mainGroup->getName(),
                         'url'   => $mainGroup->getUrl(),
                         'title' => $mainGroup->getName() . ' ' . $region->getPrefix() . ' '
                             . $region->getCase(5)];

        $siteInfo = null;
        if (isset($contacts['website'][0])) {
            if ($siteInfo = SitesDataQuery::create()->findOneByUrl($contacts['website'][0])) {
                $siteInfo = ['screen'      => $siteInfo->getScreen(),
                             'title'       => $siteInfo->getTitle(),
                             'description' => $siteInfo->getDescription()];
            }
        }

        $comments = $this->getComments($firm);
        $children = $this->getChildren($firm);
        $photos = $this->getPhotos($firm);
        $logo = $firm->getLogo();

        $worktime = false;

        $default_worktime
            = '{"monday":{"start":"09:00","end":"18:00","type":"normal",
        "obed":{"start":"12:00","end":"13:00"}},"tuesday":{"start":"09:00","end":"18:00","type":"normal","obed":{"start":"12:00","end":"13:00"}},"wednesday":{"start":"09:00","end":"18:00","type":"normal","obed":{"start":"12:00","end":"13:00"}},"thursday":{"start":"09:00","end":"18:00","type":"normal","obed":{"start":"12:00","end":"13:00"}},"friday":{"start":"09:00","end":"18:00","type":"normal","obed":{"start":"12:00","end":"13:00"}},"saturday":{"start":"09:00","end":"18:00","type":"normal","obed":{"start":"12:00","end":"13:00"}},"sunday":{"start":"09:00","end":"18:00","type":"normal","obed":{"start":"12:00","end":"13:00"}}}';
        $default_worktime = json_decode($default_worktime);

        //weird ass comparsion...
        if ((array)($wt_temp = json_decode($firm->getWorktime())) != (array)$default_worktime) {
            $worktime = (array)$wt_temp;
            unset($wt_temp);
        }

        $firmInfo = [
            'title'          => $firm->getName(),
            'id'             => $firm->getId(),
            'h2'             => $h2,
            'coords'         => "{$firm->getLat()}, {$firm->getLon()}",
            'cityPrefix'     => $region->getPrefix(),
            'city'           => $region->getName(),
            'city5'          => $region->getCase(5),
            'region'         => $parentRegion->getName(),
            'address'        => $address,
            'district'       => $district,
            'description'    => $firm->getDescription(),
            'contact'        => $contacts,
            'specialization' => $specialization,
            'worktime'       => $worktime,
            'mainCategory'   => $mainCategory,
            'similar'        => $nearby,
            'site_info'      => $siteInfo,
            'reviews'        => $comments,
            'children'       => $children,
            'photos'         => $photos,
            'legal'          => $this->getLegal($firm),
            'logo'           => $logo,
            'status'         => $firm->getStatus(),
            'official_name'  => $firm->getOfficialName()
        ];


        $tarif = [];
        $firmUp = FirmUpQuery::create()->findOneByFirmId($firm->getId());
        if ($firmUp) {
            $tarif = AdvClient::getTarif($firmUp->getType());
        }
        //meta crap
        $title = $this->generateTitle($firm, $region);
        $description = $this->generateDescription($firm, $region, $firmInfo);
        $keywords = $this->generateKeywords($firm, $region, $groups);

        $regionArray = ['prefix' => $region->getPrefix(),
                        'case5'  => $region->getCase(5),
                        'name'   => $region->getName(),
                        'list'   => []];
        foreach (
            RegionQuery::create()->addAscendingOrderByColumn('url')->filterByArea($region->getArea())->find()
            as $item
        ) {
            $regionArray['list'][] = ['url' => $item->getUrl(), 'name' => $item->getName(),
                                      'alt' => $item->getCase(3)];
        }

        $owner = 0;
        if (in_array(User::getId(), array_keys($firm->getUsers()->toArray('Id')))) {
            $owner = 1;
        }


        $corrupt_ids = [1, 315, 771];

        $has_real_user = 0;
        if ($arich = $firm->getUser()) {
            if (!in_array($firm->getUser()->getId(), $corrupt_ids)) {
                $has_real_user = 1;
            }
        }

        $is_admin = 0;
        if (!empty(User::$data['is_admin']) && User::$data['is_admin']) {
            $is_admin = 1;
        }

        /*Премодерация*/
        if (!$owner && $firm->getStatus() == 0 && !$is_admin) {
            throw new SystemExit('not found', 404);
        }
        $message = '';
        if (isset($_COOKIE['NewCommentMessage'])) {
            $message = 'Спасибо! Ваш отзыв появится на сайте после проверки модератором.';
        }
 
        $vars = [
            'head'          => [
                'title'       => $title,
                'description' => $description,
                'keywords'    => trim($keywords, " ,"),
                'noindex'     => $firm->getStatus() === 0
            ],
            'cityUrl'       => $cityUrl,
            'region'        => $regionInfo,
            'header'        => [
                'placeholder' => 'Например: ' . $randomGroup->getName(),
                'region'      => $regionArray,
                'cityUrl'     => $cityUrl
            ],
            'breadcrumbs'   => $breadcrumbs,
            'firm'          => $firmInfo,
            'city'          => $region,
            'owner'         => $owner,
            'has_real_user' => $has_real_user,
            'admin'         => $is_admin,
            'tarif'         => $tarif,
            'message'       => $message
        ];
        
        $this->view->addGlobal('googleads', Lang::adsense_check_text($title, $description, $contacts, $siteInfo) == 0);
        print $this->view->render('@firm/show.twig', $vars);

    }

    public function upload_photo($firm_id)
    {
        if (!$firm_id) {
            throw new SystemExit('not found', 404);
        }

        if (!$firm = FirmQuery::create()->findPk($firm_id)) {
            throw new SystemExit('not found', 404);
        }

        $city = RegionQuery::create()->findPk($firm->getCityId());
        $city_name = Lang::transliterate($city->getUrl());
        if (!empty($_FILES)) {
            $tempFile = $_FILES['file']['tmp_name'];
            $filename = Lang::transliterate($_FILES['file']['name']);

            $targetPath = ASSET_PATH . 'company-photos/' . $city_name . '/' . $firm_id . '/';
            if (!file_exists($targetPath)) {
                mkdir($targetPath, 0777, true);
            }

            $targetFile = $targetPath . $filename;
            if (is_file($targetFile)) {
                $targetFile = $targetPath . mt_rand(100, 999) . time() . $filename;
            }

            move_uploaded_file($tempFile, $targetFile);

            $photo = new FirmPhotos();
            $photo->setFirm($firm);
            $photo->setPhoto(serialize(['n' => str_replace(ASSET_PATH, '', $targetFile)]));
            $photo->save();
        }
    }

    public function format($type, $firm_id)
    {
        $types = ['для-печати', 'pdf'];

        if (
            !in_array($type, $types)
            || !$firm_id
            || !$firm = FirmQuery::create()->findPk($firm_id)
        ) {
            throw new SystemExit('not found', 404);
        }

        $vars = [];

        $region = $firm->getRegion();
        $address_array = [];
        $address_array[] = 'г. ' . $region->getName();
        $address_array[] = $firm->getStreet();
        $address_array[] = $firm->getHome();
        $address = '';
        $address = implode(', ', array_filter($address_array));

        $alias = $firm->getAlias();

        $sites = [];
        $phones = [];

        foreach ($firm->getContacts()->toArray() as $contact) {
            $contact['Type'] == 'phone' && $phones[] = $contact['Value'];
            $contact['Type'] == 'website' && $sites[] = $contact['Value'];
        }

        $main_cat = GroupQuery::create()->findPk($firm->getMainCategory());
        $main_cat_name = $main_cat->getName() . ' ' . $region->getPrefix() . ' ' . $region->getCase(5);
        $main_cat_link = '/' . $region->getUrl() . '/' . $main_cat->getUrl();


        $vars['address'] = $address;
        $vars['alias'] = $alias;
        $vars['contacts']['phones'] = $phones;
        $vars['contacts']['websites'] = $sites;
        $vars['main_cat'] = ['name' => $main_cat_name,
                             'link' => $main_cat_link];
        $vars['h1'] = $firm->getName();
        $vars['h2'] = $firm->getSubtitle();
        $vars['coords'] = $firm->getLon() . ',' . $firm->getLat();
        $vars['type'] = $type;
        $logo = file_get_contents(ROOT_PATH . '/public/asset/images/logo_print.png');

        $vars['logo'] = base64_encode($logo);


        $html = $this->view->render('@firm/print.twig', $vars);

        if ($type == 'для-печати') {
            print $html;
        }

        if ($type == 'pdf') {
            $mpdf = new \mPDF();
            $mpdf->WriteHTML($html);
            $mpdf->Output();
            exit;
        }
    }

    public function qrcode($firm_id)
    {
        if (!$firm = FirmQuery::create()->findPk($firm_id)) {
            throw new SystemExit('no company', 404);
        }

        $website = '';
        $phone = '';

        $contacts = ContactQuery::create()->findByFirmId($firm_id)->toArray();
        foreach ($contacts as $contact) {
            if ($contact['Type'] == 'phone') {
                empty($phone) && $phone = $contact['Value'];
            }

            if ($contact['Type'] == 'website') {
                empty($website) && $website = $contact['Value'];
            }
        }

        $address = $this->generateAddress($firm);

        $text
            = "
            BEGIN:VCARD
            VERSION:3.0
            ORG:" . $firm->getName() . "
            URL:{$website}
            TEL:{$phone}
            ADR;TYPE=home:{$address};
            END:VCARD
            ";

        header('Content-type: image/png');
        $code = new QrCode();
        $code->setText($text)
//            ->setSize(167)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->render();
    }


    public function review($firm_id)
    {
        if (empty($_POST)) {
            die('whatcha doin here? go away');
        }
        $required_fields = ['name', 'score', 'emotion', 'text'];

        foreach ($required_fields as $field) {
            if ($field == 'emotion' && isset($_POST['emotion']) && in_array($_POST['emotion'], [0, 1, 2])) {
                continue;
            }
            if (empty($_POST[$field])) {
                die('Не заполнены необходимые поля, если вы видите это сообщение - включите JS и попробуйте снова.');
            }
        }

        if (!$firm = FirmQuery::create()->findPk($firm_id)) {
            throw new SystemExit('no company', 404);
        }

        $comment = new Comment();

        /*Премодерация*/
        /*$comment->setActive(0);
         * Исправил в getComments на доп.условие filterByEdited(1)
         */
        

        $comment->setFirmId($firm_id);
        $comment->setUser($_POST['name']);
        $comment->setScore($_POST['score']);
        $comment->setEmotion($_POST['emotion']);
        $comment->setText($_POST['text']);
        $comment->setDate(time());
        $comment->setIp(Ip::get());

        if (!empty($_POST['email'])) {
            $comment->setEmail($_POST['email']);
        }

        if (!empty($_POST['twitter']) && $_POST['twitter'] == 1) {
            $comment->setTwitter(1);
        }

        $comment->save();
        Notifier::new_comment($comment);

        $link = $firm->getAlias();
        $link = 'http://' . $_SERVER['SERVER_NAME'] . $link;

        if ($comment->getTwitter() == 1) {
            //отправляем в твиттер
            define("CONSUMER_KEY", "ZiQ3wBjo2eDYn2H46w1UabJmd");
            define("CONSUMER_SECRET", "36wAf0PICfSkfO8IOD9BoUhLHbawJI0rNlCHrCAS1dbVGfafZL");
            define("OAUTH_TOKEN", "2611326686-G3xhBcyWPLzSKy83ydheoDFNyZ4GTTCVstB1K5j");
            define("OAUTH_SECRET", "Rw4gdL4KkHLJJNi0lvu3ZJnqJnEBeartyOXfnlaloumT7");


            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
            $content = $connection->get('account/verify_credentials');
            $connection->post(
                'statuses/update',
                array('status' => $comment->getUser() . ' добавил новый отзыв на сайте ТвояФирма.рф ' . $link)
            );
        }
        setcookie("NewCommentMessage", true, time() + 20, '/');
        header('Location: ' . $link);
        die();

    }

    private function getLegal(\PropelModel\Firm $firm)
    {
        if (!$firm) {
            return [];
        }

        $data = LegalInfoQuery::create()->findOneByFirmId($firm->getId());

        if (!$data) {
            return [];
        }
        $query = $data->toArray();

        $info['Activities'] = [];

        if ($query['Activities']) {
            $info['Activities'] = unserialize($query['Activities']);
        }

        $info['codes'] = array_filter(
            [
                'ИНН'       => $query['Inn'],
                'КПП'       => $query['Kpp'],
                'ОГРН'      => $query['Ogrn'],
                'ОКПО'      => $query['Okpo'],
                'Код ОКАТО' => $query['Okato'],
                'Код ФСФР'  => $query['Fsfr'],
                'ОПФ'       => $query['OrgForm'],
                'ОКОГУ'     => $query['Okogu']
            ]
        );


        return $info;
    }

    private function getLevel3(ObjectCollection $groups)
    {
        $groupsArray = [];
        foreach ($groups as $item) {
            if ($item->getLevel() == 3) {
                $groupsArray[] = $item->getName();
            }
        }
        return $groupsArray;
    }


    private function getComments(\PropelModel\Firm $firm)
    {
        $reviews = CommentQuery::create()
            ->filterByFirmId($firm->getId())
            ->filterByActive(1)
            ->filterByEdited(1)
            ->addDescendingOrderByColumn('date')
            ->groupById()
            ->find()
            ->toArray();

        $comments = [
            'comments' => $reviews,
            'title'    => 'Голосов еще нет'
        ];

        if (!function_exists('array_column')):

            function array_column(array $input, $column_key, $index_key = null)
            {

                $result = array();
                foreach ($input as $k => $v) {
                    $result[$index_key ? $v[$index_key] : $k] = $v[$column_key];
                }

                return $result;
            }
        endif;

        if ($comments['count'] = count($reviews)) {
            $comments['average'] = array_sum(array_column($reviews, 'Score')) / $comments['count'];
            $comments['title'] = 'Средняя оценка ' . round($comments['average']);
        }

        return $comments;
    }

    private function clearPhone($phone)
    {
        $phone = preg_replace('/[а-яА-Я\)\(]/ui', '', $phone);
        $phone = trim($phone);
        if (stripos($phone, '+7') === false AND stripos($phone, '8') !== 0) {
            $phone = '+7 ' . $phone;
        }

        return $phone;
    }

    private function generateTitle(\PropelModel\Firm $firm, Region $region)
    {
        $subtitle = $firm->getSubtitle();
        return (!(empty($subtitle) AND ($firm->getSubtitle() != $firm->getName())) ? "{$firm->getSubtitle()} "
                : "")
            . " «{$firm->getName()}» — г.{$region->getName()}, {$firm->getStreet()}, {$firm->getHome()}";
    }

    private function generateDescription(\PropelModel\Firm $firm, Region $region, $firmInfo)
    {
        $fields = $firmInfo['legal'];
        $legal = $firm->getLegalInfoRelatedById();

        if ($legal) {
            if (!$inn = $legal->getInn()) {
                $inn = '';
            }
            if (!$ogrn = $legal->getOgrn()) {
                $ogrn = '';
            }
        } else {
            $inn = $ogrn = '';
        }

        if ($inn) {
            $inn = ' ' . $inn;
        }

        if ($ogrn) {
            $ogrn = ' ' . $ogrn;
        }

        $phone = '';
        if (!empty($firmInfo['contact']['clearPhone'])) {
            $phone = "Телефон {$firmInfo['contact']['clearPhone']}.";
        }

        $seb = $firm->getSubtitle();
        return (!(empty($seb) AND ($firm->getSubtitle() != $firm->getName()))
                ? " — {$firm->getSubtitle()} "
                :
                "") .
            $firm->getName() . " " . $region->getPrefix() . " "
            . $region->getCase(5)
            . " ― ИНН{$inn}, ОГРН{$ogrn}, г. {$firmInfo['city']}, {$firmInfo['address']}, схема проезда.
            {$phone} Возможно добавление отзыва.";
    }

    private function generateKeywords(\PropelModel\Firm $firm, Region $region, ObjectCollection $groups)
    {
        Propel::disableInstancePooling();
        $tags = array_keys($firm->getFirmTagss()->toArray('TagId'));
        Propel::enableInstancePooling();
        $tags = TagsQuery::create()->findPks($tags);

        $keywords = '';

        foreach ($tags as $tag) {
            $keywords .= $tag->getTag() . ", ";
        }

        $keywords .= $firm->getName() . ", ";
        $keywords .= $region->getName() . ", ";
        foreach ($groups as $item) {
            if ($item->getLevel() == 2) {
                $keywords .= $item->getName() . ", ";
            }
        }
        $keywords .= "г. " . $region->getName() . ", " . $this->generateAddress($firm)
            . ", ИНН, ОГРН, схема проезда, отзывы, график работы, режим работы на карте";
        return $keywords;
    }

    private function generateAddress(\PropelModel\Firm $firm)
    {
        $address = "{$firm->getStreet()}, {$firm->getHome()}";
        if ($firm->getOffice()) {
            $address .= ", {$firm->getOffice()}";
        }

        return $address;
    }

    private function getChildren(\PropelModel\Firm $firm)
    {
        Propel::disableInstancePooling();
        $children = ChildQuery::create()
            ->findByFirmId($firm->getId())
            ->toArray();
        Propel::enableInstancePooling();
        return $children;
    }

    private function getPhotos(\PropelModel\Firm $firm)
    {
        Propel::disableInstancePooling();
        $query = FirmPhotosQuery::create()
            ->findByFirmId($firm->getId())
            ->toArray();
        Propel::enableInstancePooling();

        $photos = [];
        foreach ($query as $item) {
            $photos[] = unserialize($item['Photo'])['n'];
        }
        return $photos;
    }

    public function mistake()
    {
        $firm_id = $_GET['f'];
        $firm = FirmQuery::create()->findPk($firm_id);
        print $this->view->render(
            '@firm/error.twig', ['company_name' => $firm->getName(),
                                 'firm_id'      => $firm->getId()]
        );
    }

    public function mistakeHandler()
    {
        $_POST = self::sanitize_array($_POST);
        $response = [];
        if (empty($_POST['body'])) {
            $response['error'] = 'Поле Информация обязательно для заполнения!';
        } else {
            if (empty($_POST['email'])) {
                $_POST['email'] = Config::$mail['project'];
            }
            $_POST['body'] .= '<br><a href="http://твояфирма.рф/admin/#firm/edit/' . $_POST['firm_id'] . '">Редактировать</a>';
            $mail = Notifier::prepare_mail(
                'Пользователь оставил сообщение об ошибке',
                $_POST['email'],
                Config::$mail['project'], $_POST['body']
            );
            Notifier::send_mail($mail);
            $response['success'] = 'Спасибо за информацию!';
        }

        header("Content-Type: application/json");
        print json_encode($response);
        die();
    }

    public function showSite($id)
    {

        throw new SystemExit('not found', 404);


        $site = ContactQuery::create()->findPk($id);
        if ($site->getType() != 'website') {
            throw new SystemExit('', 404);
        }
        $firm_name = $site->getFirm()->getName();
        $title = "Сайт компании {$firm_name}";
        $site = $site->getValue();
        $site = str_replace('https://', '', $site);
        $site = str_replace('http://', '', $site);
        $site = str_replace('//', '', $site);
        $site = "http://{$site}";
        print $this->view->render(
            '@firm/show_site.twig', [
                'head'       =>
                    [
                        'title' => $title
                    ],
                'iframe_url' => $site
            ]
        );
    }

    public function myCompany($id, $errors = [])
    {
        $user_mail = null;
        if (User::authorized()) {
            $user_mail = User::$data['email'];
        }

        print $this->view->render('@firm/my_company_reg.twig', [
            'errors'    => $errors,
            'head'      => [
                'title' => 'Заявка на управление компанией'
            ],
            'user_mail' => $user_mail
        ]);
    }

    public function myCompanyHandler($id)
    {
        $_POST = self::sanitize_array($_POST);
        if (!empty($_POST['mail'])) {
            if (!User::authorized()) {
                $user = User::add($_POST['mail']);
                if ($user === User::USER_ADD_EXIST) {
                    self::myCompany($id, ['Данный адрес уже зарегистрирован! Чтобы привязать к нему компанию сначала <a href="/профиль">авторизуйтесь</a>']);
                    return;
                } elseif ($user === User::USER_ADD_WRONG_EMAIL) {
                    self::myCompany($id, ['Неверный адрес электронной почты!']);
                    return;
                }
                if (!User::authorized()) {
                    User::set_user_data($user);
                }
            } else {
                if ($_POST['mail'] !== User::$data['email']) {
                    die('email hijacking attempt!');
                }
            }
            self::myCompanySend($id, $_POST);
        }
    }


    private function myCompanySend($id, $data)
    {
        self::requireAuth();
        $firm = FirmQuery::create()->findPk($id);
        $email = User::$data['email'];
        $userId = User::getId();
        $firm_alias = $firm->getAlias();
        $name = $data['name'];
        $phone = $data['phone'];
        $desc = $data['desc'];
        $file = $_FILES['ogrn'];

        $allowed = array('jpg', 'pdf', 'jpeg', 'png');
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array($ext, $allowed)) {
            die('Недопустимый формат файла!');
        }
        $path = ASSET_PATH . "ogrn/{$id}/";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $path = $path . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $path);
        $file['path'] = Config::$site_url . "/asset/ogrn/{$id}/{$file['name']}";
        $body_user
            = "

            Ваша заявка на управление компанией <a href='http://твояфирма.рф{$firm_alias}'>http://твояфирма.рф{$firm_alias}</a> на рассмотрении.<br/>
            Ответ поступит на этот адрес в течение двух недель.
             <br/><br/>
            Вы можете войти на сайт, перейдя по ссылке или скопировав её в адресную строку браузера: <br/>

            <a href='http://" . Config::$site_url . "/профиль'>http://" . Config::$site_url . "/профиль</a> <br/> <br/>
        
                С уважением, \"" . Config::$site_name . "\"

        ";
        $mail_user = Notifier::prepare_mail(
            Config::$site_name . '. Заявка на управление компанией',
            Config::$mail['project'],
            $email, $body_user
        );
        Notifier::send_mail($mail_user);

        $body
            = " <br/>
            Пользователь $name($email) отправил запрос на управление компанией.<br/><br/>
            
            Обоснования: $desc<br/>
            Телефон: $phone<br/>
            Скан ОГРН: <a href='{$file['path']}'>{$file['name']}</a><br/>
        <br/><br/>
            Компания: <a href='http://твояфирма.рф{$firm_alias}'>http://твояфирма.рф{$firm_alias}</a><br/>
            Для привязки перейдите: 
            <a href='http://твояфирма.рф/admin/#firm/{$id}/attach_user/{$userId}'>Привязать</a>
        ";


        //moder
        $mail = \Swift_Message::newInstance()
            ->setSubject('Заявка на управление компанией')
            ->setFrom(Config::$mail['project'])
            ->setTo(Config::$mail['project'])
            ->setBody($body, 'text/html');

        if ($path) {
            $mail->attach(\Swift_Attachment::fromPath($path));
        }

        Notifier::send_mail($mail);


//        $mail = Notifier::prepare_mail(
//            'Пользователь оставил заявку на управление компанией',
//            Config::$mail['project'],
//            Config::$mail['project'], $body
//        );
//        Notifier::send_mail($mail);

        print $this->view->render('@firm/my_company_success.twig');

    }
}