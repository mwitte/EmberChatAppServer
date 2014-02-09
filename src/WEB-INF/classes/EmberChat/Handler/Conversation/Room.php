<?php

namespace EmberChat\Handler\Conversation;

use EmberChat\Entities\User as UserEntity;
use EmberChat\Model\Client;
use EmberChat\Model\Message\RoomConversation;
use EmberChat\Model\Message\RoomJoin;
use EmberChat\Model\Message\RoomLeave;
use Ratchet\ConnectionInterface;

/**
 * Class Room
 *
 * @package EmberChat\Handler
 */
class Room extends \EmberChat\Handler\Conversation
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * Process a conversation message
     *
     * @param Client    $client
     * @param \stdClass $message
     */
    public function newMessage(Client $client, \stdClass $message)
    {
        $room = $this->serviceLocator->getRoomRepository()->findById($message->room);
        if (!$room) {
            error_log('WARING: Could not find room ' . $message->room);
        }
        new RoomConversation($client->getUser(), $room, $message->content);
    }

    /**
     * Client joins room
     *
     * @param Client    $client
     * @param \stdClass $message
     */
    public function joinUser(Client $client, \stdClass $message)
    {
        $room = $this->serviceLocator->getRoomRepository()->findById($message->room);

        if (!$room) {
            error_log('WARING: Room with id ' . $message->room . ' not found!');
            return;
        }
        if (!$room->addUser($client->getUser())) {
            error_log('WARING: Could not add user ' . $client->getUser()->getName() . ' to room ' . $room->getName());
            return;
        }
        if (!$client->getUser()->joinRoom($room)) {
            error_log('WARING: Could not add room ' . $room->getName() . ' to user ' . $client->getUser()->getName());
            $room->removeUser($client->getUser());
            return;
        }
    }

    /**
     * Client leaves room
     *
     * @param Client    $client
     * @param \stdClass $message
     */
    public function leaveUser(Client $client, \stdClass $message)
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