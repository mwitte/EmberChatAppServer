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
     * Class name of the persistence container proxy that handles the data.
     *
     * @var string
     */
    const PROXY_CLASS = 'EmberChat\Services\UserProcessor';

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    protected $userRepository;

    public function __construct() {

        $this->clientRepository = new ClientRepository();
        $this->userRepository = new UserRepository();
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
    public function onOpen(ConnectionInterface $conn) {
        error_log('SocketHandler, onOpen');
        $client = new Client($conn, $this->userRepository);
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