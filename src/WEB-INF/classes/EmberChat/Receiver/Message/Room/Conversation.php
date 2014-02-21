<?php

namespace EmberChat\Receiver\Message\Room;


use EmberChat\Model\Client;
use EmberChat\Model\Message\RoomConversation;
use EmberChat\Receiver\AbstractReceiver;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class Conversation extends AbstractReceiver
{


    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        $room = $this->serviceLocator->getRoomRepository()->findById($message->room);
        if (!$room) {
            error_log('WARING: Could not find room ' . $message->room);
        }
        new RoomConversation($client->getUser(), $room, $message->content);
    }
}