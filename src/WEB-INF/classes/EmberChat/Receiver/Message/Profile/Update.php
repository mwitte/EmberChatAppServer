<?php

namespace EmberChat\Receiver\Message\Profile;

use EmberChat\Model\Client;
use EmberChat\Model\Message\ProfileUpdate;
use EmberChat\Receiver\AbstractReceiver;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class Update extends AbstractReceiver
{
    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        $user = $client->getUser();
        $profile = $message->profile;
        if (!$profile) {
            return false;
        }
        if ($profile->password) {
            if (!$user->auth($profile->currentPassword)) {
                error_log(var_export($profile, true));
                new ProfileUpdate($client, false);
                return false;
            }
            $user->setPassword($profile->password);
        }
        $this->serviceLocator->getUserRepository()->persistUser($user);
        new ProfileUpdate($client, true);
    }
}