<?php

/**
 * 2016/1/8 容联云通讯拓展 @喵了个咪<wenzhenxi@vip.qq.com>
 * 支持容联云通讯所有PHPSDK可用内容
 */
class SMS_Lite {

    //主帐号
    private $accountSid = '';

    //主帐号Token
    private $accountToken = '';

    //应用Id
    private $appId = '';

    //REST版本号
    private $softVersion = '2013-12-26';

    //请求端口
    private $serverPort = '8883';

    //请求地址，格式如下，不需要写https://
    private $serverIP = 'sandboxapp.cloopen.com';

    //SDK实例
    private $SMS_SDK_REST;

    //DEBUG
    private $debug;

    /**
     * SMS_Lite 构造方法初始化配置
     *
     * @param  $cofAddress string 配置文件地址
     * @param  $debug      bool   是否开启调试模式
     */
    public function __construct($cofAddress, $debug = false) {

        $this->debug = $debug;

        //获得配置项
        $config = DI()->config->get($cofAddress);
        //配置项是否存在
        if (!$config) {
            throw new PhalApi_Exception_BadRequest(T('Config There is no'));
        }
        //主帐号是否配置
        if ($this->getIndex($config, 'accountSid')) {
            $this->accountSid = $config['accountSid'];
        } else {
            throw new PhalApi_Exception_BadRequest(T('accountSid There is no'));
        }
        //主帐号Token是否配置
        if ($this->getIndex($config, 'accountToken')) {
            $this->accountToken = $config['accountToken'];
        } else {
            throw new PhalApi_Exception_BadRequest(T('accountSid There is no'));
        }
        //应用Id是否配置
        if ($this->getIndex($config, 'appId')) {
            $this->appId = $config['appId'];
        } else {
            throw new PhalApi_Exception_BadRequest(T('accountSid There is no'));
        }
        //请求端口
        if ($this->getIndex($config, 'serverPort')) {
            $this->serverPort = $config['serverPort'];
        }
        //请求地址
        if ($this->getIndex($config, 'serverIP')) {
            $this->serverIP = $config['serverIP'];
        }

        //初始化SDK
        $this->SMS_SDK_REST = new SMS_SDK_REST($this->serverIP, $this->serverPort, $this->softVersion);
        //设置主帐号
        $this->SMS_SDK_REST->setAccount($this->accountSid, $this->accountToken);
        //设置应用ID
        $this->SMS_SDK_REST->setAppId($this->appId);
    }

    /**
     * 语音验证码
     *
     * @param verifyCode 验证码内容，为数字和英文字母，不区分大小写，长度4-8位
     * @param playTimes  播放次数，1－3次
     * @param to         接收号码
     * @param displayNum 显示的主叫号码
     * @param respUrl    语音验证码状态通知回调地址，云通讯平台将向该Url地址发送呼叫结果通知
     * @param lang       语言类型。取值en（英文）、zh（中文），默认值zh。
     * @param userData   第三方私有数据
     */
    function voiceVerify($verifyCode, $playTimes, $to, $displayNum, $respUrl, $lang, $userData) {

        //调用语音验证码接口
        $result = $this->SMS_SDK_REST->voiceVerify($verifyCode, $playTimes, $to, $displayNum, $respUrl, $lang, $userData);
        if ($result == NULL) {
            throw new PhalApi_Exception_BadRequest(T("Error SMS_SDK_REST"));
        }
        //如果是Debug模式则打印返回信息
        if ($this->debug) {
            $this->showarr($result);
        }
        return $result;
    }

    /**
     * 发送模板短信
     *
     * @param to      手机号码集合,用英文逗号分开
     * @param datas   内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param $tempId 模板Id
     */
    function sendTemplateSMS($to, $datas, $tempId) {

        // 发送模板短信
        $result = $this->SMS_SDK_REST->sendTemplateSMS($to, $datas, $tempId);
        if ($result == NULL) {
            throw new PhalApi_Exception_BadRequest(T("Error SMS_SDK_REST"));
        }
        //如果是Debug模式则打印返回信息
        if ($this->debug) {
            $this->showarr($result);
        }
        return $result;
    }

    /**
     * 短信模板查询
     *
     * @param templateId     模板ID
     */
    function QuerySMSTemplate($templateId) {

        // 调用短信模板查询接口
        $result = $this->SMS_SDK_REST->QuerySMSTemplate($templateId);
        if ($result == NULL) {
            throw new PhalApi_Exception_BadRequest(T("Error SMS_SDK_REST"));
        }

        if ($this->debug) {
            $this->showarr($result);
        }
        return $result;
    }

