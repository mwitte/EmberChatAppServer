<?php

namespace EmberChat\Services;

use EmberChat\Entities\Room;

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

    public function createNew(Room $room){
        $entityManager = $this->getEntityManager();
        $entityManager->persist($room);
        $entityManager->flush();
        return $room;
    }
}