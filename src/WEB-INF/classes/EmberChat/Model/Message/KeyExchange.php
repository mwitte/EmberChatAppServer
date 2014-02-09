<?php

namespace EmberChat\Model\Message;

use EmberChat\Handler\MessageSender;
use EmberChat\Model\Client;
use EmberChat\Model\SendMessage;
use EmberChat\Entities\User;

class KeyExchange extends SendMessage
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @param Client    $requester
     * @param Client    $receiver
     * @param \stdClass $message
     */
    public function __construct(Client $requester, Client $receiver, \stdClass $message)
    {
        parent::__construct();

        if($message->publicKey){
            $this->publicKey = $message->publicKey;
        }
        if($message->encryptedKey){
            $this->encryptedKey = $message->encryptedKey;
        }
        $this->user = $requester->getUser();
        $messageSender = new MessageSender();
        $messageSender->sendMessageForClient($this, $receiver);
        unset($this);
    }

}