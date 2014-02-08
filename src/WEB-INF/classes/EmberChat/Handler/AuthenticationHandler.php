<?php

namespace EmberChat\Handler;

use EmberChat\Model\Client;
use EmberChat\Model\Message\Settings;
use EmberChat\Service\ServiceLocator;

class AuthenticationHandler
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

    public function authenticationMessage(Client $client, \stdClass $message)
    {
        if ($message->type !== 'authentication') {
            error_log("WARNING: Wrong message type, authentication needed");
            error_log(var_export($message, true));
            return false;
        }

        if ($message->token) {
            // fetch user by given token
            $user = $this->authByToken($message->token);
        } else {
            // fetch user by given auth
            $user = $this->authByCredentials($message);
        }
        // if no user is given authentication failed
        if (!$user) {
            //@TODO send "try again" message
            return false;
        }

        // relate client with user on both sides
        $client->setUser($user);
        $user->addClient($client);

        // send user settings
        new Settings($client, $message->keep);

        // send user and room information
        $messageReceiver = new MessageReceiver($this->serviceLocator);
        $messageReceiver->broadCastUserList();
        $messageReceiver->sendRoomList($client);
        return true;
    }

    /**
     * Authenticate by token
     *
     * @param string $token
     *
     * @return \EmberChat\Entities\User|null
     */
    protected function authByToken($token)
    {
        return $this->serviceLocator->getUserRepository()->findByToken($token);
    }

    /**
     * Authenticate by credentials
     *
     * @param $message
     *
     * @return bool|\EmberChat\Entities\User|null
     */
    protected function authByCredentials($message)
    {
        $user = $this->serviceLocator->getUserRepository()->findByAuth($message->auth);
        // check if user with given auth exists
        if (!$user) {
            return false;
        }
        // check password validity
        if (!$user->auth($message->password)) {
            return false;
        }
        return $user;
    }
}