<?php

namespace EmberChat\Receiver;

use EmberChat\Model\Client;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
interface ReceiverInterface
{
    /**
     * @param Client    $client
     * @param \stdClass $message
     *
     * @return void
     */
    public function processMessage(Client $client, \stdClass $message);
}