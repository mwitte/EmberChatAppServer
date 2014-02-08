<?php

namespace EmberChat\Entities;

use EmberChat\Model\Client;

/**
 * @Entity @Table(name="user")
 */
class User extends \EmberChat\EntitiesOriginal\User
{

    /**
     * defines visible properties
     *
     * @var array
     */
    protected $jsonProperties = array(
        'name',
        'id',
        'online'
    );

    /**
     * Is this user online?
     *
     * @var bool
     */
    protected $online = false;

    /**
     * Current client for this user, in future there will probably the possibility for multiple
     * clients for each user
     *
     * @var Client
     */
    private $client = null;

    /**
     * Rooms the user currently listens
     *
     * @var array
     */
    protected $rooms = array();

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        $this->online = true;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Join a room
     *
     * @param Room $room
     *
     * @return bool
     */
    public function joinRoom(Room $room)
    {
        if ($this->getIndexForRoom($room) === null) {
            $this->rooms[] = $room;
            return true;
        }
        return false;
    }

    /**
     * Leave a room
     *
     * @param Room $room
     *
     * @return bool
     */
    public function leaveRoom(Room $room)
    {
        $index = $this->getIndexForRoom($room);
        if ($index === null) {
            return false;
        }
        unset($this->rooms[$index]);
        // normalize array
        $this->rooms = array_values($this->rooms);
        return true;
    }

    /**
     * Gets called when the client leaves
     */
    public function unsetClient()
    {
        /** @var $room Room */
        foreach ($this->rooms as $key => $room) {
            $room->removeUser($this);
            unset($this->rooms[$key]);
        }
        $this->client = null;
        $this->online = false;
    }

    /**
     * Gets the index for the given room
     *
     * @param Room $room
     *
     * @return int|null
     */
    protected function getIndexForRoom(Room $room)
    {
        foreach ($this->rooms as $key => $roomIterator) {
            if ($room->getId() === $roomIterator->getId()) {
                return $key;
            }
        }
        return null;
    }
}