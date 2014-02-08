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
        if (count($user->getClients()) > 0) {
            $this->broadCastMessageForClients($message, $user->getClients());
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
            $this->sendMessageForClient($message, $client);
        }
    }

}