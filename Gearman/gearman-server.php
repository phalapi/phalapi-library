<?php
date_default_timezone_set('Asia/Shanghai');

defined('API_ROOT') || define('API_ROOT', dirname(dirname(__FILE__))."/../");

require_once API_ROOT . '/PhalApi/PhalApi.php';
$loader = new PhalApi_Loader(API_ROOT, 'Library');

/** ---------------- 注册&初始化 基本服务组件 ---------------- **/

//自动加载
DI()->loader = $loader;

//配置
DI()->config = new PhalApi_Config_File(API_ROOT . '/Config');

//调试模式，$_GET['__debug__']可自行改名
DI()->debug = !empty($_GET['__debug__']) ? true : DI()->config->get('sys.debug');

//日记纪录
DI()->logger = new PhalApi_Logger_File(API_ROOT . '/Runtime', PhalApi_Logger::LOG_LEVEL_DEBUG | PhalApi_Logger::LOG_LEVEL_INFO | PhalApi_Logger::LOG_LEVEL_ERROR);

//数据操作 - 基于NotORM，$_GET['__sql__']可自行改名
DI()->notorm = new PhalApi_DB_NotORM(DI()->config->get('dbs'), 1);

//翻译语言包设定
SL('zh_cn');

/** ---------------- 定制注册 可选服务组件 ---------------- **/

DI()->gearman = new Gearman_Lite(DI()->config->get('app.gearman'));

//装载你的接口
DI()->loader->addDirs('Demo');
