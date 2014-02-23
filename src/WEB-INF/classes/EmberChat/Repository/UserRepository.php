<?php

namespace EmberChat\Repository;

use EmberChat\Entities\User;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
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
     */
    public function persistUser(User $user){
        $this->getProxy($this->proxyClass)->updateEntity($user);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function createNew(User $user){
        if(!$this->findByAuth($user->getAuth())){
            // generate unique id for this user
            $user->setId(hash('sha256', mt_rand() . serialize($user) . mt_rand()));
            $this->getProxy($this->proxyClass)->createNew($user);
            $this->users[] = $user;
            return true;
        }
        return false;
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

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function remove(User $user){
        // remove room in persistence
        if($this->getProxy($this->proxyClass)->remove($user)){
            /** @var User $userIterator */
            foreach ($this->users as $key => $userIterator) {
                if ($userIterator->getId() == $user->getId()) {
                    unset($this->users[$key]);
                    $this->users = array_values($this->users);
                    $user->destruct();
                    return true;
                }
            }
        }
    }
}