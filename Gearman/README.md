_本扩展收录自[AxiosCros/PhalApi-Gearman](https://github.com/AxiosCros/PhalApi-Gearman)_
  

# 基于Phalapi框架的gearman扩展(异步并发)

## 描述
> 提到异步并发，phper往往会想到swoole，但是其仅支持cli模式的特性，以及比较难读的文档，却总是令人却步。此时，使用gearman也许是个不错的选择。

## gearman使用场景
> gearman是异步工具，当后端在处理一些用时较长且不需即时回调的请求的时候，异步IO可以有效提高响应速度。比如:后台发送邮件、发送短信验证码、存储行为日志等等。
而且，gearman支持非cli模式，这意味着基于TCP协议的接口调用也可以进行异步请求了。

## gearman安装
> 可以参考这篇博客《[CenOS7环境安装PHP7扩展](http://hanxv.cn/index.php/archives/25.html#gearman)》

### （以下操作均在命令行模式下运行）
  * 安装german
  > yum install -y gearman-server gearmand

  * 安装必备的依赖
  > yum install -y php-devel php-pear httpd-devel libgearman libgearman-devel

  * 下载gearman-php扩展
  > cd /usr/src ; git clone https://github.com/wcgallego/pecl-gearman.git;

  * 编译安装扩展 ( phpize路径视php环境安装时具体设置而定，可参考《[Centos7安装nginx+php7运行环境](http://hanxv.cn/index.php/archives/19.html)》)
  > cd pecl-gearman/ ; /usr/local/php7/bin/phpize ;./configure --with-php-config=/usr/local/php7/bin/php-config;make; make install;

  * 修改php.ini
  > vim /usr/local/php7/lib/php.ini

  * 在END前加上 extension=gearman.so;

  * 重启php-fpm
  > service php-fpm restart

  * 启动gearman
  > gearmand -d
  
## 正式使用前的准备
  * 从github下载，地址: [https://github.com/AxiosCros/PhalApi-Gearman](https://github.com/AxiosCros/PhalApi-Gearman)
  * 将Gearman目录复制到phalapi框架中的Library目录中
  * 根据项目情况修改Gearman/gearman-server.php文件(同phalapi的入口文件)
  * 修改Phalapi框架的应用配置文件Config/app.php，添加如下配置

  ``` shell
   'gearman'=>array(
          "servers"=>"127.0.0.1:4730",
          "task"=>array(
              'testTask'         =>  "Test.task",
          )
      ),
  ```

  * 运行Gearman/run.sh
  > sh run.sh 1     #运行后会在Library/Gearman/目录下生成一个nohup.out文件，也就是gearman运行时的输出文件。最后的参数 1 为生成1个worker

## 基于PhalApi的使用
  * 入口注册gearman服务
  > DI()->gearman = new Gearman_Lite(DI()->config->get('app.gearman'));

  * 调用gearman扩展的task方法,如: 
  > DI()->gearman->task('default.index',$data); //其中default.index是要执行task任务的接口，$data是要传入的参数

  * 接收gearman异步请求中的参数时，可以使用phalapi的 DI()->request->getAll();方法
  
## 测试实例
  * 部署PhalApi-example并运行gearman,(参考#正式使用前的准备)
  * 打开一个终端窗口(A)，观察gearman输出文件，

  ``` shell
    tail -f Library/Gearman/nohup.out
  ```

  * 另外再打开一个终端窗口(B)，查看worker状态
  
  ``` shell
  watch -n 1 "(echo status; sleep 0.1) | nc 127.0.0.1 4730"
  ```
  
  * 访问Index.index接口

  * 然后观察，最开始在B终端会看到有job正在执行，然后在A终端中会出现gearman的输出结果
