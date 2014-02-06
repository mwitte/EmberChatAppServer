<?php

namespace EmberChat\Model\Message;

use EmberChat\Model\Message;
use EmberChat\Entities\User;

class Settings extends Message
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