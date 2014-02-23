<?php

namespace EmberChat\Model\Message;

use EmberChat\Sender\StandardSender;
use EmberChat\Model\Client;
use EmberChat\Model\SendMessage;
use EmberChat\Entities\User;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
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
            $this->length = $message->length;
        }

        // append encryptedKey if given
        if ($message->encryptedKey) {
            $this->encryptedKey = $message->encryptedKey;
        }

        // disables the encryption for the receiver
        if ($message->disable) {
            $this->disable = $message->disable;
        }

        if ($message->acknowledge) {
            $this->acknowledge = $message->acknowledge;
        }
        $this->user = $requester;
        $standardSender = new StandardSender();
        $standardSender->sendMessageForClient($this, $receiver);
        unset($this);
    }

}