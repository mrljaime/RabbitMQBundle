<?php
/**
 * Created by Jaime Ramírez Calvo
 * Date: 19/11/17
 * Time: 20:52
 */

namespace Jaimongo\RabbitMQBundle\RabbitMQ;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;


/**
 * Class AbstractConnection
 * @package Jaimongo\RabbitMQBundle\RabbitMQ
 */
abstract class AbstractConnection
{
    /**
     * @var string $host
     */
    protected $host;

    /**
     * @var string $port
     */
    protected $port;

    /**
     * @var string $username
     */
    protected $username;

    /**
     * @var string $password
     */
    protected $password;

    /**
     * @var string $vhost
     */
    protected $vhost;

    /**
     * @var array $channels
     */
    protected $channels = [];

    /**
     * @var array $options
     */
    protected $options = [];

    /**
     * Only one instance on context
     *
     * @var AbstractConnection $mInstance
     */
    private static $mInstance = null;

    /**
     * @var AMQPStreamConnection $streamConnection
     */
    private $streamConnection;

    /**
     * Singleton
     *
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     * @param $vhost
     * @param array $options
     * @return mixed
     */
    public static function getInstance($host,
                                       $port,
                                       $username,
                                       $password,
                                       $vhost,
                                       array $options = []
    ) {

        $class = get_called_class();

        if (is_null(self::$mInstance)) {
            self::$mInstance = new $class($host, $port, $username, $password, $vhost, $options);
        }

        return self::$mInstance;
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return $this->streamConnection->isConnected();
    }

    /**
     * Cook a simple channel to start publish
     * Always use the default channel if id is not present
     *
     * @param string $queue
     * @param string $exchange
     * @param null $id
     * @return mixed|\PhpAmqpLib\Channel\AMQPChannel
     */
    public function cookSimpleChannel($queue = "jaimongo-queue", $exchange = "jaimongo-router", $id = null)
    {
        if (!is_null($id)) {
            if (!key_exists($id, $this->channels)) {
                $this->channels[$id] = $this->_cookSimpleChannel($id, $queue, $exchange);
            }

            return $this->channels[$id];
        }

        if (!array_key_exists("default", $this->channels)) {
            $this->channels["default"] = $this->_cookSimpleChannel($id, $queue, $exchange);
        }

        return $this->channels["default"];
    }

    /**
     * Close connection to avoid unexpected results and alive connections
     */
    public function __destruct()
    {
        if ($this->streamConnection->isConnected()) {
            $this->closeChannelsIfExists();
            $this->streamConnection->close();
        }
    }

    /**
     * AbstractConnection constructor.
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     * @param $vhost
     * @param array $options
     */
    private function __construct($host, $port, $username, $password, $vhost, array $options = [])
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->vhost = $vhost;
        /*
         * Handling options in other version.
         */
        $this->options = array_merge($this->options, $options);

        /*
         * Initialize connection
         */
        $this->streamConnection = new AMQPStreamConnection(
            $this->host,
            $this->port,
            $this->username,
            $this->password,
            $this->vhost
        );
    }

    /**
     * @param $id
     * @param $queue
     * @param $exchange
     * @return \PhpAmqpLib\Channel\AMQPChannel
     */
    private function _cookSimpleChannel($id, $queue, $exchange)
    {
        $channel = $this->streamConnection->channel($id);
        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange, "direct", false, true, false);
        $channel->queue_bind($queue, $exchange);

        return $channel;
    }

    /**
     * Close all open channels by connection
     */
    private function closeChannelsIfExists()
    {
        /** @var AMQPChannel $channel */
        foreach ($this->channels as $channel) {
            $channel->close();
        }
    }
}