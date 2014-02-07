<?php

namespace EmberChat\Model\Message;

use EmberChat\Model\SendMessage;
use EmberChat\Entities\User;

class ConversationUser extends SendMessage
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var array
     */
    protected $content;

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
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