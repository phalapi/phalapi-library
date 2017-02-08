<?php

/**
 * Created by PhpStorm.
 * User: qiu han
 * Date: 2016/11/25
 * Time: 8:31
 */
class PaymentProperty
{
    //以下为字段名
    /** @var string 商户系统内部的订单号,32个字符内、可包含字母 */
    public static $orderId   = 'orderId';
    
    /** @var string 微信/支付宝--订单号 */
    public static $wxOrderId = 'wxOrderId';
    
    /** @var string 商户退款单号 */
    public static $refundId  = 'refundId';
    
    /** @var string 微信/支付宝--退款单号 */
    public static $wxRefundId= 'wxRefundId';
    
    /** @var string 微信/支付宝--订单总金额，单位为分 */
    public static $total     = 'total';
    
    /** @var string 微信/支付宝--退款金额 */
    public static $refundFee = 'refundFee';
    
    /** @var string 微信--支付类型为JSAPI时，此参数必传，用户在商户appid下的唯一标识。 */
    public static $openId    = 'openId';
    
    /** @var string 微信--支付类型为JSAPI时，此参数必传，用户在子商户appid下的唯一标识。
     * openid和sub_openid可以选传其中之一，如果选择传sub_openid,则必须传sub_appid。
     * 下单前需要调用【网页授权获取用户信息】接口获取到用户的Openid。  */
    public static $sub_openid    = 'sub_openId';
    
    /** @var string 微信--取值如下：JSAPI，NATIVE，APP */
    public static $tradeType = 'tradeType';
    
    /** @var string 微信/支付宝--终端设备号(门店号或收银设备ID)，注意：PC网页或公众号内支付请传"WEB" */
    public static $deviceInfo= 'deviceInfo';
    
    /** @var string 微信/支付宝--交易标题，微信中该字段须严格按照规范传递 */
    public static $body      = 'body';
    
    /** @var string 微信/支付宝--商品详细列表，使用Json格式，微信--传输签名前请务必使用CDATA标签将JSON文本串保护起来。 */
    public static $detail    = 'detail';
    
    /** @var string 微信/支付宝--附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
     *      支付宝只在手机网页--支付完成回跳到页面时携带附加数据，passback_params
     */
    public static $attach    = 'attach';
    
    /** @var string 微信--符合ISO 4217标准的三位字母代码，默认人民币：CNY */
    public static $feeType   = 'feeType';
    
    /** @var string 微信--商品标记，代金券或立减优惠功能的参数 */
    public static $tag       = 'tag';
    
    /** @var string 微信/支付宝--线下条码支付授权码 */
    public static $authCode  = 'authCode';
    
    /** @var string 支付宝--打折金额*/
    public static $discountAmount = 'discountAmount';
    
    /** @var string 支付宝--产品代码  手机网页支付必须*/
    public static $productCode = 'productCode';
    
    /** @var string 支付宝--商品类型 */
    public static $goodsType   = 'goodsType';
    
    /** @var string 支付宝--手机网页支付回跳页面地址，http或https开头 */
    public static $returnUrl   = 'returnUrl';
}