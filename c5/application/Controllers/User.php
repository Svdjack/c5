<?php

namespace wMVC\Controllers;

use Propel\Runtime\ActiveQuery\Criteria;
use PropelModel\Child;
use PropelModel\ChildQuery;
use PropelModel\Contact;
use PropelModel\ContactQuery;
use PropelModel\Firm;
use PropelModel\FirmGroup;
use PropelModel\FirmGroupQuery;
use PropelModel\FirmQuery;
use PropelModel\FirmTags;
use PropelModel\FirmTagsQuery;
use PropelModel\FirmUser;
use PropelModel\FirmUserQuery;
use PropelModel\GroupQuery;
use PropelModel\LegalInfo;
use PropelModel\RegionQuery;
use PropelModel\Tags;
use PropelModel\TagsQuery;
use PropelModel\UrlAliases;
use PropelModel\UrlAliasesQuery;
use PropelModel\UserQuery;
use wMVC\Config;
use wMVC\Core\abstractController;
use GUMP;
use wMVC\Core\Exceptions\SystemExit;
use wMVC\Core\Router;
use wMVC\Entity\Lang;
use wMVC\Entity\Notifier;

class User extends abstractController
{
    public function login($errors = [])
    {
        $this->initSession();
        setcookie('is_user', true, time() + 3600 * 24 * 30, '/');

        print $this->view->render(
            '@user/login.twig', [
                'head'   =>
                    [
                        'title' => 'Вход на сайт',
                        'nosearch' => true
                    ],
                'errors' => $errors
            ]
        );
    }

    public function add_firm($post = [], $errors = [])
    {
        $authorized = \wMVC\Entity\User::authorized();

        $vars = [
            'head'       => [
                'title' => 'Добавить бесплатно компанию в полный справочник "Твоя Фирма"',
                'nosearch' => true
            ],
            'new_firm'   => 1,
            'authorized' => $authorized,
            'errors'     => $errors,
            'firm_edit'  => 0
        ];

        if (count($post)) {
            $firm = [
                'name'         => $post['firm']['name'],
                'subtitle'     => $post['firm']['subtitle'],
                'officialname' => $post['firm']['officialname'],
                'description'  => $post['firm']['description'],
                'postal'       => $post['firm']['postal'],
                'street'       => $post['firm']['street'],
                'home'         => $post['firm']['home'],
                'office'       => $post['firm']['office']
            ];

            $vars['firm_json'] = json_encode($firm);
            $vars['jur_json'] = json_encode($post['jur']);
            $vars['worktime_json'] = json_encode($post['worktime']);
            if (!empty($post['firm']['cityid'])) {
                $region = RegionQuery::create()->findPk($post['firm']['cityid']);
                $region = ['region_id' => $region->getArea(),
                           'city_id'   => $region->getId()];
                $vars['region_json'] = json_encode($region);
            }

            $contacts = [];
            foreach ($post['firm']['phones'] as $item) {
                $contacts[] = ['Type' => 'phone', 'Value' => $item];
            }
            foreach ($post['firm']['sites'] as $item) {
                $contacts[] = ['Type' => 'website', 'Value' => $item];
            }
            foreach ($post['firm']['emails'] as $item) {
                $contacts[] = ['Type' => 'email', 'Value' => $item];
            }
            foreach ($post['firm']['faxes'] as $item) {
                $contacts[] = ['Type' => 'fax', 'Value' => $item];
            }

            $vars['contacts_json'] = json_encode($contacts);

            if (!empty($post['firm']['categories'])) {
                $cats = GroupQuery::create()->findPks($post['firm']['categories'])->toArray();
                $vars['categories_json'] = json_encode($cats);
            }

            $children = [];
            foreach ($post['firm']['child'] as $item) {
                $children[] = ['Value' => $item];
            }

            $vars['children_json'] = json_encode($children);

            $tags = [];

            if (!empty($post['firm']['keywords'])) {
                foreach ($post['firm']['keywords'] as $keyword) {
                    $tags[] = $keyword;
                }
                $vars['keywords_json'] = json_encode($tags);
            }


            $vars['okved_json'] = json_encode($post['jur']['activities']);

            $vars['form_failed'] = 1;

        }


        print $this->view->render(
            "@user/firm_edit.twig", $vars
        );
    }

