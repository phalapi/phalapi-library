<?php
/**
 * PhpUnderControl_Wechat_InMessage_Test
 *
 * 针对 ../../../Weixin/Wechat/InMessage.php Wechat_InMessage 类的PHPUnit单元测试
 *
 * @author: dogstar 20141228
 */

//require_once dirname(__FILE__) . '/test_env.php';

if (!class_exists('Wechat_InMessage')) {
    require dirname(__FILE__) . '/../../Wechat/InMessage.php';
}

class PhpUnderControl_Wechat_InMessage_Test extends PHPUnit_Framework_TestCase
{
    public $wechatInMessage;

    protected function setUp()
    {
        parent::setUp();

        $GLOBALS['HTTP_RAW_POST_DATA'] = '<xml><ToUserName><![CDATA[gh_43235ff1360f]]></ToUserName>
<FromUserName><![CDATA[oWNXvjipYqRViMpO8GZwXxE43pUY]]></FromUserName>
<CreateTime>1419757723</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[一个人]]></Content>
<MsgId>6097812988731466682</MsgId>
</xml>';
        $this->wechatInMessage = new Wechat_InMessage();
    }

    protected function tearDown()
    {
    }


    /**
     * @group testGetMsgType
     */ 
    public function testGetMsgType()
    {
        $rs = $this->wechatInMessage->getMsgType();
        $this->assertEquals(Wechat_InMessage::MSG_TYPE_TEXT, $rs);
    }

    /**
     * @group testGetContent
     */ 
    public function testGetContent()
    {
        $rs = $this->wechatInMessage->getContent();
        $this->assertEquals('一个人', $rs);
    }

    /**
     * @group testGetFromUserName
     */ 
    public function testGetFromUserName()
    {
        $rs = $this->wechatInMessage->getFromUserName();
        $this->assertEquals('oWNXvjipYqRViMpO8GZwXxE43pUY', $rs);
    }

    /**
     * @group testGetCreateTime
     */ 
    public function testGetCreateTime()
    {
        $rs = $this->wechatInMessage->getCreateTime();
        $this->assertEquals(1419757723, $rs);
    }

    public function testImageMsg()
    {
        $GLOBALS['HTTP_RAW_POST_DATA'] = '<xml><ToUserName><![CDATA[gh_43235ff1360f]]></ToUserName>
<FromUserName><![CDATA[oWNXvjipYqRViMpO8GZwXxE43pUY]]></FromUserName>
<CreateTime>1419758800</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/q1RichwwGvembm9ICVyWiajhYIWBJBlfZpkunc5fYthiaQp3aHFVzJc0N0qerwsaHszU4JUFlTrWNf9icULGSkB3LA/0]]></PicUrl>
<MsgId>6097817614411245017</MsgId>
<MediaId><![CDATA[u5IiALYQeRRBBint89EjKAdJ9DBN3Ouimiq-e19bTmJHPtm794q2Xpy0-niECQWo]]></MediaId>
</xml>';

        $wechatInMessage = new Wechat_InMessage();
    
        $this->assertEquals(Wechat_InMessage::MSG_TYPE_IMAGE, $wechatInMessage->getMsgType());
        $this->assertEquals('http://mmbiz.qpic.cn/mmbiz/q1RichwwGvembm9ICVyWiajhYIWBJBlfZpkunc5fYthiaQp3aHFVzJc0N0qerwsaHszU4JUFlTrWNf9icULGSkB3LA/0', $wechatInMessage->getPicUrl());
    }

    public function testVoiceMsg()
    {
        $GLOBALS['HTTP_RAW_POST_DATA'] = '<xml><ToUserName><![CDATA[gh_43235ff1360f]]></ToUserName>
<FromUserName><![CDATA[oWNXvjipYqRViMpO8GZwXxE43pUY]]></FromUserName>
<CreateTime>1419759468</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
<MediaId><![CDATA[fwGXgqFETvBRyh1LHivKGd0juSiXUDvFzzzoKyNgKO_oyU6dD_CrB3mFQGt2BcQN]]></MediaId>
<Format><![CDATA[amr]]></Format>
<MsgId>6097820483449399071</MsgId>
<Recognition><![CDATA[]]></Recognition>
</xml>';

        $wechatInMessage = new Wechat_InMessage();

        $this->assertEquals(Wechat_InMessage::MSG_TYPE_VOICE, $wechatInMessage->getMsgType());
    }

}
