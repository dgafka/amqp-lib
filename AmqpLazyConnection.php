<?php

namespace Enqueue\AmqpLib;

/**
 * Class AmqpLazyConnection
 * @package Enqueue\AmqpLib
 * @author Dariusz Gafka <dgafka.mail@gmail.com>
 */
class AmqpLazyConnection extends \PhpAmqpLib\Connection\AMQPLazyConnection
{
    /**
     * @var bool
     */
    private $isInitialized = false;

    /**
     * @inheritDoc
     */
    public function isConnected()
    {
        return !$this->isInitialized || parent::isConnected();
    }
}