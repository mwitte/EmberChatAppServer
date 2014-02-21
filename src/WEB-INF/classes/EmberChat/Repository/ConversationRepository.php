<?php

namespace EmberChat\Repository;

use EmberChat\Entities\User;
use EmberChat\Model\Conversation;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class ConversationRepository
{

    /**
     * @var array
     */
    protected $conversations;

    public function __construct()
    {
        $this->conversations = array();
    }

    /**
     * @param User $userOne
     * @param User $userTwo
     *
     * @return Conversation
     */
    public function findConversationByUserPair(User $userOne, User $userTwo)
    {
        $key = $this->getKeyByUserPair($userOne, $userTwo);
        if (isset($this->conversations[$key])) {
        } else {
            $conversation = new Conversation();
            $this->conversations[$key] = $conversation;
        }
        return $this->conversations[$key];
    }

    /**
     * Generates a key by two given users which is always the same, the order of users does not care
     *
     * @param User $userOne
     * @param User $userTwo
     *
     * @return string
     */
    protected function getKeyByUserPair(User $userOne, User $userTwo)
    {
        if (strcasecmp($userOne->getId(), $userTwo->getId()) < 0) {
            $key = sha1($userOne->getId() . $userTwo->getId());
        } else {
            $key = sha1($userTwo->getId() . $userOne->getId());
        }
        return $key;
    }
}