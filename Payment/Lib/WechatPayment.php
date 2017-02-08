<?php

/**
 * Created by PhpStorm.
 * User: qiu han
 * Date: 2016/11/25
 * Time: 8:27
 */

require_once 'Wechat/WxPay.Api.php';
require_once 'PaymentProperty.php';

class WechatPayment
{
    /**配置必要的配置项
     * Payment_Lite constructor.
     *
     * @param array $config 配置项，请将配置项组成数组传递
     *
     * @throws Exception
     */
    public function __construct( array $config)
    {
        $sslPath  = trim($config['sslPath']);
        WxPayConfig::$appId         = trim($config['appId']) ;
        WxPayConfig::$key           = trim($config['key']);
        WxPayConfig::$appSecret     = trim($config['appSecret']);
        WxPayConfig::$mchId         = (int) $config['mchId'];
        WxPayConfig::$notifyUrl     = trim($config['notifyUrl']);
        WxPayConfig::$sslCertPath   = $sslPath . trim($config['sslName']) . '_cert.pem';
        WxPayConfig::$sslKeyPath    = $sslPath . trim($config['sslName']) . '_key.pem';
        
        $config['sub_appid'] && WxPayConfig::$sub_appId = trim($config['sub_appid']);
        $config['sub_mch_id'] && WxPayConfig::$sub_mch_id = trim($config['sub_mch_id']);
    }
    
    /**微信--生成订单
     * @see   https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_1
     * @param array $orderInfo  订单内容
     *                          $orderInfo[PaymentProperty::$body]      商品描述
     *                          $orderInfo[PaymentProperty::$orderId]   商户订单ID
     *                          $orderInfo[PaymentProperty::$total]     订单金额，单位为分
     *                          $orderInfo[PaymentProperty::$tradeType] 取值如下：JSAPI，NATIVE，APP，
     *                          其他字段信息请参考微信开发文档
     * @param int   $time       订单有效时常，单位秒，如：1分钟有效期则 time=60
     *
     * @return string       prepay_id,异常返回空
     */
    public function createOrder( array $orderInfo, $time = 600){
        
        $order = new WxPayUnifiedOrder();
        //必填项
        $order->SetBody($orderInfo[PaymentProperty::$body]);
        $order->SetOut_trade_no($orderInfo[PaymentProperty::$orderId]);
        $order->SetTotal_fee( (int) $orderInfo[PaymentProperty::$total]);
        //有效时间
        $order->SetTime_start(date("YmdHis"));
        $order->SetTime_expire(date("YmdHis", time() + $time));
        //非必填项
        !empty($orderInfo[PaymentProperty::$openId]) && $order->SetOpenid($orderInfo[PaymentProperty::$openId]);
        !empty($orderInfo[PaymentProperty::$tradeType]) && $order->SetTrade_type($orderInfo[PaymentProperty::$tradeType]);
        !empty($orderInfo[PaymentProperty::$deviceInfo]) && $order->SetDevice_info($orderInfo[PaymentProperty::$deviceInfo]);
        !empty($orderInfo[PaymentProperty::$detail]) && $order->SetDetail($orderInfo[PaymentProperty::$detail]);
        !empty($orderInfo[PaymentProperty::$attach]) && $order->SetAttach($orderInfo[PaymentProperty::$attach]);
        !empty($orderInfo[PaymentProperty::$feeType]) && $order->SetFee_type($orderInfo[PaymentProperty::$feeType]);
        !empty($orderInfo[PaymentProperty::$tag]) && $order->SetGoods_tag($orderInfo[PaymentProperty::$tag]);
        //服务商
        !empty($orderInfo[PaymentProperty::$sub_openid]) && $order->SetSub_openid($orderInfo[PaymentProperty::$sub_openid]);
        return WxPayApi::unifiedOrder($order);
    }
    
