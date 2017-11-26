<?php
/**
 * 图灵机器人接口调用
 *
 * 参考：http://www.tuling123.com/help/h_cent_webapi.jhtml?nav=doc
 *
 * @author: andy 2017-11-25
 */



class Tuling123_Lite {

    protected $config;

    private $tingling123_host = "http://www.tuling123.com/openapi/api";
    /**
     * @param string $key  图灵机器人key
     */
    public function __construct($key = NULL) {
        $this->key = $key;

        if ($this->key === NULL) {
            $this->key = DI()->config->get('app.Tuling123.key');
        }

        DI()->loader->addDirs('./Library/Tuling123/Tuling123');
    }


    //组装请求参数
    private function getUrl($info)
    {
        $url = $this->tingling123_host;
        $url .= "?";
        $url .= "info=" . $info;
        $url .= "&key=" . $this->key;
        return $url;
    }


    //发送信息
    public function send($info)
    {
        $url = $this->getUrl($info);


        $curl = new PhalApi_CUrl();
        $response = $curl->get($url, 3000);

        return @json_decode($response, true);
    }
}
