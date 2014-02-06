<?php

namespace EmberChat\Entities;

use EmberChat\Model\Client;

/**
 * @Entity @Table(name="user")
 */
class User extends \EmberChat\EntitiesOriginal\User implements \JsonSerializable
{

    protected $online = false;
    private $client = null;

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

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}