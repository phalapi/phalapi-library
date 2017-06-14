<?php
/**
 * Created by PhpStorm.
 * User: Axios
 * Date: 2016/7/15
 * Time: 16:58
 */
global $workman_config;
$config = include("Config".DIRECTORY_SEPARATOR."app.php");
$workman_config = $config['workman'];
require_once "Library".DIRECTORY_SEPARATOR."Workman".DIRECTORY_SEPARATOR."start.php"; //引入workman服务启动文件

