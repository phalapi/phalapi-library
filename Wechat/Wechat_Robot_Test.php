<?php
/**
 * PhpUnderControl_WechatRobot_Test
 *
 * 针对 ../Wechat/Robot.php Wechat_Robot 类的PHPUnit单元测试
 *
 * @author: dogstar 20150122
 */

//require_once dirname(__FILE__) . '/test_env.php';

if (!class_exists('Wechat_Robot')) {
    require dirname(__FILE__) . '/../../Wechat/Robot.php';
}

class PhpUnderControl_WechatRobot_Test extends PHPUnit_Framework_TestCase
{
    public $wechatRobot;

    protected function setUp()
    {
        parent::setUp();

    }

    protected function tearDown()
    {
    }


    /**
     * @group testRun
     */ 
    public function testRun()
    {
        $_SERVER['REQUEST_TIME'] = 1419757723;

        $GLOBALS['HTTP_RAW_POST_DATA'] = '<xml><ToUserName><![CDATA[gh_43235ff1360f]]></ToUserName>
<FromUserName><![CDATA[oWNXvjipYqRViMpO8GZwXxE43pUY]]></FromUserName>
<CreateTime>1419757723</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[一个人]]></Content>
<MsgId>6097812988731466682</MsgId>
</xml>';
        $wechatRobot = new Wechat_Robot_Mock('***', true);
        $rs = $wechatRobot->run();

        $expRs = "<xml>
<ToUserName><![CDATA[oWNXvjipYqRViMpO8GZwXxE43pUY]]></ToUserName>
<FromUserName><![CDATA[gh_43235ff1360f]]></FromUserName>
<CreateTime>1419757723</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[我接收到了信息: 一个人]]></Content>
<FuncFlag>0</FuncFlag>
</xml>";

        $this->assertEquals($expRs, strval($rs));
    }

}

class Wechat_Robot_Mock extends Wechat_Robot {

    protected function handleText($inMessage, &$outMessage)
    {
        $this->hanleWhat(Wechat_InMessage::MSG_TYPE_TEXT, $inMessage, $outMessage);
    }

    protected function handleImage($inMessage, &$outMessage)
    {
        $this->hanleWhat(Wechat_InMessage::MSG_TYPE_IMAGE, $inMessage, $outMessage);
    }

    protected function handleVoice($inMessage, &$outMessage)
    {
        $this->hanleWhat(Wechat_InMessage::MSG_TYPE_VOICE, $inMessage, $outMessage);
    }

    protected function handleVideo($inMessage, &$outMessage)
    {
        $this->hanleWhat(Wechat_InMessage::MSG_TYPE_VIDEO, $inMessage, $outMessage);
    }

    protected function handleLocation($inMessage, &$outMessage)
    {
        $this->hanleWhat(Wechat_InMessage::MSG_TYPE_LOCATION, $inMessage, $outMessage);
    }

    protected function handleLink($inMessage, &$outMessage)
    {
        $this->hanleWhat(Wechat_InMessage::MSG_TYPE_LINK, $inMessage, $outMessage);
    }

    protected function handleEvent($inMessage, &$outMessage)
    {
        $this->hanleWhat(Wechat_InMessage::MSG_TYPE_EVENT, $inMessage, $outMessage);
    }

    protected function handleDeviceEvent($inMessage, &$outMessage)
    {
        $this->handleWhat(Wechat_InMessage::MSG_TYPE_DEVICE_EVENT, $inMessage, $outMessage);
    }

    protected function handleDeviceText($inMessage, &$outMessage)
    {
        $this->handleWhat(Wechat_InMessage::MSG_TYPE_DEVICE_TEXT, $inMessage, $outMessage);
    }

    protected function hanleWhat($type, $inMessage, &$outMessage) {
        $outMessage = new Wechat_OutMessage_Text();
        $outMessage->setContent('我接收到了信息: ' . $inMessage->getContent());
    }
}
