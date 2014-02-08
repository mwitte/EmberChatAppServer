<?php

namespace EmberChat\Entities;

use EmberChat\Handler\Conversation;

/**
 * @Entity @Table(name="room")
 */
class Room extends \EmberChat\EntitiesOriginal\Room
{

    protected $jsonProperties = array(
        'name',
        'id',
        'users'
    );

    /**
     * @var array
     */
    protected $users = array();

    /**
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     */
    public function addUser(User $user)
    {
        $this->users[$user->getId()] = $user;
    }

    /**
     * @param User $user
     */
    public function removeUser(User $user)
    {
        unset($this->users[$user->getId()]);
        if($user->isInRoom($this)){
            $user->leaveRoom($this);
        }
    }

    public function containsUser(User $user)
    {
        return isset($this->users[$user->getId()]);
    }

}