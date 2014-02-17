<?php

namespace EmberChat\Handler;

use EmberChat\Model\Client;
use EmberChat\Model\Message\ProfileUpdate;
use EmberChat\Service\ServiceLocator;

class Profile
{
    /**
     * @var ServiceLocator
     */
    protected $serviceLocator;

    /**
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(ServiceLocator $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * User updates his profile
     * @param Client    $client
     * @param \stdClass $message
     */
    public function updateProfile(Client $client, \stdClass $message){
        $user = $client->getUser();
        $profile = $message->profile;
        if(!$profile){
            return false;
        }
        if($profile->password){
            if(!$user->auth($profile->currentPassword)){
                new ProfileUpdate($client, false);
                return false;
            }
            $user->setPassword($profile->password);
        }
        $this->serviceLocator->getUserRepository()->persistUser($user);
        new ProfileUpdate($client, true);
    }
}