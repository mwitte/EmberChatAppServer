<?php

namespace EmberChat\Receiver;


use EmberChat\Service\ServiceLocator;

/**
 * Class AbstractReceiver
 *
 *
 * @package EmberChat\Receiver
 */
abstract class AbstractReceiver implements ReceiverInterface
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
}