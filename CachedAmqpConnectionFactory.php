<?php

namespace Enqueue\AmqpLib;

use Enqueue\AmqpTools\DelayStrategy;
use Enqueue\AmqpTools\DelayStrategyAware;
use Enqueue\AmqpTools\DelayStrategyAwareTrait;
use Interop\Queue\Context;

/**
 * Class CachedConnectionFactory
 * @package Enqueue\AmqpLib
 * @author Dariusz Gafka <dgafka.mail@gmail.com>
 */
class CachedAmqpConnectionFactory implements ConnectionFactory
{
    /**
     * @var ConnectionFactory
     */
    private $connectionFactory;
    /**
     * @var AmqpConnection|null
     */
    private $cachedConnectionInstance;

    /**
     * CachedConnectionFactory constructor.
     * @param ConnectionFactory $connectionFactory
     */
    public function __construct(ConnectionFactory $connectionFactory)
    {
        $this->connectionFactory = $connectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function createConnection(): AmqpConnection
    {
        if (is_null($this->cachedConnectionInstance) || !$this->cachedConnectionInstance->isConnected()) {
            $this->cachedConnectionInstance = $this->connectionFactory->createConnection();
        }

        return $this->cachedConnectionInstance;
    }

    /**
     * @inheritdoc
     */
    public function createContext(): Context
    {
        return $this->createConnection()->createContext();
    }

    /**
     * @inheritdoc
     */
    public function setDelayStrategy(DelayStrategy $delayStrategy = null): DelayStrategyAware
    {
        $this->connectionFactory->setDelayStrategy($delayStrategy);
    }
}