<?php

/**
 * SabreXml 拓展类
 * @author: 喵了个咪 <wenzhenxi@vip.qq.com> 2017-5-29
 *
 *  官网地址: http://sabre.io/xml/
 *        DI()->response = new SabreXml_Response();
 */

include 'vendor/autoload.php';

class SabreXml_Response extends PhalApi_Response{

    public function __construct() {
        $this->addHeaders('Content-Type', 'text/xml');
    }
    protected function formatResult($result) {
        $service = new Sabre\Xml\Service();
        return $service->write("Response",$result);

    }
}