<?php

namespace EmberChat\Handler;

use EmberChat\Entities\User;
use EmberChat\Model\Client;
use EmberChat\Model\Message\AdminAction;
use EmberChat\Service\ServiceLocator;

class Admin
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

    public function processMessage(Client $client, \stdClass $message)
    {
        if (!$client->getUser()->getAdmin()) {
            error_log('Non admin user tried: ');
            error_log(var_export($message, true));
            //@TODO send a error message
            return;
        }
        switch ($message->subType) {
            case 'CreateUser':
                $this->createUser($client, $message);
                break;
            default:
                error_log('Unkown admin message subtype: ');
                error_log(var_export($message, true));
        }
    }

    protected function removeUser(Client $client, \stdClass $message)
    {
        
    }

    /**
     * User updates his profile
     *
     * @param Client    $client
     * @param \stdClass $message
     */
    protected function createUser(Client $client, \stdClass $message)
    {
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
            $messageReceiver = new MessageReceiver($this->serviceLocator);
            $messageReceiver->broadCastUserList();
        }else{
            new AdminAction($client, false, 'CreateUser', 'There is already a user with the auth ' . $newUser->getAuth());
        }
    }
}