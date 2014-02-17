<?php

namespace EmberChat\Model\Message;

use EmberChat\Handler\MessageSender;
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
        $messageSender = new MessageSender();
        $messageSender->sendMessageForClient($this, $client);
        unset($this);
    }

}