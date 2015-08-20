<?php
/**
 * PhpUnderControl_ApiAuthRule_Test
 *
 * 针对 ../../../Auth/Api/Auth/Rule.php Api_Auth_Rule 类的PHPUnit单元测试
 *
 * @author: dogstar 20150808
 */

require_once dirname(dirname(dirname(__FILE__))) . '/test_env.php';

if (!class_exists('Api_Auth_Rule')) {
    require dirname(dirname(dirname(__FILE__))) . '/../../Auth/Api/Auth/Rule.php';
}

class PhpUnderControl_ApiAuthRule_Test extends PHPUnit_Framework_TestCase
{
    public $apiAuthRule;

    protected function setUp()
    {
        parent::setUp();

        $this->apiAuthRule = new Api_Auth_Rule();
    }

    protected function tearDown()
    {
    }


    /**
     * @group testGetRules
     */
    public function testGetRules()
    {
        $rs = $this->apiAuthRule->getRules();
    }

    /**
     * @group testGetList
     */
    public function testGetList()
    {
        //Step 1. 构建请求URL
        $url = 'service=Auth_Rule.getlist';
        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url);
        $this->assertNotEmpty($rs);
    }

    /**
     * @group testGetInfo
     */
    public function testGetInfo()
    {
        //Step 1. 构建请求URL
        $url = 'service=Auth_Rule.getinfo';
        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url,array('id'=>1));
        $this->assertNotEmpty($rs);
    }

    /**
     * @group testCreate
     */
    public function testAdd()
    {
        //Step 1. 构建请求URL
        $url = 'service=Auth_Rule.add';
        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url,array('name'=>'demo_index.index'));
        $this->assertNotEmpty($rs);
    }

    /**
     * @group testModify
     */
    public function testEdit()
    {
         //Step 1. 构建请求URL
        $url = 'service=Auth_Rule.edit';
        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url,array('id'=>1,'name'=>'demo_index.index'));
        $this->assertNotEmpty($rs);
    }

    /**
     * @group testDelete
     */
    public function testDel()
    {
        //Step 1. 构建请求URL
        $url = 'service=Auth_Rule.del';
        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url,array('ids'=>1));
        $this->assertNotEmpty($rs);
    }

}