<?php

namespace Enqueue\AmqpLib;

use Enqueue\AmqpTools\DelayStrategy;
use Enqueue\AmqpTools\DelayStrategyAware;
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
     * @var AmqpContext|null
     */
    private $cachedContextInstance;

    /**
     * CachedConnectionFactory constructor.
     * @param ConnectionFactory $connectionFactory
     */
    public function __construct(ConnectionFactory $connectionFactory)
    {
        $this->connectionFactory = $connectionFactory;
    }

    /**
     * @return AmqpContext
     */
    public function createContext(): Context
    {
        if (is_null($this->cachedContextInstance) || !$this->cachedContextInstance->isConnected()) {
            $this->cachedContextInstance = $this->connectionFactory->createContext();
        }

        return $this->cachedContextInstance;
    }

    /**
     * @inheritdoc
     */
    public function setDelayStrategy(DelayStrategy $delayStrategy = null): DelayStrategyAware
    {
        $this->connectionFactory->setDelayStrategy($delayStrategy);
    }
}