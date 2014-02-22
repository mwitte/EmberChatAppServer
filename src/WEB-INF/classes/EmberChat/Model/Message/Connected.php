<?php

namespace EmberChat\Model\Message;

use EmberChat\Sender\StandardSender;
use EmberChat\Model\Client;
use EmberChat\Model\SendMessage;
use EmberChat\Service\ServiceLocator;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class Connected extends SendMessage
{

    /**
     * @param Client         $client
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(Client $client, ServiceLocator $serviceLocator)
    {
        parent::__construct();

        $this->version = $serviceLocator->getServerVersion();

        $standardSender = new StandardSender();
        $standardSender->sendMessageForClient($this, $client);
        unset($this);
    }

}