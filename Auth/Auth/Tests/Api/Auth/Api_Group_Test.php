<?php
/**
 * PhpUnderControl_ApiAuthGroup_Test
 *
 * 针对 ../../../Auth/Api/Auth/Group.php Api_Auth_Group 类的PHPUnit单元测试
 *
 * @author: dogstar 20150808
 */

require_once dirname(dirname(dirname(__FILE__))) . '/test_env.php';

if (!class_exists('Api_Auth_Group')) {
    require dirname(dirname(dirname(__FILE__))) . '/../../Auth/Api/Auth/Group.php';
}

class PhpUnderControl_ApiAuthGroup_Test extends PHPUnit_Framework_TestCase
{
    public $apiAuthGroup;

    protected function setUp()
    {
        parent::setUp();

        $this->apiAuthGroup = new Api_Auth_Group();
    }

    protected function tearDown()
    {
    }


    /**
     * @group testGetRules
     */ 
    public function testGetRules()
    {
        $rs = $this->apiAuthGroup->getRules();
    }

    /**
     * @group testGetList
     */ 
    public function testGetList()
    {
        //Step 1. 构建请求URL
        $url = 'service=Auth_Group.getlist';
        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url,array('title'=>1));
        $this->assertNotEmpty($rs);

    }

    /**
     * @group testGetInfo
     */ 
    public function testGetInfo()
    {
        
        //Step 1. 构建请求URL
        $url = 'service=Auth_Group.getinfo';

        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url,array('id'=>1));
        
        $this->assertNotEmpty($rs);
    }

    /**
     * @group testAdd
     */ 
    public function testAdd()
    {
        //Step 1. 构建请求URL
        $url = 'service=Auth_Group.add';
        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url,array('title'=>'管理员'));        
        $this->assertNotEmpty($rs);

    }

    /**
     * @group testEdit
     */ 
    public function testEdit()
    {
        //Step 1. 构建请求URL
        $url = 'service=Auth_Group.edit';
        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url,array('id'=>1,'title'=>'管理员呵呵'));        
        $this->assertNotEmpty($rs);

    }

    /**
     * @group testDel
     */ 
    public function testDel()
    {
        //Step 1. 构建请求URL
        $url = 'service=Auth_Group.del';
        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url,array('ids'=>'1'));  
        $this->assertNotEmpty($rs);

    }

    /**
     * @group testSetRule
     */ 
    public function testSetRules()
    {
           //Step 1. 构建请求URL
        $url = 'service=Auth_Group.setrules';
        //Step 2. 执行请求
        $rs = PhalApiTestRunner::go($url,array('id'=>'1'));  
        $this->assertNotEmpty($rs);
    }

}