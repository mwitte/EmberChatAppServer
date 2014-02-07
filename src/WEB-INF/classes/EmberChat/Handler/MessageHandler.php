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
     * @var Client
     */
    protected $client;

    /**
     * @var ConversationRepository
     */
    protected $conversationRepository;

    public function __construct()
    {
        $this->conversationRepository = new ConversationRepository();
    }

    /**
     * @param UserRepository $userRepository
     */
    public function setUserRepository(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ClientRepository $clientRepository
     */
    public function setClientRepository(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @param RoomRepository $roomRepository
     */
    public function setRoomRepository(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    /**
     * @param Client $client
     * @param string $rawMessage
     */
    public function processMessage(Client $client, $rawMessage)
    {
        $this->client = $client;
        $message = json_decode($rawMessage);

        switch ($message->type) {
            case 'requestHistory':
                $this->requestHistory($message);
                break;
            case 'message':
                $this->conversation($message);
                break;
            default:
                error_log('Unkown message type: ');
                error_log(var_export($message, true));
        }
    }

    /**
     * Process a conversation message
     *
     * @param \stdClass $message
     */
    public function conversation(\stdClass $message)
    {
        if ($message->user) {
            $this->userConversation($message);
        } else {
            $this->roomConversation($message);
        }
    }

    /**
     * Process a roomConversation message
     *
     * @param \stdClass $message
     */
    protected function roomConversation(\stdClass $message)
    {
        error_log('Tried to handle a conversation message which not findable User');
        $room = $this->roomRepository->findById($message->room);
        $conversationRoomMessage = new ConversationRoomMessage();
        $conversationRoomMessage->setRoom($room);
        $conversationRoomMessage->setContent(array(array('user' => 'Server', 'content' => 'No implemented yet!')));
        $this->client->getConnection()->send(json_encode($conversationRoomMessage));
    }

    /**
     * Process a userConversation message
     *
     * @param \stdClass $message
     */
    protected function userConversation(\stdClass $message)
    {
        $otherUser = $this->userRepository->findById($message->user);
        // save for history
        $conversation = $this->conversationRepository->findConversationByUserPair($this->client->getUser(), $otherUser);
        $conversation->appendContent($this->client->getUser(), $message->content);

        $tempConversation = new Conversation();
        $tempConversation->appendContent($this->client->getUser(), $message->content);

        // build message
        $conversationUserMessage = new ConversationUserMessage();
        $conversationUserMessage->setUser($otherUser);
        $conversationUserMessage->setContent($tempConversation->getContent());
        // send to client
        $this->client->getConnection()->send(json_encode($conversationUserMessage));

        if ($otherUser->getClient()) {
            $otherClient = $otherUser->getClient();
            $conversationUserMessage->setUser($this->client->getUser());
            $otherClient->getConnection()->send(json_encode($conversationUserMessage));
        }
    }

    /**
     * Handle a requestHistory message
     *
     * @param \stdClass $message
     */
    public function requestHistory(\stdClass $message)
    {
        // get other user
        $otherUser = $this->userRepository->findById($message->user);
        // get corresponding conversation
        $conversation = $this->conversationRepository->findConversationByUserPair($this->client->getUser(), $otherUser);

        // build message
        $conversationUserMessage = new ConversationUserMessage();
        $conversationUserMessage->setUser($otherUser);
        $conversationUserMessage->setContent($conversation->getContent());
        // send
        $this->client->getConnection()->send(json_encode($conversationUserMessage));
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