<?php

namespace EmberChat\Handler;

use EmberChat\Entities\User;
use EmberChat\Model\Client;
use EmberChat\Repository\ClientRepository;
use EmberChat\Repository\ConversationRepository;
use EmberChat\Repository\RoomRepository;
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
class MessageSender
{

    /**
     */
    public function __construct()
    {
    }

    public function sendMessageForUser(\JsonSerializable $message, User $user)
    {
        if($user->getClient()){
            $this->sendMessageForClient($message, $user->getClient());
        }
    }

    public function sendMessageForClient(\JsonSerializable $message, Client $client)
    {
        $client->getConnection()->send(json_encode($message));
    }

    public function broadCastMessageForUsers(\JsonSerializable $message, $users)
    {
        /** @var $user User */
        foreach ($users as $user) {
            $this->sendMessageForUser($message, $user);
        }
    }

    public function broadCastMessageForClients(\JsonSerializable $message, $clients)
    {
        /** @var $client Client */
        foreach ($clients as $client) {
            $client->getConnection()->send(json_encode($message));
        }
    }

}