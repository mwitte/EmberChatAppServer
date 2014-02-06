<?php

namespace EmberChat\Repository;

use EmberChat\Entities\User;

class RoomRepository extends AbstractRepository
{


    protected $proxyClass = 'EmberChat\Services\RoomProcessor';

    /**
     * The dummy rooms
     *
     * @var array
     */
    protected $rooms;

    public function __construct()
    {
        parent::__construct();
        $this->rooms = $this->loadAll();
    }

    public function findAll()
    {
        return $this->rooms;
    }
}