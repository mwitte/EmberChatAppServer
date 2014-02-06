<?php

namespace EmberChat\Repository;


use EmberChat\Model\Client;
use Ratchet\ConnectionInterface;

class ClientRepository
{

    /**
     * The dummy users
     *
     * @var \SplObjectStorage
     */
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    /**
     * @param Client $client
     */
    public function addClient(Client $client, ConnectionInterface $connection)
    {
        $this->clients->attach($connection, $client);
    }

    public function findAll()
    {
        return $this->clients;
    }

    /**
     * @param ConnectionInterface $connection
     *
     * @return void
     */
    public function removeClient(ConnectionInterface $connection)
    {
        if ($this->clients->contains($connection)) {
            $client = $this->clients[$connection];
            // @TODO BUG?
            $client->myDestruct();
            $this->clients->detach($connection);
            error_log('detached');
        }
    }

    /**
     * @param ConnectionInterface $connection
     *
     * @return Client|null
     */
    public function findClientByConnection(ConnectionInterface $connection)
    {
        if ($this->clients->contains($connection)) {
            return $this->clients[$connection];
        }
        return null;
    }

    /**
     * @param String $id
     *
     * @return Client|null
     */
    public function findClientByUserId($id)
    {
        /* @var Client $client */
        foreach ($this->clients as $client) {
            if ($client->getUser()->getId() === $id) {
                return $client;
            }
        }
        return null;
    }
}