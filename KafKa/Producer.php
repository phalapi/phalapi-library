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
 * KafKa-Producer类
 * @author : @喵了个咪<wenzhenxi@vip.qq.com>
 */
class KafKa_Producer {

    protected $topic = null;

    /**
     * 初始化Producer
     *
     * @param $BrokerList
     * @param $KafKaConf
     * @param $TopicConf
     * @param $Topic
     */
    public function __construct($BrokerList, $KafKaConf, $TopicConf, $Topic) {
        $rk = new RdKafka\Producer($KafKaConf);
        $rk->addBrokers($BrokerList);
        $this->topic = $rk->newTopic($Topic,$TopicConf);
    }

    /**
     * 写入一条massage
     *
     * @param      $partition
     * @param      $value
     * @param null $key
     */
    public function setMessage($partition, $value, $key = null) {
        $this->topic->produce($partition, 0, $value, $key);
    }

}