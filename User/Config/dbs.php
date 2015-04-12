<?php

$dbs = DI()->config->get('dbs');

$defaultCfg = $dbs['tables']['__default__'];

$prefix = isset($defaultCfg['prefix']) ? $defaultCfg['prefix'] : 'tbl_';
$key = isset($defaultCfg['key']) ? $defaultCfg['key'] : 'id';
$db = isset($defaultCfg['map']['0']['db']) ? $defaultCfg['map']['0']['db'] : 'unknow_db';

$dbs['tables']['user'] = array(
    'prefix' => $prefix,
    'key' => $key,
    'map' => array(
        array('db' => $db),
    ),
);

$dbs['tables']['user_session'] = array(
    'prefix' => $prefix,
    'key' => $key,
    'map' => array(
        array('db' => $db),
        array('start' => 0, 'end' => 9, 'db' => $db),
    ),
);

return $dbs;
