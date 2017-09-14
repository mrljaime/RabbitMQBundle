<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 12/09/17
 * Time: 21:34
 */

namespace Jaimongo\RabbitMQBundle\Services;


use Jaimongo\RabbitMQBundle\RabbitMQ\RabConnectionHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractRabbitService
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;
    /**
     * @var string $channel
     */
    protected $channel;

    /**
     * @var string $queue
     */
    protected $queue;

    /**
     * @var string $exchange
     */
    protected $exchange;

    /**
     * @var RabConnectionHandler $rabConnectionHandler
     */
    protected $rabConnectionHandler;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function generateConnection()
    {
        $instance = RabConnectionHandler::getInstance(
            $this->container->getParameter("rabbit_host"),
            $this->container->getParameter("rabbit_port"),
            $this->container->getParameter("rabbit_username"),
            $this->container->getParameter("rabbit_password"),
            $this->container->getParameter("rabbit_vhost")
        );

        $this->rabConnectionHandler = $instance->getConnection();
    }
}