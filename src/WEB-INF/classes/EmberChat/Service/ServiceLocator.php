<?php

namespace EmberChat\Service;

use TechDivision\WebSocketContainer\Application;
use EmberChat\Repository\ClientRepository;
use EmberChat\Repository\ConversationRepository;
use EmberChat\Repository\RoomRepository;
use EmberChat\Repository\UserRepository;

use TechDivision\PersistenceContainerClient\Context\Connection\Factory;

/**
 * Class ServiceLocator
 * Will get replaced by a dependency injection framework in future
 *
 * @package EmberChat\Service
 */
class ServiceLocator
{
    /**
     * @var \TechDivision\WebSocketContainer\Application
     */
    protected $application;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var RoomRepository
     */
    protected $roomRepository;

    /**
     * @var ConversationRepository
     */
    protected $conversationRepository;

    public function __construct(Application $application)
    {
        // this hole service locator will get removed in future
        $this->application = $application;

        $connection = Factory::createContextConnection();
        $session = $connection->createContextSession();
        $initialContext = $session->createInitialContext();


        $this->userRepository = new UserRepository($initialContext, $this);
        $this->clientRepository = new  ClientRepository();
        $this->roomRepository = new RoomRepository($initialContext);
        $this->conversationRepository = new ConversationRepository();
    }

    /**
     * @param \TechDivision\WebSocketContainer\Application $application
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }

    /**
     * @return \TechDivision\WebSocketContainer\Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param ClientRepository $clientRepository
     */
    public function setClientRepository(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @return ClientRepository
     */
    public function getClientRepository()
    {
        return $this->clientRepository;
    }

    /**
     * @param RoomRepository $roomRepository
     */
    public function setRoomRepository(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    /**
     * @return RoomRepository
     */
    public function getRoomRepository()
    {
        return $this->roomRepository;
    }

    /**
     * @param UserRepository $userRepository
     */
    public function setUserRepository(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository()
    {
        return $this->userRepository;
    }

    /**
     * @param ConversationRepository $conversationRepository
     */
    public function setConversationRepository(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    /**
     * @return ConversationRepository
     */
    public function getConversationRepository()
    {
        return $this->conversationRepository;
    }


}