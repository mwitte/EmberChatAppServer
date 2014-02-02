<?php

namespace EmberChat\Model;

use EmberChat\Entities\User;

class Conversation {

    protected $content = array();

    /**
     * @return array
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param string $line
     */
    public function appendContent(User $user, $line){
        $this->content[] = array("user"=> $user->getName(), "content" => $line);
    }
}