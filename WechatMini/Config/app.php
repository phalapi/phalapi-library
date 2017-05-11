<?php
return array(

    /** 请把下面的配置添加到项目配置./Config/app.php **/

    /**
     * 扩展类库 - 微信小程序
     */
    'WechatMini'  => array(
        'appid'      => '', // AppID(小程序ID)
        'secret'     => '', // AppSecret(小程序密钥)
        'url'        => 'https://api.weixin.qq.com/sns/jscode2session',
        'grant_type' => 'authorization_code'
    ),
);
