# 基于PhalApi的SabreXml拓展

![](http://webtools.qiniudn.com/master-LOGO-20150410_50.jpg)

## 前言
***先在这里感谢phalapi框架创始人@dogstar,为我们提供了这样一个优秀的开源框架.***

本次为大家带来PHP的最强XML解析扩展SabreXml,sabre不只有XML解析工具还有如下这么多：

```
sabre/dav
sabre/event
sabre/http
sabre/katana
sabre/vobject
sabre/xml
sabre/uri
```
这次给大家带来的主要还是XML相关的可以帮助很好的解决XML解析或转换的问题

**注:本拓展并没有开发完成,也没进行严格的测试,此版本为还处于开发阶段的鉴赏版.**

附上:

官网地址:[http://www.phalapi.net/](http://www.phalapi.net/ "PhalApi官网")

开源中国Git地址:[http://git.oschina.net/dogstar/PhalApi/tree/release](http://git.oschina.net/dogstar/PhalApi/tree/release "开源中国Git地址")

SabreXml官网地址:[http://sabre.io/xml/](http://sabre.io/xml/ "SabreXml官网地址")

## 注册

只需要简单的实例化库之后文件就已经进行了引入可以直接进行使用即可

```
$SabreXml = new SabreXml_Lite();
```

## 使用方式
    
**读取XML文件**

文件如下
```$xslt
<?xml version="1.0" encoding="utf-8"?>
<books xmlns="http://example.org/books">
    <book>
        <title>Snow Crash</title>
        <author>Neil Stephenson</author>
    </book>
    <book>
        <title>Dune</title>
        <author>Frank Herbert</author>
    </book>
</books>
```
解析代码
```$xslt
$service = new Sabre\Xml\Service();
$result = $service->parse($xml);

```
解析结果非常清晰的解析结果层级结构以及属性都特别清楚
```$xslt
Array
    (
        [0] => Array
            (
                [name] => {http://example.org/books}book
                [value] => Array
                    (
                        [0] => Array
                            (
                                [name] => {http://example.org/books}title
                                [value] => Snow Crash
                                [attributes] => Array()
                            )
                        [1] => Array
                            (
                                [name] => {http://example.org/books}author
                                [value] => Neil Stephenson
                                [attributes] => Array()
                            )
                    )
                [attributes] => Array()
            )
        [1] => Array
            (
                [name] => {http://example.org/books}book
                [value] => Array
                    (
                        [0] => Array
                            (
                                [name] => {http://example.org/books}title
                                [value] => Dune
                                [attributes] => Array()

                            )
                        [1] => Array
                            (
                                [name] => {http://example.org/books}author
                                [value] => Frank Herbert
                                [attributes] => Array()
                            )
                    )
                [attributes] => Array()
            )
    )
```

**生成XML**
```$xslt
$service = new Sabre\Xml\Service();
echo $service->write('...');
```

生产代码
```$xslt
$service = new Sabre\Xml\Service();
$service->namespaceMap = [
    'http://example.org/' => 'e',
];

echo $service->write('{http://example.org/}root', 'hello');
```
结果如下
```$xslt
<?xml version="1.0">
<e:root>hello</e:root>
```

## 对PhalApi的放回结果进行XML格式化

只需要在init.php之后加上如下代码即可:
```$xslt
DI()->response = new SabreXml_Response();
```

返回格式如下：

```$xslt

<?xml version="1.0"?>
<Response>
 <ret>200</ret>
 <data>
  <title>Hello World!</title>
  <content>PHPer您好，欢迎使用PhalApi！</content>
  <version>1.4.0</version>
  <time>1496068795</time>
 </data>
 <msg></msg>
</Response>
```

**PS：更多的用法可以看到官方文档哦！**


