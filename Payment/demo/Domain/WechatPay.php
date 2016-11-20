<?php

/**
 * Created by PhpStorm.
 * User: qiu han
 * Date: 2016/11/19
 * Time: 20:30
 */
class Domain_WechatPay
{
    /**
     * @var Payment_Lite
     */
    protected $payment;
    
    /**
     * Domain_WechatPay constructor.
     *
     * @param string $id    多商户情况下传入商户Id
     */
    public function __construct( $id = NULL )
    {
        $conf = DI()->config->get('pay.wechat');
        if ($id){
            $mch = new Model_Mch();
            $mchConf = $mch->get($id);
            if ($mchConf){
                $conf = array_merge($conf,$mchConf);
            }
        }
        $this->payment = new Payment_Lite($conf);
    }
    
    /**微信--统一下单接口，本方法不能用于被扫支付
     * @param string $body  商品描述
     * @param int    $total 总金额，单位为分
     * @param string $type  支付类型，取值如下：JSAPI，NATIVE，APP
     *
     * @return string   成功返回二维码链接或prepay_id，失败返回空
     */
    public function createOrder( $body, $total, $type = 'APP'){
        $order = new Model_Order();
        $orderInfo = array(
            Payment_Lite::$body      => (string) $body,
            Payment_Lite::$total     => (int)    $total,
            Payment_Lite::$tradeType => (string) $type,
        );
        $orderInfo[Payment_Lite::$orderId] = $order->insert($orderInfo);
        $result = $this->payment->createWechatOrder($orderInfo);
        if ($this->checkResult($result)){
            if ($type == 'NATIVE'){
                return $result['code_url'];
            }
            return $result['prepay_id'];
        }
        return '';
    }
    
    /**微信--查询订单
     * @param string $orderId  订单id
     *
     * @return string 成功返回状态，失败返回空
     */
    public function orderQuery($orderId){
        $result = $this->payment->wechatOrderQuery('',$orderId);
        if ($this->checkResult($result)){
            return $result['trade_state'];
        }
        return '';
    }
    
    public function refund($orderId,$total){
        $order      = new Model_Order();
        $orderInfo  = $order->get($orderId);
        $refund     = new Model_Refund();
        $refundInfo = array(
            Payment_Lite::$refundFee=> (int)    $total,
            Payment_Lite::$orderId  => (string) $orderId,
            Payment_Lite::$total    => (int)    $orderInfo[Payment_Lite::$total],
        );
        $refundInfo[Payment_Lite::$refundId] = $refund->insert($refundInfo);
        $result = $this->payment->wechatRefund($refundInfo);
        if ($this->checkResult($result)){
            
        }
    }
    
    protected function checkResult($result){
        if (isset($result['result_code']) && $result['result_code'] == 'SUCCESS'){
            return TRUE;
        }
        //TODO 请在此记录失败日志
        return FALSE;
    }
}