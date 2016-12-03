<?php

/**
 * Created by PhpStorm.
 * User: qiu han
 * Date: 2016/11/25
 * Time: 8:36
 */

require_once 'Alipay/AopClient.php';
require_once 'PaymentProperty.php';

class AliPayment
{
    /** @var  AopClient */
    protected $aopClient;
    /** @var string 商户Id  */
    protected $sellerId;
    /** @var string 回调地址 */
    protected $notifyUrl;
    
    public function __construct(array $config)
    {
        $sslPath = trim($config['sslPath']);
        $this->notifyUrl = trim($config['notifyUrl']);
        $this->aopClient = new AopClient();
        $this->aopClient->appId = trim($config['appId']) ;
        $this->aopClient->rsaPrivateKeyFilePath = $sslPath . trim($config['sslName']);
        $this->aopClient->alipayPublicKey       = $sslPath . trim($config['publicKey']);
        $config['mchId'] && $this->sellerId = $config['mchId'];
    }
    
    /**创建订单--扫码支付
     *
     * @param array $orderInfo
     * @param int   $time 单位为秒
     *
     * @return array
     * @throws Exception
     */
    public function createPreOrder( array $orderInfo, $time = 600)
    {
        //必填项
        if (empty($orderInfo[PaymentProperty::$orderId])){
            throw new Exception('missing order id');
        }
        if (empty($orderInfo[PaymentProperty::$total])){
            throw new Exception('missing order fee');
        }
        if (empty($orderInfo[PaymentProperty::$body])){
            throw new Exception('missing order subject');
        }
        $content['out_trade_no'] = $orderInfo[PaymentProperty::$orderId];
        $content['total_amount'] = (string) ($orderInfo[PaymentProperty::$total]/100);
        $content['subject']      = $orderInfo[PaymentProperty::$body];
        //有效时间
        $content['timeout_express'] = ((int) ($time/60)).'m';
        //非必填项
        !empty($this->sellerId)&& $content['seller_id'] = $this->sellerId;
        //商品信息，需要时打开下一行注释，严格按支付宝接口格式传递
        //!empty($orderInfo[PaymentProperty::$detail])    && $content['goods_detail'] = $orderInfo[PaymentProperty::$detail];
        !empty($orderInfo[PaymentProperty::$deviceInfo])     && $content['operator_id']  = $orderInfo[PaymentProperty::$deviceInfo];
        !empty($orderInfo[PaymentProperty::$discountAmount]) && $content['discountable_amount'] = (string) ($orderInfo[PaymentProperty::$discountAmount]/100);
        $request = new AlipayTradePrecreateRequest();
        $request->setBizContent(json_encode($content,JSON_UNESCAPED_UNICODE));
        $request->setNotifyUrl($this->notifyUrl);
        return $this->exec($request);
    }
    
    /**创建订单--条码支付或声波支付
     *
     * @param array $orderInfo
     * @param int   $type       0为条码支付，1为声波支付
     * @param int   $time
     *
     * @return array
     * @throws Exception
     */
    public function createAuthCodeOrder( array $orderInfo,$type = 0, $time = 600)
    {
        //必填项
        if (empty($orderInfo[PaymentProperty::$orderId])){
            throw new Exception('missing order id');
        }
        if (empty($orderInfo[PaymentProperty::$authCode])){
            throw new Exception('missing auth code');
        }
        if (empty($orderInfo[PaymentProperty::$total])){
            throw new Exception('missing order fee');
        }
        if (empty($orderInfo[PaymentProperty::$body])){
            throw new Exception('missing order subject');
        }
        $content['out_trade_no'] = $orderInfo[PaymentProperty::$orderId];
        $content['scene']        = $type ? 'wave_code' : 'bar_code';
        $content['auth_code']    = $orderInfo[PaymentProperty::$authCode];
        $content['total_amount'] = (string) ($orderInfo[PaymentProperty::$total]/100);
        $content['subject']      = $orderInfo[PaymentProperty::$body];
        //有效时间
        $content['timeout_express'] = ((int) ($time/60)).'m';
        //非必填项
        !empty($this->sellerId) && $content['seller_id'] = $this->sellerId;
        //商品信息，需要时打开下一行注释，严格按支付宝接口格式传递
        //!empty($orderInfo[PaymentProperty::$detail])    && $content['goods_detail'] = $orderInfo[PaymentProperty::$detail];
        !empty($orderInfo[PaymentProperty::$deviceInfo])     && $content['operator_id']  = $orderInfo[PaymentProperty::$deviceInfo];
        !empty($orderInfo[PaymentProperty::$discountAmount]) && $content['discountable_amount'] = $orderInfo[PaymentProperty::$discountAmount];
        $request = new AlipayTradePayRequest();
        $request->setBizContent(json_encode($content,JSON_UNESCAPED_UNICODE));
        $request->setNotifyUrl($this->notifyUrl);
        return $this->exec($request);
    }
    
