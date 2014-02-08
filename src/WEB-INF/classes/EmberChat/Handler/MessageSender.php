<?php

namespace EmberChat\Handler;

use EmberChat\Entities\User;
use EmberChat\Model\Client;
use Ratchet\ConnectionInterface;

/**
 * Class MessageHandler
 *
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