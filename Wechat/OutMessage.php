<?php
/**
 * @link: http://www.oschina.net/p/lanewechat
 * @author: dogstar 2014-12-28
 */

abstract class Wechat_OutMessage {

    protected $fromUserName;

    protected $toUserName;

    protected $createTime;

    protected $funcFlag;

    public function __construct()
    {
        $this->createTime = $_SERVER['REQUEST_TIME'];
        $this->funcFlag = 0;
    }

    /**
     * 为PhalApi保持一致的风格，这里添加response别名
     */
    public function output() {
        echo $this->response();
    }

    /**
     * @param $fromusername
     * @param $tousername
     * @param $funcFlag 默认为0，设为1时星标刚才收到的消息
     * @return string
     */
    public function response()
    {
        $template = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
%s
<FuncFlag>%s</FuncFlag>
</xml>";

    return sprintf($template, 
        $this->fromUserName, $this->toUserName, $this->createTime, $this->doResponse(), $this->funcFlag);
    }

    abstract protected function doResponse();

    public function setFromUserName($fromUserName)
    {
        $this->fromUserName = $fromUserName;
    }

    public function setToUserName($toUserName)
    {
        $this->toUserName = $toUserName;
    }

    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    }

    public function setFuncFlag($funcFlag)
    {
        $this->funcFlag = $funcFlag;
    }

    public function __toString()
    {
        return $this->response();
    }
}
