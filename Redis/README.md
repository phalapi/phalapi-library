#基于PhalApi的Redis_Base拓展

    //引入集群拓展拓展库
    DI()->loader->addDirs('Library/Redis');
    //redis链接
    DI()->redis = new Redis_Base(DI()->config->get('rds.servers'));
    
    //使用方式如下
    
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
    