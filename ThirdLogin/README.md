#基于PhalApi的第三方登录

首先感谢THINKPHP@麦当苗儿，我只是将别人写好的进行修改移植过来而已...

### 1.安装和配置

#### 1.1 扩展包下载
```
//从 PhalApi-Library 扩展库中下载获取 ThirdLogin 扩展包，如使用：
git clone https://git.oschina.net/dogstar/PhalApi-Library.git

//然后把 ThirdLogin 目录下的文件包括文件夹复制到程序根目录下覆盖（如果存在相同文件请先备份，Config不覆盖）

//到此处安装完毕！
```

### 2.入门使用
#### 2.1 入口注册
```
//打开init.php

//系统自带的代码
DI()->loader->addDirs('Demo');

//其他代码...

//开始注册第三方登录
DI()->login = new Login_Lite();
```
其他就不写了。。。代码中都有注释，如果有不懂的同学可以在群中AT(@)SteveAK

###3.总结

在此希望本扩展能给大家带来解决实际问题的思路，如果出现问题或者是有BUG可以直接联系我**QQ7579476**也可加入PhalApi交流群一同交流探讨

注:笔者能力有限有说的不对的地方希望大家能够指出,也希望多多交流!

**官网QQ交流群:421032344  欢迎大家的加入!**
