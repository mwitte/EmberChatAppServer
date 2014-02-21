<?php

namespace EmberChat\Receiver\Message\User;


use EmberChat\Model\Client;
use EmberChat\Model\Message\UserHistory;
use EmberChat\Receiver\AbstractReceiver;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class RequestHistory extends AbstractReceiver
{

    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        // get other user
        $otherUser = $this->serviceLocator->getUserRepository()->findById($message->user);
        new UserHistory($client, $otherUser, $this->serviceLocator);
    }
}