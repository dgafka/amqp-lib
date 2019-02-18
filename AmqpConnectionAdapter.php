<?php

namespace Enqueue\AmqpLib;

use Enqueue\AmqpTools\DelayStrategy;
use PhpAmqpLib\Connection\AbstractConnection;

/**
 * Class AmqpConnectionAdapter
 * @package Enqueue\AmqpLib
 * @author Dariusz Gafka <dgafka.mail@gmail.com>
 */
class AmqpConnectionAdapter implements AmqpConnection
{
    /**
     * @var AbstractConnection
     */
    private $abstractConnection;
    /**
     * @var array
     */
    private $config;
    /**
     * @var DelayStrategy|null
     */
    private $delayStrategy;

    /**
     * AmqpConnectionAdapter constructor.
     * @param AbstractConnection $abstractConnection
     * @param array $config
     * @param DelayStrategy|null $delayStrategy
     */
    public function __construct(AbstractConnection $abstractConnection, array $config, ?DelayStrategy $delayStrategy)
    {
        $this->abstractConnection = $abstractConnection;
        $this->config = $config;
        $this->delayStrategy = $delayStrategy;
    }

    /**
     * @inheritDoc
     */
    public function createContext(): \Interop\Amqp\AmqpContext
    {
        $context = new AmqpContext($this->abstractConnection, $this->config);
        $context->setDelayStrategy($this->delayStrategy);

        return $context;
    }

    /**
     * @inheritDoc
     */
    public function isConnected(): bool
    {
        return $this->abstractConnection->isConnected();
    }
}