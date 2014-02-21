<?php

namespace EmberChat\Model\Message;

use EmberChat\Sender\StandardSender;
use EmberChat\Model\Client;
use EmberChat\Model\SendMessage;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
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