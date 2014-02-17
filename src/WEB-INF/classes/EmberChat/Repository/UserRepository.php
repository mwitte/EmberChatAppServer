<?php

namespace EmberChat\Repository;

use EmberChat\Entities\User;
use EmberChat\Services\UserProcessor;

class UserRepository extends AbstractRepository
{


    protected $proxyClass = 'EmberChat\Services\UserProcessor';


    protected $myRepo;

    /**
     * The dummy users
     *
     * @var array
     */
    protected $users;

    public function __construct($initialContext, $serviceLocator)
    {
        parent::__construct($initialContext);
        $this->users = $this->loadAll();
    }

    /**
     * @param User $user
     *
     * @TODO pretty dirty!!!
     */
    public function persistUser(User $user){
        //$this->getProxy($this->proxyClass)->updatePassword($user->getPassword(), $user->getId());
        error_log('persistUser');
        $this->getProxy($this->proxyClass)->updateEntity($user);
    }

    /**
     * @param string $id
     *
     * @return User
     */
    public function findById($id)
    {
        /** @var User $user */
        foreach ($this->users as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }
        return null;
    }

    public function findByAuth($auth)
    {
        /** @var User $user */
        foreach ($this->users as $user) {
            if ($user->getAuth() === $auth) {
                return $user;
            }
        }
        return null;
    }

    public function findByToken($token)
    {
        /** @var User $user */
        foreach ($this->users as $user) {
            if ($user->getToken() === $token) {
                return $user;
            }
        }
        return null;
    }

    public function findAllWithout(User $user)
    {
        $users = array();
        foreach ($this->users as &$userIterator) {
            if ($userIterator !== $user) {
                $users[] = $userIterator;
            }
        }
        return $users;
    }

    /**
     * @return User
     */
    public function getOfflineUser()
    {
        /** @var $user User */
        foreach ($this->users as &$user) {
            if ($user->isOnline()) {
                return $user;
            }
        }
        return null;
    }

    /**
     * @param User $user
     */
    public function addUser($user)
    {
        array_push($this->users, $user);
    }

    /**
     * Callback for usort orders users by client state
     *
     * @param User $a
     * @param User $b
     *
     * @return int
     */
    static function cmpClientState($a, $b)
    {
        if ($a->isOnline() == $b->isOnline()) {
            return 0;
        }
        return $b->isOnline() ? +1 : -1;
    }

    /**
     * Resort the users
     */
    public function resortUsers()
    {
        if (count($this->users) > 1) {
            usort($this->users, array('\EmberChat\Repository\UserRepository', "cmpClientState"));
        }

    }
}