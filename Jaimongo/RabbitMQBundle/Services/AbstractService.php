<?php

/**
 * @author <a href="mailto:mr.ljaime@gmail.com">Jose jaime Ramirez Calvo</a>
 * @version 1.1
 *
 * @version 1
 * @since 2017-11-19
 *
 * @version 1.1
 * @since 2018-04-01
 */

namespace RabbitMQBundle\Services;


use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class AbstractService
 * @package Jaimongo\RabbitMQBundle\Services
 */
abstract class AbstractService
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


}