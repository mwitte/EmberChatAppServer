<?php

namespace EmberChat\Receiver\Message\Room;


use EmberChat\Entities\Room;
use EmberChat\Model\Client;
use EmberChat\Model\Message\AdminAction;
use EmberChat\Receiver\AbstractReceiver;
use EmberChat\Sender\BroadcastSender;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class Create extends AbstractReceiver
{

    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        if(!$message->room){
            new AdminAction($client, false, 'CreateRoom', 'Incoming message was not valid');
            return;
        }
        if(!$message->room->name || strlen((string)$message->room->name) <= 0){
            new AdminAction($client, false, 'CreateRoom', 'Name is too short');
            return;
        }
        $room = new Room();
        $room->setName($message->room->name);
        if(!$this->serviceLocator->getRoomRepository()->createNew($room)){
            new AdminAction($client, false, 'CreateRoom', 'There is already a room with this name');
            return;
        }
        new AdminAction($client, true, 'CreateRoom');
        $broadCastSender = new BroadcastSender($this->serviceLocator);
        $broadCastSender->roomList();
    }
}