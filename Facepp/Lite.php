<?php
/** 
 *
 * face++ 接口
 * @author 風 <www@webx32.com>
 */
require_once 'sdk/facepp_sdk.php';

class Facepp_Lite extends Facepp {
    /**
     * @param string $config ['api_key'] api_key
     * @param string $config ['api_secret']  api_secret
     */
    public function __construct($config = NULL) {

        if ($config === NULL) {
            $config = DI()->config->get('app.Facepp');
        }
        $this->api_key = $config['api_key'];
        $this->api_secret = $config['api_secret'];

    }
}