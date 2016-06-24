<?php
/**
 *  Lite.php
 *  第三方登录
 *  
 *  Created by SteveAK on 06/21/16
 *  Copyright (c) 2016 SteveAK. All rights reserved.
 *  Contact email(aer_c@qq.com) or qq(7579476)
 */ 

class Login_Lite {
	public function __call($method, $arguments) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array(&$this, $method), $arguments);
        }
    }

    /**
     * 取得Oauth实例
     * @return mixed 返回Oauth
     */
    public function get($type, $token = null) {
    	$name = 'Login_Engine_' . ucfirst(strtolower($type));
    	
    	if (class_exists($name)) {
    		return new $name($token);
    	} else {
    		throw new PhalApi_Exception_BadRequest('Not defined class:' . $name);
    	}
    }
}