##PhalApi框架扩展类库，欢迎大家一起来参与维护！
### -- 致力于与开源项目一起提供企业级的解决方案！
此部分类库为 [PhalAPi框架](http://git.oschina.net/dogstar/PhalApi) 下的扩展类库包，各个扩展包各自独立，可以根据需要自动下载安装。
  
虽然此部分的扩展很多都是基于已有的第三方开源实现，或者开发同学自己实现分享的，但我们所做的不只是代码的搬运工，更多的是在系统架构上的组件库重用，以及在此引入 **防腐层** ，避免进入 **供应商锁定（Vendor Lock-In）** 。  
  
除此之外，可以在PhalApi框架的基础上，快速引入符合我们国内实际项目开发需要的各种扩展。如时，您会发现，原来编程本来就是一件如此简单的事情，就像搭积木一样。  

  
正如我们一直推荐的：
```
接口，从简单开始！
```
  
##目前已提供的扩展类库 - 针对使用者
###1、微信开发
此扩展可用于微信的服务号、订阅号、设备号等功能开发，则PhalApi框架下简单配置即可开发使用。  

 [更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi/wikis/%5B3.1%5D-%E6%89%A9%E5%B1%95%E7%B1%BB%E5%BA%93%EF%BC%9A%E5%BE%AE%E4%BF%A1%E5%BC%80%E5%8F%91)
 
###2、代理模式下phprpc协议的轻松支持
此扩展可用于phprpc协议的调用，服务端只需要简单添加入口即可完美切换。  
  
 [更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi/wikis/%5B3.2%5D-%E6%89%A9%E5%B1%95%E7%B1%BB%E5%BA%93%EF%BC%9A%E4%BB%A3%E7%90%86%E6%A8%A1%E5%BC%8F%E4%B8%8Bphprpc%E5%8D%8F%E8%AE%AE%E7%9A%84%E8%BD%BB%E6%9D%BE%E6%94%AF%E6%8C%81)
   
###3、基于PHPMailer的邮件发送
 此扩展可用于发送邮件。  
 
  [更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi/wikis/%5B3.3%5D-%E6%89%A9%E5%B1%95%E7%B1%BB%E5%BA%93%EF%BC%9A%E5%9F%BA%E4%BA%8EPHPMailer%E7%9A%84%E9%82%AE%E4%BB%B6%E5%8F%91%E9%80%81)  
  
    
###4、优酷开放平台接口调用
此扩展可用于调用优酷开放平台的接口。
  
  [更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi/wikis/%5B3.4%5D-%E6%89%A9%E5%B1%95%E7%B1%BB%E5%BA%93%EF%BC%9A%E4%BC%98%E9%85%B7%E5%BC%80%E6%94%BE%E5%B9%B3%E5%8F%B0%E6%8E%A5%E5%8F%A3%E8%B0%83%E7%94%A8)  
  
    
###5、七牛云存储接口调用
此扩展可以用于将图片上传到七牛CDN，或者其他七牛接口的调用。  
  
  [更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi/wikis/%5B3.5%5D-%E6%89%A9%E5%B1%95%E7%B1%BB%E5%BA%93%EF%BC%9A%E4%B8%83%E7%89%9B%E4%BA%91%E5%AD%98%E5%82%A8%E6%8E%A5%E5%8F%A3%E8%B0%83%E7%94%A8)

###6、用户、会话和第三方登录集成
此类库主要特点有：
 + 1、可以和第三方登录集成，包括：微信登录、新浪登录、QQ登录
 + 2、为客户端提供了直接可以调用的登录接口
 + 3、为服务端提供了直接可以检测用户登录态的操作
 + 4、支持token落地、高效缓存和分布式的数据库存储  
 + 5、展示了如何开发一个项目级的类库、包括数据库配置、翻译等
  
 [更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi/wikis/%5B3.8%5D-%E6%89%A9%E5%B1%95%E7%B1%BB%E5%BA%93%EF%BC%9A%E7%94%A8%E6%88%B7%E3%80%81%E4%BC%9A%E8%AF%9D%E5%92%8C%E7%AC%AC%E4%B8%89%E6%96%B9%E7%99%BB%E5%BD%95%E9%9B%86%E6%88%90)
  
###7、swoole支持下的长链接和异步任务实现
目前，此扩展类库提供了：
 + 长链接的接口调用
 + 异步计划任务的调用
  
 [更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi/wikis/%5B3.9%5D-%E6%89%A9%E5%B1%95%E7%B1%BB%E5%BA%93%EF%BC%9Aswoole%E6%94%AF%E6%8C%81%E4%B8%8B%E7%9A%84%E9%95%BF%E9%93%BE%E6%8E%A5%E5%92%8C%E5%BC%82%E6%AD%A5%E4%BB%BB%E5%8A%A1%E5%AE%9E%E7%8E%B0)

###8、新型计划任务
此扩展类型用于后台计划任务的调度，主要功能点有：
 + 1、提供了Redis/文件/数据库三种MQ队列
 + 2、提供了本地和远程两种调度方式
 + 3、以接口的形式实现计划任务
  
[更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi/wikis/%5B3.6%5D-%E6%89%A9%E5%B1%95%E7%B1%BB%E5%BA%93%EF%BC%9A%E6%96%B0%E5%9E%8B%E8%AE%A1%E5%88%92%E4%BB%BB%E5%8A%A1)

  
###9、Auth 权限扩展 (由@黄苗笋提供)
实现了基于用户与组的权限认证功能，与RBAC权限认证类似，主要用于对服务级别的功能进行权限控制，主要功能点有：
 + 1、提供了接口服务维度的权限验证
 + 2、提供了可配置的组与规则
 + 3、支持免检用户
 
[更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi-Library/wikis/Auth-%E6%9D%83%E9%99%90%E6%89%A9%E5%B1%95%E4%BD%BF%E7%94%A8%E6%96%87%E6%A1%A3)

###11、FastRoute快速路由
此扩展基于 ![FastRoute](https://github.com/nikic/FastRoute) 实现，需要 **PHP 5.4.0** 及以上版本，可以通过配置实现自定义路由配置，从而轻松映射到PhalApi中的service接口服务。，主要有：

 + 1、基于FastRoute实现
 + 2、需要PHP 5.4.0 及以上版本  
 + 3、通过配置文件来实现自定义路由，并映射到service
 + 4、可兼容无路由的历史URI

 [更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi/wikis/%5B3.11%5D-%E6%89%A9%E5%B1%95%E7%B1%BB%E5%BA%93%EF%BC%9A%E5%9F%BA%E4%BA%8EFastRoute%E7%9A%84%E5%BF%AB%E9%80%9F%E8%B7%AF%E7%94%B1)
 
###12、基于PhalApi的DB集群拓展DB_Cluster (由@喵了个咪提供)
为应对海量数据分析与统计，提供针对分表分库统一封装的数据库操作接口，主要用于解决大量数据写入分析的问题。请注意：V0.1bate版本,很多功能尚不完善,只提供技术交流使用,请不要用户生产环境。主要特点有：
 + 1、适用于大量select和大量的insert的业务场景
 + 2、基于架构思维的实现
 + 3、分表分库算法介绍
 + 4、基准测试对比
  

 [更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi-Library/tree/master/DB_Cluster/?dir=1&filepath=DB_Cluster&oid=a50865e4e86d8105bdc30c12a8193db1f119cdb5&sha=8c16393c1286f3921cb920dd21a4aab5eb05f8a3)

###13、基于PhalApi的Redis_Base拓展 (由@喵了个咪提供)
主要提供更丰富的Redis操作,并且进行了分库处理可以自由搭配
 + 1、适用于对Redis需要其他数据类型操作的业务
 + 2、可以用于队列脚本,封装了队列处理


 [更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi-Library/wikis/%E5%9F%BA%E4%BA%8EPhalApi%E7%9A%84Redis%E6%8B%93%E5%B1%95--%E6%96%87%E6%A1%A3)

###14、基于PhalApi的图片上传拓展 (由@SteveAK提供)
此扩展可用于图片等文件的上传，使用云上传引擎,并支持local,oss,upyun。

 [更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi-Library/tree/master/UCloud/?dir=1&filepath=UCloud&oid=5e9a5775195dada3443d34c495b6e63681caf557&sha=d4a50a79ed0ed6ac46599ba0e1835679a73ccf58)


###15、基于PhalApi的第三方支付拓展 (由@SteveAK提供)
目前此扩展支持：
 + 1、支付宝支付
 + 2、微信支持
 + 3、支持第三方支付添加


[更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi-Library/tree/master/Pay/?dir=1&filepath=Pay&oid=87c82f473c95070bf36c67a40218e29dd77ff153&sha=d4a50a79ed0ed6ac46599ba0e1835679a73ccf58)

###16、PhalApi-Image -- 图像处理 (由@喵了个咪提供)
目前此扩展支持：
 + 1、压缩裁剪
 + 2、图片水印
 + 3、获取图片基础信息
 + 4、GIF图片处理

[更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi-Library/tree/master/Image/?dir=1&filepath=Image&oid=1e16d0574361a01179c1d25e9103c21b941d12e3&sha=8110fd16dc78a9267237455d068e1c2edff75369)
###17、PhalApi-SMS基于PhalApi容联云短信服务器拓展 (由@喵了个咪提供)
目前此扩展支持：
 + 1、普通短信发送
 + 2、语言短信发送
 + 3、IVR外呼

 ###18、PhalApi-ThirdLogin -- 第三方登录 (由@SteveAK提供)
 目前此拓展支持：
 + 1、QQ登录
 + 2、支持第三方登录添加

[更多请点这里查看使用文档](http://git.oschina.net/dogstar/PhalApi-Library/tree/master/SMS/?dir=1&filepath=SMS&oid=4d7320f5e9b7d1ae0f57b2f23dd328ceed55f159&sha=09ce6c0fdf45b71549e60e4a4c858f585104b168)


##扩展开发指南 - 针对开发者
为了统一扩展类库的风格、便于用户更容易使用，这里建议：  

 + 代码：统一放置在Library目录下，各扩展包各自一个目录，尽量Lite.php入口类，遵循PEAR包命名规范；
 + 配置：统一放置在DI()->config->get('app.扩展包名')中，避免配置冲突；
 + 文档：统一提供WIKI文件对扩展类库的功能、安装和配置、使用示例以及运行效果进行说明；