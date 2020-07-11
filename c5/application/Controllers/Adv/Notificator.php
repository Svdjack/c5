<?php

namespace wMVC\Controllers;

use PropelModel\CityQuery;
use PropelModel\FirmUp;
use wMVC\Config;
use wMVC\Core\abstractController;
use wMVC\Core\Controller;

final class AdvNotificator extends abstractController
{
    public function firmUp(\PropelModel\User $user, FirmUp $firmUp, $end)
    {

        $firm = $firmUp->getFirm();
        $tarifs = [
            'beta'    => ['Пробный период (7 дней)', 'Хороший выбор'],
            'premium' => ['Премиальное размещение', 'Прекрасный выбор'],
            'all-in'  => ['Всё включено', 'Прекрасный выбор'],
        ];
        $tarif = $tarifs[$firmUp->getType()];
        if ($firmUp->isNew()) {
            return $this->firmUpNew($user, $firm, $end, $tarif);
        } else {
            return $this->firmUpMore($user, $firm, $end, $tarif);
        }
    }

    public function firmUpNew(\PropelModel\User $user, \PropelModel\Firm $firm, $end, $tarif)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Пакет {$tarif[0]} - {$firm->getName()}")
            ->setFrom(Config::$mail['noreply'])
            ->setTo($user->getEmail())
            ->setBody(
                $this->view->render(
                    '@adv/emails/firm_up.twig',
                    [
                        'user'  => $user,
                        'firm'  => $firm,
                        'end'   => $end,
                        'tarif' => $tarif,
                        'city'  => $this->getCity($firm),
                    ]),
                'text/html'
            );
            
        return $this->sendMail($message);
    }

    public function getCity($firm)
    {
        if (!$firm) return;
        $city = $firm->getRegion();
        return $city;
    }

    protected function sendMail(\Swift_Message $message)
    {
        $transport = \Swift_MailTransport::newInstance();
        $mailer = \Swift_Mailer::newInstance($transport);
        return $mailer->send($message);
    }

    public function firmUpMore(\PropelModel\User $user, \PropelModel\Firm $firm, $end, $tarif)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Пакет {$tarif[0]} - {$firm->getName()}")
            ->setFrom(Config::$mail['noreply'])
            ->setTo($user->getEmail())
            ->setBody(
                $this->view->render(
                    '@adv/emails/firm_up_more.twig',
                    [
                        'user'  => $user,
                        'firm'  => $firm,
                        'end'   => $end,
                        'tarif' => $tarif,
                        'city'  => $this->getCity($firm),
                    ]),
                'text/html'
            );
            
        return $this->sendMail($message);
    }

    /**
     * @param $stat
     * @param FirmUp $firmUp
     * @param \PropelModel\Firm $firm
     */
    public function statFirmUp($stat, $firmUp, $firm)
    {
        if (!filter_var($firmUp->getEmail(), FILTER_VALIDATE_EMAIL))
            return;

        $message = \Swift_Message::newInstance()
            ->setSubject("Отчёт: {$firm->getName()}")
            ->setFrom(Config::$mail['noreply'])
            ->setTo($firmUp->getEmail())
            ->setBody(
                $this->view->render(
                    '@adv/emails/stat.twig',
                    [
                        'firm'    => $firm,
                        'firmUp'  => $firmUp,
                        'content' => $stat,
                        'city'    => $this->getCity($firm),
                    ]),
                'text/html'
            );
        $this->sendMail($message);
    }

    public function FirmUpStop($firmUp, $firm, $tarif)
    {
        if (!filter_var($firmUp->getEmail(), FILTER_VALIDATE_EMAIL))
            return;

        $message = \Swift_Message::newInstance()
            ->setSubject("Пакет “{$tarif}” закончился")
            ->setFrom(Config::$mail['noreply'])
            ->setTo($firmUp->getEmail())
            ->setBody(
                $this->view->render(
                    '@adv/emails/firm_up_stop.twig',
                    [
                        'firm'  => $firm,
                        'tarif' => $tarif,
                        'city'  => $this->getCity($firm),
                    ]),
                'text/html'
            );
        $this->sendMail($message);
    }

    /**
     * @param $firmUp \PropelModel\Firm
     * @param $firm
     * @param $tarif
     * @param $days
     */
    public function FirmUpLastDays($firmUp, $firm, $tarif, $days)
    {
        if (!filter_var($firmUp->getEmail(), FILTER_VALIDATE_EMAIL))
            return;

        $message = \Swift_Message::newInstance()
            ->setSubject("Пакет “{$tarif}” закончится через {$days}")
            ->setFrom(Config::$mail['noreply'])
            ->setTo($firmUp->getEmail())
            ->setBody(
                $this->view->render(
                    '@adv/emails/firm_up_last_days.twig',
                    [
                        'firm'  => $firm,
                        'tarif' => $tarif,
                        'days'  => $days,
                        'city'  => $this->getCity($firm),
                    ]),
                'text/html'
            );
        $this->sendMail($message);
    }

}
