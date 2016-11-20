<?php

/**
 * Created by PhpStorm.
 * User: qiu han
 * Date: 2016/11/18
 * Time: 19:53
 *
 * 说明：此文件为基础接口文件，建议在Service层调用,并自行对异常情况进行日志记录
 * 配置项使用方式(初始化)：
 *      1、只有一个商户号的情况下，可将配置项写到根目录/Config目录下，调用时直接获取配置项传进来。
 *      2、有多个商户号的情况下，可将公用S配置项写到根目录/Config目录下，将各个商户信息写入数据库或者同一个
 *          配置文件下，使用时合并数组传进来即可。
 *
 * 微信--生成订单或退款时，可直接将对应的订单对象或退款单对象转化为数组传进来
 *
 * 注：数据库字段与本文件数组键值不一致时，在此文件中更改相应的static的值即可
 */

require_once "Lib/Wechat/WxPay.Api.php";

/**
 * Class Payment_Lite
 */
class Payment_Lite
{
    /**
     * @var string
     */
    protected $sslPath  = '';
    
    /**
     * @var string  支付类型
     */
    protected $type     = '';
    

    //以下为字段名
    /**
     * @var string  商户系统内部的订单号,32个字符内、可包含字母
     */
    public static $orderId   = 'orderId';
    /**
     * @var string  微信订单号
     */
    public static $wxOrderId = 'wxOrderId';
    /**
     * @var string  商户退款单号
     */
    public static $refundId  = 'refundId';
    /**
     * @var string  微信退款单号
     */
    public static $wxRefundId= 'wxRefundId';
    /**
     * @var string  订单总金额，单位为分
     */
    public static $total     = 'total';
    /**
     * @var string  退款金额
     */
    public static $refundFee = 'refundFee';
    /**
     * @var string  trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识。
     */
    public static $openId    = 'openId';
    /**
     * @var string  取值如下：JSAPI，NATIVE，APP
     */
    public static $tradeType = 'tradeType';
    /**
     * @var string  终端设备号(门店号或收银设备ID)，注意：PC网页或公众号内支付请传"WEB"
     */
    public static $deviceInfo= 'deviceInfo';
    /**
     * @var string  商品简单描述，该字段须严格按照规范传递
     */
    public static $body      = 'body';
    /**
     * @var string  商品详细列表，使用Json格式，传输签名前请务必使用CDATA标签将JSON文本串保护起来。
     */
    public static $detail    = 'detail';
    /**
     * @var string  附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
     */
    public static $attach    = 'attach';
    /**
     * @var string  符合ISO 4217标准的三位字母代码，默认人民币：CNY
     */
    public static $feeType   = 'feeType';
    /**
     * @var string  商品标记，代金券或立减优惠功能的参数
     */
    public static $tag       = 'tag';

    
    /**配置必要的配置项
     * Payment_Lite constructor.
     *
     * @param array $config     配置项，请将配置项组成数组传递
     */
    public function __construct( array $config)
    {
        $this->sslPath  = trim($config['sslPath']);
        $this->type     = trim($config['type']);
        if ($this->type == 'wechat'){
            WxPayConfig::$appId         = trim($config['appId']) ;
            WxPayConfig::$key           = trim($config['key']);
            WxPayConfig::$appSecret     = trim($config['appSecret']);
            WxPayConfig::$mchId         = (int) $config['mchId'];
            WxPayConfig::$notifyUrl     = trim($config['notifyUrl']);
            WxPayConfig::$sslCertPath   = $this->sslPath . trim($config['sslName']) . '_cert.pem';
            WxPayConfig::$sslKeyPath    = $this->sslPath . trim($config['sslName']) . '_key.pem';
        }
    }
    
    /**微信--生成订单
     * @see   https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_1
     * @param array $orderInfo  订单内容
     *                          $orderInfo[self::$body]      商品描述
     *                          $orderInfo[self::$orderId]   商户订单ID
     *                          $orderInfo[self::$total]     订单金额，单位为分
     *                          $orderInfo[self::$tradeType] 取值如下：JSAPI，NATIVE，APP，
     *                          其他字段信息请参考微信开发文档
     * @param int   $time       订单有效时常，单位秒，如：1分钟有效期则 time=60
     *
     * @return string       prepay_id,异常返回空
     */
    public function createWechatOrder( array $orderInfo, $time = 600){
    
        $order = new WxPayUnifiedOrder();
        //必填项
        $order->SetBody($orderInfo[self::$body]);
        $order->SetOut_trade_no($orderInfo[self::$orderId]);
        $order->SetTotal_fee( (int) $orderInfo[self::$total]);
        //有效时间
        $order->SetTime_start(date("YmdHis"));
        $order->SetTime_expire(date("YmdHis", time() + $time));
        //非必填项
        !empty($orderInfo[self::$openId]) && $order->SetOpenid($orderInfo[self::$openId]);
        !empty($orderInfo[self::$tradeType]) && $order->SetTrade_type($orderInfo[self::$tradeType]);
        !empty($orderInfo[self::$deviceInfo]) && $order->SetDevice_info($orderInfo[self::$deviceInfo]);
        !empty($orderInfo[self::$detail]) && $order->SetDetail($orderInfo[self::$detail]);
        !empty($orderInfo[self::$attach]) && $order->SetAttach($orderInfo[self::$attach]);
        !empty($orderInfo[self::$feeType]) && $order->SetFee_type($orderInfo[self::$feeType]);
        !empty($orderInfo[self::$tag]) && $order->SetGoods_tag($orderInfo[self::$tag]);
        return WxPayApi::unifiedOrder($order);
    }
    
