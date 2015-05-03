##PhalApi框架扩展类库
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

##扩展开发指南 - 针对开发者
为了统一扩展类库的风格、便于用户更容易使用，这里建议：  

 + 代码：统一放置在Library目录下，各扩展包各自一个目录，尽量Lite.php入口类，遵循PEAR包命名规范；
 + 配置：统一放置在DI()->config->get('app.扩展包名')中，避免配置冲突；
 + 文档：统一提供WIKI文件对扩展类库的功能、安装和配置、使用示例以及运行效果进行说明；