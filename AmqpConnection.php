<?php

namespace Enqueue\AmqpLib;

/**
 * That interface would go Interop\Queue\Context\AmqpConnection
 *
 * Interface Connection
 * @package Enqueue\AmqpLib
 * @author Dariusz Gafka <dgafka.mail@gmail.com>
 */
interface AmqpConnection
{
    /**
     * @return \Interop\Amqp\AmqpContext
     */
    public function createContext(): \Interop\Amqp\AmqpContext;

    /**
     * @return bool
     */
    public function isConnected() : bool;
}