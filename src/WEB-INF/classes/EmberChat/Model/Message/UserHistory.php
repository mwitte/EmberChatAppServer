<?php

namespace EmberChat\Model\Message;


use EmberChat\Entities\User;
use EmberChat\Sender\StandardSender;
use EmberChat\Model\Client;
use EmberChat\Model\SendMessage;
use EmberChat\Service\ServiceLocator;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class UserHistory extends SendMessage
{

    /**
     * Overwrite type to get on client side same purpose like regular conversation
     *
     * @var string
     */
    protected $type = 'UserConversation';

    /**
     * @var User
     */
    protected $user;

    /**
     * @var array
     */
    protected $content;

    /**
     * @param Client         $client
     * @param User           $receiver
     * @param ServiceLocator $serviceLocator
     */
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
        $standardSender = new StandardSender();
        $standardSender->sendMessageForClient($this, $client);
        unset($this);
    }
}