<?php
/**
 * 移动设备通信加密类
 *
 * 使用方法:
 * 1. 将本插件目录中Config文件夹下app.php中数组内容复制到框架Config/app.php内
 * 2. 传入参数名称和返回加密参数名可以在配置文件里修改
 * 3. 在init.php中加载插件:DI()->CryptTraffic = new CryptTraffic_Lite(DI()->debug);
 * 4. Debug模式下自动关闭通信加解密
 *
 * @author    BrianWang <usualwyy@163.com> 2016-01-12
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'CryptTraffic' . DIRECTORY_SEPARATOR . 'CryptTraffic.php';
class CryptTraffic_Lite {

	private $inParam;
	public function __construct($debug = false) {
		$this->init($debug);
	}

	protected function init($debug) {
		$this ->inParam = DI() ->config ->get('app.CryptTraffic.hashParam');
		if ($debug != true) {
			$_REQUEST = $this->DeCryptHashToParams(); //API传入参数加密
			DI()->response = new Response_Encrypt(); //API返回值加密
		}
	}

	/**
	 * @tutorial DES加密，兼容IOS OC解密
	 * @example
	 * @param  string $Traf 明文字符串
	 * @return string rs 返回密文字符串
	 */
	public function EnCryptNetTraffic($Traf) {
		$IOSCrypt = new CryptTraffic_IOS();
		$keys = DI()->config->get('sys.Keys');
		$C2SKey = $keys['C2SKey'];
		$encryptData = $IOSCrypt->IOSDESencrypt($Traf, $C2SKey);
		$rs = base64_encode($encryptData);
		return $rs;
	}
	/**
	 * @tutorial DES解密，兼容IOS OC加密
	 * @example
	 * @param  string $Traf 明文字符串
	 * @return string rs 返回明文字符串
	 */
	public function DeCryptNetTraffic($Traf) {
		$IOSCrypt = new CryptTraffic_IOS();
		$Traf = base64_decode($Traf);
		$keys = DI()->config->get('sys.Keys');
		$C2SKey = $keys['C2SKey'];
		$decryptData = $IOSCrypt->IOSDESdecrypt($Traf, $C2SKey);
		$rs = $decryptData;
		return $rs;
	}
	/**
	 * @tutorial 解密所有参数,hashdata是加密过的Json字典
	 * @example
	 * @param  string $ParamName 用于传递密文的参数名
	 * @return string rs 返回密文字符串
	 */
	public function DeCryptHashToParams(){
		if ( !isset($_REQUEST[$this ->inParam])){
			echo ('{"ret":400,"data":[],"msg":"Param ' . $this ->inParam . ' is needed as hash!"}');
			die();
		}
		$hash = $_REQUEST[$this ->inParam];
		$Params=json_decode($this->DeCryptNetTraffic($hash),true);
		if (!is_array($Params)){
			echo ('{"ret":400,"data":[],"msg":"Error on decoding the param' . $this ->inParam . '"}');
		die();
		}
		foreach($Params as $x=>$x_value) {
			$_REQUEST[$x]=$x_value;
		}
		unset($_REQUEST[$this ->inParam]);
		return $_REQUEST;
	}
	//获得8位md5
	public function ShortMD5($str){
		return substr(md5($str),12,8);
	}

}
/**
 * PhalApi_Response_DESCrypt DESCrypt响应类
 *
 * @package     PhalApi\Response
 * @license
 * @link
 * @author      BrianWang <usualwyy@163.com> 2015-09-17
 * Base64(DES(PainText))
 */

class Response_Encrypt extends PhalApi_Response {

	private $outParam;
	public function __construct() {
		$this ->outParam = DI() ->config ->get('app.CryptTraffic.responseParam');
		$this->addHeaders('Content-Type', 'text/html;charset=utf-8');
	}

	protected function formatResult($result) {
		$Traf=json_encode($result);
		$rs = DI()->CryptTraffic->EnCryptNetTraffic($Traf);
		$rs = '{"' . $this ->outParam . '":"'.$rs.'"}';
		return $rs;
	}
}