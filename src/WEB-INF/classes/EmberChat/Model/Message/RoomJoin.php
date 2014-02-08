<?php

namespace EmberChat\Model\Message;

use EmberChat\Entities\Room;
use EmberChat\Entities\User;
use EmberChat\Handler\MessageSender;
use EmberChat\Model\SendMessage;

class RoomJoin extends SendMessage
{

    /**
     * @var Room
     */
    protected $room;

    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user, Room $room)
    {
        parent::__construct();
        $this->user = $user;
        $this->room = $room;
        $messageSender = new MessageSender();
        $messageSender->broadCastMessageForUsers($this, $room->getUsers());
    }
}