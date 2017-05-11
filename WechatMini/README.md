
## 扩展类库：基于think-wxminihelper的微信小程序开发扩展

此扩展基于[think-wxminihelper](https://github.com/wulongtao/think-wxminihelper)二次开发实现，并已征得原作者[wulongtao](https://github.com/wulongtao)同意。目前，此扩展提供了：  

 + 直接可访问的登录接口```?service=WechatMini_WXLoginHelper.CheckLogin```
 + 直接可访问会话检测接口```?service=WechatMini_WXLoginHelper.CheckSession```
 + 在内部获取用户登录态的接口

## 安装与配置

### (1) 扩展的安装

从[PhalApi-Library](http://git.oschina.net/dogstar/PhalApi-Library)扩展库中下载获取**WechatMini**扩展包，如使用：
```javascript
git clone https://git.oschina.net/dogstar/PhalApi-Library.git
```
 
然后把**WechatMini**目录复制到项目**./PhalApi/Library/**目录下，即：
```javascript
cp ./PhalApi-Library/WechatMini/ ./PhalApi/Library/ -R
```
 
到此安装完毕！接下是扩展的配置。

### (2) 扩展的配置

我们需要在**./Config/app.php**配置文件中追加以下配置： 
```
    /**
     * 扩展类库 - 微信小程序
     */
    'WechatMini'  => array(
        'appid'      => '', // AppID(小程序ID)
        'secret'     => '', // AppSecret(小程序密钥)
        'url'        => 'https://api.weixin.qq.com/sns/jscode2session',
        'grant_type' => 'authorization_code'
    ),
```
其中，appid和secret根据自己的小程序配置，相应更新。url和grant_type则不需要更改。  

### (3) 扩展的注册

可以在初始化文件./Public/init.php或者项目入口文件如./Public/demo/index.php注册此微信小程序扩展。
```
DI()->wechatMini = new WechatMini_Lite();
```
这里需要使用显式初始化，WechatMini扩展完成一些内部初始化工作。

注册好后，便可以开始使用了。

## 接口服务介绍

此扩展提供了客户端直接可访问使用的接口服务，主要有：登录接口、会话检测接口。下面分别讲解。  

### (1) 登录接口```?service=WechatMini_WXLoginHelper.CheckLogin```

 + **功能说明**  

此接口服务用于小程序初次登录，背后的实现原理参考自[微信开放接口文档](https://mp.weixin.qq.com/debug/wxadoc/dev/api/api-login.html#wxchecksessionobject)。

 + **接口URI**

在请求此接口服务时，需要在前面加上接口域名和项目访问路径。而接口服务service名称为：
```
/?service=WechatMini_WXLoginHelper.CheckLogin
```

 + **接口参数**
 
参数|必须|默认值|说明
---|---|---|---
code|是|无|微信登录凭证

 + **返回结果**

返回字段|类型|说明
---|---|---
code|int|操作状态码，0表示成功，否则表示失败
openid|string|微信openid，成功时才返回此字段
session3rd|string|3rd session标识，成功时才返回此字段
message|string|失败时的提示信息，调试模式下透传微信接口返回的错误信息

例如，成功时会返回：  
```
{
    "ret": 200,
    "data": {
        "code": 0,
        "openid": "oh1Mb0W63bd6u5rpiJ6eTqrYnYOc",
        "session3rd": "sTyOmVSFKL4h1hKs"
    },
    "msg": ""
}
```

失败时返回：
```
{
    "ret": 200,
    "data": {
        "code": -41005,
        "message": "请求Token失败"
    },
    "msg": ""
}
```

当开启调试模式时，例如secret设置不对时，会返回微信接口返回的错误信息，如：  
```
{
    "ret": 200,
    "data": {
        "code": 40125,
        "message": "invalid appsecret, view more at http://t.cn/RAEkdVq, hints: [ req_id: TVDXha0288s161 ]"
    },
    "msg": "",
    "debug": {
        ... ...
    }
}
```

 + **小程序使用示例**

在小程序中，可以这样调用此接口。  
```
wx.login({
      success: function(res) {
        if (res.code) {
          //发起网络请求
          wx.request({
            url: 'http://demo.phalapi.net/?service=WechatMini_WXLoginHelper.checkLogin',
            data: {
              code: res.code
            },
            success: function (res) {
              var data = res.data
              if (data.ret == 200 && data.data.code == 0) {
                console.log("登录成功")
                console.log("openid = " + data.data.openid)
                console.log("session3rd = " + data.data.session3rd)
              } else {
                console.log("登录失败")
                console.log("code = " + data.data.code)
                console.log("message = " + data.data.message)
              }
            }
          })
        } else {
          console.log('获取用户登录态失败！' + res.errMsg)
        }
      }
    });
```

### (2) 会话检测接口```?service=WechatMini_WXLoginHelper.CheckSession```

 + **功能说明**  

在调用登录接口后，接口服务会缓存成功返回的会话信息。这时可使用此会话检测接口判断用户是否已登录且在有效会话期间内。

 + **接口URI**

在请求此接口服务时，需要在前面加上接口域名和项目访问路径。而接口服务service名称为：
```
/?service=WechatMini_WXLoginHelper.CheckSession
```

 + **接口参数**
 
参数|必须|默认值|说明
---|---|---|---
session3rd|是|无|3rd session标识

 + **返回结果**

返回字段|类型|说明
---|---|---
code|int|操作状态码，0表示成功，否则表示失败
openid|string|微信openid，成功时才返回此字段
session3rd|string|3rd session标识，成功时才返回此字段
message|string|失败时的提示信息，调试模式下透传微信接口返回的错误信息

如成功时返回：
```
{
  "ret": 200,
  "data": {
    "code": 0,
    "openid": "oh1Mb0W63bd6u5rpiJ6eTqrYnYOc",
    "session3rd": "bV0FBXyjt3YsQxfc"
  },
  "msg": ""
}
```

会话过期时返回：
```
{
  "ret": 200,
  "data": {
    "code": -2,
    "message": "登录态已过期"
  },
  "msg": ""
}
```

## 内部获取用户登录态的接口

在内部，根据session3rd，可以这样获取会话信息。 
```
$session = DI()->wechatMini->getSession($session3rd);
```
未设置缓存```DI()->cache```时返回FALSE，无会话时返回NULL，正常时返回数组：```array('openid' => , 'session_key' => )```。  
