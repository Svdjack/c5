<?php
namespace wMVC\Controllers;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use PropelModel\Canonical;
use PropelModel\CanonicalQuery;
use PropelModel\Changes;
use PropelModel\ChangesQuery;
use PropelModel\Contact;
use PropelModel\ContactQuery;
use PropelModel\Firm;
use PropelModel\FirmGroup;
use PropelModel\FirmQuery;
use PropelModel\FirmTags;
use PropelModel\FirmUser;
use PropelModel\FirmUserQuery;
use PropelModel\GroupQuery;
use PropelModel\RegionQuery;
use PropelModel\Tags;
use PropelModel\TagsQuery;
use PropelModel\UserQuery;
use PropelModel\Child;
use PropelModel\ChildQuery;
use wMVC\Config;
use wMVC\Core\abstractController;
use wMVC\Core\Exceptions\SystemExit;
use wMVC\Entity\Lang;
use wMVC\Entity\Notifier;

class OneClick extends abstractController
{
    const CHANGES_ARRAY = [
        'phone', 'website', 'email', 'children'
    ];
    
    const LANG = [
        'phone' => 'Телефон',
        'website' => 'Сайт',
        'email' => 'Email',
        'children' => 'Филиал',
    ];

    public function form($firm_id)
    {
        $context = [];

        $firm = FirmQuery::create()->findPk($firm_id);
        if(!$firm){
            throw new SystemExit('', 404);
        }
        $context['firm'] = $firm;

        $contacts = [
            'phone' => [],
            'website' => []
        ];
        foreach (ContactQuery::create()->filterByFirm($firm)->find() as $item) {
            if (!in_array($item->getType(), ['phone', 'website', 'email'])) {
                continue;
            }
            $value = $item->getValue();
            $contacts[$item->getType()][] = $value;
        }
        $context['contacts'] = $contacts;

        print $this->view->render('@firm/one_click.twig', $context);
    }

    public function form_handler()
    {
        $context = [];
        $info = $_POST;
        $new_firm_data = $info['firm'];
        $old_firm_data = $info['old_firm'];
        $firm_id = $info['firm_id'];
        $error = null;
        if(serialize($new_firm_data) == serialize($old_firm_data) && empty($info['message'])){
            $error = 'Поменяйте хотя бы одно поле, либо опишите ошибку в сообщении.';
        }
        $firm = FirmQuery::create()->findPk($firm_id);
        if(!$firm){
            throw new SystemExit('', 404);
        }
        if($error){
            $context['firm'] = $firm;

            $contacts = [
                'phone' => [],
                'website' => []
            ];
            foreach (ContactQuery::create()->filterByFirm($firm)->find() as $item) {
                if (!in_array($item->getType(), ['phone', 'website', 'email'])) {
                    continue;
                }
                $value = $item->getValue();
                $contacts[$item->getType()][] = $value;
            }
            $context['contacts'] = $contacts;
            $context['error'] = $error;
            $context['uname'] = $info['name'];
            $context['uemail'] = $info['email'];
            print $this->view->render('@firm/one_click.twig', $context);
        }else{
            $changes = [];
            $changes_text = [];
            $firm_values = [
                'name' => 'Название',
                'street' => 'Улица',
                'home' => 'Дом',
                'office' => 'Офис',
                'official_name' => 'Официальное название',
                'city_id' => 'Город',
                'description' => 'Описание компании',
            ];
            foreach ($firm_values as $value => $rus) {
                if ($old_firm_data[$value] != $new_firm_data[$value]) {
                    $changes_text[] = "<strong>{$rus}</strong><br/><span style=\"color:red\">{$old_firm_data[$value]}</span> на <span style=\"color:green\">{$new_firm_data[$value]}</span>";
                    $changes[$value] = $new_firm_data[$value];
                }
            }

            foreach (static::CHANGES_ARRAY as $_change) {
                if (empty($new_firm_data[$_change])) {
                    continue;
                }

                $new_firm_data[$_change] = array_filter($new_firm_data[$_change]);
                $old_firm_data[$_change] = array_filter($old_firm_data[$_change]);
                if (serialize($new_firm_data[$_change]) != serialize($old_firm_data[$_change])) {
                    $changes_text[] = "<strong>" . static::LANG[$_change] . "</strong><br/><span style=\"color:red\">" . implode(',', $old_firm_data[$_change]) . "</span> на <span style=\"color:green\">" . implode(',', $new_firm_data[$_change]) . "</span>";
                    $changes[$_change] = $new_firm_data[$_change];
                }
            }

            $change_id = 0;
            if (count($changes) > 0) {
                $ch = new Changes();
                $ch->setFirmId($firm_id);
                $ch->setData(json_encode($changes));
                $ch->setStatus(0);
                $ch->save();

                $change_id = $ch->getId();
            }

            $message_body = "Пользователь сообщил об ошибке в компании <strong>".$firm->getName()."</strong> (".$firm->getAlias().").<br>";

            if (!empty($info['message'])) {
                $message_body .= "Комментарий: {$info['message']}<br><br>";
            }

            if (count($changes_text) > 0) {
                $message_body .= 'Пользователь предлагает внести следующие изменения:<br>';
            }

            foreach ($changes_text as $value) {
                $message_body .= $value . '<br/>';
            }
            $message_body .= "<b><a style='color:blue;' href=\"http://".Config::$site_url."/admin/#firm/edit/{$firm_id}\">Редактировать</a></b>&nbsp;&nbsp;&nbsp;";

            if($change_id)
                $message_body .= "<b><a style='color:red;' href=\"http://".Config::$site_url."/one-click/$change_id/submit\">Одобрить изменения предложенные пользователем</a></b>&nbsp;&nbsp;&nbsp;";

            $message_body .= "<b><a style='color:green;' href=\"http://".Config::$site_url."".$firm->getAlias()."\">Перейти на страницу компании</a></b>";
            
            DEBUG && \file_put_contents(
                ROOT_PATH . 'mail.log',
                \str_replace('<', "\n<", $message_body) . "\n\n",
                \FILE_APPEND
            );

            $mail = \Swift_Message::newInstance()
                ->setSubject('Пользователь сообщил об ошибке')
                ->setFrom([Config::$mail['project'] => Config::$site_name])
                ->setTo(Config::$mail['project'])
                ->setBody(
                    $message_body,
                    'text/html'
                );

            Notifier::send_mail($mail);



            $user_body = '<h1>Благодарим Вас за сообщение.</h1><br/>
            
            <p>Здравствуйте!</p>
            
            <p>Скоро неточность в информации будет проверена и исправлена.</p>

            С уважением, "'.Config::$site_name.'"
            ';

            if(!empty($info['email'])){
                $user_mail = \Swift_Message::newInstance()
                    ->setSubject('ТвояФирма.рф Сообщение об ошибке')
                    ->setFrom([Config::$mail['project'] => Config::$site_name])
                    ->setTo($info['email'])
                    ->setBody(
                        $user_body,
                        'text/html'
                    );

                Notifier::send_mail($user_mail);
            }



            print $this->view->render('@firm/one_click_success.twig', $context);
        }
    }

