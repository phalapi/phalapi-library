<?php
/*
 * +----------------------------------------------------------------------
 * | 支付宝手机端引擎
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 summer All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: summer <aer_c@qq.com> <qq7579476>
 * +----------------------------------------------------------------------
 * | This is not a free software, unauthorized no use and dissemination.
 * +----------------------------------------------------------------------
 * | Date
 * +----------------------------------------------------------------------
 */

include dirname(dirname(__FILE__)) . implode(DIRECTORY_SEPARATOR, array('', 'Pay.php'));

class Engine_Aliwap extends Pay {

    protected $gateway    = 'https://mapi.alipay.com/gateway.do?';
    protected $verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    protected $config     = array(
        'email'   => '',
        'key'     => '',
        'partner' => '',

        //商户的私钥（后缀是.pen）文件相对路径
        'private_key_path' => 'key/alipay_rsa_private_key.pem',

        //支付宝公钥（后缀是.pen）文件相对路径
        'ali_public_key_path' => 'key/alipay_public_key.pem',

        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        'transport' => 'http',

        'sign_type' => 'RSA'
    );

    /**
     * 配置检查
     * @return [type] [description]
     */
    public function check() {
        if (!$this->config['email'] || !$this->config['key'] || !$this->config['partner']) {
            DI()->logger->log('payError','aliwap setting error');
            return false;
        }
        return true;
    }

    /**
     * 请求支付
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function buildRequestForm($data) {
        //构造要请求的参数数组
        $para_temp = array(
            "service"           => "alipay.wap.create.direct.pay.by.user",
            "partner"           => trim($this->config['partner']),
            "seller_id"         => trim($this->config['email']),
            "payment_type"      => '1',
            "notify_url"        => $this->config['notify_url'],
            "return_url"        => $this->config['return_url'],
            "out_trade_no"      => $data['order_no'],
            "subject"           => $data['title'],
            "total_fee"         => $data['price'],
            "body"              => $data['body'],
            "_input_charset"    => 'utf-8'
        );
        
        //待请求参数数组
        $param = $this->buildRequestPara($para_temp);
        $sHtml = $this->_buildForm($param, $this->gateway, 'get');

        return $sHtml;
    }

    /**
     * 请求验证
     * @param  [type] $notify [description]
     * @return [type]         [description]
     */
    public function verifyNotify($notify) {
        $isSign = $this->getSignVeryfy($notify, $notify["sign"]);

        //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
        $responseTxt = 'false';
        if (! empty($notify["notify_id"])) {
            $responseTxt = $this->getResponse($notify["notify_id"]);
        }

        if (preg_match("/true$/i", $responseTxt) && $isSign) {
            $this->setInfo($notify);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 写入订单信息
     * @param [type] $notify [description]
     */
    protected function setInfo($notify) {
        $info = array();
        //支付状态
        $info['status'] = ($notify['trade_status'] == 'TRADE_FINISHED' || $notify['trade_status'] == 'TRADE_SUCCESS') ? true : false;
        $info['money'] = $notify['total_fee'];
        //商户订单号
        $info['out_trade_no'] = $notify['out_trade_no'];
        //支付宝交易号
        $info['trade_no'] = $notify['trade_no'];
        $this->info = $info;
    }

    /**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
    protected function getSignVeryfy($para_temp, $sign) {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($para_temp);
        
        //对待签名参数数组排序
        $para_sort = $this->argSort($para_filter);
        
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);
        
        $isSgin = false;
        switch (strtoupper(trim($this->config['sign_type']))) {
            case "RSA" :
                $isSgin = $this->rsaVerify($prestr, $sign);
                break;
            default :
                $isSgin = false;
        }
        
        return $isSgin;
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    protected function getResponse($notify_id) {
        $partner = trim($this->config['partner']);
        $veryfy_url = $this->verify_url."partner=" . $partner . "&notify_id=" . $notify_id;
        $responseTxt = $this->getHttpResponseGET($veryfy_url, dirname(dirname(__FILE__)) . '/key/alipay_cacert.pem');
        
        return $responseTxt;
    }


    /****************************************************************
     * RSA签名
     */
    
    /**
     * 创建签名
     * @param array $para
     * @return string
     */
    protected function createSign($para) {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para);
        
        $mysign = "";
        switch (strtoupper(trim($this->config['sign_type']))) {
            case "RSA" :
                $mysign = $this->rsaSign($prestr);
                break;
            default :
                $mysign = "";
        }
        
        return $mysign;
    }
    
    /**
     * RSA签名
     * @param $data 待签名数据
     * return 签名结果
     */
    protected function rsaSign($data) {
        //使用绝对路径
        $priKey = file_get_contents(dirname(dirname(__FILE__)) . '/' . $this->config['private_key_path']);
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);

        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * RSA验签
     * @param $data 待签名数据
     * @param $ali_public_key_path 支付宝的公钥文件路径
     * @param $sign 要校对的的签名结果
     * return 验证结果
     */
    protected function rsaVerify($data, $sign)  {
        $pubKey = file_get_contents(dirname(dirname(__FILE__)) . '/' . $this->config['ali_public_key_path']);
        $res = openssl_get_publickey($pubKey);
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);    
        return $result;
    }


    /****************************************************************
     * 其他操作
     */
    
    /**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
    protected function buildRequestPara($para_temp) {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($para_temp);

        //对待签名参数数组排序
        $para_sort = $this->argSort($para_filter);

        //生成签名结果
        $mysign = $this->createSign($para_sort);
        
        //签名结果与签名方式加入请求提交参数组中
        $para_sort['sign'] = $mysign;
        $para_sort['sign_type'] = strtoupper(trim($this->config['sign_type']));
        
        return $para_sort;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    protected function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        
        return $arg;
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    protected function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $key == "sign_type" || $val == "")continue;
            else    $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    protected function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * 远程获取数据，GET模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url 指定URL完整路径地址
     * @param $cacert_url 指定当前工作目录绝对路径
     * return 远程输出的数据
     */
    protected function getHttpResponseGET($url,$cacert_url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);
        
        return $responseText;
    }


}