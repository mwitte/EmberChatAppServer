<?php

namespace EmberChat\Receiver;

use EmberChat\Model\Client;
use EmberChat\Model\Message\ProfileUpdate;

class ProfileReceiver extends AbstractReceiver
{
    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        switch ($message->subType) {
            case 'Update':
                $this->updateProfile($client, $message);
                break;
            default:
                error_log('Unkown User message subType: ');
                error_log(var_export($message, true));
        }
    }

    /**
     * User updates his profile
     *
     * @param Client    $client
     * @param \stdClass $message
     */
    public function updateProfile(Client $client, \stdClass $message)
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