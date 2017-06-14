<?php

class RY_Lite{

	private $appKey;
	private $appSecret;

	private $RY_rongcloud;
	//构造方法
	public function __construct($cofAddress, $debug = false) {

		$this->debug = $debug;
		//获得配置项
		$config = DI()->config->get($cofAddress);
		//配置项是否存在
		if (!$config) {
			throw new PhalApi_Exception_BadRequest(T('Config There is no'));
		}

		if ($this->getIndex($config, 'appKey')) {
			$this->appKey = $config['appKey'];
		} else {
			throw new PhalApi_Exception_BadRequest(T('appKey There is no'));
		}

		if ($this->getIndex($config, 'appSecret')) {
			$this->appSecret = $config['appSecret'];
		} else {
			throw new PhalApi_Exception_BadRequest(T('appSecret There is no'));
		}

		$this->RY_rongcloud = new RY_rongcloud($this->appKey,$this->appSecret);

	}

	//获取融云token
	public function getToken($user_id,$user_name,$user_avatar){

		$result = $this->RY_rongcloud->user()->getToken($user_id, $user_name, $user_avatar);

		$result = json_decode($result);

		return $result->token;
	}

	// 刷新用户信息方法
	public function reFresh($user_id,$user_name,$user_avatar){

		$result = $this->RY_rongcloud->user()->refresh($user_id,$user_name,$user_avatar);
		return $result;
	}

	// 检查用户在线状态 方法
	public function checkOnline($user_id){

		$result = $this->RY_rongcloud->user()->checkOnline($user_id);
		$result = json_decode($result);
		return $result->status;;
	}

	// 添加用户到黑名单方法（每秒钟限 100 次）
	public function addBlacklist($user_id,$user_id2){
		$result = $this->RY_rongcloud->user()->addBlacklist($user_id, $user_id2);
		$result = json_decode($result);
		return $result->code;
	}

	// 获取某用户的黑名单列表方法（每秒钟限 100 次）
	public function queryBlacklist($user_id){

		$result = $this->RY_rongcloud->user()->queryBlacklist($user_id);
		$result = json_decode($result);
		return $result->users;
	}

	// 从黑名单中移除用户方法（每秒钟限 100 次）
	public function removeBlacklist($user_id,$user_id2){

		$result = $this->RY_rongcloud->user()->removeBlacklist($user_id, $user_id2);
		$result = json_decode($result);
		return $result;
	}

	// 发送单聊消息方法（一个用户向另外一个用户发送消息，单条消息最大 128k。每分钟最多发送 6000 条信息，每次发送用户上限为 1000 人，如：一次发送 1000 人时，示为 1000 条消息。）
	public function publishPrivate($user_id,$user_id2,$type,$content){

		$result = $this->RY_rongcloud->message()->publishPrivate($user_id, $user_id2, $type,$content,null, null, '1', '0', '0', '1');
		$result = json_decode($result);
		return $result;
	}


	/**
	 * 数组对象取值相关 - 避免出错
	 */
	public function getIndex($arr, $key, $default = '') {

		return isset($arr[$key]) ? $arr[$key] : $default;
	}

}