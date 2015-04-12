<?php
/**
 * PhpUnderControl_ApiUserInfo_Test
 *
 * 针对 ../../Api/User/Info.php Api_User_User_Info 类的PHPUnit单元测试
 *
 * @author: dogstar 20150402
 */

require_once dirname(__FILE__) . '/../../test_env.php';

if (!class_exists('Api_User_User_Info')) {
    require dirname(__FILE__) . '/../../Api/User/User/Info.php';
}

class PhpUnderControl_ApiUserInfo_Test extends PHPUnit_Framework_TestCase
{
    public $apiUserInfo;

    protected function setUp()
    {
        parent::setUp();

        $this->apiUserInfo = new Api_User_User_Info();
    }

    protected function tearDown()
    {
    }


    /**
     * @group testGetRules
     */ 
    public function testGetRules()
    {
        $rs = $this->apiUserInfo->getRules();
    }

    /**
     * @group testGetUserInfo
     */ 
    public function testGetUserInfo()
    {
        //Step 1. 构建请求URL
        $url = 'service=User_User_Info.getUserInfo&other_user_id=1';

        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url);
        //var_dump($rs);

        //Step 3. 验证
        $this->assertNotEmpty($rs);
        $this->assertArrayHasKey('code', $rs);
        $this->assertArrayHasKey('info', $rs);
    }

}
