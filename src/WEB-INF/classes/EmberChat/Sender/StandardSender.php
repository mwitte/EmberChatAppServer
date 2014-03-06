<?php

namespace EmberChat\Sender;

use EmberChat\Entities\User;
use EmberChat\Model\Client;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class StandardSender
{

    /**
     */
    public function __construct()
    {

    }

    public function sendMessageForUser(\JsonSerializable $message, User $user)
    {
        if ($user->isOnline()) {
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