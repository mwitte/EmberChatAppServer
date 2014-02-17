<?php

namespace EmberChat\Services;

use EmberChat\Entities\MyUser;
use EmberChat\Entities\User;
use EmberChat\Services\AbstractProcessor;

/**
 * A singleton session bean
 *
 * @package EmberChat
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
        // load the entity manager and the user repository
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('EmberChat\Entities\User');

        // try to load the user
        return $repository->findOneBy(array('name' => $name));
    }

    public function findAll()
    {
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
        error_log('Processor updateEntity');
        $entityManager = $this->getEntityManager();
        $entityManager->merge($user);
        $entityManager->flush();
        return $user;
    }
}