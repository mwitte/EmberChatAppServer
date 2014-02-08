<?php

namespace EmberChat\Handler;

use EmberChat\Handler\Conversation\Room as RoomConversationHandler;
use EmberChat\Handler\Conversation\User as UserConversationHandler;
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
 * @TODO    this needs abstraction and separate handlers for each case!
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
        $message = json_decode($rawMessage);

        switch ($message->type) {
            case 'requestHistory':
                $conversationHandler = new UserConversationHandler($this->serviceLocator);
                $conversationHandler->requestHistory($client, $message);
                break;
            case 'message':
                if ($message->user) {
                    $conversationHandler = new UserConversationHandler($this->serviceLocator);
                    $conversationHandler->newMessage($client, $message);
                } else {
                    $conversationHandler = new RoomConversationHandler($this->serviceLocator);
                    $conversationHandler->processMessage($client, $message);
                }
                break;
            case 'RoomJoin':

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
        $clients = $this->clientRepository->findAll();
        $this->userRepository->resortUsers();
        /* @var $client Client */
        foreach ($clients as $connection) {
            $client = $clients[$connection];
            new UserList($client, $this->serviceLocator);
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