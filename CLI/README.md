##1、 CLI扩展类库
此类库可用于开发命令行应用，基于GetOpt，主要作用是将命令参数进行解析和处理。  
  
##2、安装
从  [PhalApi-Library](http://git.oschina.net/dogstar/PhalApi-Library)  扩展库中下载获取 **CLI** 扩展包，如使用：
```javascript
git clone https://git.oschina.net/dogstar/PhalApi-Library.git
```
  
然后把 **CLI** 目录复制到 **./PhalApi/Library/** 下，即：
```javascript
cp ./PhalApi-Library/CLI/ ./PhalApi/Library/ -R
```
  
到此安装完毕！不需要配置。  

##3、编写命令行入口文件
参考原来的入口文件index.php，编写以下的CLI入口文件，保存到：./Public/demo/cli 文件：  
```
#!/usr/bin/env php
<?php
require_once dirname(__FILE__) . '/../init.php';

//装载你的接口
DI()->loader->addDirs('Demo');

$cli = new CLI_Lite();
$cli->response();
```
  
##4、运行和使用
###(1) 正常运行
默认接口服务使用```service```名称，缩写为```s```，如运行命令：  
```
$ ./cli -s Default.Index --username dogstar
{"ret":200,"data":{"title":"Default Api","content":"dogstar\u60a8\u597d\uff0c\u6b22\u8fce\u4f7f\u7528PhalApi\uff01","version":"1.3.5","time":1486291429},"msg":""}
```
  
###(2) 获取帮助
指定接口服务service后，即可使用 --help 参数以查看接口帮助信息，如：  
```
$ ./cli -s User.GetBaseInfo --help
Usage: ./cli [options] [operands]
Options:
  -s, --service <arg>     接口服务
  --help                  
  --user_id <arg>         用户ID
```

###(3) 异常情况
异常时，将显示异常错误提示信息，以及帮助信息。
  
##5、接口开发
接口开发与原来保持不变。注意：目前此扩展还在开发当中，尚未正式使用，有问题欢迎反馈、一起完善，谢谢！