    public function add_firm_handler()
    {
        $firm_validator = new GUMP();
        $firm = $_POST['firm'];
        $_POST = self::sanitize_array($_POST);
        $firm_validator->set_field_names(
            [
                'name'         => 'Название',
                'subtitle'     => 'Подзаголовок',
                'officialname' => 'Юридическое название',
                'description'  => 'Описание',
                'postal'       => 'Индекс',
                'street'       => 'Улица',
                'home'         => 'Дом',
                'office'       => 'Офис',
                'cityid'       => 'Город'
            ]
        );

        $firm_validator->validation_rules(
            [
                'name'        => 'required',
                'description' => 'required',
                'postal'      => 'numeric',
                'street'      => 'required',
                'home'        => 'required',
                'cityid'      => 'required|numeric'
            ]
        );

        $firm_validator->filter_rules(
            [
                'name'         => 'trim',
                'subtitle'     => 'trim',
                'officialname' => 'trim',
                'description'  => 'trim',
                'postal'       => 'trim',
                'street'       => 'trim',
                'home'         => 'trim',
                'office'       => 'trim'
            ]
        );
        $errors = [];
        if ($firm_validator->run($firm) === false) {
            $errors = array_merge($errors, array_values($firm_validator->get_errors_array()));
        }

        $phones = array_filter($_POST['firm']['phones']);
        $sites = array_filter($_POST['firm']['sites']);
        $emails = array_filter($_POST['firm']['emails']);
        $faxes = array_filter($_POST['firm']['faxes']);

        $worktime = json_encode($_POST['worktime']);

        if (count($phones) < 1) {
            $errors[] = 'Необходимо указать как минимум один телефон';
        }

        $keywords = !empty($_POST['firm']['keywords']) ? $_POST['firm']['keywords'] : [];
        $categories = !empty($_POST['firm']['categories']) ? $_POST['firm']['categories'] : [];

        if (count($keywords) < 5) {
            $errors[] = 'Необходимо выбрать как минимум 5 ключевых слов';
        }

        if (isset($_POST['user-email'])) {
            $user = \wMVC\Entity\User::add($_POST['user-email']);
            if ($user === \wMVC\Entity\User::USER_ADD_WRONG_EMAIL) {
                $errors[] = 'Неверный формат Email';
            }
            if ($user === \wMVC\Entity\User::USER_ADD_EXIST) {
                $errors[] = 'Данный Email уже зарегистрирован';
            }
        }

//        if()

        if (count($categories) < 1) {
            $errors[] = 'Необходимо выбрать категорию';
        }

        $shitass = ['ООО', 'ЗАО', 'ОАО', 'МУП', 'ЕМУП'];

        $contains = function ($str, array $arr) {
            foreach ($arr as $a) {
                if (stripos($str, $a) !== false) {
                    return true;
                }
            }
            return false;
        };

        if ($contains($firm['name'], $shitass)) {
            $errors[] = 'В поле "Имя" нельзя использовать следующие сокращения: ООО, ЗАО, ОАО, МУП, ЕМУП';
        }

        if (count($errors)) {
            self::add_firm($_POST, $errors);
            return;
        }


        $firm = array_map(
            function ($a) {
                return str_replace(['"', "'", "\"", "`"], '', $a);
                return htmlspecialchars_decode($a);
            }, $firm
        );

        $firm_obj = new Firm();
        $firm_obj->setChanged(time());
        $firm_obj->setName(strip_tags($firm['name']));
        $firm_obj->setSubtitle($firm['subtitle']);
        $firm_obj->setOfficialName($firm['officialname']);
        $firm_obj->setDescription($firm['description']);
        $firm_obj->setCityId($firm['cityid']);
        $firm_obj->setWorktime($worktime);
        $firm_obj->setPostal($firm['postal']);
        $firm_obj->setStreet($firm['street']);
        $firm_obj->setHome($firm['home']);
        $firm_obj->setOffice($firm['office']);
        $firm_obj->setActive(1);
        $firm_obj->setStatus(0);
        $firm_obj->setCreated(time());
        $firm_obj->setChangedTime(time());

        $region = RegionQuery::create()->findPk($firm['cityid']);

        $firm_obj->setCoordsByAddress(
            $region->getName() .
            " {$firm['postal']} {$firm['street']} {$firm['home']}"
        );
        $firm_obj->setMainCategory(reset($categories));
        $firm_obj->save();

        Notifier::new_company($firm_obj);

        $city = $region->getUrl();
        $main_cat = GroupQuery::create()->findPk($firm_obj->getMainCategory())->getUrl();
        $url = Lang::toUrl($firm_obj->getName());

        $alias = "/{$city}/{$main_cat}/{$url}";

        if (UrlAliasesQuery::create()->filterByAlias($alias)->count()) {
            $alias .= '-' . $firm_obj->getId();
        }

        $alias_obj = new UrlAliases();
        $alias_obj->setSource('/firm/show/' . $firm_obj->getId());
        $alias_obj->setAlias($alias);
        $alias_obj->save();

        $jur = $_POST['jur'];
        $jur['activities'] = serialize(array_filter($jur['activities']));


        $legal = new LegalInfo();
        $legal->setFirmId($firm_obj->getId());
        $legal->setRegDate(time());
        $legal->setInn($jur['inn']);
        $legal->setKpp($jur['kpp']);
        $legal->setOgrn($jur['ogrn']);
        $legal->setOkpo($jur['okpo']);
        $legal->setOkato($jur['okato']);
        $legal->setFsfr($jur['fsfr']);
        $legal->setOrgForm($jur['orgform']);
        $legal->setOkogu($jur['okogu']);
        $legal->setActivities($jur['activities']);
        $legal->save();

        foreach ($categories as $category) {
            $grp = new FirmGroup();
            $grp->setCity($firm['cityid']);
            $grp->setFirm($firm_obj);
            $grp->setGroupId($category);
            $grp->save();
        }

        foreach ($keywords as $keyword) {
            $tag = TagsQuery::create()->findOneByTag($keyword);
            if (!$tag) {
                $tag = new Tags();
                $tag->setTag($keyword);
                $tag->save();
            }
            $firm_to_tag = new FirmTags();
            $firm_to_tag->setFirm($firm_obj);
            $firm_to_tag->setTags($tag);
            $firm_to_tag->save();
        }

        foreach ($_POST['firm']['child'] as $child) {
            $chi = new Child();
            $chi->setFirm($firm_obj);
            $chi->setValue($child);
            $chi->save();
        }

        foreach ($phones as $value) {
            $contact = new Contact();
            $contact->setFirm($firm_obj);
            $contact->setType('phone');
            $contact->setValue($value);
            $contact->save();
        }

        foreach ($sites as $value) {
            $contact = new Contact();
            $contact->setFirm($firm_obj);
            $contact->setType('website');
            $contact->setValue($value);
            $contact->save();
        }

        foreach ($emails as $value) {
            $contact = new Contact();
            $contact->setFirm($firm_obj);
            $contact->setType('email');
            $contact->setValue($value);
            $contact->save();
        }

        foreach ($faxes as $value) {
            $contact = new Contact();
            $contact->setFirm($firm_obj);
            $contact->setType('fax');
            $contact->setValue($value);
            $contact->save();
        }

        if (isset($_POST['user-email'])) {

            \wMVC\Entity\User::set_user_data($user);

            $firm_user = new FirmUser();
            $firm_user->setFirm($firm_obj);
            $firm_user->setUser($user);
            $firm_user->save();
        } else {
            $firm_user = new FirmUser();
            $firm_user->setFirm($firm_obj);
            $firm_user->setUserId(\wMVC\Entity\User::getId());
            $firm_user->save();
        }

        header('Location: ' . '/up/' . $firm_obj->getId() . '/checkout');
    }

