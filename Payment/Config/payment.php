<?php
/**
 * Created by PhpStorm.
 * User: qiu han
 * Date: 2016/11/21
 * Time: 19:42
 *
 * 请将本文件复制到根目录下的Config目录下,并将配置项补充齐全
 */

return array(
    //微信配置文件
    'wechat' => array(
        //证书目录
        'sslPath'   => '',
        'type'      => 'wechat',
        'appId'     => '',
        'appSecret' => '',
        'mchId'     => 0,
        //证书密钥
        'key'       => '',
        //回掉地址
        'notifyUrl' => '',
        //证书名称，不带_cert.pem和_key.pem，比如证书名称为wechat_apiclient_cert.pem，则这里填wechat_apiclient
        'sslName'   => '',
    ),
);