    /**
     * 呼叫状态查询
     *
     * @param callid     呼叫Id
     * @param action     查询结果通知的回调url地址
     */
    function QueryCallState($callid, $action) {

        // 调用呼叫状态查询接口
        $result = $this->SMS_SDK_REST->QueryCallState($callid, $action);
        if ($result == NULL) {
            throw new PhalApi_Exception_BadRequest(T("Error SMS_SDK_REST"));
        }

        if ($this->debug) {
            $this->showarr($result);
        }
        return $result;
    }

    /**
     * 主帐号信息查询
     */
    function queryAccountInfo() {

        // 调用主帐号信息查询接口
        $result = $this->SMS_SDK_REST->queryAccountInfo();
        if ($result == NULL) {
            throw new PhalApi_Exception_BadRequest(T("Error SMS_SDK_REST"));
        }
        if ($this->debug) {
            $this->showarr($result);
        }
        return $result;
    }

    /**
     * 语音文件上传
     *
     * @param filename     文件名
     * @param path         文件所在路径
     */
    function MediaFileUpload($filename, $path) {

        $filePath = $path;
        $fh       = fopen($filePath, "rb");
        $body     = fread($fh, filesize($filePath));
        fclose($fh);

        // 调用语音文件上传接口
        $result = $this->SMS_SDK_REST->MediaFileUpload($filename, $body);
        if ($result == NULL) {
            throw new PhalApi_Exception_BadRequest(T("Error SMS_SDK_REST"));
        }
        if ($this->debug) {
            $this->showarr($result);
        }
        return $result;
    }

    /**
     * 外呼通知
     *
     * @param to          被叫号码
     * @param mediaName   语音文件名称，格式 wav。与mediaTxt不能同时为空。当不为空时mediaTxt属性失效。
     * @param mediaTxt    文本内容
     * @param displayNum  显示的主叫号码
     * @param playTimes   循环播放次数，1－3次，默认播放1次。
     * @param respUrl     外呼通知状态通知回调地址，云通讯平台将向该Url地址发送呼叫结果通知。
     * @param userData    用户私有数据
     * @param maxCallTime 最大通话时长
     * @param speed       发音速度
     * @param volume      音量
     * @param pitch       音调
     * @param bgsound     背景音编号
     */
    function landingCall($to, $mediaName, $mediaTxt, $displayNum, $playTimes, $respUrl, $userData, $maxCallTime, $speed, $volume, $pitch, $bgsound) {

        //调用外呼通知接口
        echo "Try to make a landingcall,called is $to <br/>";
        $result = $this->SMS_SDK_REST->landingCall($to, $mediaName, $mediaTxt, $displayNum, $playTimes, $respUrl, $userData, $maxCallTime, $speed, $volume, $pitch, $bgsound);
        if ($result == NULL) {
            throw new PhalApi_Exception_BadRequest(T("Error SMS_SDK_REST"));
        }
        if ($this->debug) {
            $this->showarr($result);
        }
        return $result;
    }

    /**
     * IVR外呼
     *
     * @param number   待呼叫号码，为Dial节点的属性
     * @param userdata 用户数据，在<startservice>通知中返回，只允许填写数字字符，为Dial节点的属性
     * @param record   是否录音，可填项为true和false，默认值为false不录音，为Dial节点的属性
     */
    function ivrDial($number, $userdata, $record) {

        // 调用IVR外呼接口
        $result = $this->SMS_SDK_REST->ivrDial($number, $userdata, $record);
        if ($result == NULL) {
            throw new PhalApi_Exception_BadRequest(T("Error SMS_SDK_REST"));
        }
        if ($this->debug) {
            $this->showarr($result);
        }
        return $result;
    }

    /**
     * 话单下载
     *
     * @param date       day 代表前一天的数据（从00:00 – 23:59）
     * @param keywords   客户的查询条件，由客户自行定义并提供给云通讯平台。默认不填忽略此参数
     */
    function billRecords($date, $keywords) {

        // 调用话单下载接口
        $result = $this->SMS_SDK_REST->billRecords($date, $keywords);
        if ($result == NULL) {
            throw new PhalApi_Exception_BadRequest(T("Error SMS_SDK_REST"));
        }
        if ($this->debug) {
            $this->showarr($result);
        }
        return $result;
    }

    /**
     * 数组对象取值相关 - 避免出错
     */
    public function getIndex($arr, $key, $default = '') {

        return isset($arr[$key]) ? $arr[$key] : $default;
    }

    /**
     * 直接显示内容数组
     */
    public function showarr($a) {

        echo('<pre>');
        print_r($a);
        echo('</pre>');
    }
}


