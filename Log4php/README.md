##扩展类库：基于log4php的日志扩展
此扩展兼容PhalApi日志的接口操作，同时基于log4php完成更多出色的日志工作。
  
需要注意的是：  
 + 日志级别转交由log4php控制（即PhalApi本身是全开的） 
 + 更多配置内容以及配置文件的类型可参考log4php官网（但为了简单统一，这里使用了xml格式）
  
##安装和配置
###(1)安装
从  [PhalApi-Library](http://git.oschina.net/dogstar/PhalApi-Library)  扩展库中下载获取 **Log4php** 包，如使用：
```javascript
git clone https://git.oschina.net/dogstar/PhalApi-Library.git
```
 
然后把 **Log4php** 目录复制到 **./PhalApi/Library/** 下，即：
```javascript
cp ./PhalApi-Library/Log4php ./PhalApi/Library/ -R
```
到此安装完毕！接下是插件的配置。

###(2)配置
需要把本扩展中的 **./Config/log4php.xml** 配置文件复制到项目的配置目录 **./Config/** 下，并根据需要相应调整。
```javascript
<?xml version="1.0" encoding="UTF-8"?>

<log4php:configuration xmlns:log4php="http://logging.apache.org/log4php/" threshold="all">    
    <appender name="default" class="LoggerAppenderFile">
        <param name="file" value="/tmp/myLog.log" /> <!-- 请修改日志文件路径 -->
        <layout class="LoggerLayoutPattern">
            <param name="conversionPattern" value="%date{Y-m-d H:i:s}|%level|%message%newline" />
        </layout>
    </appender>

    <root>
        <level value="TRACE" /> <!-- 请修改日志级别：TRACE < DEBUG < INFO < WARN < ERROR < FATAL -->
        <appender_ref ref="default" />
    </root>
</log4php:configuration>
```
更多log4php的配置说明，请参考[Apache log4php - Configuration - Apache log4php](http://logging.apache.org/log4php/docs/configuration.html) 。  

##使用
###(1)初始化注册
在 ./Public/init.php 文件中，将原来的DI()->logger服务重新注册为此扩展实例，如下：
```javascript
//日记纪录
//DI()->logger = new PhalApi_Logger_File(API_ROOT . '/Runtime', PhalApi_Logger::LOG_LEVEL_DEBUG | PhalApi_Logger::LOG_LEVEL_INFO | PhalApi_Logger::LOG_LEVEL_ERROR);

DI()->logger = new Log4php_Lite();
```
###(2)写日志
如这样的示例代码：  
```
DI()->logger->debug('这是调试信息');
DI()->logger->info('这是业务信息，带更多场景信息', array('name' => 'dogstar'));
DI()->logger->error('这是错误信息');

DI()->logger->trace('log4php trace here ...');
DI()->logger->warn('log4php warn here ...');
DI()->logger->fatal('log4php fatal here ...');
```
可以看到这样的输出（基本上和PhalApi原来自带的效果一样，但多支持了trace/warn/fatal）：  
```
2016-04-03 08:46:39|DEBUG|这是调试信息
2016-04-03 08:46:39|INFO|这是业务信息，带更多场景信息|{"name":"dogstar"}
2016-04-03 08:46:39|ERROR|这是错误信息
2016-04-03 08:46:39|TRACE|log4php trace here ...
2016-04-03 08:46:39|WARN|log4php warn here ...
2016-04-03 08:46:39|FATAL|log4php fatal here ...
```