#PhalApi-APK -- APK文件解包处理

![](http://webtools.qiniudn.com/master-LOGO-20150410_50.jpg)

##前言

应为笔者这边涉及到的项目有需求对APK进行解包的操作,所以贡献此扩展希望大家喜欢

附上:

官网地址:[http://www.phalapi.net/](http://www.phalapi.net/ "PhalApi官网")

开源中国Git地址:[http://git.oschina.net/dogstar/PhalApi/tree/release](http://git.oschina.net/dogstar/PhalApi/tree/release "开源中国Git地址")

开源中国拓展Git地址:[http://git.oschina.net/dogstar/PhalApi-Library](http://git.oschina.net/dogstar/PhalApi-Library "开源中国Git地址")


##1. 安装使用

此扩展只需要简单的把文件放到Library目录下即可使用使用方法如下:

    $appObj  = new Apk_Lite(); 
    $targetFile = a.apk;//apk所在的路径地址
    
    $res   = $appObj->open($targetFile);
    
    $appObj->getAppName();     // 应用名称
    $appObj->getPackage();    // 应用包名
    $appObj->getVersionName();  // 版本名称
    $appObj->getVersionCode();  // 版本代码


##2. 总结

希望此拓展能够给大家带来方便以及实用,拓展支持绝大部分APK文件处理!

注:笔者能力有限有说的不对的地方希望大家能够指出,也希望多多交流!

**官网QQ交流群:421032344  欢迎大家的加入!**