    /**创建订单--手机网站支付
     *
     * @param array $orderInfo
     * @param int   $time
     *
     * @return string   返回请求字符串
     * @throws Exception
     */
    public function createWapOrder( array $orderInfo, $time = 600)
    {
        //必填项
        if (empty($orderInfo[PaymentProperty::$body])){
            throw new Exception('missing order subject');
        }
        if (empty($orderInfo[PaymentProperty::$orderId])){
            throw new Exception('missing order id');
        }
        if (empty($orderInfo[PaymentProperty::$total])){
            throw new Exception('missing order fee');
        }
        if (empty($orderInfo[PaymentProperty::$productCode])){
            throw new Exception('missing product code');
        }
        if (empty($orderInfo[PaymentProperty::$returnUrl])){
            throw new Exception('missing return directer url address');
        }
        $content['subject']      = $orderInfo[PaymentProperty::$body];
        $content['out_trade_no'] = $orderInfo[PaymentProperty::$orderId];
        $content['total_amount'] = (string) ($orderInfo[PaymentProperty::$total]/100);
        $content['product_code'] = $orderInfo[PaymentProperty::$productCode];
        $returnUrl = $orderInfo[PaymentProperty::$returnUrl];
        //有效时间
        $content['timeout_express'] = ((int) ($time/60)).'m';
        //非必填项
        !empty($this->sellerId) && $content['seller_id'] = $this->sellerId;
        !empty($orderInfo[PaymentProperty::$goodsType]) && $content['goods_type'] = $orderInfo[PaymentProperty::$goodsType];
        !empty($orderInfo[PaymentProperty::$attach])    && $content['passback_params'] = $orderInfo[PaymentProperty::$attach];
        $request = new AlipayTradeWapPayRequest();
        $request->setBizContent(json_encode($content,JSON_UNESCAPED_UNICODE));
        $request->setNotifyUrl($this->notifyUrl);
        $request->setReturnUrl($returnUrl);
        return $this->aopClient->pageExecute($request);
    }
    
    /**创建订单--App支付
     *
     * @param array $orderInfo
     * @param int   $time
     *
     * @return string
     * @throws Exception
     */
    public function createAppOrder( array $orderInfo, $time = 600)
    {
        //必填项
        if (empty($orderInfo[PaymentProperty::$body])){
            throw new Exception('missing order subject');
        }
        if (empty($orderInfo[PaymentProperty::$orderId])){
            throw new Exception('missing order id');
        }
        if (empty($orderInfo[PaymentProperty::$total])){
            throw new Exception('missing order fee');
        }
        if (empty($orderInfo[PaymentProperty::$productCode])){
            throw new Exception('missing product code');
        }
        $content['subject']      = $orderInfo[PaymentProperty::$body];
        $content['out_trade_no'] = $orderInfo[PaymentProperty::$orderId];
        $content['total_amount'] = (string) ($orderInfo[PaymentProperty::$total]/100);
        $content['product_code'] = $orderInfo[PaymentProperty::$productCode];
        //有效时间
        $content['timeout_express'] = ((int) ($time/60)).'m';
        //非必填项
        !empty($this->sellerId) && $content['seller_id'] = $this->sellerId;
        !empty($orderInfo[PaymentProperty::$goodsType]) && $content['goods_type'] = $orderInfo[PaymentProperty::$goodsType];
        !empty($orderInfo[PaymentProperty::$attach])    && $content['passback_params'] = $orderInfo[PaymentProperty::$attach];
        $request = new AlipayTradeAppPayRequest();
        $request->setBizContent(json_encode($content,JSON_UNESCAPED_UNICODE));
        $request->setNotifyUrl($this->notifyUrl);
        
        return $this->aopClient->sdkExecute($request);
    }
    
