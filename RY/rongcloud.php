<?php
/**
 * 融云 Server API PHP 客户端
 * create by kitName
 * create datetime : 2016-09-05 
 * 
 * v2.0.1
 */

class RY_rongcloud
{
    /**
     * 参数初始化
     * @param $appKey
     * @param $appSecret
     * @param string $format
     */
    public function __construct($appKey, $appSecret, $format = 'json') {
        $this->SendRequest = new RY_SendRequest($appKey, $appSecret, $format);
    }
    
    public function User() {
        $User = new RY_Methods_User($this->SendRequest);
        return $User;
    }
    
    public function Message() {
        $Message = new RongYun_Methods_Message($this->SendRequest);
        return $Message;
    }
    
    public function Wordfilter() {
        $Wordfilter = new RongYun_Methods_Wordfilter($this->SendRequest);
        return $Wordfilter;
    }
    
    public function Group() {
        $Group = new RongYun_Methods_Group($this->SendRequest);
        return $Group;
    }
    
    public function Chatroom() {
        $Chatroom = new RongYun_Methods_Chatroom($this->SendRequest);
        return $Chatroom;
    }
    
    public function Push() {
        $Push = new RongYun_Methods_Push($this->SendRequest);
        return $Push;
    }
    
    public function SMS() {
        $SMS = new RongYun_Methods_SMS($this->SendRequest);
        return $SMS;
    }
    
}