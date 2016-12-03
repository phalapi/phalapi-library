<?php

/**
 * Created by PhpStorm.
 * User: qiu han
 * Date: 2016/11/25
 * Time: 5:05
 */
abstract class AlipayBase
{
    /** @var  string 回调地址 */
    protected $notifyUrl;
    /** @var  string 请求参数集合 */
    protected $bizContent;
    /** @var  string 接口名称 */
    protected $apiMethodName;
    /** @var  string 终端类型 */
    protected $terminalType;
    /** @var  string 终端信息 */
    protected $terminalInfo;
    /** @var  string 产品代码 */
    protected $prodCode;
    /** @var  string 网页支付回跳地址 */
    protected $returnUrl;
    protected $apiParas     = array();
    protected $apiVersion   = "1.0";
    protected $needEncrypt  = false;
    
    public function getApiMethodName()
    {
        return $this->apiMethodName;
    }
    
    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl=$notifyUrl;
    }
    
    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }
    
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl=$returnUrl;
    }
    
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }
    
    public function setBizContent($bizContent)
    {
        $this->bizContent = $bizContent;
        $this->apiParas["biz_content"] = $bizContent;
    }
    
    public function getBizContent()
    {
        return $this->bizContent;
    }
    
    public function getTerminalType()
    {
        return $this->terminalType;
    }
    
    public function setTerminalType($terminalType)
    {
        $this->terminalType = $terminalType;
    }
    
    public function getTerminalInfo()
    {
        return $this->terminalInfo;
    }
    
    public function setTerminalInfo($terminalInfo)
    {
        $this->terminalInfo = $terminalInfo;
    }
    
    public function getProdCode()
    {
        return $this->prodCode;
    }
    
    public function setProdCode($prodCode)
    {
        $this->prodCode = $prodCode;
    }
    
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion=$apiVersion;
    }
    
    public function getApiVersion()
    {
        return $this->apiVersion;
    }
    
    public function setNeedEncrypt($needEncrypt)
    {
        $this->needEncrypt=$needEncrypt;
    }
    
    public function getNeedEncrypt()
    {
        return $this->needEncrypt;
    }
    
    public function getApiParas()
    {
        return $this->apiParas;
    }
}

/**线下--条码支付/声波支付
 * 用于在线下场景交易一次创建并支付掉
 **/
class AlipayTradePayRequest extends AlipayBase
{
    protected $apiMethodName = 'alipay.trade.pay';
}

/**线下/线上--扫码支付
 * 收银员通过收银台或商户后台调用支付宝接口，生成二维码后，展示给用户，由用户扫描二维码完成订单支付。
 **/
class AlipayTradePrecreateRequest extends AlipayBase
{
    protected $apiMethodName = 'alipay.trade.precreate';
}

/**移动端--手机网站
 * 手机网站支付接口2.0
 **/
class AlipayTradeWapPayRequest extends AlipayBase
{
    protected $apiMethodName = 'alipay.trade.wap.pay';
}

/**-----未查找到官方文档
 * 统一收单下单并支付页面接口
 **/
class AlipayTradePagePayRequest extends AlipayBase
{
    protected $apiMethodName = 'alipay.trade.page.pay';
}

/**
 * 商户通过该接口进行交易的创建订单对象---需要买家支付宝号（手机号或唯一支付宝号）
 **/
class AlipayTradeCreateRequest extends AlipayBase
{
    protected $apiMethodName ='alipay.trade.create';
}

/**
 * app支付接口2.0对象
 **/
class AlipayTradeAppPayRequest extends AlipayBase
{
    protected $apiMethodName = 'alipay.trade.app.pay';
}

/**交易查询
 * 统一收单线下交易查询对象
 **/
class AlipayTradeQueryRequest extends AlipayBase
{
    protected $apiMethodName = "alipay.trade.query";
}

/**交易退款
 * 统一收单交易退款接口
 **/
class AlipayTradeRefundRequest extends AlipayBase
{
    protected $apiMethodName = 'alipay.trade.refund';
}

/**
 * 查询退款对象
 **/
class AlipayTradeFastpayRefundQueryRequest extends AlipayBase
{
    protected $apiMethodName ='alipay.trade.fastpay.refund.query';
}

/**交易撤销
 * 统一收单交易撤销对象
 **/
class AlipayTradeCancelRequest extends AlipayBase
{
    protected $apiMethodName = 'alipay.trade.cancel';
}

/**关闭订单
 * 统一收单交易关闭对象
 **/
class AlipayTradeCloseRequest extends AlipayBase
{
    protected $apiMethodName = 'alipay.trade.close';
    
}