<?php

namespace EmberChat\Model;

use EmberChat\Model\Message\Settings;
use EmberChat\Model\Message\UserList;
use EmberChat\Model\Serializable\User;
use EmberChat\Repository\UserRepository;
use Ratchet\ConnectionInterface;

class Client {

    /**
     * @var UserRepository
     */
    protected $userRepository;

    protected $user;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection) {
        $this->userRepository = UserRepository::getInstance();
        $this->connection = $connection;
        $this->user = $this->userRepository->getOfflineUser();
        $this->user->setClient($this);
        $this->sendSettings();
        $this->sendUserList();
    }

    protected function sendSettings(){
        $message = new Settings();
        $message->setUser($this->user);
        $this->connection->send(json_encode($message));
    }

    protected function sendUserList(){
        $message = new UserList();
        $message->setContent($this->userRepository->findAllWithout($this->user));
        $this->connection->send(json_encode($message));
    }

    public function myDestruct(){
        $this->user->unsetClient();
    }

    /**
     * @TODO This is not called, why?
     */
    public function __destruct(){
        error_log('Client: __destruct');
    }

    /**
     * @param \Ratchet\ConnectionInterface $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return \Ratchet\ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}