    public function firm_edit($id, $errors = [])
    {
        self::requireAuth();
        $firm = FirmQuery::create()->findPk($id);
        if (!$firm) {
            throw new SystemExit('', 404);
        }

        $users = $firm->getUsers();
        if (!in_array(\wMVC\Entity\User::getId(), array_keys($users->toArray('Id')))) {
            throw new SystemExit('', 404);
        }

        $firm_result = [];
        $firm_result['id'] = $firm->getId();
        $firm_result['url'] = $firm->getAlias();
        $firm_result['name'] = $firm->getName();
        $firm_result['subtitle'] = $firm->getSubtitle();
        $firm_result['officialname'] = $firm->getOfficialName();
        $firm_result['description'] = $firm->getDescription();
        $firm_result['postal'] = $firm->getPostal();
        $firm_result['street'] = $firm->getStreet();
        $firm_result['home'] = $firm->getHome();
        $firm_result['office'] = $firm->getOffice();

        $region = $firm->getRegion();

        $region_result = [
            'city_id'   => $region->getId(),
            'region_id' => $region->getArea()
        ];

        $contacts = $firm->getContacts()->toArray();
        $categories = $firm->getGroups()->toArray();
        $children = $firm->getChildren()->toArray();
        $keyword_ids = array_keys($firm->getFirmTagss()->toArray('tagid'));
        $keywords = TagsQuery::create()->select('tag')->findPks($keyword_ids)->toArray();
        
        $legalObj = $firm->getLegalInfoRelatedById();
        
        if ($legalObj) {
            $legal = array_change_key_case($legalObj->toArray(), CASE_LOWER);
            $legal['activities'] = unserialize($legal['activities']);
        } else {
            $legal = [
                'activities' => []
            ];
        }


        print $this->view->render(
            "@user/firm_edit.twig", [
                'head'            =>
                    [
                        'title' => 'Редактирование ' . $firm->getName(),
                        'nosearch' => true
                    ],
                'errors'          => $errors,
                'firm'            => $firm_result,
                'firm_json'       => json_encode($firm_result),
                'region_json'     => json_encode($region_result),
                'contacts_json'   => json_encode($contacts),
                'categories_json' => json_encode($categories),
                'children_json'   => json_encode($children),
                'keywords_json'   => json_encode($keywords),
                'worktime_json'   => $firm->getWorktime(),
                'jur_json'        => json_encode($legal),
                'okved_json'      => json_encode($legal['activities']),
                'firm_edit'       => 1
            ]
        );
    }

