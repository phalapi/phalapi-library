#基于PhalApi的Smarty拓展

![](http://webtools.qiniudn.com/master-LOGO-20150410_50.jpg)

##前言##
***先在这里感谢phalapi框架创始人@dogstar,为我们提供了这样一个优秀的开源框架.***

用过的童鞋都知道PhalApi是一个Api框架不提供view层的功能,但是很多童鞋有开发一个自己管理自己API的web界面的需求,或者是个人后台,那么是否意味着要去在学习另外一种框架来实现呢?**当然不是**在之前也有童鞋放出过一个View拓展,使用之后还是有一些不方便的地方,所以引入一个比较老牌的PHP模版引擎**Smarty**来解决这类问题,本拓展提供了对Smarty的封装,而且Smarty内容比较多在此处不会依依交与大家使用,希望的童鞋可以自己探索关于Smarty的功能,有不便之处需要封装与之联系!

**注:本拓展并没有开发完成,也没进行严格的测试,此版本为还处于开发阶段的鉴赏版.**

附上:

官网地址:[http://www.phalapi.net/](http://www.phalapi.net/ "PhalApi官网")

开源中国Git地址:[http://git.oschina.net/dogstar/PhalApi/tree/release](http://git.oschina.net/dogstar/PhalApi/tree/release "开源中国Git地址")

PhalApi Library:[http://git.oschina.net/dogstar/PhalApi-Library](http://git.oschina.net/dogstar/PhalApi-Library "PhalApi Library")

##初始化Smarty

PhalApi-Smarty的初始化也和其他拓展一样,我们只需要把上方**PhalApi Library**中的Smarty文件目录放到需要用到的项目的拓展中即可.

但是view拓展和其他拓展有一些本质的区别就是需要有存放view页面的地方,这里使用一个干净的PhalApi项目进行演示,我们在public下创建如下结构

![](http://i.imgur.com/rTNjNgC.png)

然后我们在init末尾中加入如下代码:
	
	//接受一个参数,参数为view的路径
	DI()->smarty = new Smarty_Lite('view');

现在我们就已经初始化好了PhalApi-Smarty

##一个简单的例子

我们在Default.Index接口中做如下修改:

	public function index() {

        $param = array(
            'name' => '喵咪',
            'list' => array(
                array(
                    "id"   => 1,
                    "name" => "test"
                ),
                array(
                    "id"   => 2,
                    "name" => "test2"
                )
            )
        );
        DI()->smarty->setParams($param);
        DI()->smarty->show();
    }

同时修改index.tpl:

	<HTML>
	<HEAD>
	    <style type="text/css">
	        p,table{
	            margin: auto;
	            width: 60%;
	        }
	    </style>
	</HEAD>
	<BODY>
	Hello {$name}, welcome to smarty<br/>
	
	<table border="1">
	    {section name = sec loop = $list}
	        <tr>
	            <td>{$list[sec].id}</td>
	            <td>{$list[sec].name}</td>
	        </tr>
	    {/section}
	</table>
	
	</BODY>
	</HTML>

此时我们再次运行Default.Index接口就有如下显示:

![](http://i.imgur.com/rlIjGI2.png)

setParams函数作为参数的媒介把接口中获取的参数放到模版里面进行处理,接受一个数组具体实现是对每一个参数进行**assign**操作,具体可以参考Smarty

我们在show默认不传递参数是,会更具模块名和接口名来匹配对于的模版,比如Default.Index就会匹配到view/Default/Index.tpl,当然我们也可以指定跳转到摸个模版,比如创建一个模版名称为test.tpl,然后创建一个Default.test接口,我们在index接口进行一些修改
	
	DI()->smarty->show("Default.test");

这个时候我们访问Default.Index接口的时候就会先执行Default.Index的代码然后在执行,test方法的代码最好渲染Default中的test.tpl模版

**注意:show跳转其他模块接口会执行跳转的接口,如果有参数验证会被拦截,所以使用场景比较适合处理用户登录过时跳转登录页面重新登录这类业务**

##其他

如果大家在使用IDE开发的时候嫌DI->smarty没有提示的话可以在如下目录加入此注释

	\PhalApi\PhalApi\DI.php

![](http://i.imgur.com/anwqdWh.png)

这样就可以看到如下效果

![](http://i.imgur.com/sGwfd3h.png)

##总结

当前只是提供了一个简单的封装还有很多需要优化封装的功能其他各位小伙伴的补充.
