<?php

namespace Enqueue\AmqpLib;

use Interop\Amqp\AmqpContext as InteropAmqpContext;

/**
 * !! This as final will be part of interlop package !!
 *
 * Below method should be in Interop\Amqp\AmqpContext
 *
 * Class BaseAmqpContext
 * @package Enqueue\AmqpLib
 * @author Dariusz Gafka <dgafka.mail@gmail.com>
 */
interface BaseAmqpContext extends InteropAmqpContext
{
    /**
     * @return bool
     */
    public function isConnected() : bool;
}