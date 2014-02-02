<?php

namespace EmberChat\Model\Serializable;

abstract class AbstractSerializable implements \JsonSerializable {
    public function jsonSerialize(){
        return get_object_vars($this);
    }
}