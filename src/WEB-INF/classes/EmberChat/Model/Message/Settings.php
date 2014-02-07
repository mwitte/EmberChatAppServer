<?php

namespace EmberChat\Model\Message;

use EmberChat\Model\SendMessage;
use EmberChat\Entities\User;

class Settings extends SendMessage
{

    /**
     * @var User
     */
    protected $user;

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


}