<?php

namespace EmberChat\Model\Message;

use EmberChat\Entities\Room;
use EmberChat\Sender\StandardSender;
use EmberChat\Model\Conversation;
use EmberChat\Model\SendMessage;
use EmberChat\Entities\User;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class RoomConversation extends SendMessage
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
     * Creates and sends the message
     *
     * @param User           $sender
     * @param Room           $room
     * @param                $content
     */
    public function __construct(User $sender, Room $room, $content)
    {
        parent::__construct();

        $this->room = $room;
        $this->content = $this->buildMessageContent($sender, $content);

        $standardSender = new StandardSender();
        $standardSender->broadCastMessageForUsers($this, $room->getUsers());
        unset($this);
    }

    /**
     * Builds the content for this message
     *
     * @param User $user
     * @param      $content
     *
     * @return array
     */
    protected function buildMessageContent(User $user, $content)
    {
        $tempConversation = new Conversation();
        $tempConversation->appendContent($user, $content);
        return $tempConversation->getContent();
    }
}