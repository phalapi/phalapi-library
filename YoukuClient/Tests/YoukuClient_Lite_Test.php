<?php
/**
 * PhpUnderControl_YoukuClientLite_Test
 *
 * 针对 ../Lite.php YoukuClient_Lite 类的PHPUnit单元测试
 *
 * @author: dogstar 20150310
 */

require_once dirname(__FILE__) . '/test_env.php';

if (!class_exists('YoukuClient_Lite')) {
    require dirname(__FILE__) . '/../Lite.php';
}

class PhpUnderControl_YoukuClientLite_Test extends PHPUnit_Framework_TestCase
{
    public $youkuClientLite;

    protected function setUp()
    {
        parent::setUp();

        $this->youkuClientLite = new YoukuClient_Lite('https://openapi.youku.com', 'b043a60fbef8aed0');
    }

    protected function tearDown()
    {
    }


    /**
     * @group testGet
     */ 
    public function testGet()
    {
        $uri = '/v2/videos/show_basic.json';
        $params = array(
            'video_url' => 'http://v.youku.com/v_show/id_XOTA4ODU4NjA0.html'
        );
        $timeoutMs = 3000;

        $rs = $this->youkuClientLite->get($uri, $params, $timeoutMs);

        //var_dump($rs);
    }

    /**
     * @group testPost
     */ 
    public function testPost()
    {
        $uri = '';
        $params = '';
        $timeoutMs = '';

        $rs = $this->youkuClientLite->post($uri, $params, $timeoutMs);
    }

}