    /**微信--条码支付
     * @see   https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_1
     * @param array $orderInfo  订单内容
     *                          $orderInfo[PaymentProperty::$body]      商品描述
     *                          $orderInfo[PaymentProperty::$orderId]   商户订单ID
     *                          $orderInfo[PaymentProperty::$total]     订单金额，单位为分
     *                          $orderInfo[PaymentProperty::$tradeType] 取值如下：JSAPI，NATIVE，APP，
     *                          其他字段信息请参考微信开发文档
     * @param int   $time       订单有效时常，单位秒，如：1分钟有效期则 time=60
     *
     * @return string       prepay_id,异常返回空
     */
    public function createAuthCodeOrder( array $orderInfo, $time = 600){
        
        $order = new WxPayMicroPay();
        //必填项
        $order->SetBody($orderInfo[PaymentProperty::$body]);
        $order->SetOut_trade_no($orderInfo[PaymentProperty::$orderId]);
        $order->SetTotal_fee( (int) $orderInfo[PaymentProperty::$total]);
        $order->SetAuth_code($orderInfo[]);
        //有效时间
        $order->SetTime_start(date("YmdHis"));
        $order->SetTime_expire(date("YmdHis", time() + $time));
        //非必填项
        !empty($orderInfo[PaymentProperty::$deviceInfo]) && $order->SetDevice_info($orderInfo[PaymentProperty::$deviceInfo]);
        !empty($orderInfo[PaymentProperty::$detail]) && $order->SetDetail($orderInfo[PaymentProperty::$detail]);
        !empty($orderInfo[PaymentProperty::$attach]) && $order->SetAttach($orderInfo[PaymentProperty::$attach]);
        !empty($orderInfo[PaymentProperty::$feeType]) && $order->SetFee_type($orderInfo[PaymentProperty::$feeType]);
        !empty($orderInfo[PaymentProperty::$tag]) && $order->SetGoods_tag($orderInfo[PaymentProperty::$tag]);
        return WxPayApi::micropay($order);
    }
    
    /**微信--发起退款
     * @see   https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_4
     *
     * @param array $arrRefund 退款订单信息
     *                         $arrRefund[PaymentProperty::$total]      订单金额
     *                         $arrRefund[PaymentProperty::$refundFee]  退款金额
     *                         $arrRefund[PaymentProperty::$refundId]   商户退款单号
     *                         $arrRefund[PaymentProperty::$wxOrderId]  微信订单号
     *                         $arrRefund[PaymentProperty::$orderId]    商户订单号
     *                         $arrRefund[PaymentProperty::$deviceInfo] 设备号
     *                         $arrRefund[PaymentProperty::$feeType]    货币类型
     *
     * @return array
     */
    public function refund( array $arrRefund){
        
        $refund = new WxPayRefund();
        //必填项
        $refund->SetTotal_fee($arrRefund[PaymentProperty::$total]);//订单金额
        $refund->SetRefund_fee($arrRefund[PaymentProperty::$refundFee]);//退款金额
        $refund->SetOut_refund_no($arrRefund[PaymentProperty::$refundId]);//商户退款单号
        $refund->SetOp_user_id(WxPayConfig::$mchId);//操作员账号
        //非必填项
        !empty($arrRefund[PaymentProperty::$wxOrderId])     && $refund->SetTransaction_id($arrRefund[PaymentProperty::$wxOrderId]);//微信订单号
        !empty($arrRefund[PaymentProperty::$orderId])       && $refund->SetOut_trade_no($arrRefund[PaymentProperty::$orderId]);//商户订单号
        !empty($arrRefund[PaymentProperty::$deviceInfo])    && $refund->SetDevice_info($arrRefund[PaymentProperty::$deviceInfo]);//设备号
        !empty($arrRefund[PaymentProperty::$feeType])       && $refund->SetRefund_fee_type($arrRefund[PaymentProperty::$feeType]);//货币类型
        
        return WxPayApi::refund($refund);
        
    }
    
    /**微信--订单查询
     * @see   https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_2
     * @param string $transaction_id    微信订单号,优先使用
     * @param string $out_trade_no      商户订单号
     *
     * @return array
     */
    public function orderQuery( $transaction_id = '', $out_trade_no = '' )
    {
        $orderQuery = new WxPayOrderQuery();
        $transaction_id && $orderQuery->SetTransaction_id($transaction_id);
        $out_trade_no   && $orderQuery->SetOut_trade_no($out_trade_no);
        return WxPayApi::orderQuery($orderQuery);
    }
    
