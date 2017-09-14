<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 12/09/17
 * Time: 21:34
 */

namespace Jaimongo\RabbitMQBundle\Services;


use PhpAmqpLib\Message\AMQPMessage;

class PublisherService extends AbstractRabbitService
{
    private $defaultMessageConfig = array(
        "content_type"  => "text/plain",
        "delivery_mode" => AMQPMessage::DELIVERY_MODE_PERSISTENT
    );

    public function pubMessage($message, $queue = "jaimongo-queue", $exchange = "jaimongo-router")
    {
        $this->generateConnection();

        $channel = $this->rabConnectionHandler->getConnection()->channel();
        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange, "direct", false, true, false);
        $channel->queue_bind($queue, $exchange);

        $mMessage = new AMQPMessage($message, $this->defaultMessageConfig);

        $channel->basic_publish($mMessage, $exchange);
    }
}