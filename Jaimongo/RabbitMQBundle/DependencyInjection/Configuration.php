<?php

/**
 * @author <a href="mailto:mr.ljaime@gmail.com">Jose jaime Ramirez Calvo</a>
 * @version 1.1
 *
 *
 * @version 1.1
 * @since 2018-04-01
 */

namespace RabbitMQBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('rabbit_mq');

        $rootNode
            ->children()
                ->arrayNode("connection")
                ->children()
                    ->scalarNode("host")->end()
                    ->scalarNode("port")->end()
                    ->scalarNode("username")->end()
                    ->scalarNode("password")->end()
                    ->scalarNode("vhost")->end()
                    ->scalarNode("queue")->defaultValue("jaimongo-queue")->end()
                    ->scalarNode("exchange")->defaultValue("")->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
