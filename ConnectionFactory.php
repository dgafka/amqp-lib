<?php

namespace Enqueue\AmqpLib;

use Enqueue\AmqpTools\DelayStrategyAware;
use Interop\Amqp\AmqpConnectionFactory AS PreviousConnectionFactory;

/**
 *  !! This as final will be part of interlop package !!
 *
 * Interop\Amqp\AmqpConnectionFactory should extend DelayStrategyAware, so all interface acts the same.
 *
 * Interface BaseAmqpConnectionFactory
 * @package Enqueue\AmqpLib
 * @author Dariusz Gafka <dgafka.mail@gmail.com>
 */
interface ConnectionFactory extends PreviousConnectionFactory, DelayStrategyAware
{
}