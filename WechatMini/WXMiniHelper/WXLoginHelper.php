<?php
namespace wlt\wxmini;

/**
 * Created by PhpStorm.
 * User: raid
 * Date: 2017/1/13
 * Time: 17:16
 * @modified dogstar 20170511
 */

class WXLoginHelper {

    //默认配置
    protected $config = array(
        'url' => "https://api.weixin.qq.com/sns/jscode2session", //微信获取session_key接口url
        'appid' => 'your appId', // APPId
        'secret' => 'your secret', // 秘钥
        'grant_type' => 'authorization_code', // grant_type，一般情况下固定的
    );


    /**
     * 构造函数
     * WXLoginHelper constructor.
     */
    public function __construct() {
        //可设置配置项 wxmini, 此配置项为数组。
        if ($wx = DI()->config->get('app.WechatMini')) {
            $this->config = array_merge($this->config, $wx);
        }
    }

    public function checkLogin($code) {
        /**
         * 4.server调用微信提供的jsoncode2session接口获取openid, session_key, 调用失败应给予客户端反馈
         * , 微信侧返回错误则可判断为恶意请求, 可以不返回. 微信文档链接
         * 这是一个 HTTP 接口，开发者服务器使用登录凭证 code 获取 session_key 和 openid。其中 session_key 是对用户数据进行加密签名的密钥。
         * 为了自身应用安全，session_key 不应该在网络上传输。
         * 接口地址："https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code"
         */

        $params = array(
            'appid' => $this->config['appid'],
            'secret' => $this->config['secret'],
            'js_code' => $code,
            'grant_type' => $this->config['grant_type']
        );

        $res = $this->makeRequest($this->config['url'], $params);

        DI()->logger->debug('WXLoginHelper finish to makeRequest', array('code' => $code, 'res' => $res));

        if ($res['code'] !== 200 || !isset($res['result']) || !isset($res['result'])) {
            DI()->logger->error('WXLoginHelper fail to makeRequest', array('code' => $code, 'res' => $res));

            return array('code'=>ErrorCode::$RequestTokenFailed, 'message'=>'请求Token失败');
        }

        // 正常返回：{"session_key":"oUPOucWX85xL8yMA5q2hIw==","expires_in":7200,"openid":"oh1Mb0W63bd6u5rpiJ6eTqrYnYOc"}
        // 失败返回：{"errcode":41004,"errmsg":"appsecret missing, hints: [ req_id: laaDaA0380s166 ]"}

        $reqData = json_decode($res['result'], true);
        if (isset($reqData['errcode'])) {
            return DI()->debug 
                ? array('code' => $reqData['errcode'],'message' => $reqData['errmsg'])
                : array('code'=>ErrorCode::$RequestTokenFailed, 'message'=>'请求Token失败');
        }

        $sessionKey = $reqData['session_key'];
        $expiresIn = $reqData['expires_in'];
        $openId = $reqData['openid'];

        /**
         * 生成3rd_session
         */
        $session3rd = $this->randomFromDev(16);

        $cache = DI()->cache;
        if (!empty($cache)) {
            $cache->set($session3rd, array('openid' => $openId, 'session_key' => $sessionKey), $expiresIn);
        }

        return array('code' => ErrorCode::$OK, 'openid' => $openId, 'session3rd' => $session3rd);
    }


    /**
     * 发起http请求
     * @param string $url 访问路径
     * @param array $params 参数，该数组多于1个，表示为POST
     * @param int $expire 请求超时时间
     * @param array $extend 请求伪造包头参数
     * @param string $hostIp HOST的地址
     * @return array    返回的为一个请求状态，一个内容
     */
    protected function makeRequest($url, $params = array(), $expire = 0, $extend = array(), $hostIp = '')
    {
        if (empty($url)) {
            return array('code' => '100');
        }

        $_curl = curl_init();
        $_header = array(
            'Accept-Language: zh-CN',
            'Connection: Keep-Alive',
            'Cache-Control: no-cache'
        );
        // 方便直接访问要设置host的地址
        if (!empty($hostIp)) {
            $urlInfo = parse_url($url);
            if (empty($urlInfo['host'])) {
                $urlInfo['host'] = substr(DOMAIN, 7, -1);
                $url = "http://{$hostIp}{$url}";
            } else {
                $url = str_replace($urlInfo['host'], $hostIp, $url);
            }
            $_header[] = "Host: {$urlInfo['host']}";
        }

        // 只要第二个参数传了值之后，就是POST的
        if (!empty($params)) {
            curl_setopt($_curl, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($_curl, CURLOPT_POST, true);
        }

        if (substr($url, 0, 8) == 'https://') {
            curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($_curl, CURLOPT_URL, $url);
        curl_setopt($_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($_curl, CURLOPT_USERAGENT, 'API PHP CURL');
        curl_setopt($_curl, CURLOPT_HTTPHEADER, $_header);

        if ($expire > 0) {
            curl_setopt($_curl, CURLOPT_TIMEOUT, $expire); // 处理超时时间
            curl_setopt($_curl, CURLOPT_CONNECTTIMEOUT, $expire); // 建立连接超时时间
        }

        // 额外的配置
        if (!empty($extend)) {
            curl_setopt_array($_curl, $extend);
        }

        $result['result'] = curl_exec($_curl);
        $result['code'] = curl_getinfo($_curl, CURLINFO_HTTP_CODE);
        $result['info'] = curl_getinfo($_curl);
        if ($result['result'] === false) {
            $result['result'] = curl_error($_curl);
            $result['code'] = -curl_errno($_curl);
        }

        curl_close($_curl);
        return $result;
    }

    /**
     * 读取/dev/urandom获取随机数
     * @param $len
     * @return mixed|string
     */
    protected function randomFromDev($len) {
        $fp = @fopen('/dev/urandom','rb');
        $result = '';
        if ($fp !== FALSE) {
            $result .= @fread($fp, $len);
            @fclose($fp);
        }
        else
        {
            trigger_error('Can not open /dev/urandom.');
        }
        // convert from binary to string
        $result = base64_encode($result);
        // remove none url chars
        $result = strtr($result, '+/', '-_');

        return substr($result, 0, $len);
    }

    public function checkSession($session3rd) {
        $cache = DI()->cache;
        if (empty($cache)) {
            return array('code' => -1, 'message' => '服务器缓存未设置');
        }

        $data = $cache->get($session3rd);
        if (empty($data)) {
            return array('code' => -2, 'message' => '登录态已过期');
        }

        return array('code' => ErrorCode::$OK, 'openid' => $data['openid'], 'session3rd' => $session3rd);
    }
}
