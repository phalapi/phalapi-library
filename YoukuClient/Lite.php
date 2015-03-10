<?php
/**
 * @author dogstar <chanzonghuang@gmail.com> 2015-03-10
 */

class YoukuClient_Lite {

    protected $host;
    protected $clientId;

    protected $curl;

    public function __construct($host, $clientId, $curl = null)
    {
        $this->host = rtrim($host, '/');
        $this->clientId = $clientId;

        $this->curl = new PhalApi_CUrl();
        if ($curl !== null) {
            $this->curl = $curl;
        }
    }

    public function get($uri, $params, $timeoutMs = 3000)
    {
        return $this->request('get', $uri, $params, $timeoutMs);
    }

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
