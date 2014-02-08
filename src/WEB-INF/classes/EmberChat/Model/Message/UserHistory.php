<?php

namespace EmberChat\Model\Message;


use EmberChat\Entities\User;
use EmberChat\Handler\MessageSender;
use EmberChat\Model\Client;
use EmberChat\Model\SendMessage;
use EmberChat\Service\ServiceLocator;

class UserHistory extends SendMessage
{

    protected $type = 'ConversationUser';

    /**
     * @var User
     */
    protected $user;

    /**
     * @var array
     */
    protected $content;

    public function __construct(Client $client, User $receiver, ServiceLocator $serviceLocator)
    {
        //parent::__construct();
        $this->user = $receiver;
        // get corresponding conversation
        $conversation = $serviceLocator->getConversationRepository()->findConversationByUserPair(
            $client->getUser(),
            $receiver
        );

        // @TODO send not the hole content! only the last ~50 entries or something
        $this->content = $conversation->getContent();
        $messageSender = new MessageSender();
        $messageSender->sendMessageForClient($this, $client);
        unset($this);
    }
}