<?php

namespace EmberChat\Model;

use EmberChat\Entities\User;

class Conversation
{

    /**
     * Max length for this conversation
     */
    CONST MAX_LENGTH = 100;

    /**
     * @var array
     */
    protected $content = array();

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Add content to this conversation
     *
     * @param User   $user
     * @param string $line
     */
    public function appendContent(User $user, $line)
    {
        // remove old conversation contents
        if (count($this->content) >= self::MAX_LENGTH) {
            // remove first entry
            array_shift($this->content);
        }
        // add content
        $this->content[] = array("user" => $user->getName(), "type" => "msg", "content" => $line);
    }
}