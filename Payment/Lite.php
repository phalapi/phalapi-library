<?php

/**
 * Created by PhpStorm.
 * User: qiu han
 * Date: 2016/11/18
 * Time: 19:53
 *
 * 说明：此文件为基础接口文件，建议在Service层调用,并自行对异常情况进行日志记录
 * 配置项使用方式(初始化)：
 *      1、只有一个商户号的情况下，可将配置项写到根目录/Config目录下，调用时直接获取配置项传进来。
 *      2、有多个商户号的情况下，可将公用S配置项写到根目录/Config目录下，将各个商户信息写入数据库或者同一个
 *          配置文件下，使用时合并数组传进来即可。
 *
 * 微信--生成订单或退款时，可直接将对应的订单对象或退款单对象转化为数组传进来
 *
 * 注：数据库字段与本文件数组键值不一致时，在此文件中更改相应的static的值即可
 */


/**
 * Class Payment_Lite
 */
class Payment_Lite
{
    /**
     * @param $config
     *
     * @return AliPayment|WechatPayment
     * @throws Exception
     */
    public function getPayment( $config){
        switch ($config['type']){
            case 'wechat':
                require_once 'Lib/WechatPayment.php';
                return new WechatPayment($config);
                break;
            case 'alipay':
                require_once 'Lib/AliPayment.php';
                return new AliPayment($config);
                break;
        }
        throw new Exception('error payment type');
    }
}