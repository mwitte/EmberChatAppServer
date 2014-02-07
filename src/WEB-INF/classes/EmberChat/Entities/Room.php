<?php

namespace EmberChat\Entities;

/**
 * @Entity @Table(name="room")
 */
class Room extends \EmberChat\EntitiesOriginal\Room implements \JsonSerializable
{

    /**
     * @var array
     */
    protected $users;

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

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}