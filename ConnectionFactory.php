<?php

namespace Enqueue\AmqpLib;

use Enqueue\AmqpTools\DelayStrategyAware;
use Interop\Queue\ConnectionFactory AS PreviousConnectionFactory;

/**
 * Below method would be part of Interop\Queue\ConnectionFactory
 *
 * Interface BaseAmqpConnectionFactory
 * @package Enqueue\AmqpLib
 * @author Dariusz Gafka <dgafka.mail@gmail.com>
 */
interface ConnectionFactory extends PreviousConnectionFactory, DelayStrategyAware
{
    /**
     * @return AmqpConnection
     */
    public function createConnection() : AmqpConnection;
}