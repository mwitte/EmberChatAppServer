<?php

namespace EmberChat\Model\Message;

use EmberChat\Entities\Room;
use EmberChat\Entities\User;
use EmberChat\Sender\StandardSender;
use EmberChat\Model\SendMessage;

class RoomLeave extends SendMessage
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
        $standardSender = new StandardSender();
        $standardSender->broadCastMessageForUsers($this, $room->getUsers());
        unset($this);
    }
}