    public function firm_edit_handler($id)
    {
        $firm_obj = FirmQuery::create()->findPk($id);
        $users = $firm_obj->getUsers();
        if (!in_array(\wMVC\Entity\User::getId(), array_keys($users->toArray('Id')))) {
            throw new SystemExit('', 404);
        }

        $firm_validator = new GUMP();
        $firm = $_POST['firm'];
        $_POST = self::sanitize_array($_POST);
        $firm_validator->set_field_names(
            [
                'name'         => 'Название',
                'subtitle'     => 'Подзаголовок',
                'officialname' => 'Юридическое название',
                'description'  => 'Описание',
                'postal'       => 'Индекс',
                'street'       => 'Улица',
                'home'         => 'Дом',
                'office'       => 'Офис',
                'cityid'       => 'Город'
            ]
        );

        $firm_validator->validation_rules(
            [
                'name'        => 'required',
                'description' => 'required',
                'postal'      => 'numeric',
                'street'      => 'required',
                'home'        => 'required',
                'cityid'      => 'required|numeric'
            ]
        );

        $firm_validator->filter_rules(
            [
                'name'         => 'trim|sanitize_string',
                'subtitle'     => 'trim|sanitize_string',
                'officialname' => 'trim|sanitize_string',
                'description'  => 'trim|sanitize_string',
                'postal'       => 'trim|sanitize_string',
                'street'       => 'trim|sanitize_string',
                'home'         => 'trim|sanitize_string',
                'office'       => 'trim|sanitize_string'
            ]
        );
        $errors = [];
        if ($firm_validator->run($firm) === false) {
            $errors = array_merge($errors, array_values($firm_validator->get_errors_array()));
        }

        $phones = array_filter($_POST['firm']['phones']);
        $sites = array_filter($_POST['firm']['sites']);
        $emails = array_filter($_POST['firm']['emails']);
        $faxes = array_filter($_POST['firm']['faxes']);

        $worktime = json_encode($_POST['worktime']);

        if (count($phones) < 1) {
            $errors[] = 'Необходимо указать как минимум один телефон';
        }

        $keywords = $_POST['firm']['keywords'];
        $categories = $_POST['firm']['categories'];

        if (count($keywords) < 5) {
            $errors[] = 'Необходимо выбрать как минимум 5 ключевых слов';
        }

        if (count($categories) < 1) {
            $errors[] = 'Необходимо выбрать категорию';
        }

        if (count($errors)) {
            self::firm_edit($id, $errors);
            return;
        }


        $firm = array_map(
            function ($a) {
                return str_replace(['"', "'", "\"", "`"], '', $a);
                return htmlspecialchars_decode($a);
            }, $firm
        );


        $firm_obj->setName($firm['name']);
        $firm_obj->setSubtitle($firm['subtitle']);
        $firm_obj->setOfficialName($firm['officialname']);
        $firm_obj->setDescription($firm['description']);
        $firm_obj->setCityId($firm['cityid']);
        $firm_obj->setWorktime($worktime);
        $firm_obj->setPostal($firm['postal']);
        $firm_obj->setStreet($firm['street']);
        $firm_obj->setChanged(1);
        $firm_obj->setChangedTime(time());

        $region = RegionQuery::create()->findPk($firm['cityid']);

        $firm_obj->setCoordsByAddress(
            $region->getName() .
            " {$firm['postal']} {$firm['street']} {$firm['home']}"
        );

        $firm_obj->setHome($firm['home']);
        $firm_obj->setOffice($firm['office']);
        $firm_obj->setMainCategory(reset($categories));
        $firm_obj->save();

        Notifier::edit_company($firm_obj);

        $legal = $firm_obj->getLegalInfoRelatedById();
        $jur = $_POST['jur'];
        $jur['activities'] = serialize(array_filter($jur['activities']));

        $legal->setRegDate(time());
        $legal->setInn($jur['inn']);
        $legal->setKpp($jur['kpp']);
        $legal->setOgrn($jur['ogrn']);
        $legal->setOkpo($jur['okpo']);
        $legal->setOkato($jur['okato']);
        $legal->setFsfr($jur['fsfr']);
        $legal->setOrgForm($jur['orgform']);
        $legal->setOkogu($jur['okogu']);
        $legal->setActivities($jur['activities']);
        $legal->save();

        FirmGroupQuery::create()->filterByFirm($firm_obj)->delete();

        foreach ($categories as $category) {
            $grp = new FirmGroup();
            $grp->setCity($firm['cityid']);
            $grp->setFirm($firm_obj);
            $grp->setGroupId($category);
            $grp->save();
        }

        FirmTagsQuery::create()->filterByFirm($firm_obj)->delete();

        foreach ($keywords as $keyword) {
            $tag = TagsQuery::create()->findOneByTag($keyword);
            if (!$tag) {
                $tag = new Tags();
                $tag->setTag($keyword);
                $tag->save();
            }
            $firm_to_tag = new FirmTags();
            $firm_to_tag->setFirm($firm_obj);
            $firm_to_tag->setTags($tag);
            $firm_to_tag->save();
        }

        ChildQuery::create()->filterByFirm($firm_obj)->delete();
        foreach ($_POST['firm']['child'] as $child) {
            $chi = new Child();
            $chi->setFirm($firm_obj);
            $chi->setValue($child);
            $chi->save();
        }

        ContactQuery::create()->filterByFirm($firm_obj)->delete();

        foreach ($phones as $value) {
            $contact = new Contact();
            $contact->setFirm($firm_obj);
            $contact->setType('phone');
            $contact->setValue($value);
            $contact->save();
        }

        foreach ($sites as $value) {
            $contact = new Contact();
            $contact->setFirm($firm_obj);
            $contact->setType('website');
            $contact->setValue($value);
            $contact->save();
        }

        foreach ($emails as $value) {
            $contact = new Contact();
            $contact->setFirm($firm_obj);
            $contact->setType('email');
            $contact->setValue($value);
            $contact->save();
        }

        foreach ($faxes as $value) {
            $contact = new Contact();
            $contact->setFirm($firm_obj);
            $contact->setType('fax');
            $contact->setValue($value);
            $contact->save();
        }

        header('Location: ' . $firm_obj->getAlias());
    }

