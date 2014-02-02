<?php

namespace EmberChat\Handler;

use EmberChat\Model\Client;
use EmberChat\Model\Message\UserList;
use EmberChat\Repository\ClientRepository;
use EmberChat\Repository\UserRepository;
use Ratchet\ConnectionInterface;

class ClientHandler {

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct() {
        $this->clientRepository = new ClientRepository();
        $this->userRepository = new UserRepository();
        $this->messageHandler = new MessageHandler();
        $this->messageHandler->setUserRepository($this->userRepository);
        $this->messageHandler->setClientRepository($this->clientRepository);
    }

    /**
     * @param ConnectionInterface $connection
     * @return void
     */
    public function createNewClient(ConnectionInterface $connection) {
        $client = new Client($connection, $this->userRepository);
        $this->clientRepository->addClient($client, $connection);
        $this->broadCastUserList();
    }

    /**
     * @param ConnectionInterface $connection
     * @return void
     */
    public function unsetClient(ConnectionInterface $connection) {
        $this->clientRepository->removeClient($connection);
        $this->broadCastUserList();
    }

    /**
     * @param ConnectionInterface $connection
     * @param string $message
     */
    public function messageFromClient(ConnectionInterface $connection, $message){
        $this->messageHandler->processMessage($this->clientRepository->findClientByConnection($connection), $message);
    }

    /**
     * Broadcast the current user list to all clients
     * @return void
     */
    private function broadCastUserList() {
        $clients = $this->clientRepository->findAll();
        /* @var $client Client */
        foreach($clients as $connection){
            $client = $clients[$connection];
            $otherUsers = $this->userRepository->findAllWithout($client->getUser());
            $userListMessage = new UserList();
            $userListMessage->setContent($otherUsers);
            $client->getConnection()->send(json_encode($userListMessage));
        }
    }
}