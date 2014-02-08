<?php

namespace EmberChat\Model;

use EmberChat\Model\Message\Settings;
use EmberChat\Entities\User;
use EmberChat\Repository\UserRepository;
use EmberChat\Repository\RoomRepository;
use EmberChat\Service\ServiceLocator;
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
     * @param ServiceLocator      $serviceLocator
     */
    public function __construct(
        ConnectionInterface $connection,
        ServiceLocator $serviceLocator
    ) {
        $this->serviceLocator = $serviceLocator;
        $this->userRepository = $this->serviceLocator->getUserRepository();
        $this->connection = $connection;
        $this->user = $this->userRepository->getOfflineUser();
        $this->user->setClient($this);
        new Settings($this);
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