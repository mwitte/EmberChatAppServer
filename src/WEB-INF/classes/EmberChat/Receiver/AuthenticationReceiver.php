<?php

namespace EmberChat\Receiver;

use EmberChat\Model\Client;
use EmberChat\Model\Message\Settings;


class AuthenticationReceiver extends AbstractReceiver
{
    /**
     * @param Client    $client
     * @param \stdClass $message
     *
     * @return void
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        if ($message->type !== 'authentication') {
            error_log("WARNING: Wrong message type, authentication needed");
            error_log(var_export($message, true));
            return;
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
            return;
        }

        // relate client with user on both sides
        $client->setUser($user);
        $user->addClient($client);

        // send user settings
        new Settings($client, $message->keep);

        // send user and room information
        $standardReceiver = new StandardReceiver($this->serviceLocator);
        $standardReceiver->broadCastUserList();
        $standardReceiver->sendRoomList($client);
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