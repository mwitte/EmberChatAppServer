<?php

namespace EmberChat\Receiver\Message\Admin;

use EmberChat\Model\Client;
use EmberChat\Receiver\Message\Admin;
use EmberChat\Sender\BroadcastSender;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class RemoveUser extends Admin
{
    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        parent::processMessage($client, $message);
        if (!$message->user) {
            //@TODO
            error_log('RemoveUser message was wrong: ');
            error_log(var_export($message, true));
            return;
        }
        $user = $this->serviceLocator->getUserRepository()->findById($message->user->id);
        if(!$user){
            error_log('RemoveUser message: user not found ' . $message->user->id);
            return;
        }
        if($this->serviceLocator->getUserRepository()->remove($user)){
            $broadcastSender = new BroadcastSender($this->serviceLocator);
            $broadcastSender->userList();
        }
    }
}