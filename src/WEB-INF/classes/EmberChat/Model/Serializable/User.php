<?php

namespace EmberChat\Model\Serializable;

use EmberChat\Model\Client;

class User extends AbstractSerializable {

    protected $id;
    protected $name;
    protected $online = false;
    private $client = null;

    public function __construct($id, $name) {
        $this->id  = $id;
        $this->name = $name;


    }

    public function unsetClient(){
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

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


}