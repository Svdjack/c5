<?php

namespace wMVC\Controllers;

use wMVC\Config;
use wMVC\Core\abstractController;
use wMVC\Entity\Notifier;

Class Pages extends abstractController
{
    public function pub_rules()
    {
        print $this->view->render('@pages/pub_rules.twig', [
            'head' => [
                'title' => 'Правила публикации — справочник предприятий «Твоя Фирма»',
                'nosearch' => true
            ],
        ]);
    }

    public function user_agreement()
    {
        print $this->view->render(
            '@pages/user_agreement.twig',
            ['head' => [
                'title'       => 'Пользовательское соглашение — справочник предприятий «Твоя Фирма»',
                'description' => 'Пользовательское соглашения проекта Твоя Фирма',
                'nosearch' => true
                ]
            ]
        );
    }

    public function feedback($errors = [], $post = [])
    {

        if (!isset($post['link'])) {
            $post['link'] = $this->getFeedbackLink();
        }

        print $this->view->render(
            '@pages/feedback.twig',
            [
                'head'   => [
                    'title' => 'Контакты — справочник предприятий «Твоя Фирма»',
                    'nosearch' => true
                ],
                'errors' => $errors,
                'post'   => $post
            ]
        );
    }

    /**
     * Link to first current user company
     * @access protected
     * @return string
     */
    protected function getFeedbackLink(): string
    {
        /* @var $u \PropelModel\User */
        $u = $this->user;

        if (!$u) {
            return '';
        }

        $firms = $u->getFirms();

        if (!$firms || empty($firms[0])) {
            return '';
        }

        /* @var $f \PropelModel\Firm */
        $f = $firms[0];

        $a = $f->getAlias();

        if (!$a) {
            return '';
        }

        return HTTP_HOST . $a;
    }

    public function showAds()
    {
        print $this->view->render(
                '@pages/ads.twig',
                [
                    'head' => [
                        'title' => 'Реклама',
                        'nosearch' => true
                    ]
                ]
        );
    }

    public function feedback_handler()
    {

        $response = $_POST['g-recaptcha-response'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt(
            $curl, CURLOPT_POSTFIELDS,
            "secret=6LdMASYUAAAAALd14wOzO3DBAy1_Qz8eMGA5Qg2v&response={$response}"
        );
        $out = curl_exec($curl);
        curl_close($curl);

        $out = json_decode($out);

        $errors = [];
        if ($out->success == false) {
            $errors[] = 'Вы не прошли капчу';
        }


        $_POST = self::sanitize_array($_POST);

        $spichki = [[]];
        preg_match_all('/[A-Za-zА-Яа-яЁё ]/u', $_POST['name'], $spichki);
        if (mb_strlen($_POST['name']) !== count($spichki[0])) {
            $errors[] = 'Имя должно быть на кириллице либо латинице.';
        }

        if (count($errors) > 0) {
            $this->feedback($errors, $_POST);
            return;
        }

        $user_mailsend = true;
        if (empty($_POST['email'])) {
            $user_mailsend = false;
            $_POST['email'] = Config::$mail['project'];
        }
        $prepared_mail = Notifier::prepare_mail(
            'Пользователь отправил сообщение',
            [$_POST['email'] => $_POST['name']],
            Config::$mail['project'],
            'Ссылка на компанию: ' . $_POST['link'] . '<br />' . $_POST['body']
        );

        Notifier::send_mail($prepared_mail);

        if ($user_mailsend) {
            $prepared_mail_user = Notifier::prepare_mail(
                Config::$site_name . '. Обратная связь',
                [Config::$mail['project'] => Config::$site_name],
                $_POST['email'],
                'Здравствуйте! Ваше сообщение было получено. Ожидайте ответа в ближайшее время.
            
            С уважением, ' . Config::$site_name . '
            '
            );

            Notifier::send_mail($prepared_mail_user);
        }


        print $this->view->render(
            '@pages/feedback_done.twig',
            ['head' => ['title' => 'Контакты — справочник предприятий «Твоя Фирма»']]
        );
    }

    public function abuse($errors = [], $post = [])
    {

        if (!isset($post['link'])) {
            $post['link'] = $this->getFeedbackLink();
        }

        print $this->view->render(
            '@pages/abuse.twig',
            [
                'head'   => [
                    'title' => 'Сообщить о нарушении — справочник предприятий «Твоя Фирма»'
                ],
                'errors' => $errors,
                'post'   => $post
            ]
        );
    }

    public function abuse_handler()
    {

        $response = $_POST['g-recaptcha-response'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt(
            $curl, CURLOPT_POSTFIELDS,
            "secret=6LdMASYUAAAAALd14wOzO3DBAy1_Qz8eMGA5Qg2v&response={$response}"
        );
        $out = curl_exec($curl);
        curl_close($curl);

        $out = json_decode($out);

        $errors = [];
        if ($out->success == false) {
            $errors[] = 'Вы не прошли капчу';
        }


        $_POST = self::sanitize_array($_POST);

        $spichki = [[]];
        preg_match_all('/[A-Za-zА-Яа-яЁё ]/u', $_POST['name'], $spichki);
        if (mb_strlen($_POST['name']) !== count($spichki[0])) {
            $errors[] = 'Имя должно быть на кириллице либо латинице.';
        }

        if (count($errors) > 0) {
            $this->feedback($errors, $_POST);
            return;
        }

        $user_mailsend = true;
        if (empty($_POST['email'])) {
            $user_mailsend = false;
            $_POST['email'] = Config::$mail['project'];
        }
        $prepared_mail = Notifier::prepare_mail(
            'Пользователь отправил сообщение',
            [$_POST['email'] => $_POST['name']],
            Config::$mail['project'],
            'Ссылка на компанию: ' . $_POST['link'] . '<br />' . $_POST['body']
        );

        Notifier::send_mail($prepared_mail);

        if ($user_mailsend) {
            $prepared_mail_user = Notifier::prepare_mail(
                Config::$site_name . '. Обратная связь',
                [Config::$mail['project'] => Config::$site_name],
                $_POST['email'],
                'Здравствуйте! Ваше сообщение было получено. Ожидайте ответа в ближайшее время.
            
            С уважением, ' . Config::$site_name . '
            '
            );

            Notifier::send_mail($prepared_mail_user);
        }


        print $this->view->render(
            '@pages/feedback_done.twig',
            ['head' => ['title' => 'Контакты — справочник предприятий «Твоя Фирма»']]
        );
    }

}