    /**微信--发起退款
     * @see   https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_4
     *
     * @param array $arrRefund 退款订单信息
     *                         $arrRefund[self::$total]      订单金额
     *                         $arrRefund[self::$refundFee]  退款金额
     *                         $arrRefund[self::$refundId]   商户退款单号
     *                         $arrRefund[self::$wxOrderId]  微信订单号
     *                         $arrRefund[self::$orderId]    商户订单号
     *                         $arrRefund[self::$deviceInfo] 设备号
     *                         $arrRefund[self::$feeType]    货币类型
     *
     * @return array
     */
    public function wechatRefund( array $arrRefund){
        
        $refund = new WxPayRefund();
        //必填项
        $refund->SetTotal_fee($arrRefund[self::$total]);//订单金额
        $refund->SetRefund_fee($arrRefund[self::$refundFee]);//退款金额
        $refund->SetOut_refund_no($arrRefund[self::$refundId]);//商户退款单号
        $refund->SetOp_user_id(WxPayConfig::$mchId);//操作员账号
        //非必填项
        !empty($arrRefund[self::$wxOrderId])     && $refund->SetTransaction_id($arrRefund[self::$wxOrderId]);//微信订单号
        !empty($arrRefund[self::$orderId])       && $refund->SetOut_trade_no($arrRefund[self::$orderId]);//商户订单号
        !empty($arrRefund[self::$deviceInfo])    && $refund->SetDevice_info($arrRefund[self::$deviceInfo]);//设备号
        !empty($arrRefund[self::$feeType])       && $refund->SetRefund_fee_type($arrRefund[self::$feeType]);//货币类型
        
        return WxPayApi::refund($refund);
        
    }
    
    /**微信--订单查询
     * @see   https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_2
     * @param string $transaction_id    微信订单号,优先使用
     * @param string $out_trade_no      商户订单号
     *
     * @return array
     */
    public function wechatOrderQuery( $transaction_id = '', $out_trade_no = '' )
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
    public function wechatRefundQuery( array $arrRefund){
        $refundQuery = new WxPayRefundQuery();
        
        !empty($arrRefund[self::$wxOrderId])     && $refundQuery->SetTransaction_id($arrRefund[self::$wxOrderId]);//微信订单号
        !empty($arrRefund[self::$orderId])       && $refundQuery->SetOut_trade_no($arrRefund[self::$orderId]);//商户订单号
        !empty($arrRefund[self::$refundId])      && $refundQuery->SetOut_refund_no($arrRefund[self::$refundId]);//商户退款单号
        !empty($arrRefund[self::$wxRefundId])    && $refundQuery->SetRefund_id($arrRefund[self::$wxRefundId]);//微信退款单号
        !empty($arrRefund[self::$deviceInfo])    && $refundQuery->SetDevice_info($arrRefund[self::$deviceInfo]);//设备号
        
        return WxPayApi::refundQuery($refundQuery);
    }
    
    /**微信--关闭订单
     * @see   https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_3
     * @param string $orderId  商户订单id
     *
     * @return array
     */
    public function wechatCloseOrder( $orderId){
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
    public function getWechatJsApiParameters($UnifiedOrderResult)
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
    public function getWechatEditAddressParameters($webToken)
    {
        $data = array();
        $data["appid"] = WxPayConfig::$appId;
        $data["url"] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $data["timestamp"] = (string) time();
        $data["noncestr"] = WxPayApi::getNonceStr();
        $data["accesstoken"] = $webToken;
        ksort($data);
        $params = self::ToUrlParams($data);
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
    public function checkWechatSign( $xml){
        return WxPayResults::Init($xml);
    }
    
    /**拼接签名字符串
     * @param array $urlObj
     *
     * @return string 返回已经拼接好的字符串
     */
    private function ToUrlParams($urlObj)
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