<?php

namespace EmberChat\Model\Message;

use EmberChat\Handler\MessageSender;
use EmberChat\Model\Client;
use EmberChat\Model\Conversation;
use EmberChat\Model\SendMessage;
use EmberChat\Entities\User;
use EmberChat\Service\ServiceLocator;

class UserConversation extends SendMessage
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var array
     */
    protected $content;


    /**
     * Creates and sends the message
     *
     * @param User           $sender
     * @param User           $receiver
     * @param                $content
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(User $sender, User $receiver, $content, ServiceLocator $serviceLocator)
    {
        parent::__construct();
        $this->appendConversation($sender, $receiver, $content, $serviceLocator);

        $this->content = $this->buildMessageContent($sender, $content);
        $this->user = $receiver;
        $messageSender = new MessageSender();
        $messageSender->sendMessageForUser($this, $sender);

        // send also to receiver if got connected client
        if($receiver->getClient()){
            $this->user = $sender;
            $messageSender->sendMessageForUser($this, $receiver);
        }
        unset($this);
    }

    /**
     * Append the existing conversation object with new content
     *
     * @param User           $sender
     * @param User           $receiver
     * @param                $content
     * @param ServiceLocator $serviceLocator
     */
    protected function appendConversation(User $sender, User $receiver, $content, ServiceLocator $serviceLocator){
        $conversation = $serviceLocator->getConversationRepository()->findConversationByUserPair(
            $sender,
            $receiver
        );
        $conversation->appendContent($sender, $content);
    }

    /**
     * Builds the content for this message
     *
     * @param User $user
     * @param      $content
     *
     * @return array
     */
    protected function buildMessageContent(User $user, $content)
    {
        $tempConversation = new Conversation();
        $tempConversation->appendContent($user, $content);
        return $tempConversation->getContent();
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }
}