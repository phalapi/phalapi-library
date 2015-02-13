<?php
/**
 * PhpUnderControl_WechatOutMessageNews_Test
 *
 * 针对 ../../../../Weixin/Wechat/OutMessage/News.php Wechat_OutMessage_News 类的PHPUnit单元测试
 *
 * @author: dogstar 20141228
 */

//require_once dirname(__FILE__) . '/test_env.php';
require_once dirname(__FILE__) . '/../../../Wechat/OutMessage.php';
require_once dirname(__FILE__) . '/../../../Wechat/OutMessage/News/Item.php';

if (!class_exists('Wechat_OutMessage_News')) {
    require dirname(__FILE__) . '/../../../Wechat/OutMessage/News.php';
}

class PhpUnderControl_WechatOutMessageNews_Test extends PHPUnit_Framework_TestCase
{
    public $wechatOutMessageNews;

    protected function setUp()
    {
        parent::setUp();

        $this->wechatOutMessageNews = new Wechat_OutMessage_News();
    }

    protected function tearDown()
    {
    }

    public function testResponse()
    {
        $_SERVER['REQUEST_TIME'] = 123456;

        $wechatOutMessageNews = new Wechat_OutMessage_News();

        $item1 = new Wechat_OutMessage_News_Item();
        $item1->setTitle('T1')->setDescription('D1')->setPicUrl('www.baidu.com');

        $item2 = new Wechat_OutMessage_News_Item();
        $item2->setTitle('T2')->setDescription('D2');

        $wechatOutMessageNews->addItem($item1)->addItem($item2);

        $expRs = "<xml>
<ToUserName><![CDATA[]]></ToUserName>
<FromUserName><![CDATA[]]></FromUserName>
<CreateTime>123456</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>2</ArticleCount>
<Articles>
<item>
<Title><![CDATA[T1]]></Title>
<Description><![CDATA[D1]]></Description>
<PicUrl><![CDATA[www.baidu.com]]></PicUrl>
<Url><![CDATA[]]></Url>
</item>
<item>
<Title><![CDATA[T2]]></Title>
<Description><![CDATA[D2]]></Description>
<PicUrl><![CDATA[]]></PicUrl>
<Url><![CDATA[]]></Url>
</item>
</Articles>
<FuncFlag>0</FuncFlag>
</xml>";

        $this->assertEquals($expRs, $wechatOutMessageNews->response());
    }

    /**
     * @group testAddItem
     */ 
    public function testAddItem()
    {
        $item = new Wechat_OutMessage_News_Item();

        $rs = $this->wechatOutMessageNews->addItem($item);
    }

}
