<?php

namespace EmberChat\Receiver;

use EmberChat\Model\Client;
use EmberChat\Model\Message\RoomList;
use EmberChat\Model\Message\UserList;
use EmberChat\Receiver\Message\Authentication;

/**
 * @package   EmberChatAppServer
 * @author    Matthias Witte <wittematze@gmail.com>
 */
class StandardReceiver extends AbstractReceiver
{

    /**
     * Client can instantiate all classes within this namespace
     */
    const RECEIVER_MESSAGES_NAMESPACE = "\\EmberChat\\Receiver\\Message\\";

    /**
     * @see \EmberChat\Receiver\ReceiverInterface::processMessage()
     */
    public function processMessage(Client $client, \stdClass $message)
    {
        try{
            if ($client->getUser()) {
                $this->authenticatedClientProcessing($client, $message);
            } else {
                $authentication = new Authentication($this->serviceLocator);
                $authentication->processMessage($client, $message);
            }
        }catch (\Exception $e){
            error_log(var_export($e, true));
        }
    }

    /**
     * Actions for authenticated clients
     *
     * @param Client    $client
     * @param \stdClass $message
     */
    public function authenticatedClientProcessing(Client $client, \stdClass $message)
    {
        $className = self::RECEIVER_MESSAGES_NAMESPACE . $message->type;
        // check if class is defined
        if(class_exists($className)){
            /** @var ReceiverInterface $receiver */
            $receiver = new $className($this->serviceLocator);
            $receiver->processMessage($client, $message);
        }else{
            error_log("Received unimplemented message type: " . $className);
            error_log(var_export($message, true));
        }
    }

}