    public function login_post()
    {
        $validator = new GUMP();
        $data = $validator->sanitize($_POST);
        $validator->set_field_names(
            [
                'name' => 'Логин',
                'pass' => 'Пароль'
            ]
        );
        $validator->validation_rules(
            [
                'name' => 'required',
                'pass' => 'required'
            ]
        );

        $validator->filter_rules(
            [
                'name' => 'trim|sanitize_string',
                'pass' => 'trim'
            ]
        );

        if ($validator->run($data) === false) {
            self::login(array_values($validator->get_errors_array()));
        } else {
            if (\wMVC\Entity\User::login($data['name'], $data['pass'])) {
                header('Location: /профиль', 307);
                die();
            } else {
                self::login(['Данное сочетание реквизитов не найдено, <a href="https://' . Config::$site_url . '/restore">Восстановить пароль?</a>']);
            }
        }
    }

    public function user_profile()
    {
        self::requireAuth();
        header('Location: /профиль/компании', 307);
        die();
        $user = \wMVC\Entity\User::$data;
        print $this->view->render(
            "@user/profile.twig", [
                'head' =>
                    [
                        'title' => 'Профиль'
                    ],
                'user' => $user['login']
            ]
        );
    }

    public function delete_firm_prompt($id)
    {
        $firm = FirmQuery::create()
            ->useFirmUserQuery()
            ->filterByUserId(\wMVC\Entity\User::getId())
            ->endUse()
            ->findPk($id);

        if (!$firm) {
            throw new SystemExit('Доступ запрещен', 403);
        }

        print $this->view->render(
            '@user/delete_firm_prompt.twig',
            ['id'   => $firm->getId(),
             'name' => $firm->getName()]
        );
    }

