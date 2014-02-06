<?php

namespace EmberChat\Entities;

/**
 * @Entity @Table(name="room")
 */
class Room extends \EmberChat\EntitiesOriginal\Room implements \JsonSerializable
{

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}