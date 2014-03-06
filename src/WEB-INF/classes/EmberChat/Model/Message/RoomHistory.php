<?php

namespace EmberChat\Model\Message;


use EmberChat\Entities\Room;
use EmberChat\Sender\StandardSender;
use EmberChat\Model\Client;
use EmberChat\Model\SendMessage;
use EmberChat\Service\ServiceLocator;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class RoomHistory extends SendMessage
{

    /**
     * Overwrite type to get on client side same purpose like regular conversation
     *
     * @var string
     */
    protected $type = 'RoomConversation';

    /**
     * @var Room
     */
    protected $room;

    /**
     * @var array
     */
    protected $content;

    /**
     * @var bool
     */
    protected $history = true;

    /**
     * @param Client         $client
     * @param Room           $room
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(Client $client, Room $room, ServiceLocator $serviceLocator)
    {
        //parent::__construct();
        $this->room = $room;
        // get corresponding conversation
        $conversation = $serviceLocator->getConversationRepository()->findConversationByRoom(
            $room
        );

        // @TODO send not the hole content! only the last ~50 entries or something
        $this->content = $conversation->getContent();
        $standardSender = new StandardSender();
        $standardSender->sendMessageForClient($this, $client);
        unset($this);
    }
}