<?php
/**
 * demo接口测试入口
 * @author dogstar 2015-01-28
 */
 
/** ---------------- 根目录定义，自动加载 ---------------- **/

require_once dirname(__FILE__) . '/../../Public/init.php';

DI()->loader->addDirs('Auth');

class PhalApiTestRunner {

    /**
     * @param string $url 请求的链接
     * @param array $param 额外POST的数据
     * @return array 接口的返回结果
     */
    public static function go($url, $params = array()) {
        parse_str($url, $urlParams);
        if (!isset($urlParams['service'])) {
            throw new Exception('miss service in url');
        }
        DI()->request = new PhalApi_Request(array_merge($urlParams, $params));

        list($api, $action) = explode('.', $urlParams['service']);
        $class = 'Api_' . $api;

        $apiObj = new $class();
        $apiObj->init();

        $rs = $apiObj->$action();

        return $rs;
    }
}