    /**
     * @param array $arrRefund
     *
     * @return array
     */
    public function refundQuery( array $arrRefund){
        $refundQuery = new WxPayRefundQuery();
        
        !empty($arrRefund[PaymentProperty::$wxOrderId])     && $refundQuery->SetTransaction_id($arrRefund[PaymentProperty::$wxOrderId]);//微信订单号
        !empty($arrRefund[PaymentProperty::$orderId])       && $refundQuery->SetOut_trade_no($arrRefund[PaymentProperty::$orderId]);//商户订单号
        !empty($arrRefund[PaymentProperty::$refundId])      && $refundQuery->SetOut_refund_no($arrRefund[PaymentProperty::$refundId]);//商户退款单号
        !empty($arrRefund[PaymentProperty::$wxRefundId])    && $refundQuery->SetRefund_id($arrRefund[PaymentProperty::$wxRefundId]);//微信退款单号
        !empty($arrRefund[PaymentProperty::$deviceInfo])    && $refundQuery->SetDevice_info($arrRefund[PaymentProperty::$deviceInfo]);//设备号
        
        return WxPayApi::refundQuery($refundQuery);
    }
    
    /**微信--关闭订单
     * @see   https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_3
     * @param string $orderId  商户订单id
     *
     * @return array
     */
    public function closeOrder( $orderId){
        $closeOrder = new WxPayCloseOrder();
        $closeOrder->SetOut_trade_no($orderId);
        return WxPayApi::closeOrder($closeOrder);
    }
    
    /**微信--获取jsApi支付的参数
     * @see   https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=7_7&index=6
     * @param array $UnifiedOrderResult 统一支付接口返回的数据
     *                                  $UnifiedOrderResult['appid']        appId
     *                                  $UnifiedOrderResult['prepay_id']    创建订单返回的prepay_id
     * @throws WxPayException
     *
     * @return string json数据，可直接填入js函数作为参数
     */
    public function getJsApiParameters($UnifiedOrderResult)
    {
        if(!array_key_exists("appid", $UnifiedOrderResult)
            || !array_key_exists("prepay_id", $UnifiedOrderResult)
            || $UnifiedOrderResult['prepay_id'] == "")
        {
            throw new WxPayException("参数错误");
        }
        $jsapi = new WxPayJsApiPay();
        $jsapi->SetAppid($UnifiedOrderResult["appid"]);
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");
        $jsapi->SetNonceStr(WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign());
        $parameters = json_encode($jsapi->GetValues());
        return $parameters;
    }
    
    /**微信--获取地址js参数
     * @see   https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=7_8&index=7
     * @param string $webToken 获取网页授权的TOKEN
     *
     * @return string 获取共享收货地址js函数需要的参数 ，json格式可以直接做参数使用
     */
    public function getEditAddressParameters($webToken)
    {
        $data = array();
        $data["appid"] = WxPayConfig::$appId;
        $data["url"] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $data["timestamp"] = (string) time();
        $data["noncestr"] = WxPayApi::getNonceStr();
        $data["accesstoken"] = $webToken;
        ksort($data);
        $params = $this->toUrlParams($data);
        $addressSign = sha1($params);
        
        $afterData = array(
            "addrSign" => $addressSign,
            "signType" => "sha1",
            "scope" => "jsapi_address",
            "appId" => WxPayConfig::$appId,
            "timeStamp" => $data["timestamp"],
            "nonceStr" => $data["noncestr"]
        );
        $parameters = json_encode($afterData);
        return $parameters;
    }
    
    /**微信--将xml转为array并验证签名
     * @param string $xml  回调数据
     *
     * @return array
     */
    public function checkSign( $xml){
        return WxPayResults::Init($xml);
    }
    
    /**拼接签名字符串
     * @param array $urlObj
     *
     * @return string 返回已经拼接好的字符串
     */
    protected function toUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v){
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }
}