    /**订单查询
     *
     * @param string $transaction_id 支付宝订单号
     * @param string $out_trade_no   商户订单号
     *
     * @return array
     * @throws Exception
     */
    public function orderQuery( $transaction_id = '', $out_trade_no = '')
    {
        if(empty($transaction_id) && empty($out_trade_no)){
            throw new Exception('missing order id');
        }
        $out_trade_no && $content['out_trade_no'] = $out_trade_no;
        $transaction_id && $content['trade_no'] = $transaction_id;
        $request = new AlipayTradeQueryRequest();
        $request->setBizContent(json_encode($content,JSON_UNESCAPED_UNICODE));
        return $this->exec($request);
    }
    
    /**订单退款
     * @param array $arrRefund
     *
     * @return array
     * @throws Exception
     */
    public function refund( array $arrRefund )
    {
        if(empty($arrRefund[PaymentProperty::$refundFee])){
            throw new Exception('missing refund fee');
        }
        if(empty($arrRefund[PaymentProperty::$refundId])){
            throw new Exception('missing refund id');
        }
        if(empty($arrRefund[PaymentProperty::$orderId]) && empty($arrRefund[PaymentProperty::$wxOrderId])){
            throw new Exception('missing order id');
        }
        $content['refund_amount'] = (string) ($arrRefund[PaymentProperty::$refundFee]/100);//退款金额
        $content['out_request_no'] = $arrRefund[PaymentProperty::$refundId];//商户退款单号
        
        !empty($arrRefund[PaymentProperty::$orderId]) && $content['out_trade_no'] = $arrRefund[PaymentProperty::$orderId];
        !empty($arrRefund[PaymentProperty::$wxOrderId]) && $content['trade_no'] = $arrRefund[PaymentProperty::$wxOrderId];
        !empty($arrRefund[PaymentProperty::$deviceInfo]) && $content['terminal_id'] = $arrRefund[PaymentProperty::$deviceInfo];//设备编号
        
        $request = new AlipayTradeRefundRequest();
        $request->setBizContent(json_encode($content,JSON_UNESCAPED_UNICODE));
        return $this->exec($request);
    }
    
    /**退款订单查询
     *
     * @param array $arrRefund
     *
     * @return array
     * @throws Exception
     */
    public function refundQuery( array $arrRefund )
    {
        if (empty($arrRefund[PaymentProperty::$refundId])){
            throw new Exception('missing refund id');
        }
        if (empty($arrRefund[PaymentProperty::$wxOrderId]) && empty($arrRefund[PaymentProperty::$orderId])){
            throw new Exception('missing order id');
        }
        $content['out_request_no'] = $arrRefund[PaymentProperty::$refundId];
        !empty($arrRefund[PaymentProperty::$wxOrderId]) && $content['trade_no'] = $arrRefund[PaymentProperty::$wxOrderId];
        !empty($arrRefund[PaymentProperty::$orderId]) && $content['out_trade_no'] = $arrRefund[PaymentProperty::$orderId];
        
        $request = new AlipayTradeFastpayRefundQueryRequest();
        $request->setBizContent(json_encode($content,JSON_UNESCAPED_UNICODE));
        return $this->exec($request);
    }
    
    /**关闭订单
     *
     * @param string $orderId 商户订单号
     *
     * @return array
     * @throws Exception
     */
    public function closeOrder( $orderId )
    {
        $content['out_trade_no'] = $orderId[PaymentProperty::$orderId];
        if (!$orderId){
            throw new Exception('error order id');
        }
        $request = new AlipayTradeCloseRequest();
        $request->setBizContent(json_encode($content,JSON_UNESCAPED_UNICODE));
        return $this->exec($request);
    }
    
    /**
     * 验签方法
     * @param array $arr 验签支付宝返回的信息，使用支付宝公钥。
     * @return boolean
     */
    function check($arr){
        return  $this->aopClient->rsaCheckV1($arr, $this->aopClient->alipayPublicKey);
    }
    
    /**
     * @param AlipayBase $request
     *
     * @return array
     */
    protected function exec( $request){
        
        $result = $this->aopClient->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $result = $result->$responseNode;
        return (array) $result;
    }
}