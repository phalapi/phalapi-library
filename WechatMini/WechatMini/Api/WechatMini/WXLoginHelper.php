<?php
/**
 * 微信小程序登录
 *
 * @return int code 操作状态码，0表示成功，否则表示失败
 * @return string openid 微信openid，成功时才返回此字段
 * @return string session3rd 3rd session标识，成功时才返回此字段
 * @return string message 失败时的提示信息，调试模式下透传微信接口返回的错误信息
 */

class Api_WechatMini_WXLoginHelper extends PhalApi_Api {

    public function getRules() {
        return array(
            'checkLogin' => array(
                'code' => array('name' => 'code', 'require' => true, 'desc' => '微信登录凭证'),
            ),
            'checkSession' => array(
                'session3rd' => array('name' => 'session3rd', 'require' => true, 'desc' => '3rd session标识'),
            ),
        );
    }

    /**
     * 小程序登录接口
     *
     * @desc 用于小程序初次登录
     */
    public function checkLogin() {
        $wxHelper = new wlt\wxmini\WXLoginHelper();
        return $wxHelper->checkLogin($this->code);
    }

    /**
     * 小程序会话检测接口
     *
     * @desc 在调用登录接口后，判断用户是否已登录且在有效会话期间内
     */
    public function checkSession() {
        $wxHelper = new wlt\wxmini\WXLoginHelper();
        return $wxHelper->checkSession($this->session3rd);
    }
}
