<?php

namespace EmberChat\Entities;

use EmberChat\Model\Client;

/**
 * @Entity @Table(name="user")
 */
class User extends \EmberChat\Entities\Original\User
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
    private $clients = array();

    /**
     * Rooms the user currently listens
     *
     * @var array
     */
    protected $rooms = array();

    public function isOnline(){
        return $this->online;
    }

    /**
     * @param Client $client
     */
    public function addClient(Client $client)
    {
        $this->clients[] = $client;
        $this->online = true;
    }

    /**
     * @return Client
     */
    public function getClients()
    {
        return $this->clients;
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
    public function removeClient(Client $client)
    {
        foreach ($this->clients as $index => $cmpClient) {
            if ($client == $cmpClient) {
                unset($this->clients[$index]);
            }
            // normalize clients array
            $this->clients = array_values($this->clients);
        }
        if (count($this->clients) <= 0) {
            $this->online = false;
            /** @var $room Room */
            foreach ($this->rooms as $key => $room) {
                $room->removeUser($this);
                unset($this->rooms[$key]);
            }
        }
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

    /**
     * Check if password is valid
     *
     * @param string $password
     *
     * @return bool
     */
    public function auth($password)
    {
        // @TODO Should get a salt
        // password should come sha256 hashed from db and from client so there is no plain text here
        if ($this->password === $password) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        // @TODO Should get a salt
        return hash('sha256', $this->auth . $this->password);
    }
}