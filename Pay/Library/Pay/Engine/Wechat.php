<?php
/*
 * +----------------------------------------------------------------------
 * | 微信支付引擎
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

class Pay_Engine_Wechat extends Pay_Base {

	protected $snsapi_base_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
	protected $openid_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
	protected $pay_url = "https://api.mch.weixin.qq.com/pay/unifiedorder?";

	protected $config = array(
		//公众号的唯一标识
		'appid' => '',

		//商户号
		'mchid' => '',

		//公众号的appsecret
		'appsecret' => '',

		//微信支付Key
		'key' => '',

		//商户证书
		'cert_path' => 'key/wechat_apiclient_cert.pem',
		'key_path' => 'key/wechat_apiclient_key.pem'
	);

	//网页授权接口微信服务器返回的数据
	protected $data = null;

	//请求参数，类型为关联数组
	protected $param;

	//请求后返回的参数
	protected $values = array();

	public function check() {
        if (!$this->config['appid'] || !$this->config['mchid'] || !$this->config['appsecret'] || !$this->config['key']) {
        	DI()->logger->log('payError','wechat setting error');
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
    	//获取Openid
    	$open_id = $this->getOpenid();
    
    	$this->param['appid'] = $this->config['appid'];
        $this->param['mch_id'] = $this->config['mchid'];
        $this->param['nonce_str'] = $this->createNoncestr(32);
        $this->param['body'] = $data['body'];
        $this->param['attach'] = $data['title'];
        $this->param['out_trade_no'] = $data['order_no'];
        //$this->param['fee_type'] = 'CNY';
        $this->param['total_fee'] = $data['price'] * 100;
        $this->param['spbill_create_ip'] = PhalApi_Tool::getClientIp();
        $this->param['notify_url'] = $this->config['notify_url'];
        $this->param['trade_type'] = 'JSAPI';
        $this->param['openid'] = $open_id;

        //获取签名信息
        $this->param['sign'] = $this->getSign($this->param);

        //转换为XML
        $xml = $this->arrayToXml($this->param);

        //提交XML信息后，将返回的XML转换成数组
        $response = $this->postXmlCurl($xml, $this->pay_url, false, 6);
        $this->values = $this->xmlToArray($response);

		if($this->values['return_code'] != 'SUCCESS'){
			DI()->logger->log('payError','支付失败', $this->values);
			return false;
		}

		//验证签名
		if(!$this->checkSign()){
			DI()->logger->log('payError','签名错误', $this->values);
			return false;
		}

		//获取jsapi支付的参数
		$this->getJsApiParameters();

		//输出HTML唤起微信支付
		$html = $this->showHtml($this->param);

        return $html;
    }

    /**
     * 请求验证
     */
    public function verifyNotify($notify) {
    	//xml转array
    	$this->values = $this->xmlToArray($notify);
		if($this->values['return_code'] != 'SUCCESS'){
			DI()->logger->log('payError','支付失败', $this->values);
			return false;
		}

		if(!$this->checkSign()){
			DI()->logger->log('payError','签名错误', $this->values);
			return false;
		}

		//写入订单信息
		$this->setInfo($this->values);
		return true;
    }

    /**
     * 异步通知验证成功返回信息
     */
    public function notifySuccess(){
    	$return = array();
    	$return['return_code'] = 'SUCCESS';
    	$return['return_msg'] = 'OK';
    	echo $this->arrayToXml($return);
    }

    /**
     * 异步通知验证失败返回信息
     * @return [type] [description]
     */
    public function notifyError(){
        $return = array();
    	$return['return_code'] = 'FAIL';
    	$return['return_msg'] = '验证失败';
    	echo $this->arrayToXml($return);
    }

    /**
     * 写入订单信息
     * @param [type] $notify [description]
     */
    protected function setInfo($notify) {
        $info = array();
        //支付状态
        $info['status'] = ($notify['return_code'] == 'SUCCESS') ? true : false;
        $info['money'] = $notify['total_fee']/100;
        //商户订单号
        $info['out_trade_no'] = $notify['out_trade_no'];
        //微信交易号
        $info['trade_no'] = $notify['transaction_id'];
        $this->info = $info;
    }

    /**
     * 获取微信支付回调信息，以html形式输出
     * @param  [type] $jsApiParameters [description]
     * @return [type]                  [description]
     */
    private function showHtml($jsApiParameters){

		$html = <<<EOT
			<html>
				<head>
					<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
					<title>微信安全支付</title>
					<script type="text/javascript">
						//调用微信JS api 支付
				        function jsApiCall(){
				            WeixinJSBridge.invoke(
				                'getBrandWCPayRequest',
				                {$jsApiParameters},
				                function(res){
				                    //WeixinJSBridge.log(res.err_msg);
				                    //alert(res.err_code+res.err_desc+res.err_msg);
				                    //After the payment is successful, you can setting here.
				                }
				            );
				        }

				        function callpay(){
				            if (typeof WeixinJSBridge == "undefined"){
				                if( document.addEventListener ){
				                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
				                }else if (document.attachEvent){
				                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
				                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
				                }
				            }else{
				                jsApiCall();
				            }
				        }
				    </script>
				</head>
				<body onload='javascript:callpay();'>
				    </br></br></br></br>
				    <div align="center">
				        <button style="width:210px; height:30px; background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >立即支付</button>
				    </div>
				</body>
			</html>
EOT;
        return $html;
    }

    /**
	 * 
	 * 检测签名
	 */
	protected function checkSign(){
		if(!array_key_exists('sign', $this->values)){
			return false;
		}
		
		$sign = $this->getSign($this->values);
		if($this->values['sign'] == $sign){
			return true;
		}
		return false;
	}

	/**
	 * 
	 * 获取jsapi支付的参数
	 * @param array $UnifiedOrderResult 统一支付接口返回的数据
	 * @throws WxPayException
	 * 
	 * @return json数据，可直接填入js函数作为参数
	 */
	protected function getJsApiParameters(){
		if(!array_key_exists("appid", $this->values)
		|| !array_key_exists("prepay_id", $this->values)
		|| $this->values['prepay_id'] == ""){
			$this->error = '参数错误';
			DI()->logger->error('参数错误');
			return false;
		}
		
		$jsApiObj["appId"] = $this->values['appid'];
        $timeStamp = time();
        $jsApiObj["timeStamp"] = "$timeStamp";
        $jsApiObj["nonceStr"] = $this->createNoncestr(32);
        $jsApiObj["package"] = "prepay_id=".$this->values['prepay_id'];
        $jsApiObj["signType"] = "MD5";
        $jsApiObj["paySign"] = $this->getSign($jsApiObj);
        $this->param = json_encode($jsApiObj);

        return $this->param;
	}

    /**
	 * 
	 * 通过跳转获取用户的openid，跳转流程如下：
	 * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
	 * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
	 * 
	 * @return 用户的openid
	 */
    protected function getOpenid(){
		//通过code获得openid
		if (!isset($_GET['code'])){
			//触发微信返回code码
			$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			$url = $this->__createOauthUrlForCode($baseUrl);
			Header("Location: $url");
			exit();
		} else {
			//获取code码，以获取openid
		    $code = $_GET['code'];
			$openid = $this->getOpenidFromMp($code);
			return $openid;
		}
	}

	/**
	 * 
	 * 通过code从工作平台获取openid机器access_token
	 * @param string $code 微信跳转回来带上的code
	 * 
	 * @return openid
	 */
	private function getOpenidFromMp($code){
		$url = $this->__createOauthUrlForOpenid($code);
		//初始化curl
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		//取出openid
		$data = json_decode($res,true);
		$this->data = $data;
		$openid = $data['openid'];
		return $openid;
	}

	/**
	 * 
	 * 构造获取code的url连接
	 * @param string $redirectUrl 微信服务器回跳的url，需要url编码
	 * 
	 * @return 返回构造好的url
	 */
	private function __createOauthUrlForCode($redirectUrl){
		$urlObj["appid"] = $this->config['appid'];
		$urlObj["redirect_uri"] = "$redirectUrl";
		$urlObj["response_type"] = "code";
		$urlObj["scope"] = "snsapi_base";
		$urlObj["state"] = "STATE"."#wechat_redirect";
		$bizString = $this->toUrlParams($urlObj);
		return $this->snsapi_base_url.$bizString;
	}

	/**
	 * 
	 * 构造获取open和access_toke的url地址
	 * @param string $code，微信跳转带回的code
	 * 
	 * @return 请求的url
	 */
	private function __createOauthUrlForOpenid($code){
		$urlObj["appid"] = $this->config['appid'];
		$urlObj["secret"] = $this->config['appsecret'];
		$urlObj["code"] = $code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = $this->toUrlParams($urlObj);
		return $this->openid_url.$bizString;
	}

	/**
	 * 
	 * 拼接签名字符串
	 * @param array $urlObj
	 * 
	 * @return 返回已经拼接好的字符串
	 */
	private function toUrlParams($urlObj){
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			if($k != "sign"){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}

	/**
     *  产生随机字符串，不长于32位
     */
    private function createNoncestr( $length = 32 ){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {  
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
        }  
        return $str;
    }

    /**
     *  生成签名
     */
    private function getSign($data){
        //第一步：对参数按照key=value的格式，并按照参数名ASCII字典序排序如下：
        ksort($data);
        $string = $this->toUrlParams($data);

        //第二步：拼接API密钥
        $string = $string."&key=".$this->config['key'];

        //MD5加密
        $string = md5($string);

        //将得到的字符串全部大写并返回
        return strtoupper($string);
    }

    /**
     * 	array转xml
     */
    private function arrayToXml($arr){
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val))
            {
                $xml=$xml."<".$key.">".$val."</".$key.">";

            }
            else
                $xml=$xml."<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml=$xml."</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
	public function xmlToArray($xml){	
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $array = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		return $array;
	}

    /**
	 * 以post方式提交xml到对应的接口url
	 * 
	 * @param string $xml  需要post的xml数据
	 * @param string $url  url
	 * @param bool $useCert 是否需要证书，默认不需要
	 * @param int $second   url执行超时时间，默认30s
	 * @throws WxPayException
	 */
	private function postXmlCurl($xml, $url, $useCert = false, $second = 30){		
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		
		//这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
		if($useCert == true){
			//设置证书
			//使用证书：cert 与 key 分别属于两个.pem文件
			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLCERT, dirname(dirname(__FILE__)) . '/' . $this->config['cert_path']);
			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLKEY, dirname(dirname(__FILE__)) . '/' . $this->config['key_path']);
		}
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//运行curl
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		} else { 
			$error = curl_errno($ch);
			DI()->logger->log('payError','curl出错，错误码', $error);
			curl_close($ch);
			return false;
		}
	}
}