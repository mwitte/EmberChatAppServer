<?php

namespace EmberChat\Model;

use EmberChat\Model\Message\Settings;
use EmberChat\Model\Message\UserList;
use EmberChat\Entities\User;
use EmberChat\Repository\UserRepository;
use EmberChat\Repository\RoomRepository;
use Ratchet\ConnectionInterface;

class Client
{

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var RoomRepository
     */
    protected $roomRepository;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @param ConnectionInterface $connection
     * @param UserRepository      $userRepository
     */
    public function __construct(
        ConnectionInterface $connection,
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
        $this->connection = $connection;
        $this->user = $this->userRepository->getOfflineUser();
        $this->user->setClient($this);
        $this->sendSettings();
    }

    protected function sendSettings()
    {
        $message = new Settings();
        $message->setUser($this->user);
        $this->connection->send(json_encode($message));
    }

    public function myDestruct()
    {
        $this->user->unsetClient();
    }

    /**
     * @TODO This is not called, why?
     */
    public function __destruct()
    {
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