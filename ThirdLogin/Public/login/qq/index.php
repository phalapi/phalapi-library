<?php
/**
 *  index.php
 *  qq登录回调
 *  
 *  Created by SteveAK on 06/21/16
 *  Copyright (c) 2016 SteveAK. All rights reserved.
 *  Contact email(aer_c@qq.com) or qq(7579476)
 */ 

//写入超全局变量
$GLOBALS['LOGIN_DATA'] = $_POST ? $_POST : $_GET;

$_REQUEST['service'] = 'Login.cbThirdLogin';
$_REQUEST['type']	= 'qq';
require_once(dirname(dirname(dirname(__FILE__))) . '/index.php');