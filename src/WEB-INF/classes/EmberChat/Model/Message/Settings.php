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
     * @var string
     */
    protected $token;

    /**
     * @param Client  $client
     * @param boolean $sendToken
     */
    public function __construct(Client $client, $sendToken)
    {
        parent::__construct();
        $this->user = $client->getUser();
        if ($sendToken) {
            $this->token = $client->getUser()->getToken();
        }
        $this->admin = $client->getUser()->getAdmin();
        $messageSender = new MessageSender();
        $messageSender->sendMessageForClient($this, $client);
        unset($this);
    }

}