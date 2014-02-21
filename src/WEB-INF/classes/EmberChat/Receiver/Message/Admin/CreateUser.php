<?php

namespace EmberChat\Receiver\Message\Admin;

use EmberChat\Entities\User;
use EmberChat\Model\Client;
use EmberChat\Model\Message\AdminAction;
use EmberChat\Receiver\Message\Admin;
use EmberChat\Receiver\StandardReceiver;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class CreateUser extends Admin
{
    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        self::processMessage($client, $message);
        $msgUser = $message->user;
        if (!$msgUser || !$msgUser->name ||
            strlen($msgUser->name) < 4 ||
            !$msgUser->auth ||
            strlen($msgUser->auth) < 4 ||
            !$msgUser->password ||
            strlen($msgUser->password) < 4
        ) {
            //@TODO
            error_log('Message was wrong: ');
            error_log(var_export($message, true));
            return;
        }

        $newUser = new User();
        $newUser->setName($msgUser->name);
        $newUser->setAuth($msgUser->auth);
        $newUser->setPassword($msgUser->password);
        $newUser->setAdmin((bool)$msgUser->admin);
        if($this->serviceLocator->getUserRepository()->createNew($newUser)){
            new AdminAction($client, true, 'CreateUser');
            $standardReceiver = new StandardReceiver($this->serviceLocator);
            $standardReceiver->broadCastUserList();
        }else{
            new AdminAction($client, false, 'CreateUser', 'There is already a user with the auth ' . $newUser->getAuth());
        }
    }
}