<?php

namespace EmberChat\Receiver\Message\Room;


use EmberChat\Model\Client;
use EmberChat\Receiver\AbstractReceiver;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class Join extends AbstractReceiver
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
        if (!$room->addUser($client->getUser())) {
            error_log('WARING: Could not add user ' . $client->getUser()->getAuth() . ' to room ' . $room->getName());
            return;
        }
        if (!$client->getUser()->joinRoom($room)) {
            error_log('WARING: Could not add room ' . $room->getName() . ' to user ' . $client->getUser()->getAuth());
            $room->removeUser($client->getUser());
            return;
        }
    }
}