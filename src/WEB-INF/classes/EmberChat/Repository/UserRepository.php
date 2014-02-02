<?php

namespace EmberChat\Repository;



use EmberChat\Model\Serializable\User;

class UserRepository {

    protected static $instance;

    /**
     * The dummy users
     *
     * @var array
     */
    protected $users;

    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new UserRepository();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->users = array();
        $this->createDummyData();
    }

    /**
     * for Development
     */
    protected function createDummyData(){
        $this->users = array();
        $this->users[] = new User('de713bf89dd84fd5648a08b8ba4a5d1b18a964c1', 'Matthias');
        $this->users[] = new User('01b7974ee4de9fba4cb4e777a29673163ed4347d', 'Dominik');
        $this->users[] = new User('22caebc61d4bbdf69fa6b19da6b10ae3dca5a2cf', 'Prof. Dr. Bert');
        $this->users[] = new User('43954a1bc424d641406148334c3c4defa4b45f47', 'Bam Oida');
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