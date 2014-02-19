<?php

namespace EmberChat\Model\Message;

use EmberChat\Sender\StandardSender;
use EmberChat\Model\Client;
use EmberChat\Model\SendMessage;
use EmberChat\Service\ServiceLocator;

class UserList extends SendMessage
{

    /**
     * @var array
     */
    protected $content;

    /**
     * @param Client         $client
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(Client $client, ServiceLocator $serviceLocator)
    {
        parent::__construct();
        $otherUsers = $serviceLocator->getUserRepository()->findAllWithout($client->getUser());
        $this->content = $otherUsers;
        $standardSender = new StandardSender();
        $standardSender->sendMessageForClient($this, $client);
        unset($this);
    }
}