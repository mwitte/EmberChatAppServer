<?php

namespace EmberChat\Receiver\Message\Room;


use EmberChat\Model\Client;
use EmberChat\Receiver\AbstractReceiver;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class Leave extends AbstractReceiver
{

    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        $room = $this->serviceLocator->getRoomRepository()->findById($message->room);

        if (!$room) {
            error_log('WARING: Room with id ' . $message->room . ' not found!');
            return;
        }
        if (!$room->removeUser($client->getUser())) {
            error_log(
                'WARING: Could not remove user ' . $client->getUser()->getName() . ' of room ' . $room->getName()
            );
        }
        if (!$client->getUser()->leaveRoom($room)) {
            error_log(
                'WARING: Could not remove room ' . $room->getName() . ' of user ' . $client->getUser()->getName()
            );
        }
    }
}