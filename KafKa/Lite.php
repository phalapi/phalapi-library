<?php
// Copyright 2016 The See-KafKa Authors. All rights reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License"): you may
// not use this file except in compliance with the License. You may obtain
// a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
// WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
// License for the specific language governing permissions and limitations
// under the License.

/**
 * KafKa拓展类
 * @author : @喵了个咪<wenzhenxi@vip.qq.com>
 * 依赖PHP-C拓展:https://github.com/arnaud-lb/php-rdkafka
 * 依赖服务底层:https://github.com/edenhill/librdkafka
 * setKafkaConf和setTopicConf配置说明地址:
 * https://github.com/edenhill/librdkafka/blob/master/CONFIGURATION.md
 */

// KafKa分区自动随机分配
const KAFKA_PARTITION_UA = RD_KAFKA_PARTITION_UA;

class KafKa_Lite {

    // KafKaHostList
    protected $BrokerList = array();

    // KafKa-Topci名称
    protected $Topic = null;

    // KafKa-Partition
    protected $Partition = 0;

    protected $KafKaConf = array();

    protected $TopicConf = array();

    /**
     * 初始化KafKa
     *
     * @param $HostList
     */
    public function __construct($HostList) {
        //验证BrokerList
        $this->BrokerList = $HostList;
        $this->ping();
    }

    /**
     * 检测KafKa是否可用
     */
    public function ping() {
        $errTCP = 0;
        $List = explode(',', $this->BrokerList);
        foreach ($List as $v) {
            $IP = explode(':', $v);
            if (!$this->checkTCP($IP[0], isset($IP[1]) ? $IP[1] : 9092)) {
                $errTCP++;
            }
        }
        if ((count($List) - $errTCP) == 0){
            throw new KafKa_Exception_Base("No can use KafKa");
        }
    }

    /**
     * 初始化生产者
     * @return KafKa_Producer
     */
    public function newProducer() {
        return new KafKa_Producer($this->BrokerList, $this->getKafKaConf(), $this->getTopicConf(), $this->Topic);
    }

    /**
     * 初始化消费者
     * @return KafKa_Consumer
     */
    public function newConsumer() {
        return new KafKa_Consumer($this->BrokerList, $this->getKafKaConf(), $this->getTopicConf(), $this->Topic);
    }

    /**
     * 设置Topic
     *
     * @param $TopicName
     */
    public function setTopic($TopicName) {
        $this->Topic = $TopicName;
    }

    /**
     * 设置Group - 不配置则无Group
     *
     * @param $GroupName
     */
    public function setGroup($GroupName) {

        $this->KafKaConf["group.id"] = $GroupName;
    }

    /**
     * 设置librdKafKa配置文件
     *
     * @param $key
     * @param $value
     */
    public function setKafkaConf($key, $value) {
        $this->KafKaConf[$key] = $value;
    }

    /**
     * 设置librdKafKa配置文件
     *
     * @param $key
     * @param $value
     */
    public function setTopicConf($key, $value) {
        $this->TopicConf[$key] = $value;
    }

    /**
     * 处理配置文件获取Topic实例
     * @return \RdKafka\TopicConf
     */
    private function getTopicConf() {
        $conf = new RdKafka\TopicConf();
        if ($this->TopicConf) {
            foreach ($this->TopicConf as $k => $v) {
                $conf->set($k, $v);
            }
        }

        return $conf;
    }

    /**
     * 处理配置文件获取KafKaConf实例
     * @return \RdKafka\Conf
     */
    private function getKafKaConf() {

        $conf = new RdKafka\Conf();
        if ($this->KafKaConf) {

            foreach ($this->KafKaConf as $k => $v) {
                $conf->set($k, $v);
            }
        }
        return $conf;
    }

    /**
     * 对KafKa进行连通性测试
     */
    private function checkTCP($ip, $port) {

        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_nonblock($sock);
        socket_connect($sock, $ip, $port);
        socket_set_block($sock);
        switch (socket_select($r = array($sock), $w = array($sock), $f = array($sock), 5)) {
            case 1:
                socket_close($sock);
                return true;
            default:
                socket_close($sock);
                return false;
        }
    }

}
