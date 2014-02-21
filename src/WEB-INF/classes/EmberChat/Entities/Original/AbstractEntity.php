<?php

namespace EmberChat\Entities\Original;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
abstract class AbstractEntity implements \JsonSerializable
{

    /**
     * Should get overwritten to define visible properties for serialization
     *
     * @var array
     */
    protected $jsonProperties;

    public function __construct(){
        // every new entity gets a unique id by default
        $this->id = hash('sha256', mt_rand());
    }

    /**
     * Implementing \JsonSerializable. This will return the properties with it's values for json serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if (!$this->jsonProperties) {
            error_log("WARNING: Entity with class " . get_class($this) . " got serialized but got no jsonProperties");
        }
        $export = array();
        foreach ($this->jsonProperties as $property) {
            $export[$property] = $this->{$property};
        }
        return $export;
    }
}