<?php

namespace EmberChat\Handler;

use EmberChat\Model\Client;
use EmberChat\Repository\ClientRepository;
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

    public function __construct()
    {
        $this->clientRepository = new ClientRepository();
        $this->userRepository = new UserRepository();
        $this->messageHandler = new MessageHandler($this->clientRepository, $this->userRepository);
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