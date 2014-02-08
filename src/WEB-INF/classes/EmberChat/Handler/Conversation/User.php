<?php

namespace EmberChat\Handler\Conversation;

use EmberChat\Model\Client;
use EmberChat\Model\Message\UserConversation;
use EmberChat\Model\Message\UserHistory;
use Ratchet\ConnectionInterface;

/**
 * Class ConversationHandler
 *
 * @package EmberChat\Handler
 */
class User extends \EmberChat\Handler\Conversation
{

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

        $conversation = $this->serviceLocator->getConversationRepository()->findConversationByUserPair(
            $client->getUser(),
            $otherUser
        );
        $conversation->appendContent($client->getUser(), $message->content);

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

}