<?php

namespace EmberChat\Model\Message;

use EmberChat\Handler\MessageSender;
use EmberChat\Model\Client;
use EmberChat\Model\SendMessage;
use EmberChat\Entities\User;

class Settings extends SendMessage
{

    /**
     * @var User
     */
    protected $user;


    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();
        $this->user = $client->getUser();
        $messageSender = new MessageSender();
        $messageSender->sendMessageForClient($this, $client);
        unset($this);
    }

}