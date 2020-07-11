<?php

namespace wMVC\Entity;

use Propel\Runtime\ActiveQuery\Criteria;
use PropelModel\UserQuery;

class User
{
    const USER_ADD_EXIST = 11100;
    const USER_ADD_WRONG_EMAIL = 11101;
    public static $data = [];

    public static function init()
    {
        self::$data = &$_SESSION;
    }

    public static function add($email, $password = '')
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return self::USER_ADD_WRONG_EMAIL;
        }
        if (UserQuery::create()->filterByEmail($email)->count()) {
            return self::USER_ADD_EXIST;
        }

        $user = new \PropelModel\User();
        $user->setLogin($email);
        $user->setEmail($email);
        $user->setName($email);
        $user->setRegDate(time());
        if (!$password) {
            $password = self::generatePassword();
        }
        $user->setPassword($password);
        $user->save();
        Notifier::new_user($user, $password);
        return $user;
    }

    public static function authorized()
    {
        if (isset(self::$data['user_id'])) {
            return true;
        }
        return false;
    }

    public static function login($login, $password)
    {
        $user = UserQuery::create()
            ->filterByEmail($login)
            ->_or()
            ->filterByLogin($login)
            ->findOne();
        if ($user && $user->checkPassword($password)) {
            self::set_user_data($user);
            return true;
        }
        return false;
    }

    public static function set_user_data(\PropelModel\User $user)
    {
        self::$data = [
            'user_id'  => $user->getId(),
            'login'    => $user->getLogin(),
            'name'     => $user->getName(),
            'email'    => $user->getEmail(),
            'is_admin' => $user->getRole()
        ];
    }

    public static function logout()
    {
        unset(self::$data['user_id']);
        unset(self::$data['login']);
        unset(self::$data['name']);
        unset(self::$data['email']);
        unset(self::$data['is_admin']);
        $_SESSION['is_admin'] = false;
        \session_destroy();
        return true;
    }

    public static function getId()
    {
        if (empty(self::$data['user_id'])) {
            return 0;
        }
        return self::$data['user_id'];
    }

    public static function generatePassword($length = 9, $add_dashes = false, $available_sets = 'luds')
    {
        $sets = array();
        if (strpos($available_sets, 'l') !== false) {
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        }
        if (strpos($available_sets, 'u') !== false) {
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }
        if (strpos($available_sets, 'd') !== false) {
            $sets[] = '23456789';
        }
        if (strpos($available_sets, 's') !== false) {
            $sets[] = '!@#$%&*?';
        }
        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++) {
            $password .= $all[array_rand($all)];
        }
        $password = str_shuffle($password);
        if (!$add_dashes) {
            return $password;
        }
        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while (strlen($password) > $dash_len) {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }
}