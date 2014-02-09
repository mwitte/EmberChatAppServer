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
     * @param User      $requester
     * @param Client    $receiver
     * @param \stdClass $message
     */
    public function __construct(User $requester, Client $receiver, \stdClass $message)
    {
        parent::__construct();

        // append publicKey if given
        if ($message->publicKey) {
            $this->publicKey = $message->publicKey;
        }

        // append encryptedKey if given
        if ($message->encryptedKey) {
            $this->encryptedKey = $message->encryptedKey;
        }

        // disables the encryption for the receiver
        if ($message->disable) {
            $this->disable = $message->disable;
        }
        $this->user = $requester;
        $messageSender = new MessageSender();
        $messageSender->sendMessageForClient($this, $receiver);
        unset($this);
    }

}