<?php

namespace EmberChat\Receiver\Message\User;


use EmberChat\Model\Client;
use EmberChat\Model\Message\UserConversation;
use EmberChat\Receiver\AbstractReceiver;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class Conversation extends AbstractReceiver
{

    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        $otherUser = $this->serviceLocator->getUserRepository()->findById($message->user);

        // save message content only if it's not encrypted
        if (!$message->encrypted) {
            $conversation = $this->serviceLocator->getConversationRepository()->findConversationByUserPair(
                $client->getUser(),
                $otherUser
            );
            $conversation->appendContent($client->getUser(), $message->content);
        }

        new UserConversation($client->getUser(), $otherUser, $message->content);
    }
}