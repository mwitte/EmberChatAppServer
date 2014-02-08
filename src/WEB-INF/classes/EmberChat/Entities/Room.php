<?php

namespace EmberChat\Entities;

use EmberChat\Handler\Conversation;

/**
 * @Entity @Table(name="room")
 */
class Room extends \EmberChat\EntitiesOriginal\Room
{

    /**
     * defines visible properties
     *
     * @var array
     */
    protected $jsonProperties = array(
        'name',
        'id'
    );

    /**
     * @var \EmberChat\Model\Conversation
     */
    protected $conversation;

    /**
     * @var array
     */
    protected $users = array();


    /**
     * A room got always a conversation
     */
    public function __construct()
    {
        $this->conversation = new \EmberChat\Model\Conversation();
    }

    /**
     * @return \EmberChat\Model\Conversation
     */
    public function getConversation()
    {
        return $this->conversation;
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Adds a user in this room
     *
     * @param User $user
     */
    public function addUser(User $user)
    {
        if ($this->getIndexForUser($user) === null) {
            $this->users[] = $user;
            return true;
        }
        return false;
    }

    /**
     * Removes a user from this room
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $index = $this->getIndexForUser($user);
        if ($index === null) {
            return false;
        }
        unset($this->users[$index]);
        // normalize array
        $this->users = array_values($this->users);
        return true;
    }

    /**
     * Get the index for the given user
     *
     * @param User $user
     *
     * @return int|null
     */
    protected function getIndexForUser(User $user)
    {
        foreach ($this->users as $key => $userIterator) {
            if ($user->getId() === $userIterator->getId()) {
                return $key;
            }
        }
        return null;
    }

}