    public function make_changes($id)
    {
        $this->requireAdmin();
        $change = ChangesQuery::create()->findPk($id);
        if(!$change){
            die('Нет такого изменения, отправьте ссылку разработчику');
        }

        if($change->getStatus() == 1){
            die('Изменения уже были применены');
        }

        $firm = FirmQuery::create()->findPk($change->getFirmId());
        if(!$firm){
            die('Нет такой фирмы, отправьте ссылку разработчику');
        }
        $data = json_decode($change->getData(), true);

        var_dump($data);

        if (isset($data['name'])) {
            $firm->setName($data['name']);
        }
        
        if (isset($data['official_name'])) {
            $firm->setOfficialName($data['official_name']);
        }

        if(isset($data['office'])){
            $firm->setOffice($data['office']);
        }
        
        if (isset($data['description'])) {
            $firm->setDescription($data['description']);
        }
        
        if (!empty($data['city_id'])) {
            $firm->setCityId($data['city_id']);
        }

        if(isset($data['street'])){
            $firm->setStreet($data['street']);
        }

        if(isset($data['home'])){
            $firm->setHome($data['home']);
        }

        $firm->save();

        if(!empty($data['phone'])){
            ContactQuery::create()->filterByType('phone')->filterByFirmId($firm->getId())->delete();
            foreach($data['phone'] as $phone_string){
                $phone = new Contact();
                $phone->setFirmId($firm->getId());
                $phone->setType('phone');
                $phone->setValue($phone_string);
                $phone->save();
                $phone = null;
            }
        }
        
        if (!empty($data['email'])) {
            ContactQuery::create()->filterByType('email')->filterByFirmId($firm->getId())->delete();
            foreach ($data['email'] as $e) {
                $email = new Contact();
                $email->setFirmId($firm->getId());
                $email->setType('email');
                $email->setValue($e);
                $email->save();
            }
        }

        if (!empty($data['website'])) {
            ContactQuery::create()->filterByType('website')->filterByFirmId($firm->getId())->delete();
            foreach ($data['website'] as $website_string) {
                $phone = new Contact();
                $phone->setFirmId($firm->getId());
                $phone->setType('website');
                $phone->setValue($website_string);
                $phone->save();
                $phone = null;
            }
        }
        
        if (!empty($data['children'])) {
            ChildQuery::create()->filterByFirmId($firm->getId())->delete();
            foreach ($data['children'] as $ch) {
                $child = new Child();
                $child->setFirmId($firm->getId());
                $child->setValue($ch);
                $child->save();
            }
        }

        $change->setStatus(1);
        $change->save();

        header("Location: http://".Config::$site_url."".$firm->getAlias());
        //echo "Изменения сохранены<br/><a href='http://".Config::$site_url."".$firm->getAlias()."'>Перейти на страницу компании</a>";
    }

    public function attach_user($firm_id, $user_id)
    {
        $this->requireAdmin();
        $user = UserQuery::create()->findPk($user_id);
        $firm = FirmQuery::create()->findPk($firm_id);
        if(!$user || !$firm){
            die('нет такого пользователя или компании, скиньте эту ссылку разработчику');
        }
        $firm->setChangedTime(time());
        $firm->save();
        FirmUserQuery::create()->filterByFirmId($firm->getId())->delete();
        $firm_user = new FirmUser();
        $firm_user->setUserId($user->getId());
        $firm_user->setFirmId($firm->getId());
        $firm_user->save();

        Notifier::company_attached($user, $firm);

        die('компания привязана<br/><a href="http://'.Config::$site_url.''.$firm->getAlias().'">Перейти на страницу компании</a>');
    }
}
