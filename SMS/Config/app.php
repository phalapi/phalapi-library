<?php
/**
 * 请在下面放置任何您需要的应用配置
 */

return array(
    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(//'sign' => array('name' => 'sign', 'require' => true),
    ),

    "SMSService" => array(
        "accountSid"   => "",  //主帐号
        "accountToken" => "",  //主帐号Token
        "appId"        => "",  //应用Id
        "serverPort"   => "",  //请求端口 默认:8883
        "serverIP"     => ""   //请求地址不需要写https:// 默认:sandboxapp.cloopen.com
    )

);
