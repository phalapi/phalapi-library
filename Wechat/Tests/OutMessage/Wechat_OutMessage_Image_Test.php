<?php
/**
 * PhpUnderControl_WechatOutMessageImage_Test
 *
 * 针对 ../../../../Weixin/Wechat/OutMessage/Image.php Wechat_OutMessage_Image 类的PHPUnit单元测试
 *
 * @author: dogstar 20141228
 */

//require_once dirname(__FILE__) . '/test_env.php';
require_once dirname(__FILE__) . '/../../..//Wechat/OutMessage.php';

if (!class_exists('Wechat_OutMessage_Image')) {
    require dirname(__FILE__) . '/../../../Wechat/OutMessage/Image.php';
}

class PhpUnderControl_WechatOutMessageImage_Test extends PHPUnit_Framework_TestCase
{
    public $wechatOutMessageImage;

    protected function setUp()
    {
        parent::setUp();

        $this->wechatOutMessageImage = new Wechat_OutMessage_Image();
    }

    protected function tearDown()
    {
    }


    public function testResponse()
    {
        $_SERVER['REQUEST_TIME'] = 1419763711;

        $wechatOutMessageImage = new Wechat_OutMessage_Image();
        $wechatOutMessageImage->setMediaId('phpunit');

        $expRs = "<xml>
<ToUserName><![CDATA[]]></ToUserName>
<FromUserName><![CDATA[]]></FromUserName>
<CreateTime>1419763711</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
<Image>
<MediaId><![CDATA[phpunit]]></MediaId>
</Image>
<FuncFlag>0</FuncFlag>
</xml>";

        $this->assertEquals($expRs, $wechatOutMessageImage->response());
    }

    /**
     * @group testSetMediaId
     */ 
    public function testSetMediaId()
    {
        $mediaId = 'XXX';

        $rs = $this->wechatOutMessageImage->setMediaId($mediaId);
    }

}
