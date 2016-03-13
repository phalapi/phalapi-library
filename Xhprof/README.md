#PhalApi-Xhprof -- Facebook开源的轻量级PHP性能分析工具

![](http://webtools.qiniudn.com/master-LOGO-20150410_50.jpg)

##前言

日常大家都会选择文件服务器,阿里云的OSS当然是个不错的选择,可以存放大量的图片以及压缩文件等,还可以开启cdn加速,但是使用起来并不是那么的舒服,所以对OSS进行了封装希望大家喜欢!

附上:

官网地址:[http://www.phalapi.net/](http://www.phalapi.net/ "PhalApi官网")

开源中国Git地址:[http://git.oschina.net/dogstar/PhalApi/tree/release](http://git.oschina.net/dogstar/PhalApi/tree/release "开源中国Git地址")

开源中国拓展Git地址:[http://git.oschina.net/dogstar/PhalApi-Library](http://git.oschina.net/dogstar/PhalApi-Library "开源中国Git地址")


##1. 安装

首先需要安装配置Xhprof

    wget http://pecl.php.net/get/xhprof-0.9.2.tgz
    
    tar zxf xhprof-0.9.2.tgz
    
    cd xhprof-0.9.2/extension/
    
    sudo phpize
    ./configure --with-php-config=/usr/local/php/bin/php-config
    sudo make
    sudo make install
    
    需要在php.ini中配置好
    
[xhprof]
    extension=xhprof.so;
    ; directory used by default implementation of the iXHProfRuns
    ; interface (namely, the XHProfRuns_Default class) for storing
    ; XHProf runs.
    ;
    ;xhprof.output_dir=<directory_for_storing_xhprof_runs>
    xhprof.output_dir=/tmp/xhprof
    
通过phpinfo()看到xhprof扩展则为安装成功
    
**注意:xhprof.output_dir=/tmp/xhprof,设置必须统一不然需要自行替换编译出来html的问题到拓展项目中**

然后对我们的index.php文件做如此的改造
    
在头部加上:

    if (!empty($_GET['__debug__'])) {
        xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
    }
    
    
在尾部加上:
    if (!empty($_GET['__debug__'])) {
    
        $data = xhprof_disable();
    
        include API_ROOT . "/Library/Xhprof/utils/xhprof_lib.php";
        include API_ROOT . "/Library/Xhprof/utils/xhprof_runs.php";
        $objXhprofRun = new XHProfRuns_Default();//数据会保存在php.ini中xhprof.output_dir设置的目录去中
        echo $objXhprofRun->save_run($data, "developers");
    }
    
这个时候我们访问的时候带入请求参数__debug__可以获得如下返回

![](http://i.imgur.com/r0h7YTu.png)

然后我们访问http://xxxx/Library/Xhprof/index.php可以的到如下界面

![](http://i.imgur.com/a48fUSz.png)

我们可以看到有一个key和上面生成的一样的我们点击进去:

![](http://i.imgur.com/VMseHtJ.png)

![](http://i.imgur.com/xj27xFI.png)

##2. 总结

希望此拓展能够给大家带来方便以及实用,此扩展可以分析出在整个运行途中的消耗用时可以针对进行优化,在压力情况下可以非常好的辨别出慢代码出现在哪里!

注:笔者能力有限有说的不对的地方希望大家能够指出,也希望多多交流!

**官网QQ交流群:421032344  欢迎大家的加入!**