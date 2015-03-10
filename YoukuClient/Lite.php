<?php
/**
 * 优酷开放平台接口调用
 *
 * 使用示例:
 * 
 *  $youkuClient = new YoukuClient_Lite('https://openapi.youku.com', '******');
 *  $rs = $youkuClient->get('/v2/videos/show.json', array('video_id' => 'XOTA4ODU4NjA0'));
 *
 * @link http://open.youku.com/docs
 * @author dogstar <chanzonghuang@gmail.com> 2015-03-10
 */

class YoukuClient_Lite {

    protected $host;
    protected $clientId;

    protected $curl;

    /**
     * @param string $host 优酷开放平台接口HOST，默认为：https://openapi.youku.com
     * @param string $clientId client_id，可通过在优酷开放平台上创建应用获取
     * @param PhalApi_CUrl $curl 用于实现CURL请求的实例
     */
    public function __construct($host, $clientId, $curl = null)
    {
        $this->host = rtrim($host, '/');
        $this->clientId = $clientId;

        $this->curl = new PhalApi_CUrl();
        if ($curl !== null) {
            $this->curl = $curl;
        }
    }

    /**
     * GET方式的接口请求
     *
     * @param string $uri 除去接口域名的相对路径，如：/v2/videos/show_basic.json
     * @param array $params 传递接口参数
     * @param int $timeoutMs 超时设置，单位：毫秒
     * @return array 失败情况下统一返回空数组
     */
    public function get($uri, $params, $timeoutMs = 3000)
    {
        return $this->request('get', $uri, $params, $timeoutMs);
    }

    /**
     * POST方式的接口请求
     */
    public function post($uri, $params, $timeoutMs = 3000)
    {
        return $this->request('post', $uri, $params, $timeoutMs);
    }

    protected function request($type, $uri, $params, $timeoutMs)
    {
        $url = $this->host . '/' . ltrim($uri, '/');
        $params['client_id'] = $this->clientId;

        $curl = $this->curl;

        $apiRs = null;

        if ($type == 'get') {
            $apiRs = $curl->get($url . '?' . http_build_query($params), $timeoutMs);
        } else {
            $apiRs = $curl->post($url, $params, $timeoutMs);
        }

        if ($apiRs === false) {
            DI()->logger->debug("youku api $type timeout", $url . '?' . http_build_query($params));
            return array();
        }

        $apiRsArr = json_decode($apiRs, true);
        if (empty($apiRsArr) || !is_array($apiRsArr)) {
            DI()->logger->debug("youku api $type nothing return", $url . '?' . http_build_query($params));
            return array();
        }

        if (isset($apiRsArr['error'])) {
            DI()->logger->debug("youku  api $type error", array('error' => $apiRsArr['error'], 'url' => $url . '?' . http_build_query($params)));
        }

        return $apiRsArr;
    }
}
