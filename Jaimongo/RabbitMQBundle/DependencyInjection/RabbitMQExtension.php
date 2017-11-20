<?php

namespace Jaimongo\RabbitMQBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class RabbitMQExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter("rabbit_mq.connection.host", $config["connection"]["host"]);
        $container->setParameter("rabbit_mq.connection.port", $config["connection"]["port"]);
        $container->setParameter("rabbit_mq.connection.username", $config["connection"]["username"]);
        $container->setParameter("rabbit_mq.connection.password", $config["connection"]["password"]);
        $container->setParameter("rabbit_mq.connection.vhost", $config["connection"]["vhost"]);
        $container->setParameter("rabbit_mq.connection.queue", $config["connection"]["queue"]);
        $container->setParameter("rabbit_mq.connection.exchange", $config["connection"]["exchange"]);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
