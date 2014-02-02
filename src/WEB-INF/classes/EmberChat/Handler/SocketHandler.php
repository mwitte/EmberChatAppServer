<?php

namespace EmberChat\Handler;

use EmberChat\Model\Client;
use EmberChat\Repository\ClientRepository;
use EmberChat\Repository\UserRepository;
use Ratchet\ConnectionInterface;
use TechDivision\WebSocketContainer\Handlers\HandlerConfig;
use TechDivision\WebSocketContainer\Handlers\AbstractHandler;


class SocketHandler extends AbstractHandler {

    /**
     * @var ClientHandler
     */
    protected $clientHandler;

    public function __construct(){
        $this->clientHandler = new ClientHandler();
    }

    public function init(HandlerConfig $config){
        error_log('SocketHandler, init');
        parent::init($config);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Ratchet\ComponentInterface::onOpen()
     */
    public function onOpen(ConnectionInterface $connection) {
        error_log('SocketHandler, onOpen');
        $this->clientHandler->createNewClient($connection);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Ratchet\ComponentInterface::onClose()
     */
    public function onClose(ConnectionInterface $connection) {
        error_log('SocketHandler, onClose');
        $this->clientHandler->unsetClient($connection);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Ratchet\MessageInterface::onMessage()
     */
    public function onMessage(ConnectionInterface $connection, $message) {
        error_log('SocketHandler, onMessage');
        $this->clientHandler->messageFromClient($connection, $message);
    }

    public function onError(ConnectionInterface $connection,\Exception $e){
        $this->clientHandler->unsetClient($connection);
        error_log($e->__toString());
        $connection->close();
    }
}