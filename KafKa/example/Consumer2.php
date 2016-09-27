<?php
/**
 * See-kafka Consumer例子1
 * 889 毫秒 获取1w条
 */

// 配置KafKa集群(默认端口9092)通过逗号分隔
$KafKa_Lite = new KafKa_Lite("127.0.0.1,localhost");
// 设置一个Topic
$KafKa_Lite->setTopic("test");
// 设置Consumer的Group分组(不使用自动offset的时候可以不设置)
$KafKa_Lite->setGroup("test");

// 此项设置决定 在使用一个新的group时  是从 最小的一个开始 还是从最大的一个开始  默认是最大的(或尾部)
$KafKa_Lite->setTopicConf('auto.offset.reset', 'smallest');
// 此项配置决定在获取数据后回自动作为一家消费 成功 无需在 一定要 stop之后才会 提交 但是也是有限制的
// 时间越小提交的时间越快,时间越大提交的间隔也就越大 当获取一条数据之后就抛出异常时 更具获取之后的时间来计算是否算作处理完成
// 时间小于这个时间时抛出异常 则不会更新offset 如果大于这个时间则会直接更新offset 建议设置为 100~1000之间
$KafKa_Lite->setTopicConf('auto.commit.interval.ms', 1000);

// 获取Consumer实例
$consumer = $KafKa_Lite->newConsumer();

// 开启Consumer获取,参数分别为partition(默认:0),offset(默认:KAFKA_OFFSET_STORED)
$consumer->consumerStart(0);

for ($i = 0; $i < 100; $i++) {
    // 当获取不到数据时会阻塞默认10秒可以通过$consumer->setTimeout()进行设置
    // 阻塞后由数据能够获取会立即返回,超过10秒回返回null,正常返回格式为Kafka_Message
    $message = $consumer->consume();
}

// 关闭Consumer(不关闭程序不会停止)
$consumer->consumerStop();
