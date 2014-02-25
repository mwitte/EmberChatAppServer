<?php

namespace EmberChat\Services;

use EmberChat\Entities\User;
use EmberChat\Services\AbstractProcessor;

/**
 * A singleton session bean
 *
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 * @Stateless
 */
class UserProcessor extends AbstractProcessor
{

    /**
     * Finds a single user by name
     *
     * @param string $name
     *
     * @return null|object
     */
    public function findByName($name)
    {
        error_log(date("H:i:s") .' ' .__METHOD__);
        // load the entity manager and the user repository
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('EmberChat\Entities\User');

        // try to load the user
        return $repository->findOneBy(array('name' => $name));
    }

    public function findAll()
    {
        error_log(date("H:i:s") .' ' .__METHOD__);
        // load the entity manager and the user repository
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('EmberChat\Entities\User');

        // try to load the users
        return $repository->findAll();
    }

    /**
     * Finds a single user by name
     *
     * @param string $name
     *
     * @return null|object
     */
    public function findById($id)
    {
        error_log(date("H:i:s") .' ' .__METHOD__);
        // load the entity manager and the user repository
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('EmberChat\Entities\User');

        // try to load the user
        return $repository->findOneBy(array('id' => $id));
    }

    /**
     * Updates a user
     *
     * @param $user
     */
    public function updateEntity(User $user){
        error_log(date("H:i:s") .' ' .__METHOD__);
        $entityManager = $this->getEntityManager();
        $entityManager->merge($user);
        $entityManager->flush();
        return $user;
    }

    public function createNew(User $user){
        error_log(date("H:i:s") .' ' .__METHOD__);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $user;
    }

    public function remove(User $user){
        error_log(date("H:i:s") .' ' .__METHOD__);
        $entityManager = $this->getEntityManager();
        // @TODO why it the $room the same object like in websocket handler?
        // @TODO why are there more properties that defined in __sleep()?
        $roomMock = $entityManager->getPartialReference('\EmberChat\Entities\User', array('id' => $user->getId()));

        $entityManager->remove($roomMock);
        $entityManager->flush();
        return true;
    }
}