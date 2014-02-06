<?php

namespace EmberChat\Services;

/**
 * A singleton session bean
 *
 * @package EmberChat
 * @Stateless
 */
class RoomProcessor extends AbstractProcessor
{

    public function findAll()
    {
        // load the entity manager and the user repository
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository('EmberChat\Entities\Room');

        // try to load the users
        return $repository->findAll();
    }
}