<?php

namespace EmberChat\Repository;

use EmberChat\Entities\Room;

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
}