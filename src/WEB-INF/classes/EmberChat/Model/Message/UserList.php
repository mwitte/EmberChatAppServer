<?php

namespace EmberChat\Model\Message;

use EmberChat\Model\SendMessage;

class UserList extends SendMessage
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