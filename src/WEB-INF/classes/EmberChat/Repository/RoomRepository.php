<?php

namespace EmberChat\Repository;

use EmberChat\Entities\Room;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class RoomRepository extends AbstractRepository
{


    protected $proxyClass = 'EmberChat\Services\RoomProcessor';

    /**
     * The dummy rooms
     *
     * @var array
     */
    protected $rooms;

    public function __construct($initialContext)
    {
        parent::__construct($initialContext);
        $this->rooms = $this->loadAll();
    }

    public function findAll()
    {
        return $this->rooms;
    }

    /**
     * @param string $id
     *
     * @return Room
     */
    public function findById($id)
    {
        foreach ($this->rooms as $room) {
            if ($room->getId() == $id) {
                return $room;
            }
        }
    }

    public function createNew(Room $room){
        /** @var Room $roomIterator */
        foreach($this->rooms as $roomIterator){
            if($room->getName() === $roomIterator->getName()){
                return false;
            }
        }
        $room->setId(hash('sha256', mt_rand() . serialize($room) . mt_rand()));
        $this->getProxy($this->proxyClass)->createNew($room);
        $this->rooms[] = $room;
        return true;
    }

    /**
     * @param Room $room
     *
     * @return mixed
     */
    public function remove(Room $room){
        // remove room in persistence
        if($this->getProxy($this->proxyClass)->remove($room)){
            foreach ($this->rooms as $key => $roomIterator) {
                if ($roomIterator->getId() == $room->getId()) {
                    unset($this->rooms[$key]);
                    $this->rooms = array_values($this->rooms);
                    $room->destruct();
                    return true;
                }
            }
        }
    }
}