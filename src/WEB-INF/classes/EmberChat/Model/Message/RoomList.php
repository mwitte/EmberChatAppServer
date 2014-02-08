<?php

namespace EmberChat\Model\Message;

use EmberChat\Handler\MessageSender;
use EmberChat\Model\Client;
use EmberChat\Model\SendMessage;
use EmberChat\Service\ServiceLocator;

class RoomList extends SendMessage
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
        $this->content = $serviceLocator->getRoomRepository()->findAll();
        $messageSender = new MessageSender();
        $messageSender->sendMessageForClient($this, $client);
        unset($this);
    }
}