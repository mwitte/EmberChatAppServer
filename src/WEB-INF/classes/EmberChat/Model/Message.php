<?php

namespace EmberChat\Model;

abstract class Message implements \JsonSerializable {

    /**
     * Type of the Message
     *
     * @var string
     */
    protected $type = '';

    /**
     * Sets the type name for the class
     */
    public function __construct() {
        $fullClassName = explode('\\', get_class($this));
        $this->type = (string) array_pop($fullClassName);
    }

    /**
     * Implementing JsonSerializable
     * @return array|mixed
     */
    public function jsonSerialize(){
        return get_object_vars($this);
    }
}