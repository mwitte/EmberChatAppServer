<?php

namespace EmberChat\Repository;

use EmberChat\Entities\User;

class UserRepository extends AbstractRepository
{


    protected $proxyClass = 'EmberChat\Services\UserProcessor';

    /**
     * The dummy users
     *
     * @var array
     */
    protected $users;

    public function __construct()
    {
        parent::__construct();
        $this->users = $this->loadAll();
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
        foreach ($this->users as &$user) {
            if (count($user->getClients()) <= 0) {
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
        error_log('addUser: ' . count($this->users));
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
        if ((bool)count($a->getClients()) == count((bool)$b->getClients())) {
            return 0;
        }
        return (count($b->getClients())) ? +1 : -1;
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