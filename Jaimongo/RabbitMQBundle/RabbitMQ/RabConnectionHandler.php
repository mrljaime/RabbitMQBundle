<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 12/09/17
 * Time: 22:10
 */

namespace Jaimongo\RabbitMQBundle\RabbitMQ;


use Doctrine\Common\Util\Debug;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabConnectionHandler
{
    /**
     * @var RabConnectionHandler $mInstance
     */
    private static $mInstance = null;

    /**
     * @var AMQPStreamConnection $connection
     */
    private static $connection;

    /**
     * @var string $host
     */
    private static $host;

    /**
     * @var string $port
     */
    private static $port;

    /**
     * @var string $username
     */
    private static $username;

    /**
     * @var string $password
     */
    private static $password;

    /**
     * @var string $vhost
     */
    private static $vhost;

    private function __construct()
    {
        self::$connection = new AMQPStreamConnection(
            self::$host,
            self::$port,
            self::$username,
            self::$password,
            self::$vhost
        );
    }

    /**
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     * @param $vhost
     * @return RabConnectionHandler
     */
    public static function getInstance($host, $port, $username, $password, $vhost)
    {
        self::$host = $host;
        self::$port = $port;
        self::$username = $username;
        self::$password = $password;
        self::$vhost = $vhost;

        if (is_null(self::$mInstance)) {
            self::$mInstance = new RabConnectionHandler();
        }

        return self::$mInstance;
    }

    public function getConnection()
    {
        return self::$connection;
    }
}