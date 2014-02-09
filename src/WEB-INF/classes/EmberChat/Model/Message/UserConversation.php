<?php

namespace EmberChat\Model\Message;

use EmberChat\Handler\MessageSender;
use EmberChat\Model\Conversation;
use EmberChat\Model\SendMessage;
use EmberChat\Entities\User;

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
     */
    public function __construct(User $sender, User $receiver, $content)
    {
        parent::__construct();

        $this->content = $this->buildMessageContent($sender, $content);
        $this->user = $receiver;
        $messageSender = new MessageSender();
        $messageSender->sendMessageForUser($this, $sender);

        // send also to receiver if got connected client
        if ($receiver->isOnline()) {
            $this->user = $sender;
            $messageSender->sendMessageForUser($this, $receiver);
        }
        unset($this);
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