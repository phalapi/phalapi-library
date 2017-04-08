# PhalApi-RabbitMQ基于PhalApi专业队列拓展

![](http://webtools.qiniudn.com/master-LOGO-20150410_50.jpg)

## 前言

RabbitMQ一直都是队列中的标杆,这次有幸PhalApi也能啃上RabbitMQ真是一件大好事,感谢**@牧鱼人**提供基于php-amqplib/php-amqplib封装的PhalApi-RabbitMQ扩展

**关于RabbitMQ相关的安装集群配置可以参考笔者博客的MQ模块**

附上:

官网地址:[http://www.phalapi.net/](http://www.phalapi.net/ "PhalApi官网")

开源中国Git地址:[http://git.oschina.net/dogstar/PhalApi/tree/release](http://git.oschina.net/dogstar/PhalApi/tree/release "开源中国Git地址")

开源中国拓展Git地址:[http://git.oschina.net/dogstar/PhalApi-Library](http://git.oschina.net/dogstar/PhalApi-Library "开源中国Git地址")


## 1.安装

使用PhalApi-RabbitMQ扩展和使用其他扩展也是一样简单,只需要把目录存放到Library即可进行使用

在Config中创建文件rabbitmq.php配置文件格式如下:

```
return array(
    'servers' => array(
        'host'     => '127.0.0.1',
        'port'     => '5672',
        'user'     => 'admin',
        'password' => 'admin',
        'vhost'    => '/',
    )
)
```

## 2.使用RabbitMQ写入和处理消息

然后就可以进行实例化使用了:

```
// 实例化RabbitMQ实例
$rm = RabbitMQ_Lite(DI()->config->get('rabbitmq.servers'));

// 检查test队列是否存在,如果不存在则创建,频繁调用会带来较大性能消耗
// 建议在出队列脚本处进行调用,写入队列不进行调用
$rm->queue_declare("test");

// 向队列写入一条消息
$rm->push("测试消息","test");

// 定义处理消息的方法
$func = function ($msg){
            echo $msg;
        };
// 处理任务(会阻塞进行)
$rm->pop("test",$func);

```


注:笔者能力有限有说的不对的地方希望大家能够指出,也希望多多交流!

**官网QQ交流群:①群:421032344 ②群:459352221 欢迎大家的加入!**