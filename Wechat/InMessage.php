<?php

class Wechat_InMessage {

    const MSG_TYPE_TEXT = 'text';
    const MSG_TYPE_EVENT= 'event';
    const MSG_TYPE_IMAGE = 'image';
    const MSG_TYPE_VOICE = 'voice';
    const MSG_TYPE_VIDEO = 'video';
    const MSG_TYPE_LOCATION = 'location';
    const MSG_TYPE_LINK = 'link';
    const MSG_TYPE_DEVICE_EVENT = 'device_event';
    const MSG_TYPE_DEVICE_TEXT = 'device_text';

    const MSG_TYPE_UNKNOW = 'unkonw';

    protected $msgType;
    protected $content;
    protected $fromUserName;
    protected $createTime;
    protected $picUrl;

    protected $postData = array();

    public function __construct()
    {
        if (!isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
            throw new PhalApi_Exception_BadRequest(
                T('miss HTTP_RAW_POST_DATA')
            );
        }

        //接受并解析微信中心POST发送XML数据
        $postData = (array) simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA);

        $this->postData = $postData;

        $this->msgType = isset($postData['MsgType']) 
            ? $postData['MsgType'] : self::MSG_TYPE_UNKNOW;

        $this->content = isset($postData['Content']) 
            ? $postData['Content'] : '';

        $this->fromUserName = isset($postData['FromUserName']) 
            ? $postData['FromUserName'] : '';

        $this->createTime = isset($postData['CreateTime']) 
            ? $postData['CreateTime'] : $_SERVER['REQUEST_TIME'];
    }

    public function __call($method, $params)
    {
        if (substr($method, 0, 3) == 'get') {
            $key = ucfirst(substr($method, 3));
            return isset($this->postData[$key]) ? $this->postData[$key] : null;
        }

        throw new PhalApi_Exception_InternalServerError(
            T("Call undefined method WechatInMessage::$method()")
        );
    }

    public function getMsgType()
    {
        return $this->msgType;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getFromUserName()
    {
        return $this->fromUserName;
    }

    public function getCreateTime()
    {
        return $this->createTime;
    }
}
