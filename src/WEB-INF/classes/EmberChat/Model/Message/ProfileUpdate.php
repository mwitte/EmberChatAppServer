<?php

namespace EmberChat\Model\Message;

use EmberChat\Sender\StandardSender;
use EmberChat\Model\Client;
use EmberChat\Model\SendMessage;

class ProfileUpdate extends SendMessage
{

    /**
     * @param Client  $client
     * @param boolean $success
     */
    public function __construct(Client $client, $success)
    {
        parent::__construct();
        $this->success = $success;
        $standardSender = new StandardSender();
        $standardSender->sendMessageForClient($this, $client);
        unset($this);
    }

}