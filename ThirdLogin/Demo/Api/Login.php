<?php
/**
 *  Login.php
 *  第三方登录演示
 *  
 *  Created by SteveAK on 06/24/16
 *  Copyright (c) 2016 SteveAK. All rights reserved.
 *  Contact email(aer_c@qq.com) or qq(7579476)
 */ 

class Api_Login extends PhalApi_Api {
	public function getRules() {
		'cbThirdLogin' => array(
                'type' => array('name' => 'type', 'type' =>'string', 'require' => true, 'desc' => '登录类型' ),
        ),
        'appThirdLogin' => array(
            'type' => array('name' => 'type', 'type' =>'enum', 'require' => true, 'range' => array('qq'), 'desc' => '登录类型'),
            'usid' => array('name' => 'usid', 'type' =>'string', 'require' => true, 'desc' => '唯一标识'),
            'accessToken' => array('name' => 'access_token', 'type' =>'string', 'require' => true, 'desc' => 'token'),
        ),
	}

	/**
     * 第三方登录
     */
    public function thirdLogin() {
        $sns = DI()->login->get('qq');
        header("Location: " . $sns->getRequestCodeURL());
        exit;
    }

    /**
     * 第三方登录回调
     */
    public function cbThirdLogin() {
        $data = $GLOBALS['LOGIN_DATA'];
        $type = $this->type;

        $sns = DI()->login->get($type);
        $token = $sns->getAccessToken($data['code'], $extend);
        var_dump($token);
        exit;
    }

    /**
     * APP第三方登录
     * @desc 只需将APP端获取到的usid(openId),access_token传给该接口即可
     * @return string token 登录token
     */
    public function appThirdLogin() {
    	//将usid跟数据库进行匹配，如果数据库已经存在就登录，如果不存在就注册用户，如果以登录状态即绑定
    	
        //获取第三方用户信息
        
        $sns = DI()->login->get($this->type);
        $user_info = $sns->getUserInfo($token);
        DI()->logger->log('LoginInfo', $type, $user_info);
    }
}