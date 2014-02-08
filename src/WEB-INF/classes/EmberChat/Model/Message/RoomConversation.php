<?php

namespace EmberChat\Model\Message;

use EmberChat\Entities\Room;
use EmberChat\Model\SendMessage;

class ConversationRoom extends SendMessage
{

    /**
     * @var Room
     */
    protected $room;

    /**
     * @var array
     */
    protected $content;

    /**
     * @param Room $room
     */
    public function setRoom(Room $room)
    {
        $this->room = $room;
    }

    /**
     * @return Room
     */
    public function getRoom()
    {
        return $this->room;
    }


    /**
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }
}