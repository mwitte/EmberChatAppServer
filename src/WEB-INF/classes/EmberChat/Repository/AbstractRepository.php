<?php

namespace EmberChat\Repository;

use TechDivision\PersistenceContainerClient\Context\Connection\Factory;

abstract class AbstractRepository
{

    /**
     * @var \TechDivision\PersistenceContainerClient\Interfaces\Connection
     */
    protected $connection;

    /**
     * @var \TechDivision\PersistenceContainerClient\Interfaces\TechDivision\PersistenceContainerClient\Interfaces\Session
     */
    protected $session;

    /**
     * @var string
     */
    protected $proxyClass;

    public function __construct()
    {
        $this->connection = Factory::createContextConnection();
        $this->session = $this->connection->createContextSession();
    }

    /**
     * Creates a new proxy for the passed session bean class name
     * and returns it.
     *
     * @param string $proxyClass The session bean class name to return the proxy for
     *
     * @return mixed The proxy instance
     */
    public function getProxy($proxyClass)
    {
        $initialContext = $this->session->createInitialContext();
        return $initialContext->lookup($proxyClass);
    }

    public function loadAll()
    {
        return $this->getProxy($this->proxyClass)->findAll();
    }
}