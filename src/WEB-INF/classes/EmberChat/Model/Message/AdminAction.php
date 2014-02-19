<?php

namespace EmberChat\Model\Message;

use EmberChat\Sender\StandardSender;
use EmberChat\Model\Client;
use EmberChat\Model\SendMessage;

class AdminAction extends SendMessage
{

    /**
     * @param Client  $client
     * @param boolean $success determines the success
     * @param string  $action  specific action
     * @param string  $message Message for errors
     */
    public function __construct(Client $client, $success, $action, $message = '')
    {
        parent::__construct();
        $this->success = $success;
        $this->action = $action;
        $this->message = $message;
        $standardSender = new StandardSender();
        $standardSender->sendMessageForClient($this, $client);
        unset($this);
    }

}