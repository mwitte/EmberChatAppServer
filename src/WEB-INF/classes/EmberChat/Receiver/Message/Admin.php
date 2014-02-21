<?php

namespace EmberChat\Receiver\Message;

use EmberChat\Entities\User;
use EmberChat\Model\Client;
use EmberChat\Model\Message\AdminAction;
use EmberChat\Receiver\AbstractReceiver;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class Admin extends AbstractReceiver
{
    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        if (!$client->getUser()->getAdmin()) {
            //@TODO send a error message
            throw new \Exception("Non admin user tried admin action");
        }
    }

}