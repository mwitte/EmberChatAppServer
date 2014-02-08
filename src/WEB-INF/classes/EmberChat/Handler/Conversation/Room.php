<?php

namespace EmberChat\Handler\Conversation;

use EmberChat\Entities\User;
use EmberChat\Handler\MessageHandler;
use EmberChat\Model\Client;
use EmberChat\Model\Message\ConversationRoom as ConversationRoomMessage;
use EmberChat\Model\Message\ConversationRoom;
use EmberChat\Model\Message\ConversationUser as ConversationUserMessage;
use EmberChat\Model\Message\RoomList;
use EmberChat\Model\Message\UserList;
use EmberChat\Model\Conversation;
use EmberChat\Repository\ConversationRepository;
use EmberChat\Repository\RoomRepository;
use EmberChat\Service\ServiceLocator;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\Version\RFC6455\Message;

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
    public function processMessage(Client $client, \stdClass $message)
    {

        error_log('roomConversation needs to get implemented');
        $room = $this->serviceLocator->getRoomRepository()->findById($message->room);

        $this->broadCastMessage($message, $room, $client);
        return;

        $conversationRoomMessage = new ConversationRoomMessage();
        $conversationRoomMessage->setRoom($room);
        $conversationRoomMessage->setContent(array(array('user' => 'Server', 'content' => 'No implemented yet!')));
        $client->getConnection()->send(json_encode($conversationRoomMessage));
    }


    protected function broadCastMessage(\stdClass $message, \EmberChat\Entities\Room $room, Client $client)
    {
        $roomMessage = new ConversationRoomMessage();
        $roomMessage->setContent(array('user' => $client->getUser()->getName(), 'content'=> $message->content));
        $roomMessage->setRoom($room);
        $messageHandler = new MessageHandler($this->serviceLocator);
        $clients = array();
        foreach($room->getUsers() as $user){
            if($user->getClient()){
                $clients[] = $user->getClient();
            }
        }
        $messageHandler->broadCastMessage($roomMessage, $clients);
    }

    public function joinUser(\EmberChat\Entities\Room $room, User $user)
    {

    }

}