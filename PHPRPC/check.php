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

require_once dirname(__FILE__) . '/phprpc/phprpc_client.php';

$client = new PHPRPC_Client();
$client->setProxy(NULL);  
$client->useService($url);  
$client->setKeyLength(1000);  
$client->setEncryptMode(3);  
$client->setCharset('UTF-8');  
$client->setTimeout(10);

print_r($client->response(json_encode($params)));

