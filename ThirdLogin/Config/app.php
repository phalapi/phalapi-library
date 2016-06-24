<?php
return array(
	//第三方登录
    'Login' => array(
        'callback' => 'http://你的接口地址/login/', //该地址为Public下的login目录
        'qq' => array(
            'APP_KEY' => '*****',
            'APP_SECRET' => '******',
        ),
        //微信可以配置在下面
        'wechat' => array(),
    ),
);