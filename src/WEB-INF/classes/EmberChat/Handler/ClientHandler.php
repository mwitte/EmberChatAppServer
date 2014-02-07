<?php

namespace EmberChat\Handler;

use EmberChat\Model\Client;
use EmberChat\Repository\ClientRepository;
use EmberChat\Repository\UserRepository;
use EmberChat\Service\ServiceLocator;
use Ratchet\ConnectionInterface;

class ClientHandler
{

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var ServiceLocator
     */
    protected $serviceLocator;

    /**
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(ServiceLocator $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        $this->clientRepository = $serviceLocator->getClientRepository();
        $this->messageHandler = new MessageHandler($this->serviceLocator);
    }

    /**
     * @param ConnectionInterface $connection
     *
     * @return void
     */
    public function createNewClient(ConnectionInterface $connection)
    {
        $client = new Client($connection, $this->serviceLocator);
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