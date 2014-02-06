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
     * @var array
     */
    protected $rooms;

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
     * @param array $rooms
     */
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;
    }

    /**
     * @return array
     */
    public function getRooms()
    {
        return $this->rooms;
    }


}