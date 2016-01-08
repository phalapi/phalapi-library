#PhalApi-SMS基于PhalApi容联云短信服务器拓展

![](http://webtools.qiniudn.com/master-LOGO-20150410_50.jpg)

##前言

在做项目时更换短信运营商时,找到了容联云通讯(滴滴用的是他的服务),感觉很不错看了下SDK文件和测试Demo感觉使用起来并不是很方便,
因为确实很多项目都会用到这一套服务比较希望好用优雅一些,所以提供了本次拓展也希望大家喜欢

附上:

官网地址:[http://www.phalapi.net/](http://www.phalapi.net/ "PhalApi官网")

开源中国Git地址:[http://git.oschina.net/dogstar/PhalApi/tree/release](http://git.oschina.net/dogstar/PhalApi/tree/release "开源中国Git地址")

开源中国拓展Git地址:[http://git.oschina.net/dogstar/PhalApi-Library](http://git.oschina.net/dogstar/PhalApi-Library "开源中国Git地址")


##1. 安装

配置方式非常简单只需要把拓展下载下来放入Library文件内即可,然后就可以使用如下方法进行实例

	//初始化传入配置文件地址
	$SMS = new SMS_Lite("app.SMSService");
	//初始化并且打开调试模式
	$SMS = new SMS_Lite("app.SMSService",true);

在调试模式下返回信息会被打印出来(建议在生产环境不要开启)

##2.配置

配置文件约定存放在app.SMSService,serverPort以及serverIP不进行配置为默认环境

	"SMSService" => array(
        "accountSid"   => "",  //主帐号
        "accountToken" => "",  //主帐号Token
        "appId"        => "",  //应用Id
        "serverPort"   => "",  //请求端口 默认:8883
        "serverIP"     => ""   //请求地址不需要写https:// 默认:sandboxapp.cloopen.com 测试环境
    )


##3. SDK-API

通过如上配置都可以开始进行正常的使用了如下

	//发送模板短信
    $SMS->sendTemplateSMS("手机号码", "内容数据", "模板Id");
    
    //短信模板查询
    $SMS->QuerySMSTemplate("模板ID");
    
    //语音验证码
    $SMS->voiceVerify("验证码内容", "循环播放次数", "接收号码", "显示的主叫号码", "营销外呼状态通知回调地址", '语言类型', '第三方私有数据');
    
    //语音文件上传
    $SMS->MediaFileUpload("文件名", "文件二进制数据");
    
    //话单下载 前一天的数据（从00:00 – 23:59）
    $SMS->billRecords("话单规则", "客户的查询条件");
    
    //IVR外呼
    $SMS->ivrDial("待呼叫号码", "用户数据", "是否录音");
    
    //外呼通知
    $SMS->landingCall("被叫号码", "语音文件名称", "文本内容", "显示的主叫号码", "循环播放次数", "外呼通知状态通知回调地址", '用户私有数据', '最大通话时长', '发音速度', '音量', '音调', '背景音编号');
    
    //主帐号信息查询
    $SMS->queryAccountInfo();
    
    //呼叫状态查询
    $SMS->QueryCallState("callid", "查询结果通知的回调url地址");


##4. 总结

希望此拓展能够给大家带来方便以及实用,暂时只支持容联云如有其他童鞋希望能加入其余常用运营商可与笔者进行联系!

注:笔者能力有限有说的不对的地方希望大家能够指出,也希望多多交流!

**官网QQ交流群:421032344  欢迎大家的加入!**