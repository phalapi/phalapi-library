<?php

if ($argc < 3) {
    echo "Usage: $argv[0] <ip> <port> [service] [params ...]\n\n";
    echo "Example: $argv[0] 127.0.0.1 9501 Default.Index username=swoole\n\n";
    exit(1);
}

$ip = trim($argv[1]);
$port = intval($argv[2]);
$service = isset($argv[3]) ? trim($argv[3]) : 'Default.Index';
$params = array('service' => $service);

if (isset($argv[4])) {
    $moreParams = array_slice($argv, 4);
    foreach ($moreParams as $param) {
        list($key, $value) = explode('=', $param);
        $params[$key] = $value;
    }
}

$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
//设置事件回调函数
$client->on("connect", function($cli) {
    global $params;
    //$params = array();
    //$params['service'] = 'Default.Index';
    //$params['username'] = 'swoole';

    $data = json_encode($params);

    echo "Send: " . $data . "\n";;

    $cli->send($data);
});
$client->on("receive", function($cli, $data){
    echo "Received: " . $data . "\n";
});
$client->on("error", function($cli){
    echo "Connect failed\n";
});
$client->on("close", function($cli){
    echo "Connection close\n";
});
//发起网络连接
$client->connect($ip, $port, 3);
