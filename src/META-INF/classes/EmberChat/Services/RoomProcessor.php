<?php

namespace EmberChat\Services;

use EmberChat\Entities\Room;


/**
 * A singleton session bean
 *
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
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

    public function remove(Room $room){

        $entityManager = $this->getEntityManager();
        // @TODO why it the $room the same object like in websocket handler?
        // @TODO why are there more properties that defined in __sleep()?
        $roomMock = $entityManager->getPartialReference('\EmberChat\Entities\Room', array('id' => $room->getId()));

        $entityManager->remove($roomMock);
        $entityManager->flush();
        return true;
    }
}