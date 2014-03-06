<?php

namespace EmberChat\Handler;

use EmberChat\Service\ServiceLocator;
use Ratchet\ConnectionInterface;
use TechDivision\WebSocketContainer\Handlers\HandlerConfig;
use TechDivision\WebSocketContainer\Handlers\AbstractHandler;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class SocketHandler extends AbstractHandler
{

    /**
     * @var ClientHandler
     */
    protected $clientHandler;

    /**
     * @var ServiceLocator
     */
    protected $serviceLocator;

    public function __construct()
    {

    }

    /**
     * @param HandlerConfig $config
     */
    public function init(HandlerConfig $config)
    {

        parent::init($config);
        $this->serviceLocator = new ServiceLocator($this->getApplication());
        $this->clientHandler = new ClientHandler($this->serviceLocator);
    }

    /**
     * @see \Ratchet\ComponentInterface::onOpen()
     */
    public function onOpen(ConnectionInterface $connection)
    {

        $this->clientHandler->createNewClient($connection);
    }

    /**
     * @see \Ratchet\MessageInterface::onMessage()
     */
    public function onMessage(ConnectionInterface $connection, $message)
    {

        $this->clientHandler->messageFromClient($connection, $message);
    }

    /**
     * @see \Ratchet\ComponentInterface::onClose()
     */
    public function onClose(ConnectionInterface $connection)
    {

        $this->clientHandler->unsetClient($connection);
    }

    /**
     * @see \Ratchet\ComponentInterface::onError()
     */
    public function onError(ConnectionInterface $connection, \Exception $e)
    {

        $this->clientHandler->unsetClient($connection);
        error_log($e->__toString());
        $connection->close();
    }
}