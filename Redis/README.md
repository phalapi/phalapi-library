#基于PhalApi的Redis拓展

![](http://webtools.qiniudn.com/master-LOGO-20150410_50.jpg)

##前言##
***先在这里感谢phalapi框架创始人@dogstar,为我们提供了这样一个优秀的开源框架.***

编写本次拓展出于的目的是为了解决并不是非常熟悉redis的童鞋能够方便的使用redis进行实际的运用
,对原生的phpredis进行的封装优化良好的注释和例子希望能提供更好的帮助!

**注:本拓展并没有开发完成,也没进行严格的测试,此版本为还处于开发阶段的鉴赏版.**

附上:

官网地址:[http://www.phalapi.net/](http://www.phalapi.net/ "PhalApi官网")

开源中国Git地址:[http://git.oschina.net/dogstar/PhalApi/tree/release](http://git.oschina.net/dogstar/PhalApi/tree/release "开源中国Git地址")


##安装配置redis以及phpredis##

基于centos6.5

        //下redis解压安装
        wget http://download.redis.io/releases/redis-2.8.17.tar.gz
        tar zxvf redis-2.8.17.tar.gz
        cd redis-2.8.17
        make
        make test
        make install
        //生成6379端口以及配置文件
        cd utils
        ./install_server.sh
        Please select the redis port for this instance: [6379]
        Please select the redis config file name [/etc/redis/6379.conf]
        Please select the redis log file name [/var/log/redis_6379.log]
        Please select the data directory for this instance [/var/lib/redis/6379]
        Please select the redis executable path [/usr/local/bin/redis-server]
        //对配置文件进行配置
        vi /etc/redis/6379.conf
        databases 100                            #可以使用的库的数量修改16为100
        masterauth xxxxxxxxxxxxx                 #连接 master 的认证密码
        requirepass woyouwaimai76                #连接此redis的连接密码
        :wq
        //修改关闭redis需要密码
        vi /etc/rc.d/init.d/redis_6379
        $CLIEXEC -p $REDISPORT -a woyouwaimai76 shutdown    #stop redis需要密码
        //重启redis
        service redis_6379 restart
        //添加到系统启动项
        chkconfig redis_6379 on

         //下载phpredis解压安装
         wget https://github.com/nicolasff/phpredis/archive/master.zip
         unzip master.zip -d phpredis
         cd phpredis/phpredis-master
         phpize
         ./configure
         make && make install
         //在php.ini中注册phpredis
         extension = redis.so

         //测试
          <?php
             $auth     = 'xxxxxxxxx';
             $source   = '127.0.0.1';
             $host     = '6379';
             $redis    = new Redis();
             echo $redis->connect($host) ? "$host connect" : "$host fail";
             if($auth){
                 echo $redis->auth($auth) ? " auth success" : " auth fail";
             }


##注册配置文件在Config.app文件下面##
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

##在init入口文件注册redis拓展##

    //redis链接
    DI()->redis = new Redis_Lite(DI()->config->get('app.redis.servers'));

##开始使用##

    //存入永久的键值队
    DI()->redis->set_forever(键名,值,库名);
    //获取永久的键值队
    DI()->redis->get_forever(键名, 库名);
    
    //存入一个有时效性的键值队,默认600秒
    DI()->redis->set_Time(键名,值, 库名,有效时间);
    //获取一个有时效性的键值队
    DI()->redis->get_Time(键名, 库名);
    
    //写入队列左边
    DI()->redis->set_Lpush(队列键名,值, 库名);
    //读取队列右边
    DI()->redis->get_lpop(队列键名, 库名);
    //读取队列右边 如果没有读取到阻塞一定时间(阻塞时间或读取配置文件blocking的值)
    DI()->redis->get_Brpop(队列键名,值, 库名);
    
    //删除一个键值队适用于所有
    DI()->redis->del(键名, 库名);
    //自动增长
    DI()->redis->get_incr(键名, 库名);
    //切换DB并且获得操作实例
    DI()->redis->get_redis(键名, 库名);
    