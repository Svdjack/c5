<?php

namespace wMVC\Entity;

use PropelModel\AdminQuery;

class Admin extends User
{
    public static function authorized()
    {
        if (self::$data['admin_id']) {
            return true;
        }
        return false;
    }

    public static function login($login, $password)
    {
        $admin = AdminQuery::create()->filterByLogin($login)->findOneByHash(md5($password));
        if($admin->getId()){
            self::$data['admin_id'] = $admin->getId();
            return true;
        }
        return false;
    }

    public static function logout()
    {
        unset(self::$data['admin_id']);
        return true;
    }
    
}