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
        foreach ($this->users as $user) {
            if ($user->getId() == $id) {
                return $user;
            }
        }
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
            if (!$user->getClient()) {
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
        if ((bool)$a->getClient() == (bool)$b->getClient()) {
            return 0;
        }
        return ($b->getClient()) ? +1 : -1;
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