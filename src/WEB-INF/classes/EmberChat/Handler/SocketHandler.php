<?php

namespace EmberChat\Handler;

use EmberChat\Model\Client;
use EmberChat\Model\Message\Settings;
use EmberChat\Model\Message\SettingsMessage;
use EmberChat\Repository\ClientRepository;
use EmberChat\Repository\UserRepository;
use Ratchet\ConnectionInterface;
use TechDivision\WebSocketContainer\Handlers\HandlerConfig;
use TechDivision\WebSocketContainer\Handlers\AbstractHandler;
use TechDivision\PersistenceContainerClient\Context\Connection\Factory;


class SocketHandler extends AbstractHandler {

    /**
     * Class name of the persistence container proxy that handles the data.
     *
     * @var string
     */
    const PROXY_CLASS = 'EmberChat\Services\UserProcessor';

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    protected $connection;

    protected $session;

    public function __construct() {
        $this->connection = Factory::createContextConnection();
        $this->session = $this->connection->createContextSession();
    }

    /**
     * Creates a new proxy for the passed session bean class name
     * and returns it.
     *
     * @param string $proxyClass The session bean class name to return the proxy for
     * @return mixed The proxy instance
     */
    public function getProxy($proxyClass) {
        $initialContext = $this->session->createInitialContext();
        return $initialContext->lookup($proxyClass);
    }

    public function init(HandlerConfig $config){

        error_log('SocketHandler, init');

        $user = $this->getProxy(self::PROXY_CLASS)->findByName('Matthias');
        error_log(var_export($user, true));

        // call parent init() method
        parent::init($config);

        $this->clientRepository = new ClientRepository();
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Ratchet\ComponentInterface::onOpen()
     */
    public function onOpen(ConnectionInterface $conn) {
        error_log('SocketHandler, onOpen');
        $client = new Client($conn);
        $this->clientRepository->addClient($client, $conn);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Ratchet\ComponentInterface::onClose()
     */
    public function onClose(ConnectionInterface $conn) {
        error_log('SocketHandler, onClose');
        $this->clientRepository->removeClient($conn);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Ratchet\MessageInterface::onMessage()
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        error_log('SocketHandler, onMessage');
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Ratchet\ComponentInterface::onError()
     */
    public function onError(ConnectionInterface $conn,\Exception $e)
    {
        error_log($e->__toString());
        $conn->close();
    }
}