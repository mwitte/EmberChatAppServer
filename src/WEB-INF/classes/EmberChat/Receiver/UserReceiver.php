<?php

namespace EmberChat\Receiver;


use EmberChat\Model\Client;
use EmberChat\Model\Message\KeyExchange;
use EmberChat\Model\Message\UserConversation;
use EmberChat\Model\Message\UserHistory;
use Ratchet\ConnectionInterface;


class UserReceiver extends AbstractReceiver
{

    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        switch ($message->subType) {
            case 'RequestHistory':
                $this->requestHistory($client, $message);
                break;
            case 'KeyExchange':
                $this->keyExchange($client, $message);
                break;
            case 'Conversation':
                $this->newMessage($client, $message);
                break;
            default:
                error_log('Unkown User message subType: ');
                error_log(var_export($message, true));
        }
    }

    /**
     * @var Client
     */
    protected $client;

    /**
     * Process a conversation message
     *
     * @param Client    $client
     * @param \stdClass $message
     */
    public function newMessage(Client $client, \stdClass $message)
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

    /**
     * Handle a requestHistory message
     *
     * @param Client    $client
     * @param \stdClass $message
     */
    public function requestHistory(Client $client, \stdClass $message)
    {
        // get other user
        $otherUser = $this->serviceLocator->getUserRepository()->findById($message->user);
        new UserHistory($client, $otherUser, $this->serviceLocator);
    }

    /**
     * Client requests a encrypted key of other user
     *
     * @param Client    $clinet
     * @param \stdClass $message
     */
    public function keyExchange(Client $client, \stdClass $message)
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
            new KeyExchange($otherUser, $client, $message);
            //@todo notify both that end-to-end encryption only works with one client each user
            return false;
        }
        // everything is fine, send exchange message
        new KeyExchange($client->getUser(), $otherUser->getClients()[0], $message);
    }
}