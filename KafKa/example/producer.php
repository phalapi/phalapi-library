<?php
/**
 * See-kafka Producer例子
 * 循环写入1w条数据15毫秒
 */

// 配置KafKa集群(默认端口9092)通过逗号分隔
$KafKa_Lite = new KafKa_Lite("127.0.0.1,localhost");
// 设置一个Topic
$KafKa_Lite->setTopic("test");
// 单次写入效率ok  写入1w条15 毫秒
$Producer = $KafKa_Lite->newProducer();
// 参数分别是partition,消息内容,消息key(可选)
// partition:可以设置为KAFKA_PARTITION_UA会自动分配,比如有6个分区写入时会随机选择Partition
$Producer->setMessage(0, "hello");

