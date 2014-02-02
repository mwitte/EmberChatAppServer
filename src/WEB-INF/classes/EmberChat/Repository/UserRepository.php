<?php

namespace EmberChat\Repository;

use EmberChat\Entities\User;

class UserRepository extends AbstractRepository {


    protected $proxyClass = 'EmberChat\Services\UserProcessor';

    /**
     * The dummy users
     *
     * @var array
     */
    protected $users;

    public function __construct() {
        parent::__construct();
        $this->users = $this->findAll();
    }

    /**
     * @param string $id
     * @return User
     */
    public function findById($id){
        foreach($this->users as $user){
            if($user->getId() == $id){
                return $user;
            }
        }
    }

    public function findAllWithout(User $user){
        $users = array();
        foreach($this->users as &$userIterator){
            if($userIterator !== $user){
                $users[] = $userIterator;
            }
        }
        return $users;
    }

    /**
     * @return User
     */
    public function getOfflineUser() {
        foreach($this->users as &$user){
            if(!$user->getClient()){
                return $user;
            }
        }
        return null;
    }

    /**
     * @param User $user
     */
    public function addUser($user) {
        error_log('addUser: ' . count($this->users));
        array_push($this->users, $user);
    }
}