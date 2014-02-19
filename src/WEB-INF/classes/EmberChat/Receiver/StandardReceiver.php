<?php

namespace EmberChat\Receiver;

use EmberChat\Model\Client;
use EmberChat\Model\Message\RoomList;
use EmberChat\Model\Message\UserList;
use Ratchet\ConnectionInterface;

/**
 * Class MessageHandler
 *
 *
 * @package EmberChat\Handler
 */
class StandardReceiver extends AbstractReceiver
{

    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        if ($client->getUser()) {
            $this->authenticatedClientProcessing($client, $message);
        } else {
            $authentication = new AuthenticationReceiver($this->serviceLocator);
            $authentication->processMessage($client, $message);
        }
    }

    /**
     * Actions for authenticated clients
     *
     * @param Client    $client
     * @param \stdClass $message
     */
    public function authenticatedClientProcessing(Client $client, \stdClass $message)
    {
        switch ($message->type) {
            case 'Room':
                $roomReceiver = new RoomReceiver($this->serviceLocator);
                $roomReceiver->processMessage($client, $message);
                break;
            case 'User':
                $userReceiver = new UserReceiver($this->serviceLocator);
                $userReceiver->processMessage($client, $message);
                break;
            case 'Profile':
                $profileHandler = new ProfileReceiver($this->serviceLocator);
                $profileHandler->processMessage($client, $message);
                break;
            case 'Admin':
                $adminHandler = new AdminReceiver($this->serviceLocator);
                $adminHandler->processMessage($client, $message);
                break;
            default:
                error_log('Unkown message type: ');
                error_log(var_export($message, true));
        }
    }


    /**
     * Broadcast the current user list to all clients
     *
     * @return void
     */
    public function broadCastUserList()
    {
        // @TODO refactor only for authed clients/users
        $clients = $this->serviceLocator->getClientRepository()->findAll();
        $this->serviceLocator->getUserRepository()->resortUsers();
        /* @var $client Client */
        foreach ($clients as $connection) {
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
    public function sendRoomList(Client $client)
    {
        new RoomList($client, $this->serviceLocator);
    }

}