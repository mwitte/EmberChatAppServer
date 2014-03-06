<?php

namespace EmberChat\Model;

use EmberChat\Model\Message\Connected;
use EmberChat\Entities\User;
use EmberChat\Repository\UserRepository;
use EmberChat\Repository\RoomRepository;
use EmberChat\Service\ServiceLocator;
use Ratchet\ConnectionInterface;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
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
        new Connected($this, $serviceLocator);
    }

    /**
     * releases all references that this can be destructed
     * @returns void
     */
    public function destruct()
    {

        // only client has a user
        if ($this->user) {
            $this->user->removeClient($this);
        }
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