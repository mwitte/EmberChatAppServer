<?php

namespace EmberChat\Handler;

use EmberChat\Model\Client;
use EmberChat\Model\Message\Conversation as ConversationMessage;
use EmberChat\Model\Conversation;
use EmberChat\Repository\ClientRepository;
use EmberChat\Repository\ConversationRepository;
use EmberChat\Repository\UserRepository;
use Ratchet\ConnectionInterface;

class MessageHandler {

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ConversationRepository
     */
    protected $conversationRepository;

    public function __construct(){
        $this->conversationRepository = new ConversationRepository();
    }

    /**
     * @param UserRepository $userRepository
     */
    public function setUserRepository(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ClientRepository $clientRepository
     */
    public function setClientRepository(ClientRepository $clientRepository) {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @param Client $client
     * @param string $rawMessage
     */
    public function processMessage(Client $client, $rawMessage){
        $this->client = $client;
        $message = json_decode($rawMessage);


        switch($message->type){
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

    public function conversation(\stdClass $message){
        $otherUser = $this->userRepository->findById($message->user);

        // save for history
        $conversation = $this->conversationRepository->findConversationByUserPair($this->client->getUser(), $otherUser);
        $conversation->appendContent($this->client->getUser(), $message->content);

        $tempConversation = new Conversation();
        $tempConversation->appendContent($this->client->getUser(), $message->content);

        // build message
        $conversationMessage = new ConversationMessage();
        $conversationMessage->setUser($otherUser);
        $conversationMessage->setContent($tempConversation->getContent());
        // send to client
        $this->client->getConnection()->send(json_encode($conversationMessage));

        if($otherUser->getClient()){
            $otherClient = $otherUser->getClient();
            $conversationMessage->setUser($this->client->getUser());
            $otherClient->getConnection()->send(json_encode($conversationMessage));
        }
    }

    /**
     * Handle a requestHistory message
     * @param \stdClass $message
     */
    public function requestHistory(\stdClass $message){
        // get other user
        $otherUser = $this->userRepository->findById($message->user);
        // get corresponding conversation
        $conversation = $this->conversationRepository->findConversationByUserPair($this->client->getUser(), $otherUser);

        // build message
        $conversationMessage = new ConversationMessage();
        $conversationMessage->setUser($otherUser);
        $conversationMessage->setContent($conversation->getContent());
        // send
        $this->client->getConnection()->send(json_encode($conversationMessage));
    }
}