<?php

namespace EmberChat\Model;

use TechDivision\Stream\Client;

interface IReceiveMessage
{
    public function process(\stdClass $message, Client $client);
}