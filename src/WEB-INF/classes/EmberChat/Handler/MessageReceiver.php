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
        $message = json_decode($rawMessage);

        /**
         * @TODO this is awful
         */
        switch ($message->type) {
            case 'requestHistory':
                $conversationHandler = new User($this->serviceLocator);
                $conversationHandler->requestHistory($client, $message);
                break;
            case 'message':
                if ($message->user) {
                    $conversationHandler = new User($this->serviceLocator);
                    $conversationHandler->newMessage($client, $message);
                } else {
                    $conversationHandler = new Room($this->serviceLocator);
                    $conversationHandler->newMessage($client, $message);
                }
                break;
            case 'RoomJoin':
                $conversationHandler = new Room($this->serviceLocator);
                $conversationHandler->joinUser($client, $message);
                break;
            case 'RoomLeave':
                $conversationHandler = new Room($this->serviceLocator);
                $conversationHandler->leaveUser($client, $message);
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