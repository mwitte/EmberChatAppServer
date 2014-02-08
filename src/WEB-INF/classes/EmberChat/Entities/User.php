<?php

namespace EmberChat\Entities;

use EmberChat\Model\Client;

/**
 * @Entity @Table(name="user")
 */
class User extends \EmberChat\EntitiesOriginal\User
{

    protected $jsonProperties = array(
        'name',
        'id',
        'online'
    );

    protected $online = false;
    private $client = null;

    /**
     * @var array
     */
    protected $rooms;

    public function joinRoom(Room $room)
    {
        $this->rooms[$room->getId()] = $room;
    }

    public function leaveRoom(Room $room)
    {
        unset($this->rooms[$room->getId()]);
        if($room->containsUser($this)){
            $room->removeUser($this);
        }
    }

    public function isInRoom(Room $room)
    {
        return isset($this->rooms[$room->getId()]);
    }

    public function unsetClient()
    {
        $this->client = null;
        $this->online = false;
    }

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


}