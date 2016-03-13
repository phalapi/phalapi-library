<?php
//个人测试
//ACCESS_ID
//define('OSS_ACCESS_ID', 'YH0ZF0bROjQkCL7N');//线上
define('OSS_ACCESS_ID',DI()->config->get('sys.OSS_ACCESS_ID'));//线下

//ACCESS_KEY
//define('OSS_ACCESS_KEY', '2asd5I1povrfmVmU6hYrBU9f5h3WLC');//线上
define('OSS_ACCESS_KEY', DI()->config->get('sys.OSS_ACCESS_KEY'));//线下

//是否记录日志
define('ALI_LOG', FALSE);

//自定义日志路径，如果没有设置，则使用系统默认路径，在./logs/
//define('ALI_LOG_PATH','/app/user/sam/logs');

//是否显示LOG输出
define('ALI_DISPLAY_LOG', FALSE);

//语言版本设置
define('ALI_LANG', 'zh');


