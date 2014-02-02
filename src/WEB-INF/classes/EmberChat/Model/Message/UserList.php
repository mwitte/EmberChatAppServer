<?php

namespace EmberChat\Model\Message;

use EmberChat\Model\Message;
use EmberChat\Model\Serializable\User;

class UserList extends Message {

    /**
     * @var array
     */
    protected $content;

    public function __construct() {
        parent::__construct();
        $this->content = array();
    }

    /**
     * @param User $user
     */
    public function setContent($content) {
        $this->content = $content;
    }
}