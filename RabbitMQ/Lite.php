<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;



class RabbitMQ_Lite{

    private  $connection;
    private  $channel;

    /**
     * RabbitMQ_Lite constructor.
     * 建立连接
     * @param $config
     */
    function __construct(array $config) {
        $this->connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password'], $config['vhost']);
        $this->channel = $this->connection->channel();

    }

    /**
     * 消息入队列
     * @param mixed  $msg 消息
     * @param string $queue 队列名称
     */
    function push($msg, $queue){
        $msg = serialize($msg);
        $message = new AMQPMessage($msg, array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $this->channel->basic_publish($message, '', $queue);
    }

    /**
     * 消息出队列
     * @param string $queue 队列名称
     * @param        $callback 处理消息的回调函数
     */
    function pop($queue, $callback){
        $this->channel->basic_qos(null, 1, null);
        $this->channel->basic_consume($queue, '', false, false, false, false, $callback);

        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    /**
     * 声明队列，不存在会去创建
     */
    public function queue_declare($queue){
        $this->channel->queue_declare($queue, false, true, false, false);
    }

    /**
     * 关闭连接
     */
    function close(){
        $this->channel->close();
        $this->connection->close();
    }
}

