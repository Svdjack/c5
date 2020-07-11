<?php

namespace wMVC\Controllers\Admin;

use PropelModel\Base\UserQuery;
use wMVC\Controllers\abstractAdmin;
use wMVC\Entity\User;

class Auth extends abstractAdmin
{
    public function auth()
    {
        $form = json_decode(self::getInputContent());
        $user = UserQuery::create()
            ->filterByRole(1)
            ->findOneByLogin($form->login);
        if (!$user) {
            return self::response(array('error' => 1));
        }
        if (!$user->checkPassword($form->password)) {
            if(DEBUG) {
                return self::response(array(
                    'error' => 2,
                    'db' => $user->getHash(),
                    'form' => $form->password
                ));
            }
            return self::response(array('error' => 2));
        }

        User::login($form->login, $form->password);

        return self::response(['ok' => 1]);
    }

    public function logout()
    {
        User::logout();
    }

    public function checkAuth()
    {
        $user = UserQuery::create()->findPk(User::getId());
        if (!$user)
            return self::response(array('error' => 1));

        if ($user->getRole() == 1)
            return self::response(array('ok' => 1));
        return self::response(array('error' => 1));
    }
}