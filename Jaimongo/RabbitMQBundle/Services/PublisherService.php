<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 12/09/17
 * Time: 21:34
 */

namespace Jaimongo\RabbitMQBundle\Services;


use Jaimongo\RabbitMQBundle\Exception\RabbitNotConnectedException;
use Jaimongo\RabbitMQBundle\Exception\RemoteHostUnrechableException;
use Jaimongo\RabbitMQBundle\RabbitMQ\RabbitConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class PublisherService
 * @package Jaimongo\RabbitMQBundle\Services
 */
class PublisherService extends AbstractService
{
    /**
     * @var RabbitConnection $rabbitConnection
     */
    private $rabbitConnection;

    /**
     * @var string $queue
     */
    private $queue;

    /**
     * @var string $exchange
     */
    private $exchange;

    /**
     * PublisherService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, $queue, $exchange)
    {
        parent::__construct($container);

        try {
            $this->rabbitConnection = RabbitConnection::getInstance(
                $this->container->getParameter("rabbit_mq.connection.host"),
                $this->container->getParameter("rabbit_mq.connection.port"),
                $this->container->getParameter("rabbit_mq.connection.username"),
                $this->container->getParameter("rabbit_mq.connection.password"),
                $this->container->getParameter("rabbit_mq.connection.vhost"),
                [] // By now is empty
            );

        } catch (RemoteHostUnrechableException $exception) {
            $this->container->get("logger")->critical($exception->getMessage());
            $this->container->get("logger")->critical($exception->getTraceAsString());

            return;
        }

        $this->queue = $queue;
        $this->exchange = $exchange;
    }

    /**
     * @param $message
     * @param array $options
     * @param null $id
     * @throws RabbitNotConnectedException
     */
    public function sendMessage($message,
                                $options = [
                                    "content-type"  => "application/json",
                                    "delivery"      => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                                ],
                                $id = null) {

        if (!$this->rabbitConnection->isConnected()) {

            throw new RabbitNotConnectedException("There's no connection with server", 500);
        }

        $message = new AMQPMessage($message, $options);
        $channel = $this->rabbitConnection->cookSimpleChannel($this->queue, $this->exchange, $id);

        /*
         * Publish message
         */
        $channel->basic_publish($message, $this->exchange);
    }

    /**
     * @param $queue
     * @param $exchange
     */
    public function overrideChannelOptions($queue, $exchange)
    {
        $this->queue = $queue;
        $this->exchange = $exchange;
    }
}