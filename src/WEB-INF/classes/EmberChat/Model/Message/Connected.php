<?php

namespace EmberChat\Model\Message;

use EmberChat\Sender\StandardSender;
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
        $standardSender = new StandardSender();
        $standardSender->sendMessageForClient($this, $client);
        unset($this);
    }

}