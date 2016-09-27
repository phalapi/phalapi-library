<?php
/**
 * See-kafka Consumer例子1
 */

// 配置KafKa集群(默认端口9092)通过逗号分隔
$KafKa_Lite = new KafKa_Lite("127.0.0.1,localhost");
// 设置一个Topic
$KafKa_Lite->setTopic("test");
// 设置Consumer的Group分组(不使用自动offset的时候可以不设置)
$KafKa_Lite->setGroup("test");
// 获取Consumer实例
$consumer = $KafKa_Lite->newConsumer();

// 获取一组消息参数分别为:Partition,maxsize最大返回条数,offset(可选)默认KAFKA_OFFSET_STORED
$rs = $consumer->getMassage(0,100);
//返回结果是一个数组,数组元素类型为Kafka_Message
