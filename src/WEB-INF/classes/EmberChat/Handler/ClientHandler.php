<?php

namespace EmberChat\Handler;

use EmberChat\Model\Client;
use EmberChat\Repository\ClientRepository;
use EmberChat\Repository\RoomRepository;
use EmberChat\Repository\UserRepository;
use Ratchet\ConnectionInterface;

class ClientHandler
{

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var RoomRepository
     */
    protected $roomRepository;

    public function __construct()
    {
        $this->clientRepository = new ClientRepository();
        $this->userRepository = new UserRepository();
        $this->messageHandler = new MessageHandler();
        $this->roomRepository = new RoomRepository();
        $this->messageHandler->setUserRepository($this->userRepository);
        $this->messageHandler->setClientRepository($this->clientRepository);
        $this->messageHandler->setRoomRepository($this->roomRepository);
    }

    /**
     * @param ConnectionInterface $connection
     *
     * @return void
     */
    public function createNewClient(ConnectionInterface $connection)
    {
        $client = new Client($connection, $this->userRepository);
        $this->clientRepository->addClient($client, $connection);
        $this->messageHandler->broadCastUserList();
        $this->messageHandler->sendRoomList($client);
    }

    /**
     * @param ConnectionInterface $connection
     *
     * @return void
     */
    public function unsetClient(ConnectionInterface $connection)
    {
        $this->clientRepository->removeClient($connection);
        $this->messageHandler->broadCastUserList();
    }

    /**
     * @param ConnectionInterface $connection
     * @param string              $message
     */
    public function messageFromClient(ConnectionInterface $connection, $message)
    {
        $this->messageHandler->processMessage($this->clientRepository->findClientByConnection($connection), $message);
    }


}