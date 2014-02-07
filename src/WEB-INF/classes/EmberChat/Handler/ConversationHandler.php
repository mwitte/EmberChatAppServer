<?php

namespace EmberChat\Handler;

use EmberChat\Model\Client;
use EmberChat\Model\Message\ConversationRoom as ConversationRoomMessage;
use EmberChat\Model\Message\ConversationUser as ConversationUserMessage;
use EmberChat\Model\Message\RoomList;
use EmberChat\Model\Message\UserList;
use EmberChat\Model\Conversation;
use EmberChat\Repository\ClientRepository;
use EmberChat\Repository\ConversationRepository;
use EmberChat\Repository\RoomRepository;
use EmberChat\Repository\UserRepository;
use Ratchet\ConnectionInterface;

/**
 * Class ConversationHandler
 *
 * @package EmberChat\Handler
 */
class ConversationHandler
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
     * @var RoomRepository
     */
    protected $roomRepository;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ConversationRepository
     */
    protected $conversationRepository;

    public function __construct(
        UserRepository $userRepository,
        ClientRepository $clientRepository,
        RoomRepository $roomRepository
    ) {
        $this->userRepository = $userRepository;
        $this->clientRepository = $clientRepository;
        $this->roomRepository = $roomRepository;
        $this->conversationRepository = new ConversationRepository();
    }

    /**
     * Process a conversation message
     *
     * @param Client    $client
     * @param \stdClass $message
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        if ($message->user) {
            $this->userConversation($client, $message);
        } else {
            $this->roomConversation($client, $message);
        }
    }

    /**
     * Process a roomConversation message
     *
     * @param Client    $client
     * @param \stdClass $message
     */
    protected function roomConversation(Client $client, \stdClass $message)
    {
        error_log('roomConversation needs to get implemented');
        $room = $this->roomRepository->findById($message->room);
        $conversationRoomMessage = new ConversationRoomMessage();
        $conversationRoomMessage->setRoom($room);
        $conversationRoomMessage->setContent(array(array('user' => 'Server', 'content' => 'No implemented yet!')));
        $client->getConnection()->send(json_encode($conversationRoomMessage));
    }

    /**
     * Process a userConversation message
     *
     * @param Client    $client
     * @param \stdClass $message
     */
    protected function userConversation(Client $client, \stdClass $message)
    {
        $otherUser = $this->userRepository->findById($message->user);
        // save for history
        $conversation = $this->conversationRepository->findConversationByUserPair($client->getUser(), $otherUser);
        $conversation->appendContent($client->getUser(), $message->content);

        $tempConversation = new Conversation();
        $tempConversation->appendContent($client->getUser(), $message->content);

        // build message
        $conversationUserMessage = new ConversationUserMessage();
        $conversationUserMessage->setUser($otherUser);
        $conversationUserMessage->setContent($tempConversation->getContent());
        // send to client
        $client->getConnection()->send(json_encode($conversationUserMessage));

        if ($otherUser->getClient()) {
            $otherClient = $otherUser->getClient();
            $conversationUserMessage->setUser($client->getUser());
            $otherClient->getConnection()->send(json_encode($conversationUserMessage));
        }
    }

}