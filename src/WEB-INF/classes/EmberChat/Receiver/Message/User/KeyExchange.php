<?php

namespace EmberChat\Receiver\Message\User;


use EmberChat\Model\Client;
use EmberChat\Model\Message\KeyExchange as KeyExchangeMessage;
use EmberChat\Receiver\AbstractReceiver;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class KeyExchange extends AbstractReceiver
{

    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        // get other user
        $otherUser = $this->serviceLocator->getUserRepository()->findById($message->user);
        if (!$otherUser->isOnline()) {
            //@todo notify current client
            return false;
        }

        // if one user got multiple clients
        if (count($otherUser->getClients()) > 1 || count($client->getUser()->getClients()) > 1) {
            $message = new \stdClass();
            $message->disable = true;
            // set the other user as receiver that the current client receives this message for the correct conversation
            new KeyExchangeMessage($otherUser, $client, $message);
            //@todo notify both that end-to-end encryption only works with one client each user
            return false;
        }
        // everything is fine, send exchange message
        new KeyExchangeMessage($client->getUser(), $otherUser->getClients()[0], $message);
    }
}