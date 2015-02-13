#!/usr/bin/env php

<?php
/**
 * 微信服务号辅助脚本
 *
 * 模拟向服务器发送微信文本消息
 *
 * @author dogstar <chanzonghuang@gmail.com> 2015-01-08
 */

if ($argc < 3) {
    echo "Usage: $argv[0] <host> <content>\n\n";
    die();
}

$host = $argv[1];
$content = $argv[2];

$ch = curl_init();

$xml= '<xml>
    <ToUserName><![CDATA[gh_43235ff1360f]]></ToUserName>
    <FromUserName><![CDATA[oWNXvjipYqRViMpO8GZwXxE43pUY]]></FromUserName>
    <CreateTime>1419757723</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[' . $content . ']]></Content>
    <MsgId>6097812988731466682</MsgId>
    </xml>';

$header[]="Content-Type: text/xml; charset=utf-8";  
$header[]="Content-Length: ".strlen($xml);


curl_setopt($ch, CURLOPT_URL, $host);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_HEADER, 0);  

$res = curl_exec($ch);

echo $res, "\n\n";

