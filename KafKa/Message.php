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
 * KafKa-Message类
 * @author : @喵了个咪<wenzhenxi@vip.qq.com>
 */
class Kafka_Message
{
    /**
     * @var int
     */
    public $err;

    /**
     * @var string
     */
    public $topic_name;

    /**
     * @var int
     */
    public $partition;

    /**
     * @var string
     */
    public $payload;

    /**
     * @var string
     */
    public $key;

    /**
     * @var int
     */
    public $offset;

    /**
     * @return string
     */
    public function errstr()
    {
    }
}
