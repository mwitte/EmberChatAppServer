<?php

namespace EmberChat\Model\Message;

use EmberChat\Model\Message;

class RoomList extends Message
{

    /**
     * @var array
     */
    protected $content;

    public function __construct()
    {
        parent::__construct();
        $this->content = array();
    }

    /**
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}