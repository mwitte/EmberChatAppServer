<?php

namespace EmberChat\Handler;

use EmberChat\Model\Client;
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
 * Class MessageHandler
 *
 * @TODO    this needs abstraction and separate handlers for each case!
 *
 * @package EmberChat\Handler
 */
class MessageHandler
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
     * @var ConversationRepository
     */
    protected $conversationRepository;

    public function __construct(ClientRepository $clientRepository, UserRepository $userRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
        $this->roomRepository = new RoomRepository();
        $this->conversationRepository = new ConversationRepository();
        $this->conversationHandler = new ConversationHandler(
            $this->userRepository,
            $this->clientRepository,
            $this->roomRepository
        );
    }

    /**
     * @param Client $client
     * @param string $rawMessage
     */
    public function processMessage(Client $client, $rawMessage)
    {
        $message = json_decode($rawMessage);

        switch ($message->type) {
            case 'requestHistory':
                $this->requestHistory($client, $message);
                break;
            case 'message':
                $this->conversationHandler->processMessage($client, $message);
                break;
            default:
                error_log('Unkown message type: ');
                error_log(var_export($message, true));
        }
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
        $otherUser = $this->userRepository->findById($message->user);
        // get corresponding conversation
        $conversation = $this->conversationRepository->findConversationByUserPair($client->getUser(), $otherUser);

        // build message
        $conversationUserMessage = new ConversationUserMessage();
        $conversationUserMessage->setUser($otherUser);
        $conversationUserMessage->setContent($conversation->getContent());
        // send
        $client->getConnection()->send(json_encode($conversationUserMessage));
    }

    /**
     * Broadcast the current user list to all clients
     *
     * @return void
     */
    public function broadCastUserList()
    {
        $clients = $this->clientRepository->findAll();
        $this->userRepository->resortUsers();
        /* @var $client Client */
        foreach ($clients as $connection) {
            $client = $clients[$connection];
            $otherUsers = $this->userRepository->findAllWithout($client->getUser());
            $userListMessage = new UserList();
            $userListMessage->setContent($otherUsers);
            $client->getConnection()->send(json_encode($userListMessage));
        }
    }

    /**
     * Send the current room list to all clients
     *
     * @param Client $client
     *
     * @return void
     */
    public function sendRoomList(Client $client)
    {
        $roomListMessage = new RoomList();
        $roomListMessage->setContent($this->roomRepository->findAll());

        $client->getConnection()->send(json_encode($roomListMessage));
    }
}