#图灵机器人

![](http://www.tuling123.com/resources/web/v4/img/index/by.png)

##前言
简单的图灵机器人数据交互分享大家，希望给小伙伴们提供新的接入思路!



  
##安装与使用

配置方式非常简单只需要把Tuling123放入Library文件内，然后在app.php添加[图灵APIKEY](http://www.tuling123.com/member/robot/index.jhtml)配置即可,然后就可以使用如下方法进行实例

    //图灵APIKEY
    'Tuling123' => array(
        'key' => 'xxx',
    )
	//初始化
	DI()->tuling123=new Tuling123_Lite();
	