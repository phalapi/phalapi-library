_本扩展类库转自[AxiosCros/PhalApi--Workman](https://github.com/AxiosCros/PhalApi--Workman)_  

## 前言
> 本扩展旨在让socket编程，像用PhalApi开发接口一样简单，让开发者只需专注于业务逻辑，创建相应的Server类并编写action方法即可。此外，Server层还可以和其它接口共用Domain层和Model层，实现功能上的复用。

## 准备工作
### 基本运行环境
> CentOS7、php7

### workman运行所需扩展
``` shell
# 检查环境
curl -Ss http://www.workerman.net/check.php | php
```
如果不全是ok的话，可以参考我的这篇博客安装php7运行环境
[Centos7安装nginx+php7运行环境](http://hanxv.cn/index.php/archives/19.html)

### 安装PhalApi-Workman扩展
* 从github上下载[PhalApi-Workman扩展](https://github.com/AxiosCros/PhalApi--Workman.git),
* 拷贝Workman文件夹至PhalApi的Library目录下
* 拷贝start_workman.php文件至PhalApi的根目录，与Demo同级
* 拷贝Server文件夹至接口项目目录中，与Domain等同级(其中Common.php与Container.php为必备文件)

### 设置配置文件
> 打开Config下的app.php配置文件
``` php
//添加以下内容
'workman'=>array(
        'app_name'      => "my_app",                  // 项目应用名称
        'socket_host'   => "tcp://0.0.0.0:1212",      // socket连接端口地址
        'service_port'  => "1238",                    // 服务注册端口
        'lan_ip'        => "127.0.0.1",               // 本机ip，分布式部署时使用通信ip
        'process_count' => 4,                         // 进程数
        'start_port'    => "2900",                    // 内部通讯起始端口
        'heartbeat'     => 10,                        // 心跳间隔时间，单位秒
        'heartbeat_data'=> '{"type":"ping"}',        // 心跳数据
        'default_server'=> "Index",                  // 客户端连接时或消息中没有server参数时，默认的消息处理类
        'default_action'=> "index",                  // 客户端连接时或消息中没有action参数时，默认的消息处理方法
    )
```

### 编写入口文件
> 在Public目录下创建socket.php文件，代码内容如下

``` php
<?php
require_once dirname(__FILE__) . '/init.php';
//装载你的接口
DI()->loader->addDirs('Demo');
```


## 使用教程
### 启动或停止workman
``` shell
#以debug（调试）方式启动
php start_workman.php start

#以daemon（守护进程方式启动
php start_workman.php start -d

#以nohup方式启动
nohup php start_workman.php start -d

#停止
php start_workman.php stop

#重启
php start_workman.php restart

#平滑重启
php start_workman.php reload

#查看状态
php start_workman.php status

#强行杀死所有workerman进程（要求workerman版本>=3.2.2）
php start_workman.php kill

#nohup启动
nohup [启动命令]
```

 > debug和daemon方式区别
 * 以debug方式启动，代码中echo、var_dump、print等打印函数会直接输出在终端。
 * 以daemon方式启动，代码中echo、var_dump、print等打印会默认重定向到/dev/null文件，可以通过设置Worker::$stdoutFile = '/your/path/file';来设置这个文件路径。
 * 以debug方式启动，终端关闭后workerman会随之关闭并退出。
 * 以daemon方式启动，终端关闭后workerman继续后台正常运行。
 * 具体请参考[workman文档](http://doc3.workerman.net/install/start-and-stop.html)


## 消息发送规则
### 客户端发送消息格式

``` json
 {
  "server": "",
  "action": "",
  "data": {

  },
 }
```
 > 其中server是消息处理类的名称，action是类中方法的名称，data为传送的数据。

### 服务端回调消息格式
 > 消息格式自定义，在方法中回调就可以了

 > 回调数据前，需调用setTarget(设置目标类型，0当前用户，1特定用户id，2多个用户，3全部在线用户),setClient(setTarget为0或3时，不用调用该方法，其余情况需调用该方法设置目标id)