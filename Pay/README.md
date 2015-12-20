#基于PhalApi的第三方支付拓展

### 1.安装和配置

#### 1.1 扩展包下载
从 PhalApi-Library 扩展库中下载获取 Pay 扩展包，如使用：

git clone https://git.oschina.net/dogstar/PhalApi-Library.git
然后把 Pay 目录下对应的文件移动至对应的目录

#### 1.2 扩展包配置
我们需要在 ./Config/app.php 配置文件中追加以下配置：
##### 1.2.1 第三方支付配置
```
   /**
     * 支付相关配置
     */
    'Payment' => array(
        //异步/同步地址 如果域名指向到Public，那么地址应该是 http://你的域名/pay/
        'notify_url' => 'http://你的域名/PhalApi/Public/pay/',

        //支付宝wap端设置
        'aliwap' => array( 
            //收款账号邮箱
            'email' => 'admin@admin.com', 

            //加密key
            'key' => 'xxx', 

            //合作者ID
            'partner' => '123456' 
        ),

        //微信支付设置
        'wechat' => array(
            //公众号的唯一标识
            'appid' => 'xxx',

            //商户号
            'mchid' => '123456',

            //公众号的appsecret
            'appsecret' => 'xxx',

            //微信支付Key
            'key' => 'xxx'
        ),
    ),
```
支付宝私钥公钥的生成就不多说了，还是自己去看吧!
任意门：https://cshall.alipay.com/enterprise/help_detail.htm?help_id=483847
生成后的文件放至Library/Pay/key目录下，文件名请按照对应的文件名设置

### 2.入门使用
#### 2.1 入口注册
```
$loader->addDirs('Library');

//其他代码...

//支付
DI()->pay = new Pay_Lite();
```
### 3. 使用接口进行支付
```
找到Demo/Api/Pay.php这个放到对应的项目下，Notify.php也在同级目录下
访问链接 http://你的域名/Public/项目/?service=Pay.index&type=wechat
参数type为对应的支付引擎的名称。微信为wechat 支付宝wap端为aliwap
```
如果域名已经设置至Public目录，就不需要Public目录了

如果需要测试微信JSAPI支付，需要在微信浏览器中测试，先将以上的访问链接生成为二维码，然后打开微信扫一扫就可以支付了

#### 3.1 异步回调接口说明
```
//支付宝的异步回调接口
http:://你的域名/Public/pay/aliwap/notify.php

//支付宝同步回调接口
http:://你的域名/Public/pay/aliwap/return.php

//微信异步回调接口
http:://你的域名/Public/pay/wechat/notify.php

```
支付宝同步回调不知道为什么进行验证时会验证失败，为了节约时间，也没有再修复了，毕竟如果是做接口大部分用不到。如果有同学把问题解决了，请AT我 aer_c@qq.com QQ:7579476 或者添加PhalApi官方交流群：421032344 AT：Summer

### 4. 回调成功说明
异步回调成功后会在日志中生成成功后的信息，失败也会生成

```
2015-12-18 10:43:36|PAYSUCCESS|Pay Success|{"Type":"aliwap","Method":"notify","Data":{"status":true,"money":"0.01","out_trade_no":"FC180660663677d","trade_no":"2015121800001000630003030428"}}

2015-12-18 10:43:59|PAYSUCCESS|Pay Success|{"Type":"wechat","Method":"notify","Data":{"status":true,"money":0.01,"out_trade_no":"FC180663422083d","trade_no":"1007480911201512182153438132"}}
```
```
返回的数据说明
Type 支付方法 aliwap/wechat
Method 回调方式 notify:异步回调 return: 同步回调
Data 订单信息 status: 支付状态 money: 支付的总金额 out_trade_no: 商户订单号 trade_no: 支付宝/微信订单号
```

###5. 添加新的第三方支付
我相信同学们都看的懂，我都有注释，安装规定的格式添加即可，如果看不懂可以问我，联系方式上面有，就不说了！

PS：我们致力于代码开源，引用老大的一句话
PhalApi是一个PHP轻量级开源接口框架。我们致力于将PhalApi维护成像恒星一样：不断更新，保持生气；为接口负责，为开源负责！让后台接口开发更简单！ 