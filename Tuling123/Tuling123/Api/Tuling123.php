<?php
/**
 * 图灵机器人
 *
 * @author: andy 2017-11-25
 */
class Api_Tuling123 extends PhalApi_Api
{


    public function getRules() {
        return array(
            'send' => array(
                'info' => array('name' => 'info', 'require' => true, 'default' => '')
            )
        );
    }

    /**
     * 发送信息到图灵
     * @desc 图灵机器人数据交互
     * @return int code 图灵机器人返回码
     * @return object data 图灵机器人反馈信息
     */
    public function send()
    {
        return DI()->tuling123->send($this->info);
    }
}