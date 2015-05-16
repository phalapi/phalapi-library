<?php

//require_once dirname(__FILE__) . '/../../../../Public/init.php';
require_once '/home/dogstar/projects/library.phalapi.net/Public/init.php';


DI()->logger = new PhalApi_Logger_Explorer( 
    PhalApi_Logger::LOG_LEVEL_DEBUG | PhalApi_Logger::LOG_LEVEL_INFO | PhalApi_Logger::LOG_LEVEL_ERROR);

SL('en');
