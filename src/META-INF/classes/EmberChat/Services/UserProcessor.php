<?php

namespace EmberChat\Services;

/**
 * A singleton session bean
 *
 * @package EmberChat
 * @Stateless
 */
class UserProcessor extends AbstractProcessor {

    /**
     * Finds a single user by name
     *
     * @param string $name
     * @return null|object
     */
    public function findByName($name) {
        // load the entity manager and the user repository
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('EmberChat\Entities\User');

        // try to load the user
        return $repository->findOneBy(array('name' => $name));
    }
}