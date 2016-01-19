<?php

abstract class Pay_Base {

    protected $config;
    protected $info;
    
    public function __construct($config) {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * 配置检查
     * @return boolean
     */
    public function check() {
        return true;
    }

    /**
     * 验证通过后获取订单信息
     * @return type
     */
    public function getInfo() {
        return $this->info;
    }

    /**
     * 生成订单号
     * 可根据自身的业务需求更改
     */
    public function createOrderNo() {
        $year_code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        return $year_code[intval(date('Y')) - 2010] .
                strtoupper(dechex(date('m'))) . date('d') .
                substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('d', rand(0, 99));
    }

    /**
     * 建立提交表单
     */
    abstract public function buildRequestForm($vo);

    /**
     * 构造表单
     */
    protected function _buildForm($params, $gateway, $method = 'post', $charset = 'utf-8') {

        header("Content-type:text/html;charset={$charset}");
        $sHtml = "<form id='paysubmit' name='paysubmit' action='{$gateway}' method='{$method}'>";

        foreach ($params as $k => $v) {
            $sHtml.= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
        }

        $sHtml = $sHtml . "</form>Loading......";

        $sHtml = $sHtml . "<script>document.forms['paysubmit'].submit();</script>";
        return $sHtml;
    }

    /**
     * 支付通知验证
     */
    abstract public function verifyNotify($notify);

    /**
     * 异步通知验证成功返回信息
     */
    public function notifySuccess() {
        echo "success";
    }

    /**
     * 异步通知验证失败返回信息
     * @return [type] [description]
     */
    public function notifyError(){
        echo "fail";
    }

    final protected function formatPostkey($post, &$result, $key = '') {
        foreach ($post as $k => $v) {
            $_k = $key ? $key . '[' . $k . ']' : $k;
            if (is_array($v)) {
                $this->formatPostkey($v, $result, $_k);
            } else {
                $result[$_k] = $v;
            }
        }
    }

}
