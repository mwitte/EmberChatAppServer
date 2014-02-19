<?php

namespace EmberChat\Receiver;

use EmberChat\Model\Client;

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