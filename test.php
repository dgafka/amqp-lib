<?php
require __DIR__."/vendor/autoload.php";

use Enqueue\AmqpLib\AmqpConnectionFactory;
use Interop\Amqp\AmqpQueue;
use Interop\Amqp\AmqpTopic;
use Interop\Amqp\Impl\AmqpBind;
use Enqueue\Consumption\QueueConsumer;
use Interop\Queue\Message;

function sendMessages(\Enqueue\AmqpLib\AmqpContext $context): void
{
// declare queues and exchange
    $destination = $context->createTopic("rabbit");
    $context->declareTopic($destination);

    $queue = $context->createQueue("");
    $queue->addFlag(AmqpQueue::FLAG_DURABLE);
    $context->declareQueue($queue);

    $context->bind(new AmqpBind($destination, $queue, "some"));


// create producer and send mesasge
    $producer = $context->createProducer();
    $message = $context->createMessage("some");
    $message->setRoutingKey("some");
    $producer->send($destination, $message);
    $producer->send($destination, $message);
    $producer->send($destination, $message);
    $producer->send($destination, $message);
}

function checkIfCanReceive(\Enqueue\AmqpLib\AmqpContext $context): void
{
// declare queues
    $queue = $context->createQueue("");
    $queue->addFlag(AmqpQueue::FLAG_DURABLE);
    $context->declareQueue($queue);

// create consumer and receive
    $consumer = $context->createConsumer($queue);
    $subscriptionConsumer = $context->createSubscriptionConsumer();

    $subscriptionConsumer->subscribe($consumer, function (Message $message, \Interop\Queue\Consumer $consumer) {
        \PHPUnit\Framework\TestCase::assertNotNull($message);
    });
    $subscriptionConsumer->consume(1);
}

$factory = new AmqpConnectionFactory(["dsn" => "amqp://rabbitmq:5672", "lazy" => false]);

// Amqp Connection should create new instance each time called
\PHPUnit\Framework\TestCase::assertNotEquals($factory->createContext(), $factory->createContext());

// caching does provide same instance as long it's connected correctly
$cachedFactory = new \Enqueue\AmqpLib\CachedAmqpConnectionFactory($factory);
\PHPUnit\Framework\TestCase::assertEquals($cachedFactory->createContext(), $cachedFactory->createContext());

$context = $cachedFactory->createContext();
sendMessages($context);
checkIfCanReceive($context);

// * Soft Error: Channel closed via API
$context->close();
if (!$context->isConnected()) {
    $context = $cachedFactory->createContext();
}
checkIfCanReceive($context);


// * Fatal Error: Connection was closed
$context->getLibChannel()->getConnection()->close();
if (!$context->isConnected()) {
    $context = $cachedFactory->createContext();
}
checkIfCanReceive($context);


// * amqp lazy connection factory is special case. Is neither connected, neither closed
// but we do not want cached factory create new instance, if it not initialized
$factory = new AmqpConnectionFactory(["dsn" => "amqp://rabbitmq:5672"]);

$cachedFactory = new \Enqueue\AmqpLib\CachedAmqpConnectionFactory($factory);
$context1 = $cachedFactory->createContext();
$context2 = $cachedFactory->createContext();
\PHPUnit\Framework\TestCase::assertEquals($context1, $context2);

$context1->close();
\PHPUnit\Framework\TestCase::assertNotEquals($context1, $cachedFactory->createContext());
