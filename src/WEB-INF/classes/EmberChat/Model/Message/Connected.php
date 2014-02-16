<?php

namespace EmberChat\Model\Message;

use EmberChat\Handler\MessageSender;
use EmberChat\Model\Client;
use EmberChat\Model\SendMessage;

class Connected extends SendMessage
{

    /**
     * @param Client  $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();
        $messageSender = new MessageSender();
        $messageSender->sendMessageForClient($this, $client);
        unset($this);
    }

}