<?php

namespace EmberChat\EntitiesOriginal;

abstract class AbstractEntity implements \JsonSerializable
{

    /**
     * Should get overwritten to define visible properties for serialization
     *
     * @var array
     */
    protected $jsonProperties;

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