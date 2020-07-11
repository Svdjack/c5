<?php

namespace wMVC\Entity;

use PropelModel\Comment;
use PropelModel\Firm;
use PropelModel\User as UserModel;
use wMVC\Config;

class Notifier
{
    public static function send_mail(\Swift_Message $message)
    {
        $transport = \Swift_MailTransport::newInstance();
        $mailer = \Swift_Mailer::newInstance($transport);
        return $mailer->send($message);
    }

    public static function prepare_mail($subject, $from, $to, $body)
    {
        return \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $body,
                'text/html'
            );
    }

    public static function new_user(UserModel $user, $password)
    {
        $body
            = '
        Здравствуйте, '.$user->getLogin().'.<br/>
        Благодарим Вас за регистрацию на сайте ' . Config::$site_name . '!<br/>
        <br/>
        Для дальнейшего управления компанией используйте вход в личный кабинет<br/>
        <a href="https://твояфирма.рф/профиль">https://твояфирма.рф/профиль</a>, реквизиты доступа в который представлены ниже:<br/>
        Логин: ' . $user->getLogin() . '<br/>
        Пароль: ' . $password . '<br/>
        ';
        $mail = \Swift_Message::newInstance()
            ->setSubject('Детали учётной записи на сайте «' . Config::$site_name . '»')
            ->setFrom([Config::$mail['noreply'] => Config::$site_name])
            ->setTo($user->getEmail())
            ->setBody(
                $body,
                'text/html'
            );
        self::send_mail($mail);
    }

    public static function company_attached(UserModel $user, Firm $firm)
    {
        $companyname = $firm->getName();
        $alias = $firm->getAlias();
        $body = "Вам было передано право на управление компанией {$companyname} <br/>
            <a href='https://твояфирма.рф{$alias}'>https://твояфирма.рф{$alias}</a>";

        $mail = \Swift_Message::newInstance()
            ->setSubject('ТвояФирма.рф Передача прав на управление компанией')
            ->setFrom([Config::$mail['project'] => Config::$site_name])
            ->setTo($user->getEmail())
            ->setBody(
                $body,
                'text/html'
            );
        self::send_mail($mail);

    }
    
    public static function new_comment(Comment $comment)
    {
        $base_url = 'https://' . Config::$site_url;
        $firm_alias = $comment->getFirm()->getAlias();
        $body
            = '
        Пользователь добавил новый отзыв. <br/>

        Имя: ' . $comment->getUser() . '<br/>
        Комментарий: ' . $comment->getText() . ' <br/>
        
        Компания: <a href="'.$base_url . $firm_alias . '">'.$base_url . $firm_alias . '</a> <br/>
        
        Редактировать: <a href="'.$base_url.'/admin/#review/' . $comment->getId() . '">'.$base_url.'/admin/#review/' . $comment->getId() . '</a> <br/>
        
        Удалить: <a href="'.$base_url.'/admin/#review/' . $comment->getId() . '">'.$base_url.'/admin/#review/' . $comment->getId() . '</a><br/>

        ';
        $mail = \Swift_Message::newInstance()
            ->setSubject('ТвояФирма.рф Новый отзыв')
            ->setFrom([Config::$mail['project'] => Config::$site_name])
            ->setTo(Config::$mail['moderator'])
            ->setBody(
                $body,
                'text/html'
            );
        self::send_mail($mail);
    }

    public static function new_company(Firm $firm)
    {
        $body
            = '
        Добавлена новая компания. <br/>

        Редактировать: <a href="https://' . Config::$site_url . '/admin/#firm/edit/' . $firm->getId() . '">https://' . Config::$site_url . '/admin/#firm/edit/' . $firm->getId() . '</a>
        ';
        $mail = \Swift_Message::newInstance()
            ->setSubject('ТвояФирма.рф Новая компания на сайте')
            ->setFrom([Config::$mail['project'] => Config::$site_name])
            ->setTo(Config::$mail['moderator'])
            ->setBody(
                $body,
                'text/html'
            );
        self::send_mail($mail);

        if($user_object = $firm->getUser()){

            $user_body = '<h1>' . Config::$site_name . '. Добавление компании.</h1><br/>
            
            <p>Здравствуйте!</p>
            
            <p>Страница вашей компании: <a href="https://'. Config::$site_url . $firm->getAlias() . '">https://'. Config::$site_url . $firm->getAlias() . '</a></p>
            <p>Ваш профиль: <a href="https://'. Config::$site_url . '/профиль">Профиль</a></p>
            С уважением, "'.Config::$site_name.'"
            ';


            $user_mail = \Swift_Message::newInstance()
                ->setSubject('ТвояФирма.рф Новая компания на сайте')
                ->setFrom([Config::$mail['project'] => Config::$site_name])
                ->setTo($user_object->getEmail())
                ->setBody(
                    $user_body,
                    'text/html'
                );
            self::send_mail($user_mail);
        }

    }

    public static function edit_company(Firm $firm)
    {
        $body
            = '
        Пользователь изменил информацию о компании. <br/>

        Редактировать: <a href="https://' . Config::$site_url . '/admin/#firm/edit/' . $firm->getId() . '">https://' . Config::$site_url . '/admin/#firm/edit/' . $firm->getId() . '</a>
        ';
        $mail = \Swift_Message::newInstance()
            ->setSubject('ТвояФирма.рф Обновление компании')
            ->setFrom([Config::$mail['project'] => Config::$site_name])
            ->setTo(Config::$mail['moderator'])
            ->setBody(
                $body,
                'text/html'
            );
        self::send_mail($mail);



        if($user_object = $firm->getUser()) {
            $user_body = '<h1>' . Config::$site_name . '. Обновление компании.</h1><br/>
            
            <p>Здравствуйте!</p>
            
            <p>Ваша компания была изменена!</p>
            <p>Страница вашей компании: <a href="https://' . Config::$site_url . $firm->getAlias()
                . '">https://' . Config::$site_url . $firm->getAlias() . '</a></p>
            <p>Ваш профиль: <a href="https://' . Config::$site_url . '/профиль">Профиль</a></p>
            С уважением, "' . Config::$site_name . '"
            ';


            $user_mail = \Swift_Message::newInstance()
                ->setSubject('ТвояФирма.рф Обновление компании')
                ->setFrom([Config::$mail['project'] => Config::$site_name])
                ->setTo(Config::$mail['moderator'])
                ->setBody(
                    $user_body,
                    'text/html'
                );
            self::send_mail($user_mail);
        }
    }
}