    public function delete_firm($id)
    {
        $firm = FirmQuery::create()
            ->useFirmUserQuery()
            ->filterByUserId(\wMVC\Entity\User::getId())
            ->endUse()
            ->findPk($id);

        if (!$firm) {
            throw new SystemExit('', 403);
        }

        $firm->setActive(0);
        $firm->save();

        header('Location: /профиль/компании', 307);
        die();
    }

    public function user_firms($page = 1)
    {
        self::requireAuth();

        $user = $this->user;
        $user OR (new Router())->redirect('/профиль');
        $this->setTitle('Мои компании');
        $firms = FirmQuery::create()
            ->filterByUser($user)
            ->leftJoinFirmUp()
            ->addAsColumn('Up', 'FirmUp.Status')
            ->addAsColumn('Tarif', 'FirmUp.Type')
            ->addAsColumn('TimeEnd', 'FirmUp.TimeEnd')
            ->orderByCreated(Criteria::DESC)
            ->limit(100)
            ->find();
        $list = [];
        $up = false;
        foreach ($firms AS $firm) {
            $list[$firm->getId()] = $firm->toArray();
            if ($firm->getUp() == 1) {
                $up = true;
            }

            $list[$firm->getId()]['Alias'] = $firm->getAlias();

            if ($firm->getRegion()) {
                $list[$firm->getId()]['City'] = $firm->getRegion()->toArray();
            }
            $list[$firm->getId()]['Tarif'] = AdvClient::getTarif($firm->getTarif());
            $list[$firm->getId()]['TarifName'] = AdvClient::getTarifName($firm->getTarif());
        }

        print $this->view->render(
            "@user/firms.twig", [
                'firms' => $list,
                'user'  => $user,
                'up'    => $up,
            ]
        );

    }


