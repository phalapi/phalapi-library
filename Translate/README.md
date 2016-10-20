#PhalApi-Translate百度地图翻译拓展

![](http://webtools.qiniudn.com/master-LOGO-20150410_50.jpg)

##前言

在全球化的脚步下,为了更好的用户体验翻译是少不了的(总有一些和我一样的英文菜鸡),所以给到用户最好的时当地语言,但是很多地方并没提供多语言,比如地理位置一般采集上来
的都是当地的语言位置信息,这个时候我们就需要用到翻译了,对比了很多翻译有道,谷歌翻译和百度翻译,最终还是选择了百度翻译,那么话不多说我们就开始具体介绍一下此拓展!

附上:

官网地址:[http://www.phalapi.net/](http://www.phalapi.net/ "PhalApi官网")

开源中国Git地址:[http://git.oschina.net/dogstar/PhalApi/tree/release](http://git.oschina.net/dogstar/PhalApi/tree/release "开源中国Git地址")

开源中国拓展Git地址:[http://git.oschina.net/dogstar/PhalApi-Library](http://git.oschina.net/dogstar/PhalApi-Library "开源中国Git地址")

百度地图开放平台地址:[http://api.fanyi.baidu.com/api/trans/product/index](http://api.fanyi.baidu.com/api/trans/product/index "百度地图开放平台地址")

##1. 安装

配置方式非常简单只需要把拓展下载下来放入Library文件内即可,然后就可以使用如下方法进行实例

	//初始化传入配置文件地址
	$Translate = new Translate_Lite("appId", "secKey");
	
需要传入两个参数一个是appId一个是secKey在百度翻译平台申请的时候会给你appId和secKey

百度翻译支持语言:
```
语言简写	名称
auto	自动检测
zh	中文
en	英语
yue	粤语
wyw	文言文
jp	日语
kor	韩语
fra	法语
spa	西班牙语
th	泰语
ara	阿拉伯语
ru	俄语
pt	葡萄牙语
de	德语
it	意大利语
el	希腊语
nl	荷兰语
pl	波兰语
bul	保加利亚语
est	爱沙尼亚语
dan	丹麦语
fin	芬兰语
cs	捷克语
rom	罗马尼亚语
slo	斯洛文尼亚语
swe	瑞典语
hu	匈牙利语
cht	繁体中文

```

**注意:也可以直接改写框架中的appId和secKey变量在初始时可以不用传递**

##2.使用

使用非常简单,参数分别为,需要翻译的内容数组,需要翻译的语言(推荐auto自动匹配),需要翻译的语言

```
$rs = $Translate_Lite->translate(array("上海市", "上海市", "杨浦区"), "auto", "jp");


// 结果
array(3) {
  [0]=>
  string(9) "上海市"
  [1]=>
  string(9) "上海市"
  [2]=>
  string(9) "楊浦区"
}
```

返回结果为一个数组和需要翻译的数组对应的数组,数组可以指定key返回时会和key对应返回

```
$rs = $Translate_Lite->translate(array("province" => "上海市", "city" => "上海市", "area" => "杨浦区"), "auto", "jp");

// 结果
array(3) {
  ["province"]=>
  string(9) "上海市"
  ["city"]=>
  string(9) "上海市"
  ["area"]=>
  string(9) "楊浦区"
}
```

##3. 异常

在调试过程中或使用过程中常常会遇到一些异常,拓展中会抛出一个**Translate_Exception_Base**的异常可以使用try进行捕获并且通过code对应以下情况进行处理

```
// 成功
const TRANSLATE_SUCCESS = 52000;
// 请求超时
const TRANSLATE_OVERTIME = 52001;
// 系统错误
const TRANSLATE_ERROR = 52002;
// 未授权用户
const TRANSLATE_APPID_UNAUTHORIZED = 52003;
// 必填参数为空
const TRANSLATE_LACK_PARAMETER = 54000;
// 客户端IP非法
const TRANSLATE_IP_ILLEGAL = 58000;
// 签名错误
const TRANSLATE_SIGNATURE_ERROR = 54001;
// 访问频率受限
const TRANSLATE_FREQUENCY_LIMIT = 54003;
// 译文语言方向不支持
const TRANSLATE_LANGUAGE_NOT_SUPPORTED = 58001;
// 账户余额不足
const TRANSLATE_LACK_BALANCE = 54004;
// 长query请求频繁
const TRANSLATE_LONG_FREQUENT_QUERY_REQUEST = 54005;

// 参数类型不对需要传递数组类型
const TRANSLATE_NOT_SUPPORT_TYPES = 10001;
```

##4. 总结

希望此拓展能够给大家带来方便以及实用,暂时只支持容联云如有其他童鞋希望能加入其余常用运营商可与笔者进行联系!

注:笔者能力有限有说的不对的地方希望大家能够指出,也希望多多交流!

**官网QQ交流群:421032344  欢迎大家的加入!**