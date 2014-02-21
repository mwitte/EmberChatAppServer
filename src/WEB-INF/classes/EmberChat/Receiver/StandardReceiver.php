<?php

namespace EmberChat\Receiver;

use EmberChat\Model\Client;
use EmberChat\Model\Message\RoomList;
use EmberChat\Model\Message\UserList;
use EmberChat\Receiver\Message\Authentication;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class StandardReceiver extends AbstractReceiver
{

    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        try{
            if ($client->getUser()) {
                $this->authenticatedClientProcessing($client, $message);
            } else {
                $authentication = new Authentication($this->serviceLocator);
                $authentication->processMessage($client, $message);
            }
        }catch (\Exception $e){
            error_log(var_export($e, true));
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
        $className = "\\EmberChat\\Receiver\\Message\\". $message->type;
        if(class_exists($className)){
            /** @var ReceiverInterface $receiver */
            $receiver = new $className($this->serviceLocator);
            $receiver->processMessage($client, $message);
        }else{
            error_log("Tried to instantiate class: " . $className);
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