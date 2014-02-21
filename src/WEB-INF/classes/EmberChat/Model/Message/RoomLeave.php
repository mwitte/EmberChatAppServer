<?php

namespace EmberChat\Model\Message;

use EmberChat\Entities\Room;
use EmberChat\Entities\User;
use EmberChat\Sender\StandardSender;
use EmberChat\Model\SendMessage;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
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