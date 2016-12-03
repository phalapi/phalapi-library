#微信支付及支付宝支付扩展

### 1.配置

####通用配置
请将配置文件Config/payment的内容添加到相应框架的配置文件中

**多商户情况下可以将商户相关配置存到数据库里**

微信--sslName、key、appId、appSecret、mchId

支付宝--sslName、appId、mchId

#### 字段配置

所有的传递数组字段都可以在PaymentProperty文件中自行配置，
**强烈建议将需要用到的字段跟数据库字段设置一致**

### 2.入门使用

通过Payment_Lite::getPayment($config)获取支付实例，根据$ocnfig['type']类型返回相应的实例

以phalapi框架为例
```
    DI()->payment = new Payment_Lite();
    $wechatPay = DI()->payment->getPayment(DI()->config->get('payment.wechat'));
    $aliPay = DI()->payment->getPayment(DI()->config->get('payment.alipay'));
```

### 3. 接口列表
大多数接口是直接传数据进去，**强烈建议将需要用到的字段跟数据库字段设置一致**

请自行查看接口说明文档
####微信：
```
    string WechatPayment::createOrder( array $orderInfo, $time = 600)
    string WechatPayment::createAuthCodeOrder( array $orderInfo, $time = 600)
    array  WechatPayment::refund( array $arrRefund)
    array  WechatPayment::orderQuery( string $transaction_id = '',string $out_trade_no = '' )
    array  WechatPayment::refundQuery( array $arrRefund)
    array  WechatPayment::closeOrder( string $orderId)
    string WechatPayment::getJsApiParameters( array $UnifiedOrderResult)
    string WechatPayment::getEditAddressParameters( string $webToken)
    array  WechatPayment::checkSign( string $xml)
```
####支付宝
```
    array  AliPayment::createPreOrder( array $orderInfo, $time = 600)
    array  AliPayment::createAuthCodeOrder( array $orderInfo,$type = 0, $time = 600)
    string AliPayment::createWapOrder( array $orderInfo, $time = 600)
    string AliPayment::createAppOrder( array $orderInfo, $time = 600)
    array  AliPayment::orderQuery( string $transaction_id = '',string $out_trade_no = '')
    array  AliPayment::refund( array $arrRefund )
    array  AliPayment::refundQuery( array $arrRefund )
    array  AliPayment::closeOrder(string $orderId )
    bool   AliPayment::check($arr)
```
### 4. 回调处理
微信--调用WechatPayment::checkSign( string $xml)方法可验证回调签名并返回数组格式的内容
支付宝--AliPayment::check(array $arr)