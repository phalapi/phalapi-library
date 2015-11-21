<?php
/**
 * Redis缓存配置
 * @author: 喵了个咪 <wenzhenxi@vip.qq.com> 2015-11-15
 */

return array(
    //Redis配置项
    'redis' => array(
        //Redis缓存配置项
        'servers'  => array(
            'host'   => '127.0.0.1',        //Redis服务器地址
            'port'   => '6379',             //Redis端口号
            'prefix' => 'developers_',      //Redis-key前缀
            'auth'   => 'woyouwaimai76',    //Redis链接密码
        ),
        // Redis分库对应关系
        'DB'       => array(
            'developers' => 1,
            'user'       => 2,
            'code'       => 3,
        ),
        //使用阻塞式读取队列时的等待时间单位/秒
        'blocking' => 5,
    ),

);
