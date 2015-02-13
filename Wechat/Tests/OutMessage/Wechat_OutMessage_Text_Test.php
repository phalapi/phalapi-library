<?php
/**
 * PhpUnderControl_WechatOutMessageText_Test
 *
 * 针对 ../../../../ThirdParty/Wechat/WechatOutMessage/Text.php WechatOutMessage_Text 类的PHPUnit单元测试
 *
 * @author: dogstar 20141228
 */

//require_once dirname(__FILE__) . '/test_env.php';
require_once dirname(__FILE__) . '/../../../Wechat/OutMessage.php';

if (!class_exists('WechatOutMessage_Text')) {
    require dirname(__FILE__) . '/../../../Wechat/OutMessage/Text.php';
}

class PhpUnderControl_WechatOutMessageText_Test extends PHPUnit_Framework_TestCase
{
    public $wechatOutMessageText;

    protected function setUp()
    {
        parent::setUp();

        $this->wechatOutMessageText = new Wechat_OutMessage_Text();
    }

    protected function tearDown()
    {
        $_SERVER['REQUEST_TIME'] = time();
    }


    /**
     * @group testResponse
     */ 
    public function testResponse()
    {
        $_SERVER['REQUEST_TIME'] = '123456';

        $wechatOutMessageText = new Wechat_OutMessage_Text();

        $wechatOutMessageText->setContent('phupnit');
        $wechatOutMessageText->setToUserName('123');
        $wechatOutMessageText->setFromUserName('456');

        $rs = $wechatOutMessageText->response();

        $expRs = '<xml>
<ToUserName><![CDATA[456]]></ToUserName>
<FromUserName><![CDATA[123]]></FromUserName>
<CreateTime>123456</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[phupnit]]></Content>
<FuncFlag>0</FuncFlag>
</xml>';
        $this->assertEquals($expRs, $rs);
    }

    /**
     * @group testSetContent
     */ 
    public function testSetContent()
    {
        $content = 'XXX';

        $rs = $this->wechatOutMessageText->setContent($content);
    }

    public function testTextEquals()
    {
        $t1 = new Wechat_OutMessage_Text();
        $t1->setContent('phpunit');

        $t2 = new Wechat_OutMessage_Text();
        $t2->setContent('phpunit');

        $this->assertEquals($t1, $t2);
    }

    public function testTextNotEquals()
    {
        $t1 = new Wechat_OutMessage_Text();
        $t1->setContent('phpunit');

        $t2 = new Wechat_OutMessage_Text();
        $t2->setContent('phpunitXXXX');

        $this->assertNotEquals($t1, $t2);

    }

    public function testToString()
    {
        $_SERVER['REQUEST_TIME'] = 1420193419;

        $text = new Wechat_OutMessage_Text();
        $text->setContent('php');

        $expRs = "<xml>
<ToUserName><![CDATA[]]></ToUserName>
<FromUserName><![CDATA[]]></FromUserName>
<CreateTime>1420193419</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[php]]></Content>
<FuncFlag>0</FuncFlag>
</xml>";

        $this->assertEquals($expRs, strval($text));
    }
}
