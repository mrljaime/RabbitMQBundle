<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 19/11/17
 * Time: 22:29
 */

namespace Jaimongo\RabbitMQBundle\Services;


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