<?php
/**
 * 请在下面放置任何您需要的应用配置
 */

return array(

    /**
     * 支付相关配置
     */
    'Pay' => array(
        //异步/同步地址
        'notify_url' => 'http://www.xxx.com/PhalApi/Public/pay/',

        //支付宝wap端设置
        'aliwap' => array( 
            //收款账号邮箱
            'email' => 'admin@admin.com', 

            //加密key
            'key' => 'xxx', 

            //合作者ID
            'partner' => '123456' 
        ),

        //微信支付设置
        'wechat' => array(
            //公众号的唯一标识
            'appid' => 'xxx',

            //商户号
            'mchid' => '123456',

            //公众号的appsecret
            'appsecret' => 'xxx',

            //微信支付Key
            'key' => 'xxx'
        ),
    ),
);