    public function settings($messages = [])
    {
        self::requireAuth();

        $user = $this->user;
        $user OR (new Router())->redirect('/профиль');
        print $this->view->render(
            "@user/settings.twig", [
                'messages' => $messages,
                'user'     => $user
            ]
        );
    }

    public function settingsSubmit()
    {
        self::requireAuth();
        $messages = [];
        if (count($_POST) > 0) {
            $user = UserQuery::create()->findPk(\wMVC\Entity\User::getId());
            if (!empty($_POST['password'])) {
                $user->setPassword($_POST['password']);
                $user->save();
                $messages[] = 'Новый пароль сохранен!';
            }
        }
        $this->settings($messages);
    }


    public function restore()
    {
        $context = [];
        $context = ['head' => ['title' => 'Восстановление пароля']];

        print $this->view->render('@user/restore.twig', $context);
    }

    public function restore_post()
    {
        $info = $_POST;
        $context = [];
        $context = ['head' => ['title' => 'Восстановление пароля']];
        if (empty($info['email'])) {
            $context['info'] = 'Введите Email!';
        } else {
            $user = UserQuery::create()->findOneByEmail($info['email']);
            if (!$user) {
                $context['info'] = 'Нет пользователя с таким Email';
            } else {
                $random = microtime() + mt_rand(1, 9999);
                $secret = md5($random . $info['email']);
                $user->setSecret($secret);
                $user->save();

                $link = 'https://' . Config::$site_url . '/restore/' . $secret;
                $body = "Для восстановления пароля перейдите по ссылке <a href='$link'>$link</a>";

                $mail = Notifier::prepare_mail(
                    Config::$site_name . '. Восстановление пароля',
                    Config::$mail['noreply'],
                    $info['email'],
                    $body
                );
                Notifier::send_mail($mail);

                $context['info'] = 'Письмо с дальнейшими инструкциями отправлено на ваш Email';
            }
        }

        print $this->view->render('@user/restore.twig', $context);
    }

    public function restore_handler($secret)
    {
        $context = [];
        $context['form'] = 1;
        $context = ['head' => ['title' => 'Восстановление пароля']];
        if (empty($secret)) {
            throw new SystemExit('', 404);
        }
        if (count($_POST)) {
            if (empty($_POST['password'])) {
                $context['info'] = 'Введите новый пароль';
            } else {
                $user = UserQuery::create()->findOneBySecret($secret);
                $user->setPassword($_POST['password']);
                $user->save();
                $user->setSecret('');
                $user->save();
                $context['info'] = 'Новый пароль сохранен. Для авторизации воспользуйтесь формой входа.';
                $context['form'] = 0;
            }

        } else {

            if (empty($secret)) {
                throw new SystemExit('', 404);
            }

            $user = UserQuery::create()->findOneBySecret($secret);
            if (!$user) {
                $context['info'] = 'Ссылка устарела';
                $context['form'] = 0;
            } else {
                $context['form'] = 1;
            }
        }

        print $this->view->render('@user/restore_handler.twig', $context);
    }

    public function user_logout()
    {
        self::requireAuth();
        \wMVC\Entity\User::logout();
        header('Location: /', 307);
        die();
    }
}