#!/usr/bin/env php
<?php

if ($argc < 2) {
        echo "Usage: $argv[0] <url> [POST data]\n";
            die();
}
$url = $argv[1];
$params = array();
if (isset($argv[2])) {
        parse_str($argv[2], $params);
}

try {
    $client = new SoapClient(null,
        array(
            'location' => $url,
            'uri'      => $url,
        )
    );

    $data = $client->__soapCall('response', array(json_encode($params)));

    //处理返回的数据。。。
    var_dump($data);
}catch(SoapFault $fault){
    echo "Error: ".$fault->faultcode.", string: ".$fault->faultstring;
}

