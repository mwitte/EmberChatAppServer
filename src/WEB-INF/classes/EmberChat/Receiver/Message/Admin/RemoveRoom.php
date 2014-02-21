<?php

namespace EmberChat\Receiver\Message\Admin;

use EmberChat\Model\Client;
use EmberChat\Receiver\Message\Admin;
use EmberChat\Sender\BroadcastSender;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class RemoveRoom extends Admin
{
    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        parent::processMessage($client, $message);
        if (!$message->room) {
            //@TODO
            error_log('RemoveRoom message was wrong: ');
            error_log(var_export($message, true));
            return;
        }
        $room = $this->serviceLocator->getRoomRepository()->findById($message->room->id);
        if(!$room){
            error_log('RemoveRoom message: room not found ' . $message->room->id);
        }
        if($this->serviceLocator->getRoomRepository()->remove($room)){
            $broadcastSender = new BroadcastSender($this->serviceLocator);
            $broadcastSender->roomList();
        }
    }
}