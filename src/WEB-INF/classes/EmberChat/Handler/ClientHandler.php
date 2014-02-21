<?php

namespace EmberChat\Handler;

use EmberChat\Model\Client;
use EmberChat\Receiver\StandardReceiver;
use EmberChat\Repository\ClientRepository;
use EmberChat\Service\ServiceLocator;
use Ratchet\ConnectionInterface;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
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
        $this->standardReceiver = new StandardReceiver($this->serviceLocator);
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
    }

    /**
     * @param ConnectionInterface $connection
     *
     * @return void
     */
    public function unsetClient(ConnectionInterface $connection)
    {
        $this->clientRepository->removeClient($connection);
        $this->standardReceiver->broadCastUserList();
    }

    /**
     * @param ConnectionInterface $connection
     * @param string              $rawMessage
     */
    public function messageFromClient(ConnectionInterface $connection, $rawMessage)
    {
        /** @var \stdClass $message */
        $message = json_decode($rawMessage);
        if ($message === null) {
            error_log('ERROR: Could not decode given message: ' . (string)$rawMessage);
            return;
        }
        $this->standardReceiver->processMessage($this->clientRepository->findClientByConnection($connection), $message);
    }


}