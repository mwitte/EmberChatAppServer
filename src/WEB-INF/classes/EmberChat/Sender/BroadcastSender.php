<?php

namespace EmberChat\Sender;

use EmberChat\Entities\User;
use EmberChat\Model\Client;
use EmberChat\Model\Message\RoomList;
use EmberChat\Model\Message\UserList;
use EmberChat\Service\ServiceLocator;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class BroadcastSender
{

    /**
     * @var ServiceLocator
     */
    protected $serviceLocator;

    /**
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(ServiceLocator $serviceLocator)
    {

        $this->serviceLocator = $serviceLocator;
    }

    public function userList()
    {
        $clients = $this->serviceLocator->getClientRepository()->findAll();
        $this->serviceLocator->getUserRepository()->resortUsers();
        foreach ($clients as $connection) {
            /* @var $client Client */
            $client = $clients[$connection];
            if ($client->getUser()) {
                new UserList($client, $this->serviceLocator);
            }
        }
    }

    /**
     * Send the current room list to all clients
     *
     * @param Client $client
     *
     * @return void
     */
    public function roomList()
    {
        $clients = $this->serviceLocator->getClientRepository()->findAll();
        foreach($clients as $connection){
            /* @var $client Client */
            $client = $clients[$connection];
            if ($client->getUser()) {
                new RoomList($client, $this->serviceLocator);
            }
        }
    }
}