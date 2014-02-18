<?php

namespace EmberChat\Handler;

use EmberChat\Handler\Conversation\Room;
use EmberChat\Handler\Conversation\User;
use EmberChat\Model\Client;
use EmberChat\Model\Message\RoomList;
use EmberChat\Model\Message\UserList;
use EmberChat\Repository\ClientRepository;
use EmberChat\Repository\UserRepository;
use EmberChat\Service\ServiceLocator;
use Ratchet\ConnectionInterface;

/**
 * Class MessageHandler
 *
 *
 * @package EmberChat\Handler
 */
class MessageReceiver
{

    /**
     * @var UserRepository
     */
    protected $userRepository;

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
        $this->clientRepository = $this->serviceLocator->getClientRepository();
        $this->userRepository = $this->serviceLocator->getUserRepository();
    }

    /**
     * @param Client $client
     * @param string $rawMessage
     */
    public function processMessage(Client $client, $rawMessage)
    {
        /** @var \stdClass $message */
        $message = json_decode($rawMessage);
        if ($message === null) {
            error_log('ERROR: Could not decode given message: ' . (string)$rawMessage);
            return false;
        }
        if ($client->getUser()) {
            $this->authenticatedClientProcessing($client, $message);
        } else {
            $authenticationHandler = new AuthenticationHandler($this->serviceLocator);
            $authenticationHandler->authenticationMessage($client, $message);
        }
    }

    /**
     * Actions for authenticated clients
     *
     * @param Client    $client
     * @param \stdClass $message
     */
    public function authenticatedClientProcessing(Client $client, \stdClass $message)
    {
        /**
         * @TODO this is awful
         */
        switch ($message->type) {
            case 'requestHistory':
                $conversationHandler = new User($this->serviceLocator);
                $conversationHandler->requestHistory($client, $message);
                break;
            case 'UserConversation':
                $conversationHandler = new User($this->serviceLocator);
                $conversationHandler->newMessage($client, $message);
                break;
            case 'RoomConversation':
                $conversationHandler = new Room($this->serviceLocator);
                $conversationHandler->newMessage($client, $message);
                break;
            case 'RoomJoin':
                $conversationHandler = new Room($this->serviceLocator);
                $conversationHandler->joinUser($client, $message);
                break;
            case 'RoomLeave':
                $conversationHandler = new Room($this->serviceLocator);
                $conversationHandler->leaveUser($client, $message);
                break;
            case 'KeyExchange':
                $conversationHandler = new User($this->serviceLocator);
                $conversationHandler->keyExchange($client, $message);
                break;
            case 'UpdateProfile':
                $profileHandler = new Profile($this->serviceLocator);
                $profileHandler->updateProfile($client, $message);
                break;
            case 'Admin':
                $adminHandler = new Admin($this->serviceLocator);
                $adminHandler->processMessage($client, $message);
                break;
            default:
                error_log('Unkown message type: ');
                error_log(var_export($message, true));
        }
    }


    /**
     * Broadcast the current user list to all clients
     *
     * @return void
     */
    public function broadCastUserList()
    {
        // @TODO refactor only for authed clients/users
        $clients = $this->clientRepository->findAll();
        $this->userRepository->resortUsers();
        /* @var $client Client */
        foreach ($clients as $connection) {
            $client = $clients[$connection];
            if ($client->getUser()) {
                new UserList($client, $this->serviceLocator);
            }
        }
    }

    /**
     * Send the current room list to all clients
     *
     * @param Client $client
     *
     * @return void
     */
    public function sendRoomList(Client $client)
    {
        new RoomList($client, $this->serviceLocator);
    }

}