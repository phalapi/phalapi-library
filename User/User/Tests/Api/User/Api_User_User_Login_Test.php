<?php
/**
 * PhpUnderControl_ApiUserLogin_Test
 *
 * 针对 ../../Api/User/Login.php Api_User_User_Login 类的PHPUnit单元测试
 *
 * @author: dogstar 20150328
 */

require_once dirname(__FILE__) . '/../../test_env.php';

if (!class_exists('Api_User_User_Login')) {
    require dirname(__FILE__) . '/../../Api/User/User/Login.php';
}

class PhpUnderControl_ApiUserLogin_Test extends PHPUnit_Framework_TestCase
{
    public $apiUserLogin;

    protected function setUp()
    {
        parent::setUp();

        $this->apiUserLogin = new Api_User_User_Login();

    }

    protected function tearDown()
    {
    }


    /**
     * @group testGetRules
     */ 
    public function testGetRules()
    {
        $rs = $this->apiUserLogin->getRules();
        $this->assertTrue(is_array($rs));
    }

    /**
     * @group testWeixin
     */ 
    public function testWeixin()
    {
        //Step 1. 构建请求URL
        $url = 'service=User_User_Login.weixin&wx_openid=wx_122348561111&wx_token=ASDF&wx_expires_in=130000000&name=weixinName&avatar=phpunit.png';

        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url);
        //var_dump($rs);

        //Step 3. 验证
        $this->assertNotEmpty($rs);
        $this->assertArrayHasKey('code', $rs);
        $this->assertArrayHasKey('user_id', $rs['info']);
        $this->assertArrayHasKey('token', $rs['info']);
        $this->assertArrayHasKey('is_new', $rs['info']);

        $this->assertEquals(0, $rs['info']['is_new']);
    }

    /**
     * @group testSina
     */ 
    public function testSina()
    {
        //Step 1. 构建请求URL
        $url = 'service=User_User_Login.sina&sina_openid=sina_12345611111&sina_token=ASDF&sina_expires_in=130000000&name=sinaName&avatar=http://dev.phalapi.com/no_avatar.png';

        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url);
        //var_dump($rs);

        //Step 3. 验证
        $this->assertNotEmpty($rs);
        $this->assertArrayHasKey('code', $rs);
        $this->assertArrayHasKey('user_id', $rs['info']);
        $this->assertArrayHasKey('token', $rs['info']);
        $this->assertArrayHasKey('is_new', $rs['info']);

        $this->assertEquals(0, $rs['info']['is_new']);
    }

    /**
     * @group testQq
     */ 
    public function testQq()
    {
        //Step 1. 构建请求URL
        $url = 'service=User_User_Login.qq&qq_openid=qq_123456&qq_token=ASDF&qq_expires_in=130000000&name=qqName&avatar=http://dev.phalapi.com/no_avatar.png';

        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url);
        //var_dump($rs);

        //Step 3. 验证
        $this->assertNotEmpty($rs);
        $this->assertArrayHasKey('code', $rs);
        $this->assertArrayHasKey('user_id', $rs['info']);
        $this->assertArrayHasKey('token', $rs['info']);
        $this->assertArrayHasKey('is_new', $rs['info']);

        $this->assertEquals(0, $rs['info']['is_new']);
    }
}
