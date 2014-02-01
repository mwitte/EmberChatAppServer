<?php

namespace EmberChat\Handler;

use Ratchet\ConnectionInterface;
use TechDivision\WebSocketContainer\Handlers\HandlerConfig;
use TechDivision\WebSocketContainer\Handlers\AbstractHandler;


class SocketHandler extends AbstractHandler {

    /**
     * The connected clients
     *
     * @var \SplObjectStorage
     */
    protected $clients;

    public function init(HandlerConfig $config){

        error_log('SocketHandler, init');

        // call parent init() method
        parent::init($config);

        // initialize the object storage for the client connections
        $this->clients = new \SplObjectStorage();
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Ratchet\ComponentInterface::onOpen()
     */
    public function onOpen(ConnectionInterface $conn) {
        error_log('SocketHandler, onOpen');
        $this->clients->attach($conn, 0);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Ratchet\ComponentInterface::onClose()
     */
    public function onClose(ConnectionInterface $conn) {
        error_log('SocketHandler, onClose');
        $this->clients->detach($conn);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Ratchet\MessageInterface::onMessage()
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        error_log('SocketHandler, onMessage');
        foreach ($this->clients as $client) {
            $client->send(trim($msg) . count($this->clients));
        }
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