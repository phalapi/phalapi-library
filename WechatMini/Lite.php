<?php
use wlt\wxmini;

require_once dirname(__FILE__) . '/WXMiniHelper/ErrorCode.php';
require_once dirname(__FILE__) . '/WXMiniHelper/PKCS7Encoder.php';
require_once dirname(__FILE__) . '/WXMiniHelper/Prpcrypt.php';
require_once dirname(__FILE__) . '/WXMiniHelper/WXBizDataCrypt.php';
require_once dirname(__FILE__) . '/WXMiniHelper/WXLoginHelper.php';

class WechatMini_Lite {

    public function __construct() {
        DI()->loader->addDirs('Library/WechatMini/WechatMini');
    }

    /**
     * 获取会话信息
     *
     * @param string $session3rd 3rd session标识
     * @return boolean/array 未设置缓存时返回FALSE，无会话时返回NULL，正常时返回数组：array('openid' => , 'session_key' => )
     */
    public function getSession($session3rd) {
        $cache = DI()->cache;
        return !empty($cache) ? $cache->get($session3rd) : FALSE;
    }
}
