<?php

namespace wMVC\Controllers\Admin;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use PropelModel\UserQuery;
use PropelModel\FirmQuery;
use PropelModel\FirmUserQuery;
use PropelModel\Map\RegionTableMap;
use PropelModel\Region;
use PropelModel\RegionQuery;
use wMVC\Controllers\abstractAdmin;
use wMVC\Core\Exceptions\SystemExit;

Class User extends abstractAdmin
{
    const SORT_NAME = 'name';
    const SORT_MAIL = 'email';
    const SORT_CREATED = 'created';
    const MEDIA_ID = 1;
    const KOTEL_ID = 31337;

    public function getList($type, $page = 0)
    {
        self::requireAdmin();
        $users = UserQuery::create()
            ->filterById([self::MEDIA_ID, self::KOTEL_ID], Criteria::NOT_IN);
        switch ($type) {
            case 'users-name':
                $users = $users->orderByName(Criteria::ASC);
                break;

            case 'users-email':
                $users = $users->orderByEmail(Criteria::ASC);
                break;

            case 'users-created':
                $users = $users->orderByRegDate(Criteria::DESC);
                break;

            default:
//                \Page::show404();
                break;
        }

        self::response($this->formListResponse($users, $page));
    }

    public function searchUser($name)
    {
        self::requireAdmin();
        $users = UserQuery::create()
            ->filterByEmail('%'.$name.'%')
            ->orderByEmail(Criteria::ASC)
            ->limit(13);
        self::response($this->formListResponse($users));
    }

    protected function formListResponse(UserQuery $users, $page = null)
    {
        $currentPage = 1;
        $totalPages = 1;
        if($page !== null){
            $users = $users->paginate($page, 100);
            $totalPages = $users->getLastPage();
            $currentPage = $page;
        }
        $data = [];
        foreach ($users as $user) {
            if($user->getName() === 'kotel21'){
                continue;
            }
            $data[$user->getId()] = array_change_key_case($user->toArray(), CASE_LOWER);
            $data[$user->getId()]['firms'] = [];
        }

        $firms_all = FirmQuery::create()
            ->useFirmUserQuery()
            ->filterByUserId(array_keys($data), Criteria::IN)
            ->addAsColumn('user_id', 'FirmUser.UserId')
            ->endUse()
            ->find();

        foreach ($firms_all as $firm) {
            $data[$firm->getVirtualColumn('user_id')]['firms'][] = array(
                'firm_name' => $firm->getName(),
                'firm_id'   => $firm->getId(),
                'firm_url'  => $firm->getAlias()
            );
        }


        $result = array('data'        => array_values($data),
                        'currentPage' => $currentPage,
                        'totalPages'  => $totalPages);
        return $result;
    }


    public function getUser($id)
    {
        self::requireAdmin();
        $result = array_change_key_case(UserQuery::create()->findPk($id)->toArray());
        self::response($result);
    }

    public function unbindFirm($id)
    {
        self::requireAdmin();
        FirmUserQuery::create()->filterByFirmId($id)->delete();
        self::response(array('ok' => 1));
    }

    public function removeUser($id)
    {
        self::requireAdmin();
        FirmUserQuery::create()->filterByUserId($id)->delete();
        UserQuery::create()->filterById($id)->delete();
        self::response(array('ok' => 1));
    }

    public function updateUser($id)
    {
        self::requireAdmin();
        $user_data = (array)json_decode(self::getInputContent());

        $user = UserQuery::create()->findPk($id);

        $user->setName($user_data['name'])
            ->setLogin($user_data['login'])
            ->save();

        self::response(array('ok' => 1));
    }

    public function addUser()
    {
        self::requireAdmin();
        $email = $_POST['email'];
        $response = \wMVC\Entity\User::add($email);
        if($response === \wMVC\Entity\User::USER_ADD_WRONG_EMAIL){
            self::response(array('error' => 'Неверный email'));
        }elseif ($response === \wMVC\Entity\User::USER_ADD_EXIST){
            self::response(array('error' => 'Пользователь уже существует'));
        }

        self::response(array('ok' => 1));
    }
}